<?php
/**
 * @file
 * File Data.php
 */

namespace WCrypt;

/**
 * Class Data
 * @package WCrypt
 */
class Data {

  /**
   * Encrypt string.
   *
   * @param $string
   * @return null|string
   */
  public static function encrypt($string) {
    global $config;

    $password = NULL;
    if (!empty($string)) {
      $key = md5($config['system']['salt']);
      $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
      $password = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB, $iv)));
    }

    return $password;
  }

  /**
   * Decrypt string.
   *
   * @param $string
   * @return string
   */
  public static function decrypt($string) {
    global $config;

    $ivsize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($ivsize, MCRYPT_RAND);
    $key = md5($config['system']['salt']);

    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB, $iv));
  }

}
