<?php

require_once WRAPPY_ROOT . '/core/vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

// Define wrappy.ws modules.
$loader->registerNamespaces(array(
  // Contrib module.
  'WRoutes' =>  array(WRAPPY_ROOT . '/modules/contrib'),
  'WCrypt' =>  array(WRAPPY_ROOT . '/modules/contrib'),
  'WClient' =>  array(WRAPPY_ROOT . '/modules/contrib'),
  'WForm' =>  array(WRAPPY_ROOT . '/modules/contrib'),

  // Custom modules.
  'Contact' =>  array(WRAPPY_ROOT . '/modules/custom'),
));


$loader->useIncludePath(true);
$loader->register();
