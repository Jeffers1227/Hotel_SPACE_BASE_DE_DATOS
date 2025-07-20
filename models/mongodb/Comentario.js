const mongoose = require('mongoose');

const ComentarioSchema = new mongoose.Schema({
  usuario: { type: String, required: true },
  correo: { type: String, required: true },
  comentario: { type: String, required: true },
  peticion: { type: String },
  fecha: { type: Date, default: Date.now }
});

module.exports = mongoose.model('Comentario', ComentarioSchema);
