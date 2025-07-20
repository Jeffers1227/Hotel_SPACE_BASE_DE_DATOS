const express = require('express');
const router = express.Router();
const Comentario = require('../models/mongodb/Comentario');

// POST: guardar comentario en MongoDB
router.post('/guardar', async (req, res) => {
  const { usuario, correo, comentario, peticion } = req.body;

  if (!usuario || !correo || !comentario) {
    return res.status(400).send('Faltan campos obligatorios');
  }

  try {
    const nuevoComentario = new Comentario({
      usuario,
      correo,
      comentario,
      peticion
    });

    await nuevoComentario.save();
    res.send('✅ Comentario enviado');

  } catch (error) {
    console.error('❌ Error al guardar comentario en MongoDB:', error.message);
    res.status(500).send('Error al guardar comentario');
  }
});

module.exports = router;
