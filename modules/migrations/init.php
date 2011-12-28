<?php

Route::set('migrations', 'migrations(/<action>(/<id>))')
  ->defaults(array(
    'controller' => 'migrations',
    'action' => 'index'
  ));
