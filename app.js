const express = require('express');
const app = express();
const port = 3000;

// Rute utama
app.get('/', (req, res) => {
  res.send('Halaman Utama');
});

// Rute untuk menangani 404
app.use((req, res, next) => {
  res.status(404).send('Halaman tidak ditemukan');
});

app.listen(port, () => {
  console.log(`Server berjalan di http://localhost:${port}`);
});
