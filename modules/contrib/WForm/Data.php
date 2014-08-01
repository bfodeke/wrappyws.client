<?php
/**
 * @file
 * File Data.php
 */

namespace WForm;

class Data {

  public static function get($form_id) {
    $user = new \WClient\User();
    $login = $user->logIn($variables = array('form_id' => $form_id));

    return $vars = json_decode($login, TRUE);
  }

}
