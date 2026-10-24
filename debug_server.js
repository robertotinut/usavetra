import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  const cmd = `
    echo "=== 1. Nginx Status ==="
    sudo systemctl status nginx --no-pager -l
    echo ""
    echo "=== 2. Nginx Config Test ==="
    sudo nginx -t
    echo ""
    echo "=== 3. Sites Enabled ==="
    ls -la /etc/nginx/sites-enabled/
    echo ""
    echo "=== 4. Usavetra Config ==="
    cat /etc/nginx/sites-available/usavetra
    echo ""
    echo "=== 5. Project Files ==="
    ls -la /var/www/usavetra/public/
    echo ""
    echo "=== 6. File Permissions ==="
    ls -la /var/www/usavetra/public/index.php
    echo ""
    echo "=== 7. PHP-FPM Status ==="
    sudo systemctl status php8.2-fpm --no-pager -l
    echo ""
    echo "=== 8. Curl Test ==="
    curl -I http://localhost -H "Host: usavetra.web.id" 2>&1
    echo ""
    echo "=== 9. .env check ==="
    head -5 /var/www/usavetra/.env
    echo ""
    echo "=== 10. Storage link ==="
    ls -la /var/www/usavetra/public/storage 2>&1 || echo "No storage link"
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
