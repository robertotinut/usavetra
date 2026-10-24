import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  console.log('SSH Connected. Checking VPS repository state...');
  const cmd = `
    echo "=== Current working dir git info in /var/www/usavetra ==="
    cd /var/www/usavetra
    git status || echo "No git repo in /var/www/usavetra"
    git remote -v || true
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
