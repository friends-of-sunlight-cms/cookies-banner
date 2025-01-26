<?php
use Sunlight\Database\Database as DB;
use Sunlight\Template;

return function (array $args) {
  $scriptsQuery = DB::query("SELECT * FROM ".DB::table('cookies_scripts')." WHERE published=1");
  $settingsQuery = DB::queryRow("SELECT * FROM ".DB::table('cookies_settings')." WHERE id=1");

  $bannerAddedClass = '';
  if($settingsQuery['page_id'] == Template::currentID()) {
    $bannerAddedClass = ' hidden ';
  }

  if($bannerAddedClass == true || (isset($_COOKIE['sl_cid']) == false && DB::size($scriptsQuery) != 0)) {
    $boxAnalytics = $boxMarketing = '';
    
    if(isset($_COOKIE['sl_cid']) && $_COOKIE['sl_cid'] == 42) {
      $boxAnalytics = $boxMarketing = ' checked ';
    } else if(isset($_COOKIE['sl_cid']) && $_COOKIE['sl_cid'] == 1) {
      $boxAnalytics = ' checked ';
    } else if(isset($_COOKIE['sl_cid']) && $_COOKIE['sl_cid'] == 2) {
      $boxMarketing = ' checked ';
    }

    $args['output'] .= '<div id="cookies-consent-banner" class="consent-wrap-full '.$bannerAddedClass.'">';
    $args['output'] .= '<div class="consent-inner-wrap">';
    $args['output'] .= '<div class="consent-text">';
    $args['output'] .= '<h2>'.$settingsQuery['headline'].'</h2>';
    $args['output'] .= $settingsQuery['text'];
    if($settingsQuery['page_id']) {
      $pageQuery = DB::queryRow("SELECT `slug` FROM ".DB::table('page')." WHERE id=".$settingsQuery['page_id']);
      $args['output'] .= '<a href="/'.$pageQuery['slug'].'">'._lang('cookies.banner.readmore').'</a>';
    }
    $args['output'] .= '</div>';

    $args['output'] .= '<div id="settings-tab" class="settings-tab">';

    $args['output'] .= '<div class="cookie-line">';
    $args['output'] .= '<h3>'._lang('cookies.banner.type.technical.headline').'</h3>';
    $args['output'] .= '<div class="cookie-desc">'._lang('cookies.banner.type.technical.desc').'</div>';
    $args['output'] .= '<input type="checkbox" checked disabled>';
    $args['output'] .= '</div>';

    $args['output'] .= '<div class="cookie-line">';
    $args['output'] .= '<h3>'._lang('cookies.banner.type.analytics.headline').'</h3>';
    $args['output'] .= '<div class="cookie-desc">'._lang('cookies.banner.type.analytics.desc').'</div>';
    $args['output'] .= '<input id="cookies-consent-analytics" type="checkbox" '.$boxAnalytics.'>';
    $args['output'] .= '</div>';

    $args['output'] .= '<div class="cookie-line">';
    $args['output'] .= '<h3>'._lang('cookies.banner.type.marketing.headline').'</h3>';
    $args['output'] .= '<div class="cookie-desc">'._lang('cookies.banner.type.marketing.desc').'</div>';
    $args['output'] .= '<input id="cookies-consent-marketing" type="checkbox" '.$boxMarketing.'>';
    $args['output'] .= '</div>';
    
    $args['output'] .= '</div>';

    $args['output'] .= '<div id="consent-btns" class="consent-btns">';
    $args['output'] .= '<span id="consent-accept-all" class="consent-accept">'.$settingsQuery['btn_accept'].'</span>';

    $args['output'] .= '<span id="consent-save" class="consent-accept">'._lang('global.save').'</span>';

    $args['output'] .= '<span id="consent-decline-all" class="consent-decline-all">'.$settingsQuery['btn_decline'].'</span>';

    $args['output'] .= '<span id="consent-settings" class="consent-settings">'.$settingsQuery['btn_settings'].'</span>';

    $args['output'] .= '<span id="consent-settings-back" class="consent-settings">'._lang('global.return').'</span>';
    $args['output'] .= '</div>';
    
    $args['output'] .= '</div>';
    $args['output'] .= '</div>';
  }

  if(isset($_COOKIE['sl_cid']) && $_COOKIE['sl_cid'] > 0) {
    $scriptsQuery = DB::query("SELECT * FROM ".DB::table('cookies_scripts')." WHERE position=2 AND published=1");

    if (DB::size($scriptsQuery) != 0) {
      while ($row = DB::row($scriptsQuery)) {
        if($_COOKIE['sl_cid'] == 42 || $_COOKIE['sl_cid'] == $row['type']) {
          $args['output'] .= $row['code'];
        }
      };
    };
  }
};