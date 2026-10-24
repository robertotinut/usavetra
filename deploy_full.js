import { Client } from 'ssh2';
import fs from 'fs';
import archiver from 'archiver';

async function compressProject() {
  return new Promise((resolve, reject) => {
    console.log('Compressing project...');
    const output = fs.createWriteStream('C:\\Users\\LENOVO\\.gemini\\antigravity\\brain\\0155fc01-39cb-40ad-aaf1-975bdbb48923\\scratch\\deploy\\usavetra.zip');
    const archive = archiver('zip', { zlib: { level: 9 } });
    
    output.on('close', () => {
      console.log(`Archive created: ${archive.pointer()} total bytes`);
      resolve();
    });
    
    archive.on('error', (err) => reject(err));
    archive.pipe(output);
    
    // Add all files except ignored ones
    archive.glob('**/*', {
      cwd: 'd:\\Project\\usavetra',
      ignore: ['vendor/**', 'node_modules/**', '.git/**', 'storage/framework/cache/**', 'storage/framework/views/**', 'storage/logs/**', '.env']
    });
    // Add explicitly .env.example
    archive.file('d:\\Project\\usavetra\\.env.example', { name: '.env.example' });
    
    archive.finalize();
  });
}

async function deploy() {
  await compressProject();
  
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
          sudo rm -f /etc/nginx/sites-enabled/usavetra &&
          sudo rm -f /etc/nginx/sites-available/usavetra &&
          sudo rm -rf /var/www/usavetra && 
          sudo mkdir -p /var/www/usavetra && 
          sudo chown ubuntu:ubuntu /var/www/usavetra && 
          unzip -q /home/ubuntu/usavetra.zip -d /var/www/usavetra && 
          
          cd /var/www/usavetra && 
          cp .env.example .env && 
          
          sudo mysql -e "CREATE DATABASE IF NOT EXISTS usavetra; CREATE USER IF NOT EXISTS 'usavetra'@'localhost' IDENTIFIED BY 'Usavetra2026!'; GRANT ALL PRIVILEGES ON usavetra.* TO 'usavetra'@'localhost'; FLUSH PRIVILEGES;" &&
          
          sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/g' .env &&
          sed -i 's/DB_DATABASE=.*/DB_DATABASE=usavetra/g' .env &&
          sed -i 's/# DB_USERNAME=.*/DB_USERNAME=usavetra/g' .env &&
          sed -i 's/DB_USERNAME=.*/DB_USERNAME=usavetra/g' .env &&
          sed -i 's/# DB_PASSWORD=.*/DB_PASSWORD=Usavetra2026!/g' .env &&
          sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=Usavetra2026!/g' .env &&
          sed -i 's/APP_URL=.*/APP_URL=http:\\/\\/usavetra.web.id/g' .env &&
          sed -i 's/APP_ENV=local/APP_ENV=production/g' .env &&
          sed -i 's/APP_DEBUG=true/APP_DEBUG=false/g' .env &&
          
          composer install --no-dev --optimize-autoloader && 
          php artisan key:generate && 
          php artisan migrate:fresh --seed --force &&
          
          sudo chown -R www-data:www-data /var/www/usavetra && 
          sudo chmod -R 775 /var/www/usavetra/storage /var/www/usavetra/bootstrap/cache && 
          
          cat << 'EOF' | sudo tee /etc/nginx/sites-available/usavetra
server {
    listen 80;
    server_name usavetra.web.id www.usavetra.web.id;
    root /var/www/usavetra/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \\.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\\.(?!well-known).* {
        deny all;
    }
}
EOF
          sudo ln -s /etc/nginx/sites-available/usavetra /etc/nginx/sites-enabled/usavetra &&
          sudo systemctl restart nginx &&
          echo "Deployment Finalized Successfully!"
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
}

deploy().catch(console.error);
