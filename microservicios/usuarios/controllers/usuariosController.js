const { Router } = require('express');
const router = Router();
const usuariosModel = require('../models/usuariosModel');

// Obtener todos los usuarios
router.get('/usuarios', async (req, res) => {
    const result = await usuariosModel.traerUsuarios();
    res.json(result);
});

// Obtener un usuario por nombre de usuario
router.get('/usuarios/:usuario', async (req, res) => {
    const usuario = req.params.usuario;
    const result = await usuariosModel.traerUsuario(usuario);
    if (result.length === 0) {
        return res.status(404).json({ message: 'Usuario no encontrado' });
    }
    res.json(result[0]);
});

// Validar usuario por nombre de usuario y contraseña
router.get('/usuarios/:usuario/:password', async (req, res) => {
    const usuario = req.params.usuario;
    const password = req.params.password;
    const result = await usuariosModel.validarUsuario(usuario, password);
    if (result.length === 0) {
        return res.status(401).json({ message: 'Usuario o contraseña incorrectos' });
    }
    res.json(result[0]);
});

// Crear un nuevo usuario
router.post('/usuarios', async (req, res) => {
    const { nombre, email, usuario, password } = req.body;
    await usuariosModel.crearUsuario(nombre, email, usuario, password);
    res.status(201).json({ message: 'Usuario creado' });
});

module.exports = router;
