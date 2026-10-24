import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  const cmd = `
    echo "1. DB check:"
    sudo mysql -e "SHOW DATABASES;"
    echo "2. Composer check:"
    composer --version
    echo "3. artisan check:"
    cd /var/www/usavetra && php artisan --version
  `;
  
  conn.exec(cmd, (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      conn.end();
    }).on('data', (data) => {
      process.stdout.write(data);
    }).stderr.on('data', (data) => {
      process.stderr.write(data);
    });
  });
}).connect({
  host: '43.133.154.13',
  port: 22,
  username: 'ubuntu',
  password: 'shadow-64$-storm'
});
