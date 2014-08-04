<?php
/**
 * @file
 * File client.example.php
 */

/**
 * System settings.
 */
/* Application salt */
$config['system']['salt'] = 'DEFAULT-SALT';
/* Return system logs.  */
$config['system']['logs'] = TRUE; // Default. Required.

/**
 * Server settings.
 */
/* Server end point. */
$config['server_endpoint'] = 'https://wrappy.ws/client';
/* Server endpoint method. */
$config['server_endpoint_method'] = 'GET';
/* Server endpoint version. */
$config['server_endpoint_version'] = '1.0';

/**
 * Client settings.
 */
/* Client email address. */
$config['client_email'] = 'user@email';
/* Client password */
$config['client_password'] = 'password';
/* Client public key. */
$config['client_rsa_public'] = '';
/* Client private key. */
$config['client_rsa_private'] = '';
