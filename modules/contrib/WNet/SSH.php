<?php
/**
 * @file
 * File WNetSSH.php
 */

namespace WNet;

/**
 * Class SSH
 * @package WNet
 */
class SSH {

  /**
   * Server host name.
   * @var string
   */
  public $host = '127.0.0.1';

  /**
   * Server port.
   * @var string
   */
  public $port = '22';

  /**
   * Server user name.
   * @var string
   */
  public $user = 'root';

  /**
   * Server user password.
   * @var string
   */
  public $password = '';

  /**
   * Server RSA private key.
   * @var string
   */
  public $rsaKey = '';

  /**
   * Server ssh timeout.
   * @var int
   */
  public $timeout = 15;

  /**
   * Server commands.
   * @var array
   */
  public $commands = array();

  /**
   * SSH results.
   * @var array
   */
  public $sshResult = array();

  /**
   * Log token.
   * @var
   */
  public $logToken;

  /**
   * @var int
   */
  public static $instance_count = 0;

  /**
   * Declare constructor methods for class.
   */
  public function __construct() {
    self::$instance_count++;
  }

  /**
   * Clone method.
   */
  public function __clone() {
    self::$instance_count++;
  }

  /**
   * Destructor method.
   */
  public function __destruct() {
    self::$instance_count--;

    if (self::$instance_count == 0) {
      $this->sshLoggerEof();
    }
  }

  /**
   * Exec method.
   *
   * @return array
   */
  public function exec() {
    global $config;

    $results = array();

    session_write_close();

    $ssh = new \Net_SSH2($this->host, $this->port, $this->timeout);
    $key = new \Crypt_RSA();

    if (!empty($this->password)) {
      $key = $this->password;
    }
    elseif (!empty($this->rsaKey)) {

      $rsa = trim($this->rsaKey);

      if ($rsa == NULL) {
        die('RSA Key not found!');
      }

      $rsa_key = $key->loadKey($rsa);

      if (!$rsa_key) {
        die('Invalid RSA Key!.');
      }
    }
    else {
      $rsa = $config['client_rsa_private'];
      if ($rsa == NULL) {
        die('RSA Key not found! Please provide client private RSA key in client.php file.');
      }
      $rsa_key = $key->loadKey($rsa);

      if (!$rsa_key) {
        die('Invalid RSA Key!');
      }
    }

    if (!$ssh->login($this->user, $key)) {
      $results = array('0' => 'Login Failed');
    }
    else {
      if (is_array($this->commands)) {
        foreach ($this->commands as $key => $command) {
          $this->sshResult = '';

          // Clean output.
          $log_command = preg_replace('/--password=[^\s]*/is', '--password=********', $command['command']);
          $log_command = preg_replace("/IDENTIFIED BY '[^']*/is", "IDENTIFIED BY '********'", $log_command);

          $this->sshLoggerPacketHandler("\n# " . $log_command . "\n...running...\n", TRUE);

          $ssh->exec($command['command'], array($this, 'sshLoggerPacketHandler'));

          $results[$key] = $this->sshResult;
        }
        // Reset command array.
        $this->commands = array();
      }
    }
    // Restart session.
    session_start();

    if ($config['system']['logs']) {
      // Return result as array.
      //print_r(var_export($results));
    }

    return $results;
  }

  /**
   * SSH exec command callback.
   *
   * @param $data
   * @param bool $log_only
   */
  public function sshLoggerPacketHandler($data, $log_only = FALSE) {
    if (!empty($this->logToken)) {
      // Log result to file.
      $filename = '/tmp/' . $this->logToken . '.log';
      file_put_contents($filename, $data, FILE_APPEND);

      // Send result to server.
      \WForm\Data::logs($this->logToken, $data, $live = TRUE);
    }

    if (!$log_only) {
      $this->sshResult .= $data;
    }
  }

  /**
   * Set end of file marker.
   */
  public function sshLoggerEof() {
    $this->sshLoggerPacketHandler("\n" . END_OF_WRAPPY_LOG . "\n");
  }

}
