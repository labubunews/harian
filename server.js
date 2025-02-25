const express = require('express');
const app = express();
app.use(express.json());

const posts = [];

app.get('/posts', (req, res) => {
  res.json(posts);
});

app.post('/posts', (req, res) => {
  const post = req.body;
  posts.push(post);
  res.status(201).json(post);
});

app.get('/posts/:id', (req, res) => {
  const post = posts.find(p => p.id === parseInt(req.params.id));
  if (!post) return res.status(404).send('Post not found');
  res.json(post);
});

app.put('/posts/:id', (req, res) => {
  const post = posts.find(p => p.id === parseInt(req.params.id));
  if (!post) return res.status(404).send('Post not found');
  post.title = req.body.title;
  post.content = req.body.content;
  res.json(post);
});

app.delete('/posts/:id', (req, res) => {
  const postIndex = posts.findIndex(p => p.id === parseInt(req.params.id));
  if (postIndex === -1) return res.status(404).send('Post not found');
  posts.splice(postIndex, 1);
  res.status(204).send();
});

const port = 3000;
app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});
