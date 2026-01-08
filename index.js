const express = require('express');
const nodemailer = require('nodemailer');

const app = express();
app.use(express.json());

app.get('/', (req, res) => {
  res.send('Notify service running');
});

app.post('/notify', async (req, res) => {
  try {
    const { product, qty } = req.body;

    if (!product || !qty) {
      return res.status(400).send('Invalid payload');
    }

    const transporter = nodemailer.createTransport({
      host: 'smtp.gmail.com',
      port: 587,
      secure: false,
      auth: {
        user: process.env.MAIL_USER,
        pass: process.env.MAIL_PASS
      }
    });

    await transporter.sendMail({
      from: process.env.MAIL_USER,
      to: process.env.MAIL_TO,
      subject: 'Stok Barang Masuk',
      text: `Barang ${product} masuk sebanyak ${qty}`
    });

    res.send('Email sent');
  } catch (err) {
    console.error(err);
    res.status(500).send('Mail error');
  }
});

const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
  console.log(`Listening on ${PORT}`);
});
