const express = require('express');
const ordenesController = require('./controllers/ordenesController');
const morgan = require('morgan');
const app = express();

app.use(morgan('dev'));
app.use(express.json());

// Usa las rutas definidas en ordenesController
app.use(ordenesController);

app.listen(3003, () => {
    console.log('Microservicio de órdenes escuchando en el puerto 3003');
});
