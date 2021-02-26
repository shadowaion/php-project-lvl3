const { Client } = require('pg');

const pgclient = new Client({
    host: process.env.POSTGRES_HOST,
    port: process.env.POSTGRES_PORT,
    user: 'postgres',
    password: 'postgres',
    database: 'postgres'
});

pgclient.connect();

const table1 = 'CREATE TABLE urls(id bigint PRIMARY KEY GENERATED ALWAYS AS IDENTITY, name varchar(255) UNIQUE, created_at timestamp, updated_at timestamp)'
const table2 = 'CREATE TABLE urls(id bigint PRIMARY KEY GENERATED ALWAYS AS IDENTITY, url_id bigint REFERENCES urls (id), status_code int, h1 varchar(255), keywords varchar(255), description varchar(255), created_at timestamp, updated_at timestamp)'
const text = 'INSERT INTO urls(id, name, created_at, updated_at) VALUES($1, $2, $3, $4) RETURNING *'
const values = [99, 'http://joyreactor.cc', '2021-02-26 02:00:00', '2021-02-26 02:00:00']

pgclient.query(table1, (err, res) => {
    if (err) throw err
});

pgclient.query(table2, values, (err, res) => {
    if (err) throw err
});

pgclient.query(text, values, (err, res) => {
    if (err) throw err
});

pgclient.query('SELECT * FROM urls', (err, res) => {
    if (err) throw err
    console.log(err, res.rows) // Print the data in student table
    pgclient.end()
});