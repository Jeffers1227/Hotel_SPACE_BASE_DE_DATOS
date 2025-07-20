const express = require('express');
const cors = require('cors');
const path = require('path');
const app = express();

const conexion = require('./models/mysql/conexionmysql');


// Middlewares
app.use(cors());
app.use(express.json());

// ✅ Servir archivos HTML, CSS y JS desde la carpeta "public"
app.use(express.static(path.join(__dirname, 'public')));

// ✅ Conexión a bases de datos
require('./models/mysql/conexionmysql');      // Conexión a MySQL
require('./models/mongodb/conexionmongodb');  // Conexión a MongoDB
require('./models/postgresql/conexionpg');    // Conexión a PostgreSQL
require('./models/redis/conexionredis');      // Conexión a Redis



// ✅ Rutas de autenticación (login y registro)
app.use('/api/auth', require('./routes/auth'));

// ✅ Ruta para guardar comentarios (Node puro, no PHP)
app.use('/api/comentarios', require('./routes/comentarios'));

// ✅ Ruta para obtener productos desde MySQL
app.get('/api/productos', (req, res) => {
  const sql = 'SELECT * FROM productos';
  conexion.query(sql, (err, results) => {
    if (err) {
      console.error('❌ Error al obtener productos:', err);
      return res.status(500).json({ error: 'Error al obtener productos' });
    }
    res.json(results);
  });
});


// ✅ Ruta para registrar una reserva con productos
app.post('/api/reservas', (req, res) => {
  const { nombre, habitacion, fecha, productos } = req.body;

  // Validar que todos los campos están presentes
  if (!nombre || !habitacion || !fecha || !productos || !Array.isArray(productos)) {
    return res.status(400).json({ error: 'Datos incompletos' });
  }

  // Insertar la reserva (incluyendo habitacion)
  const sqlReserva = 'INSERT INTO reservas (nombre_cliente, habitacion, fecha) VALUES (?, ?, ?)';
  conexion.query(sqlReserva, [nombre, habitacion, fecha], (err, result) => {
    if (err) {
      console.error('❌ Error al registrar la reserva:', err);
      return res.status(500).json({ error: 'Error al registrar la reserva' });
    }

    const reservaId = result.insertId;

    // Insertar los productos asociados a la reserva
    const sqlProductos = 'INSERT INTO reserva_productos (reserva_id, producto_id) VALUES ?';
    const valores = productos.map(idProducto => [reservaId, idProducto]);

    conexion.query(sqlProductos, [valores], (err2) => {
      if (err2) {
        console.error('❌ Error al asociar productos:', err2);
        return res.status(500).json({ error: 'Reserva creada pero error al asociar productos' });
      }

      res.json({ mensaje: '✅ Reserva registrada correctamente' });
    });
  });
});



// Iniciar servidor
const PORT = 3000;
app.listen(PORT, () => {
  console.log('🚀 Node server corriendo en http://localhost:${PORT}');
});