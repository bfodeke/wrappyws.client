<?php
/**
 * @file
 * File Data.php
 */

namespace WClient;

class User {

  private $server_endpoint;

  private $method_name;

  private $version;

  private $hash;

  public function logIn($variables = array()) {
    global $config;

    $this->server_endpoint = $config['server_endpoint'];
    $this->method_name = urlencode($config['server_endpoint_method']);
    $this->version = urlencode($config['server_endpoint_version']);

    $vars_to_encrypt = array(
      'time' => time(),
      'salt' => md5($config['system']['salt']),
      'email' => $config['client_email'],
      'passw' => md5($config['client_password']),
      'data' => $variables
    );

    $this->hash = urlencode(\WCrypt\Data::encrypt(json_encode($vars_to_encrypt)));

    // Set the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->server_endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);

    // Turn off the server and peer verification (TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    // Set the API operation, version, and API signature in the request.
    $nvpreq = "HASH=$this->hash";

    // Set the request as a POST FIELD for curl.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

    // Get response from the server.
    $httpResponse = curl_exec($ch);

    if (!$httpResponse) {
      exit("$this->method_name failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
    }

    $data = \WCrypt\Data::decrypt($httpResponse);

    return $data;
  }

  public static function getSSLPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }

}
