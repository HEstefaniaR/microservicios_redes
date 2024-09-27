const mysql = require('mysql2/promise');

const connection = mysql.createPool({
    host: 'db',
    user: 'root',
    password: '',
    database: 'usuariosDB'
});

async function traerUsuarios() {
    const [rows] = await connection.query('SELECT * FROM usuarios');
    return rows;
}

async function traerUsuario(usuario) {
    const [rows] = await connection.query('SELECT * FROM usuarios WHERE usuario = ?', [usuario]);
    return rows;
}

async function validarUsuario(usuario, password) {
    const [rows] = await connection.query('SELECT * FROM usuarios WHERE usuario = ? AND password = ?', [usuario, password]);
    return rows;
}

async function crearUsuario(nombre, email, usuario, password) {
    const [result] = await connection.query('INSERT INTO usuarios (nombre, email, usuario, password) VALUES (?, ?, ?, ?)', [nombre, email, usuario, password]);
    return result;
}

module.exports = {
    traerUsuarios,
    traerUsuario,
    validarUsuario,
    crearUsuario
};
