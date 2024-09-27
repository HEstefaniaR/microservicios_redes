const { Router } = require('express');
const router = Router();
const productosModel = require('../models/productosModel');

// Obtener todos los productos
router.get('/productos', async (req, res) => {
    try {
        const result = await productosModel.traerProductos();
        res.json(result);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Obtener un producto por ID
router.get('/productos/:id', async (req, res) => {
    const id = req.params.id;
    try {
        const result = await productosModel.traerProducto(id);
        if (result.length === 0) {
            return res.status(404).json({ message: 'Producto no encontrado' });
        }
        res.json(result[0]);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Crear un nuevo producto
router.post('/productos', async (req, res) => {
    const { nombre, precio, inventario } = req.body;
    try {
        await productosModel.crearProducto(nombre, precio, inventario);
        res.status(201).json({ message: 'Producto creado' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Actualizar un producto por ID
router.put('/productos/:id', async (req, res) => {
    const id = req.params.id;
    const { nombre, precio, inventario } = req.body;
    try {
        const result = await productosModel.actualizarProducto(id, nombre, precio, inventario);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Producto no encontrado' });
        }
        res.json({ message: 'Producto actualizado' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// Eliminar un producto por ID
router.delete('/productos/:id', async (req, res) => {
    const id = req.params.id;
    try {
        const result = await productosModel.borrarProducto(id);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: 'Producto no encontrado' });
        }
        res.json({ message: 'Producto eliminado' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

module.exports = router;
