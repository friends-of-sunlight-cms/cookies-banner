<?php

use Sunlight\User;
use Sunlight\Router;

return function (array $args) {
  $args['modules']['others']['modules']['cookie-consent'] = [
    'url' => Router::admin('cookie-consent'),
    'icon' => $this->getAssetPath('public/images/cookie-icon.png'),
    'access' => User::hasPrivilege('administration'),
    'label' => _lang('cookies.btn.label'),
  ];
};