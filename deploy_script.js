import { Client } from 'ssh2';
import fs from 'fs';

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Connection Ready. Starting SFTP upload...');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    console.log('SFTP session started. Uploading usavetra.zip...');
    
    const readStream = fs.createReadStream('C:\\Users\\LENOVO\\.gemini\\antigravity\\brain\\0155fc01-39cb-40ad-aaf1-975bdbb48923\\scratch\\deploy\\usavetra.zip');
    const writeStream = sftp.createWriteStream('/home/ubuntu/usavetra.zip');
    
    writeStream.on('close', () => {
      console.log('Upload complete. Executing server commands...');
      const cmd = `
        sudo rm -rf /var/www/usavetra && 
        sudo mkdir -p /var/www/usavetra && 
        sudo chown ubuntu:ubuntu /var/www/usavetra && 
        unzip -q /home/ubuntu/usavetra.zip -d /var/www/usavetra && 
        cd /var/www/usavetra && 
        cp .env.example .env && 
        composer install --no-dev --optimize-autoloader && 
        php artisan key:generate && 
        sudo chown -R www-data:www-data /var/www/usavetra && 
        sudo chmod -R 775 /var/www/usavetra/storage /var/www/usavetra/bootstrap/cache && 
        echo "Server setup completed!"
      `;
      conn.exec(cmd, (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
          console.log('Server commands finished.');
          conn.end();
        }).on('data', (data) => {
          process.stdout.write(data);
        }).stderr.on('data', (data) => {
          process.stderr.write(data);
        });
      });
    });
    
    writeStream.on('error', (err) => {
      console.error('SFTP Write Error:', err);
      conn.end();
    });
    
    readStream.pipe(writeStream);
  });
}).on('error', (err) => {
  console.error('SSH Connection Error:', err);
}).connect({
  host: '43.133.154.13',
  port: 22,
  username: 'ubuntu',
  password: 'shadow-64$-storm'
});
