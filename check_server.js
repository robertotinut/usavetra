import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Connection Ready');
  conn.exec('ls -la /var/www; echo "---"; nginx -v; echo "---"; php -v; echo "---"; mysql -V', (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      conn.end();
    }).on('data', (data) => {
      process.stdout.write(data);
    }).stderr.on('data', (data) => {
      process.stderr.write(data);
    });
  });
}).on('error', (err) => {
  console.error('SSH Connection Error:', err);
}).connect({
  host: '43.133.154.13',
  port: 22,
  username: 'ubuntu',
  password: 'shadow-64$-storm'
});
