import { Client } from 'ssh2';
import fs from 'fs';

const conn = new Client();
conn.on('ready', () => {
  const envContent = fs.readFileSync('d:\\Project\\usavetra\\.env', 'utf8');
  const b64Env = Buffer.from(envContent).toString('base64');
  
  const cmd = `
    sudo rm -rf /var/www/usavetra && 
    sudo mkdir -p /var/www/usavetra && 
    sudo chown ubuntu:ubuntu /var/www/usavetra && 
    unzip -q /home/ubuntu/usavetra.zip -d /var/www/usavetra && 
    
    cd /var/www/usavetra && 
    echo "${b64Env}" | base64 -d > .env &&
    
    sudo mysql -e "CREATE DATABASE IF NOT EXISTS usavetra; CREATE USER IF NOT EXISTS 'usavetra'@'localhost' IDENTIFIED BY 'Usavetra2026!'; GRANT ALL PRIVILEGES ON usavetra.* TO 'usavetra'@'localhost'; FLUSH PRIVILEGES;" &&
    
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/g' .env &&
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=usavetra/g' .env &&
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=usavetra/g' .env &&
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
    sudo rm -f /etc/nginx/sites-enabled/usavetra &&
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
}).on('error', (err) => {
  console.error('SSH Connection Error:', err);
}).connect({
  host: '43.133.154.13',
  port: 22,
  username: 'ubuntu',
  password: 'shadow-64$-storm'
});
