const mongoose = require('mongoose');

mongoose.connect('mongodb+srv://Proyect:Jeff1234@cluster0.dtmidik.mongodb.net/hotel')
  .then(() => console.log('✅ Conectado a MongoDB'))
  .catch(err => console.error('❌ Error al conectar a MongoDB:', err));
