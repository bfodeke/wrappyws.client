<?php
/**
 * @file
 * File Contact.php
 */

namespace Contact;

use WClient\User;

class Form {

  public function render() {
    // Get form variables.
    $vars = \WForm\Data::get($form_id = 'client_demo_form');

    // Implements SSH connection.
    $ssh = new \Net_SSH2($vars['#form_state']['#values']['host'], 22, 15);
    if (!$ssh->login($vars['#form_state']['#values']['user'], $vars['#form_state']['#values']['password'])) {
      $results = array('0' => 'Login Failed');
    }
    else {
      $results = $ssh->exec($vars['#form_state']['#values']['command'] , array($this, 'loggerPacketHandler'));
    }
  }

  public function loggerPacketHandler($data) {
    $filename = '/tmp/demo01.log';

    if (file_exists($filename)) {
      //unlink($filename);
    }

    file_put_contents($filename, $data, FILE_APPEND);
  }

}