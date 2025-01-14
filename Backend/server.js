const express = require('express');
const mysql = require('mysql');
const app = express();

const db = mysql.createConnection({
    host: 'mysql',
    user: 'user',
    password: 'userpassword',
    database: 'mydatabase',
});

db.connect(err => {
    if (err) {
        console.error('Error connecting to MySQL:', err.message);
        return;
    }
    console.log('Connected to MySQL');
});

app.get('/', (req, res) => {
    res.send('Hello from Node.js backend!');
});

app.listen(3000, () => {
    console.log('Server is running on port 3000');
});
