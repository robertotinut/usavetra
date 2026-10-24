import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  const cmd = `
    sudo rm -f /etc/nginx/sites-enabled/usavetra
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
    sudo ln -s /etc/nginx/sites-available/usavetra /etc/nginx/sites-enabled/usavetra
    sudo nginx -t && sudo systemctl restart nginx
  `;
  
  conn.exec(cmd, (err, stream) => {
    if (err) throw err;
    stream.on('close', (code, signal) => {
      console.log('Nginx setup finished.');
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
