import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Connection Ready. Setting up DB and Nginx...');
  
  const cmd = `
    cd /var/www/usavetra && 
    sudo mysql -e "CREATE DATABASE IF NOT EXISTS usavetra; CREATE USER IF NOT EXISTS 'usavetra'@'localhost' IDENTIFIED BY 'Usavetra2026!'; GRANT ALL PRIVILEGES ON usavetra.* TO 'usavetra'@'localhost'; FLUSH PRIVILEGES;" &&
    
    sudo sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/g' .env &&
    sudo sed -i 's/DB_DATABASE=.*/DB_DATABASE=usavetra/g' .env &&
    sudo sed -i 's/DB_USERNAME=.*/DB_USERNAME=usavetra/g' .env &&
    sudo sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=Usavetra2026!/g' .env &&
    sudo sed -i 's/APP_URL=.*/APP_URL=http:\\/\\/usavetra.web.id/g' .env &&
    sudo sed -i 's/APP_ENV=local/APP_ENV=production/g' .env &&
    sudo sed -i 's/APP_DEBUG=true/APP_DEBUG=false/g' .env &&
    
    sudo php artisan migrate:fresh --seed --force &&
    
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
    sudo ln -sf /etc/nginx/sites-available/usavetra /etc/nginx/sites-enabled/ &&
    sudo systemctl restart nginx &&
    echo "Deployment Finalized!"
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
