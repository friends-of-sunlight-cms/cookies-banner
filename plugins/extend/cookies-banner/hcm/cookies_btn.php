<?php

return function() {
  $output = '';
  $output .= '<div class="settings-in-text">';
  $output .= '<span id="cookies-settings-in-text" class="cookies-settings-btn">'._lang('cookies.settings.intext').'</span>';
  $output .= '</div>';

  return $output;
};