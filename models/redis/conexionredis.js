const redis = require('redis');

const client = redis.createClient({
  url: 'redis://default:Qo8pTaKlvrmaavy2THTaVD8V9YxGM1G0@redis-14598.c73.us-east-1-2.ec2.redns.redis-cloud.com:14598'
});

client.connect()
  .then(() => console.log('✅ Conectado a Redis'))
  .catch(err => console.error('❌ Error al conectar a Redis:', err));

module.exports = client;
