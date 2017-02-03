# A simple wrapper to connect to another instance using php-ssh2 and execute commands

You'll need libssh2

For Debain running PHP 5
```
$ apt-get install php5-ssh2
```
For machines running PHP 7
```
$ sudo apt-get install libssh2–1-dev libssh2–1
```

Once you're done restart nginx / apache
```
$ /etc/init.d/nginx restart
```


```php
use miradnan\ssh2\SSH;

$ssh = SSH::connect('test.example.com', 'username', 'secure_password');
$cmd = 'pwd';
// . ' > /dev/null &'; // if you want to execute as a background job
$output = $ssh->exec($cmd);
```
The SSH object automatically closes the ssh connection.
