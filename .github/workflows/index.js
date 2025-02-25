const express = require('express');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

app.use(bodyParser.json());

// Endpoint GET
app.get('/api/items', (req, res) => {
  res.json({ message: 'GET request received' });
});

// Endpoint POST
app.post('/api/items', (req, res) => {
  const newItem = req.body;
  res.json({ message: 'POST request received', item: newItem });
});

// Endpoint PUT
app.put('/api/items/:id', (req, res) => {
  const { id } = req.params;
  const updatedItem = req.body;
  res.json({ message: `PUT request received for item ${id}`, item: updatedItem });
});

// Endpoint DELETE
app.delete('/api/items/:id', (req, res) => {
  const { id } = req.params;
  res.json({ message: `DELETE request received for item ${id}` });
});

// Endpoint PATCH
app.patch('/api/items/:id', (req, res) => {
  const { id } = req.params;
  const updatedFields = req.body;
  res.json({ message: `PATCH request received for item ${id}`, updatedFields });
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});