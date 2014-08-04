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

    $ssh = new \WNet\SSH();
    $ssh->host = $vars['#form_state']['#values']['host'];
    $ssh->user = $vars['#form_state']['#values']['user'];
    //$ssh->password = $vars['#form_state']['#values']['password'];
    $ssh->port = 22;
    $ssh->timeout = 15;
    $ssh->commands = array('exec' => array('command' => $vars['#form_state']['#values']['command']));
    $ssh->logToken = '963b6b6f64cf2e5e3fc40e99f67b3abf';
    $ssh->exec();
  }

}
