const express = require('express');
const router = express.Router();
const mysql = require('../models/mysql/conexionmysql');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const UsuarioMongo = require('../models/mongodb/Usuario');

// Registro en MySQL + MongoDB
router.post('/registro', async (req, res) => {
  const { nombre, correo, contraseña, rol } = req.body;

  try {
    const hashedPassword = await bcrypt.hash(contraseña, 10);

    // Insertar en MySQL
    mysql.query(
      'INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES (?, ?, ?, ?)',
      [nombre, correo, hashedPassword, rol || 'cliente'],
      async (err, result) => {
        if (err) {
          console.error('❌ Error al guardar en MySQL:', err.message);
          return res.status(500).send({ error: 'Error al registrar en MySQL' });
        }

        // Insertar en MongoDB
        try {
          const nuevoUsuario = new UsuarioMongo({
            nombre,
            correo,
            rol: rol || 'cliente',
            fechaRegistro: new Date()
          });

          await nuevoUsuario.save();

          console.log('✅ Usuario guardado en MySQL y MongoDB');
          res.send({ mensaje: 'Usuario registrado correctamente en ambas bases de datos' });

        } catch (mongoErr) {
          console.error('❌ Fallo al guardar en MongoDB:', mongoErr.message);
          res.status(500).send({ error: 'Registrado en MySQL, pero falló MongoDB' });
        }
      }
    );

  } catch (bcryptErr) {
    console.error('❌ Error al encriptar contraseña:', bcryptErr.message);
    res.status(500).send({ error: 'Error interno en el servidor' });
  }
});

// Login
router.post('/login', (req, res) => {
  const { correo, contraseña } = req.body;

  mysql.query('SELECT * FROM usuarios WHERE correo = ?', [correo], async (err, results) => {
    if (err || results.length === 0) {
      console.warn('⚠️ Usuario no encontrado');
      return res.status(401).send({ mensaje: 'Usuario no encontrado' });
    }

    const usuario = results[0];
    const esValida = await bcrypt.compare(contraseña, usuario.contraseña);

    if (!esValida) {
      console.warn('⚠️ Contraseña incorrecta');
      return res.status(401).send({ mensaje: 'Contraseña incorrecta' });
    }

    const token = jwt.sign(
      { id: usuario.id, correo: usuario.correo, nombre: usuario.nombre },
      process.env.JWT_SECRET || 'mi_secreto_seguro',
      { expiresIn: '1h' }
    );

    console.log(`✅ Login exitoso: ${usuario.correo}`);

    res.send({
      mensaje: 'Login exitoso',
      token,
      usuario: {
        id: usuario.id,
        nombre: usuario.nombre,
        correo: usuario.correo,
        rol: usuario.rol
      }
    });
  });
});

module.exports = router;
