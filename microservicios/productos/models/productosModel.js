const mysql = require('mysql2/promise');

const connection = mysql.createPool({
    host: 'db',
    user: 'root',
    password: 'password',
    database: 'productosDB'
});

async function traerProductos() {
    try {
        const [rows] = await connection.query('SELECT * FROM productos');
        return rows;
    } catch (error) {
        throw new Error(`Error al traer productos: ${error.message}`);
    }
}

async function traerProducto(id) {
    try {
        const [rows] = await connection.query('SELECT * FROM productos WHERE id = ?', [id]);
        return rows;
    } catch (error) {
        throw new Error(`Error al traer producto: ${error.message}`);
    }
}

async function actualizarProducto(id, nombre, precio, inventario) {
    try {
        const [result] = await connection.query(
            'UPDATE productos SET nombre = ?, precio = ?, inventario = ? WHERE id = ?',
            [nombre, precio, inventario, id]
        );
        return result;
    } catch (error) {
        throw new Error(`Error al actualizar producto: ${error.message}`);
    }
}

async function crearProducto(nombre, precio, inventario) {
    try {
        const [result] = await connection.query(
            'INSERT INTO productos (nombre, precio, inventario) VALUES (?, ?, ?)',
            [nombre, precio, inventario]
        );
        return result;
    } catch (error) {
        throw new Error(`Error al crear producto: ${error.message}`);
    }
}

async function borrarProducto(id) {
    try {
        const [result] = await connection.query('DELETE FROM productos WHERE id = ?', [id]);
        return result;
    } catch (error) {
        throw new Error(`Error al borrar producto: ${error.message}`);
    }
}

module.exports = {
    traerProductos,
    traerProducto,
    actualizarProducto,
    crearProducto,
    borrarProducto
};
