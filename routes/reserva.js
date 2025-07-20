const express = require('express');
const router = express.Router();
const pool = require('../models/postgresql/conexionpg');

router.post('/', async (req, res) => {
  const { nombre, habitacion, fecha, hora, productos } = req.body;

  try {
    await pool.query('BEGIN');
    const result = await pool.query(
      `INSERT INTO reservas (nombre, habitacion, fecha, hora)
       VALUES ($1, $2, $3, $4) RETURNING id`,
      [nombre, habitacion, fecha, hora]
    );

    const reservaId = result.rows[0].id;

    for (const productoId of productos) {
      await pool.query(
        `INSERT INTO reserva_productos (reserva_id, producto_id)
         VALUES ($1, $2)`,
        [reservaId, productoId]
      );
    }

    await pool.query('COMMIT');
    res.json({ ok: true });
  } catch (error) {
    console.error('❌ Error al guardar reserva:', error);
    await pool.query('ROLLBACK');
    res.status(500).json({ ok: false, error: 'Error al guardar reserva' });
  }
});

module.exports = router;

