import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  const cmd = `
    echo "=== Check files in /var/www/usavetra ==="
    ls -la /var/www/usavetra/public/assets/css/mobile-app.css
    ls -la /var/www/usavetra/resources/views/layouts/app.blade.php
    
    echo "=== Curl test local on server ==="
    curl -s -I http://127.0.0.1/assets/css/mobile-app.css | head -n 10
    
    echo "=== Restart php-fpm & nginx to ensure fresh reload ==="
    sudo systemctl reload nginx
    sudo systemctl restart php8.2-fpm || sudo systemctl restart php8.3-fpm || sudo systemctl restart php-fpm || true
    
    cd /var/www/usavetra
    php artisan view:clear
    php artisan cache:clear
    php artisan optimize:clear
    
    echo "=== All caches cleared and services reloaded ==="
  `;
  conn.exec(cmd, (err, stream) => {
    if (err) throw err;
    stream.on('close', () => {
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
