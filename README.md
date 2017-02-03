# php-ssh2
PHP SSH2 Class


```php
$ssh = SSH::connect('test.example.com', 'username', 'secure_password');
$cmd = 'pwd';
// . ' > /dev/null &'; // if you want to execute as a background job
$cmd = 'python2 /home/ubuntu/RecieptBot/test.py ' . ($file->filename) . ' > /dev/null &';
$output = $ssh->exec($cmd);
```
