<?php

namespace miradnan\ssh;

use Exception;

/**
 * Description of SSH
 *
 * @author miradnan
 */
class SSH {

    // SSH Host 
    private $ssh_host;
    // SSH Port 
    private $ssh_port = 22;
    // SSH Server Fingerprint 
    private $ssh_server_fp;
    // SSH Username 
    private $ssh_auth_user;
    // SSH Public Key File 
    private $ssh_auth_pub = '/home/%s/.ssh/id_rsa.pub';
    // SSH Private Key File 
    private $ssh_auth_priv = '/home/%s/.ssh/id_rsa';
    // SSH Private Key Passphrase (null == no passphrase) 
    private $ssh_auth_pass;
    // SSH Connection 
    private $connection;

    /**
     * 
     * @param type $hostname
     * @param type $username
     * @param type $password
     * @return type
     * @throws Exception
     */
    public static function connect($hostname, $username, $password) {
        if (!function_exists('ssh2_connect')) {
            throw new Exception('You don\'t have ssh2 extension installed. Please install and try again');
        }

        $ssh = new self;
        $ssh->ssh_host = $hostname;
        $ssh->ssh_auth_user = $username;
        $ssh->ssh_auth_pass = $password;
        
        $ssh->__connect();
        return $ssh;
    }

    /**
     * 
     * @throws Exception
     */
    protected function __connect() {

        if (!($this->connection = ssh2_connect($this->ssh_host, $this->ssh_port))) {
            throw new Exception('Cannot connect to server');
        }
        $fingerprint = ssh2_fingerprint($this->connection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
        if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
            //throw new Exception('Unable to verify server identity!');
        }
        //pr($this);
        if (!ssh2_auth_password($this->connection, $this->ssh_auth_user, $this->ssh_auth_pass)) {
            throw new Exception('Autentication rejected by server');
        }
    }

    /**
     * 
     * @param type $cmd
     * @return type
     * @throws \Exception
     */
    public function exec($cmd) {
        if (!($stream = ssh2_exec($this->connection, $cmd))) {
            throw new Exception('SSH command failed');
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return $data;
    }

    /**
     * 
     */
    public function disconnect() {
        $this->exec('echo "EXITING" && exit;');
        $this->connection = null;
    }

    /**
     * 
     */
    public function __destruct() {
        $this->disconnect();
    }

}
