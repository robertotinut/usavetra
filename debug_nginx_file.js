import { Client } from 'ssh2';

const conn = new Client();
conn.on('ready', () => {
  const cmd = `ls -la /etc/nginx/sites-available/usavetra && cat /etc/nginx/sites-available/usavetra`;
  
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
