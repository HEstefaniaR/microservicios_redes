const express = require('express');
const usuariosController = require('./controllers/usuariosController');
const morgan = require('morgan'); 
const app = express();
app.use(morgan('dev'));
app.use(express.json());


app.use(usuariosController);

//Es el puerto del microservicio, no de MySQL
app.listen(3001, () => {
  console.log('Microservicio Usuarios ejecutandose en el puerto 3001');
});