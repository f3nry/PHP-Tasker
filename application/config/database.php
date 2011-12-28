<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
  'default' => array(
    'type' => 'mysql',
    'connection' => array(
      'hostname'   => 'localhost',
      'database'   => 'phptasker',
      'username'   => 'root',
      'password'   => false,
      'persistent' => false
    ),
    'table_prefix' => '',
    'charset'      => 'utf8',
    'caching'      => false,
    'profiling'    => true,
  )
);
