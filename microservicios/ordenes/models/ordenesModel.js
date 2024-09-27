const mysql = require('mysql2/promise');

const connection = mysql.createPool({
    host: 'db',
    user: 'root',
    password: '',
    database: 'ordenesDB'
});

async function crearOrden(orden) {
    const { nombre_cliente, email_cliente, total_cuenta } = orden;
    try {
        const [result] = await connection.query(
            'INSERT INTO ordenes (nombre_cliente, email_cliente, total_cuenta, fechahora) VALUES (?, ?, ?, NOW())',
            [nombre_cliente, email_cliente, total_cuenta]
        );
        return result;
    } catch (error) {
        throw new Error(`Error al crear orden: ${error.message}`);
    }
}

async function traerOrden(id) {
    try {
        const [rows] = await connection.query('SELECT * FROM ordenes WHERE id = ?', [id]);
        return rows; // Asegúrate de devolver rows directamente aquí
    } catch (error) {
        throw new Error(`Error al traer orden: ${error.message}`);
    }
}

async function traerOrdenes() {
    try {
        const [rows] = await connection.query('SELECT * FROM ordenes');
        return rows;
    } catch (error) {
        throw new Error(`Error al traer órdenes: ${error.message}`);
    }
}

module.exports = {
    crearOrden,
    traerOrden,
    traerOrdenes
};
