const express = require('express');
const cors = require('cors');
const jwt = require('jwt-simple');
const bcrypt = require('bcryptjs');
const mysql = require('mysql2');

const app = express();
const port = 5000;

// MySQL connection
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'student_portal'
});

app.use(cors());
app.use(express.json());

// Middleware to check JWT token
const authenticate = (req, res, next) => {
  const token = req.header('Authorization')?.replace('Bearer ', '');
  if (!token) return res.status(403).send('Access denied.');

  try {
    const decoded = jwt.decode(token, 'secretKey');
    req.user = decoded;
    next();
  } catch (error) {
    res.status(400).send('Invalid token.');
  }
};

// Login route using username and password
app.post('/api/login', (req, res) => {
  const { username, password } = req.body;

  // Query to find the student based on the username
  const query = 'SELECT * FROM student WHERE username = ?';

  db.query(query, [username], async (err, result) => {
    if (err) return res.status(500).send('Server error.');
    if (result.length === 0) return res.status(404).send('User not found.');

    const student = result[0];
    
    // Compare the provided password with the stored hashed password
    const validPassword = await bcrypt.compare(password, student.password);
    if (!validPassword) return res.status(400).send('Invalid password.');

    // Generate a JWT token containing the student ID and username
    const token = jwt.encode({ id: student.student_id, username: student.username }, 'secretKey');
    
    // Send the token as a response
    res.json({ token });
  });
});

// Grades route (Authenticated)
app.get('/api/grades', authenticate, (req, res) => {
  const query = 'SELECT course, grade FROM grades WHERE student_id = ?';
  db.query(query, [req.user.id], (err, result) => {
    if (err) return res.status(500).send('Server error.');
    res.json(result);
  });
});

// Clearance status route (Authenticated)
app.get('/api/clearance', authenticate, (req, res) => {
  const query = 'SELECT status FROM clearance WHERE student_id = ?';
  db.query(query, [req.user.id], (err, result) => {
    if (err) return res.status(500).send('Server error.');
    res.json(result[0]);
  });
});

// Server start
app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
