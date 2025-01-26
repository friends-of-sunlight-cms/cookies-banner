<?php
use Sunlight\Template;
use Sunlight\Database\Database as DB;
use Sunlight\Core;
use Sunlight\Settings;

return function(array $args) {
  $settingsQuery = DB::queryRow("SELECT * FROM ".DB::table('cookies_settings')." WHERE id=1");
  $scriptsQuery = DB::query("SELECT * FROM ".DB::table('cookies_scripts')." WHERE published=1");

  if($settingsQuery['page_id'] == Template::currentID() || (isset($_COOKIE['sl_cid']) == false && DB::size($scriptsQuery) != 0)) {
	  $args['css_before'] .= '<link rel="stylesheet" href="/plugins/extend/cookies-banner/public/consent_style.css?v='.substr(hash_hmac('sha256', Core::VERSION.'$'.Settings::get('cacheid'), Core::$secret), 0, 8).'" type="text/css">';
	
    $args['js_after'] .= '<script src="/plugins/extend/cookies-banner/public/consent_script.js?v='.substr(hash_hmac('sha256', Core::VERSION.'$'.Settings::get('cacheid'), Core::$secret), 0, 8).'"></script>';
  }

  if(isset($_COOKIE['sl_cid']) && $_COOKIE['sl_cid'] > 0) {
    $scriptsQuery = DB::query("SELECT * FROM ".DB::table('cookies_scripts')." WHERE position=1 AND published=1");

    if (DB::size($scriptsQuery) != 0) {
      while ($row = DB::row($scriptsQuery)) {
        if($_COOKIE['sl_cid'] == 42 || $_COOKIE['sl_cid'] == $row['type']) {
          $args['js_after'] .= $row['code'];
        }
      };
    };
  }
};