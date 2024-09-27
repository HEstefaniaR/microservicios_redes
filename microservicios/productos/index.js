const express = require('express');
const productosController = require('./controllers/productosController');
const morgan = require('morgan'); 
const app = express();
app.use(morgan('dev'));
app.use(express.json());


app.use(productosController);

//Aquí se pone el puerto del microservicio, no del MySQL. Cada mciroservicio tiene un puerto diferentes
app.listen(3002, () => {
  console.log('Microservicio Productos ejecutandose en el puerto 3002');
});