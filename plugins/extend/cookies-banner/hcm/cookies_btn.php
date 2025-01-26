<?php

return function() {
  $output = '';
  $output .= '<div class="settings-in-text">';
  $output .= '<span id="cookies-settings-in-text" class="cookies-settings-btn">'._lang('cookies.settings.intext').'</span>';
  $output .= '</div>';

  $output .= '<script>';
  $output .= 'document.addEventListener("DOMContentLoaded", () => {';
  $output .= 'const consentSettingsBtnInText = document.getElementById("cookies-settings-in-text");';
  $output .= 'consentSettingsBtnInText.addEventListener("click", function() {
    consentBanner.classList.add("show-settings");
    consentBanner.classList.remove("hidden");
  }, false);';
  $output .= '});';
  $output .= '</script>';

  return $output;
};