<?php
/**
 * @file
 * File index.php
 */
error_reporting(E_ERROR);
ini_set('display_errors', 1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

define('WRAPPY_ROOT', getcwd());

/* Load autoloader. */
require 'core/vendor/autoload.php';

/* Load default settings. */
require 'config/client.php';

/* Load modules. */
require 'config/modules.php';


try {
  global $config;
  $salt = md5($config['system']['salt']);

  $request = Request::createFromGlobals();
  $getParams = $request->query->get('q');

  /* Define the routes for our application */
  $routes = new RouteCollection();
  $routes->add('api/v1', new Route('/api/v1/{hash}', array('hash' => $getParams)));
  $context = new RequestContext();
  $context->fromRequest($request);

  $matcher = new UrlMatcher($routes, $context);
  $match = $matcher->match($request->getPathInfo());

  if (!empty($match['_route']) && !empty($match['hash'])) {
    $data = WCrypt\Data::decrypt($match['hash']);

    $variables = explode(':', $data);

    if ($variables[1] == $salt) {
      $namespace = $variables[2];
      $method = $variables[3];
      $obj = new $namespace;

      call_user_func(array($obj, &$method));
    }
    else {
      exception('Error: Hash validation failed.');
    }
  }

} catch (Exception $e) {
  $message = "We're sorry, but something went wrong.";
  exception($message);
  //throw $e;
}

function exception($message) {
  // For 4.3.0 <= PHP <= 5.4.0
  if (!function_exists('http_response_code')) {
    function http_response_code($newcode = NULL) {
      static $code = 500;
      if ($newcode !== NULL) {
        header('X-PHP-Response-Code: ' . $newcode, TRUE, $newcode);
        if (!headers_sent()) {
          $code = $newcode;
        }
      }
      return $code;
    }
  }
  else {
    http_response_code(500);
  }

  print $message;
}


