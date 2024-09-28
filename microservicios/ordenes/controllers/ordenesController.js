const express = require('express');
const router = express.Router();
const axios = require('axios');
const ordenesModel = require('../models/ordenesModel');

// Obtener una orden por ID
router.get('/ordenes/:id', async (req, res) => {
    const id = req.params.id;
    try {
        const result = await ordenesModel.traerOrden(id);
        if (result.length === 0) {
            return res.status(404).json({ error: 'Orden no encontrada' });
        }
        res.json(result[0]); // Asegúrate de enviar el primer resultado aquí
    } catch (error) {
        res.status(500).json({ error: `Error al obtener la orden: ${error.message}` });
    }
});

// Obtener todas las órdenes
router.get('/ordenes', async (req, res) => {
    try {
        const result = await ordenesModel.traerOrdenes();
        res.json(result);
    } catch (error) {
        res.status(500).json({ error: `Error al obtener las órdenes: ${error.message}` });
    }
});

// Crear una nueva orden
router.post('/ordenes', async (req, res) => {
    const { usuario, items } = req.body;

    try {
        const totalCuenta = await calcularTotal(items);

        if (totalCuenta <= 0) {
            return res.status(400).json({ error: 'Total de la orden inválido' });
        }

        const disponibilidad = await verificarDisponibilidad(items);

        if (!disponibilidad) {
            return res.status(400).json({ error: 'No hay disponibilidad de productos' });
        }

        const responseUsuario = await axios.get(`http://usuarios:3001/usuarios/${usuario}`);
        const { nombre, email } = responseUsuario.data;

        const orden = { 
            nombreCliente: nombre, 
            emailCliente: email, 
            totalCuenta: totalCuenta 
        };
        await ordenesModel.crearOrden(orden);

        await actualizarInventario(items);

        res.status(201).json({ message: 'Orden creada exitosamente' });
    } catch (error) {
        res.status(500).json({ error: `Error al crear la orden: ${error.message}` });
    }
});

async function calcularTotal(items) {
    let ordenTotal = 0;
    try {
        for (const producto of items) {
            const response = await axios.get(`http://productos:3002/productos/${producto.id}`);
            ordenTotal += response.data.precio * producto.cantidad;
        }
    } catch (error) {
        throw new Error(`Error al calcular el total de la orden: ${error.message}`);
    }
    return ordenTotal;
}

async function verificarDisponibilidad(items) {
    let disponibilidad = true;
    try {
        for (const producto of items) {
            const response = await axios.get(`http://productos:3002/productos/${producto.id}`);
            if (response.data.inventario < producto.cantidad) {
                disponibilidad = false;
                break;
            }
        }
    } catch (error) {
        throw new Error(`Error al verificar la disponibilidad: ${error.message}`);
    }
    return disponibilidad;
}

async function actualizarInventario(items) {
    try {
        for (const producto of items) {
            const response = await axios.get(`http://productos:3002/productos/${producto.id}`);
            const inventarioActual = response.data.inventario;
            const inv = inventarioActual - producto.cantidad;
            await axios.put(`http://productos:3002/productos/${producto.id}`, { inventario: inv });
        }
    } catch (error) {
        throw new Error(`Error al actualizar el inventario: ${error.message}`);
    }
}

module.exports = router;
