const currentUrl = window.location.href;
const cookieName = 'sl_cid';
let cookiesDuration = 1;
let cookiesCidValue = 0;

document.addEventListener("DOMContentLoaded", () => {
  const consentAcceptBtn = document.getElementById('consent-accept-all');
  const consentSaveBtn = document.getElementById('consent-save');
  const consentDeclineBtn = document.getElementById('consent-decline-all');
  const consentSettingsBtn = document.getElementById('consent-settings');
  const consentSettingsBtnBack = document.getElementById('consent-settings-back');
  const consentBanner = document.getElementById('cookies-consent-banner');
  const consentCheckboxAnalytics = document.getElementById('cookies-consent-analytics');
  const consentCheckboxMarketing = document.getElementById('cookies-consent-marketing');
  const consentSettingsBtnInText = document.getElementById('cookies-settings-in-text');

  consentAcceptBtn.addEventListener('click', function() {
    setCookie(cookieName, 42, 365);

    window.location.href = currentUrl;
  }, false);

  consentDeclineBtn.addEventListener('click', function() {
    setCookie(cookieName, 0, 1);

    window.location.href = currentUrl;
  }, false);

  consentSettingsBtn.addEventListener('click', function() {
    consentBanner.classList.add('show-settings');
  }, false);

  if(consentSettingsBtnInText) {
    consentSettingsBtnInText.addEventListener('click', function() {
      consentBanner.classList.add('show-settings');
      consentBanner.classList.remove('hidden');
    }, false);
  }

  consentSaveBtn.addEventListener('click', function() {
    setCookie(cookieName, cookiesCidValue, cookiesDuration);
    window.location.href = currentUrl;
  }, false);

  consentSettingsBtnBack.addEventListener('click', function() {
    consentBanner.classList.remove('show-settings');
  }, false);

  consentCheckboxAnalytics.addEventListener('change',function(e){
    setDurationAndCid(consentCheckboxAnalytics, consentCheckboxMarketing);
   },false);

  consentCheckboxMarketing.addEventListener('change',function(e){
    setDurationAndCid(consentCheckboxAnalytics, consentCheckboxMarketing);
   },false);
})

function setCookie(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  const expires = "expires=" + date.toUTCString();
  document.cookie = name + "=" + encodeURIComponent(value) + ";" + expires + ";path=/";
}

function setDurationAndCid(checkboxAnalytics, checkboxMarketing) {
  if(checkboxAnalytics.checked && checkboxMarketing.checked) {
    cookiesDuration = 365;
    cookiesCidValue = 42;
  } else if(checkboxAnalytics.checked) {
    cookiesDuration = 7;
    cookiesCidValue = 1;
  } else if(checkboxMarketing.checked) {
    cookiesDuration = 7;
    cookiesCidValue = 2;
  } else {
    cookiesDuration = 1;
    cookiesCidValue = 0;
  }
}