import { Client } from 'ssh2';
import fs from 'fs';

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Connected. Uploading updated project...');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    
    const readStream = fs.createReadStream('d:\\Project\\usavetra\\usavetra_git.zip');
    const writeStream = sftp.createWriteStream('/home/ubuntu/usavetra_git.zip');
    
    writeStream.on('close', () => {
      console.log('Upload complete. Re-deploying...');
      
      const cmd = `
        sudo rm -rf /var/www/usavetra &&
        sudo mkdir -p /var/www/usavetra &&
        sudo chown ubuntu:ubuntu /var/www/usavetra &&
        unzip -q /home/ubuntu/usavetra_git.zip -d /var/www/usavetra &&

        cd /var/www/usavetra &&

        # Restore .env
        cat << 'ENVEOF' > .env
APP_NAME=Usavetra
APP_ENV=production
APP_KEY=base64:XDQlLWUEoRKTBzRgUVf76m5WG2elCgBXY329nF7rmkI=
APP_DEBUG=false
APP_URL=http://usavetra.web.id

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=usavetra
DB_USERNAME=usavetra
DB_PASSWORD=Usavetra2026!

SESSION_DRIVER=database
SESSION_LIFETIME=120
ENVEOF

        composer install --no-dev --optimize-autoloader &&
        php artisan key:generate --force &&
        php artisan migrate:fresh --seed --force &&
        php artisan storage:link &&

        sudo chown -R www-data:www-data /var/www/usavetra &&
        sudo chmod -R 775 /var/www/usavetra/storage /var/www/usavetra/bootstrap/cache &&

        echo "=== DONE! Deployment successful! ==="
      `;
      
      conn.exec(cmd, (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
          console.log('Finished with code:', code);
          conn.end();
        }).on('data', (data) => {
          process.stdout.write(data);
        }).stderr.on('data', (data) => {
          process.stderr.write(data);
        });
      });
    });
    
    readStream.pipe(writeStream);
  });
}).on('error', (err) => {
  console.error('SSH Error:', err);
}).connect({
  host: '43.133.154.13',
  port: 22,
  username: 'ubuntu',
  password: 'shadow-64$-storm'
});
