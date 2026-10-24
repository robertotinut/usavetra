import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  const cmd = `
    cd /var/www/usavetra &&

    # Fix permissions
    sudo chown -R www-data:www-data /var/www/usavetra &&
    sudo chmod -R 775 /var/www/usavetra/storage /var/www/usavetra/bootstrap/cache &&

    # Create storage link
    sudo -u www-data php artisan storage:link &&

    # Check Laravel log for errors
    echo "=== Laravel Error Log (last 30 lines) ===" &&
    sudo tail -30 /var/www/usavetra/storage/logs/laravel.log 2>/dev/null || echo "No log file yet" &&

    # Test curl again
    echo "" &&
    echo "=== Curl Test ===" &&
    curl -I http://localhost -H "Host: usavetra.web.id" 2>&1
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
