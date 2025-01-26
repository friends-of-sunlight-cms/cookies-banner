<?php

use Sunlight\User;

return function (array $args) {
  $args['admin']->modules['cookie-consent'] = [
    'title' => _lang('cookies.tab.title'),
    'parent' => 'content',
    'access' => User::hasPrivilege('administration'),
    'script' => __DIR__ . DIRECTORY_SEPARATOR . '../script/cookie-consent.php'
  ];
  $args['admin']->modules['cookie-script-edit'] = [
    'title' => _lang('cookies.add.title'),
    'parent' => 'cookie-consent',
    'access' => User::hasPrivilege('administration'),
    'script' => __DIR__ . DIRECTORY_SEPARATOR . '../script/cookie-script-edit.php'
  ];
  $args['admin']->modules['cookie-script-delete'] = [
    'title' => _lang('cookies.delete.title'),
    'parent' => 'cookie-consent',
    'access' => User::hasPrivilege('administration'),
    'script' => __DIR__ . DIRECTORY_SEPARATOR . '../script/cookie-script-delete.php'
  ];
};