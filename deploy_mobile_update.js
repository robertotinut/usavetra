import { Client } from 'ssh2';
import fs from 'fs';
import path from 'path';

const filesToUpload = [
  { local: 'resources/views/layouts/app.blade.php', temp: '/home/ubuntu/app.blade.php', dest: '/var/www/usavetra/resources/views/layouts/app.blade.php' },
  { local: 'resources/views/sales/create.blade.php', temp: '/home/ubuntu/create.blade.php', dest: '/var/www/usavetra/resources/views/sales/create.blade.php' },
  { local: 'public/assets/css/mobile-app.css', temp: '/home/ubuntu/mobile-app.css', dest: '/var/www/usavetra/public/assets/css/mobile-app.css' },
  { local: 'app/Http/Controllers/SaleController.php', temp: '/home/ubuntu/SaleController.php', dest: '/var/www/usavetra/app/Http/Controllers/SaleController.php' },
  { local: 'app/Traits/Multitenantable.php', temp: '/home/ubuntu/Multitenantable.php', dest: '/var/www/usavetra/app/Traits/Multitenantable.php' },
  { local: 'app/Models/StockMovement.php', temp: '/home/ubuntu/StockMovement.php', dest: '/var/www/usavetra/app/Models/StockMovement.php' }
];

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Connected to VPS...');
  conn.sftp(async (err, sftp) => {
    if (err) throw err;

    for (const f of filesToUpload) {
      const localPath = path.resolve('d:/Project/usavetra', f.local);
      console.log(`Uploading ${f.local} -> ${f.temp}`);
      await new Promise((resolve, reject) => {
        const readStream = fs.createReadStream(localPath);
        const writeStream = sftp.createWriteStream(f.temp);
        writeStream.on('close', resolve);
        writeStream.on('error', reject);
        readStream.pipe(writeStream);
      });
    }

    console.log('All files uploaded to temp! Copying and applying permissions...');
    const cmd = `
      ${filesToUpload.map(f => `sudo cp ${f.temp} ${f.dest}`).join(' && ')} &&
      sudo chown -R www-data:www-data /var/www/usavetra/resources /var/www/usavetra/public /var/www/usavetra/app &&
      cd /var/www/usavetra && 
      php artisan view:clear && 
      php artisan route:clear && 
      php artisan config:clear && 
      php artisan cache:clear &&
      php artisan optimize:clear &&
      echo "=== VPS update applied successfully! ==="
    `;

    conn.exec(cmd, (err, stream) => {
      if (err) throw err;
      stream.on('close', (code) => {
        console.log('Finished with exit code:', code);
        conn.end();
      }).on('data', (data) => {
        process.stdout.write(data);
      }).stderr.on('data', (data) => {
        process.stderr.write(data);
      });
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
