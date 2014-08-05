<?php
/**
 * @file
 * File Data.php
 */

namespace WForm;

/**
 * Class Data
 * @package WForm
 */
class Data {

  /**
   * Get form values.
   * @param $form_id
   * @return mixed
   */
  public static function get($form_id) {
    $user = new \WClient\User();
    $login = $user->logIn($variables = array('form_id' => $form_id));

    return $vars = json_decode($login, TRUE);
  }

  /**
   * Set logs.
   * @param $result
   * @return mixed
   */
  public static function logs($token, $result, $live = TRUE) {
    if ($live) {
      echo($result);
    }
    else {
      $user = new \WClient\User();
      $login = $user->logIn($variables = array('result' => print_r($result, true), 'token' => $token), true);

      return $vars = json_decode($login, TRUE);
    }
  }

}
