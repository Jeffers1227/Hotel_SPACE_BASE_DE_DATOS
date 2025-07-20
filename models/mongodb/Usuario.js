const mongoose = require('mongoose');

const usuarioSchema = new mongoose.Schema({
  nombre: String,
  correo: String,
  rol: { type: String, default: 'cliente' },
  fechaRegistro: { type: Date, default: Date.now }
});

module.exports = mongoose.model('UsuarioMongo', usuarioSchema);
