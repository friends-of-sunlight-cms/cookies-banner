<?php 
use Sunlight\Router;
use Sunlight\Database\Database as DB;
use Sunlight\Message;
use Sunlight\Admin\Admin;
use Sunlight\Util\Request;
use Sunlight\Util\Form;
use Sunlight\Xsrf;
use Sunlight\Util\SelectOption;

defined('SL_ROOT') or exit;


$message = '';
if (isset($_GET['script_deleted'])) {
  $message = Message::ok(_lang('cookies.delete.done'));
}

if (isset($_GET['settings_updated'])) {
  $message = Message::ok(_lang('cookies.settings.updated'));
}

if($message) {
  $output .= $message;
}

$settingsQuery = DB::queryRow("SELECT * FROM ".DB::table('cookies_settings')." WHERE id=1");

if($settingsQuery == false) {
  $newset = [];
  $newset['id'] = 1;
  $newset['headline'] = _lang('cookies.banner.headline');
  $newset['text'] = '<p>'._lang('cookies.banner.text').'</p>';
  $newset['btn_accept'] = _lang('cookies.banner.btn.accept');
  $newset['btn_decline'] = _lang('cookies.banner.btn.decline');
  $newset['btn_settings'] = _lang('cookies.banner.btn.settings');

  DB::insert('cookies_settings', $newset);

  $settingsQuery = DB::queryRow("SELECT * FROM ".DB::table('cookies_settings')." WHERE id=1");
}

if (!empty($_POST)) {
  $changeset = [];
  $changeset['headline'] = Request::post('headline');
  $changeset['btn_accept'] = Request::post('btn_accept');
  $changeset['btn_decline'] = Request::post('btn_decline');
  $changeset['btn_settings'] = Request::post('btn_settings');
  $changeset['text'] = Request::post('text');
  $changeset['page_id'] = Request::post('page_id', null);

  // save
  DB::update('cookies_settings', 'id=1', $changeset);

  // redirect
  $_admin->redirect(Router::admin('cookie-consent', ['query' => ['settings_updated' => true]]));

  return;
}

$scriptsQuery = DB::query("SELECT `id`, `name`, `type`, `published`, `position` FROM ".DB::table('cookies_scripts')." ORDER BY `id`");

$pagesQuery = DB::query("SELECT `id`, `slug` FROM ".DB::table('page')." ORDER BY `id`");

$pageOptions = [
  new SelectOption('0', _lang('global.choice'), ['disabled' => 1])
];

while ($row = DB::row($pagesQuery)) {
  array_push($pageOptions, new SelectOption($row['id'], $row['slug']));
}

$output .= '<table class="formtable edittable">';
$output .= '<tbody>';
$output .= '<tr class="valign-top">';
$output .= '<td class="contenttable-box" style="width: 50%">';
$output .= '<h2 class="bborder">';
$output .= _lang('cookies.list.title');
$output .= ' <a class="button" href="'._e(Router::admin('cookie-script-edit')).'">';
$output .= '<img src="'._e(Router::path('admin/public/images/icons/new.png')).'" alt="new" class="icon">';
$output .= _lang('cookies.add.title');
$output .= '</a></h2>';
$output .= '<table id="contenttable">';
$output .= '<thead>';
$output .= '<tr>';
$output .= '<th>ID</th>';
$output .= '<th>'._lang('cookies.list.name').'</th>';
$output .= '<th>'._lang('cookies.list.type').'</th>';
$output .= '<th>'._lang('cookies.list.position').'</th>';
$output .= '<th>'._lang('cookies.list.published').'</th>';
$output .= '<th>'._lang('cookies.list.action').'</th>';
$output .= '</tr>';
$output .= '</thead>';
$output .= '<tbody>';

if (DB::size($scriptsQuery) != 0) {
  while ($row = DB::row($scriptsQuery)) {
    $scriptType = $row['type'] == 1 ? _lang('cookies.type.analytics') : _lang('cookies.type.marketing');
    $scriptPosition = $row['position'] == 1 ? _lang('cookies.position.head') : _lang('cookies.position.footer');
    $scriptPublished = $row['published'] == 1 ? _lang('global.yes') : _lang('global.no');

    $output .= '<tr>';
    $output .= '<td>'.$row['id'].'</td>';
    $output .= '<td><strong>'.$row['name'].'</strong></td>';
    $output .= '<td>'.$scriptType.'</td>';
    $output .= '<td>'.$scriptPosition.'</td>';
    $output .= '<td>'.$scriptPublished.'</td>';
    $output .= '<td>';
    $output .= '<a class="button" href="'._e(Router::admin('cookie-script-edit', ['query' => ['id' => $row['id']]])).'">';
    $output .= '<img src="'._e(Router::path('admin/public/images/icons/edit.png')).'" alt="edit" class="icon">';
    $output .= _lang('global.edit');
    $output .= '</a>';
    $output .= '  ';
    $output .= '<a class="button" href="'._e(Router::admin('cookie-script-delete', ['query' => ['id' => $row['id']]])).'">';
    $output .= '<img src="'._e(Router::path('admin/public/images/icons/delete.png')).'" alt="delete" class="icon">';
    $output .= _lang('global.delete');
    $output .= '</a>';
    $output .= '</td>';
    $output .= '</tr>';
  };
};
$output .= '</tbody>';
$output .= '</table>';
$output .= '</td>';

$output .= '<td class="contenttable-box" style="width: 50%">';
$output .= '<h2 class="bborder">Nastavení</h2>';
$output .= '<form method="post" action="'._e(Router::admin('cookie-consent')).'">';
$output .= '<table><tbody>';
$output .= '<tr>';
$output .= '<th>'._lang('cookies.settings.headline').'</th>';
$output .= '<td>'.Form::input('text', 'headline', Request::post('headline', $settingsQuery['headline']), ['class' => 'inputmedium']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.settings.btn.accept').'</th>';
$output .= '<td>'.Form::input('text', 'btn_accept', Request::post('btn_accept', $settingsQuery['btn_accept']), ['class' => 'inputmedium']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.settings.btn.decline').'</th>';
$output .= '<td>'.Form::input('text', 'btn_decline', Request::post('btn_decline', $settingsQuery['btn_decline']), ['class' => 'inputmedium']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.settings.btn.settings').'</th>';
$output .= '<td>'.Form::input('text', 'btn_settings', Request::post('btn_settings', $settingsQuery['btn_settings']), ['class' => 'inputmedium']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.settings.page').'</th>';
$output .= '<td>'.Form::select('page_id', $pageOptions, Request::post('page_id', $settingsQuery['page_id']), ['class' => 'inputmedium']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.settings.text').'</th>';
$output .= '<td>'.Admin::editor('text', 'text', Request::post('text', $settingsQuery['text']), ['rows' => 9, 'cols' => 33, 'class' => 'areamedium']).'</td>';
$output .= '</tr>';
$output .= '</tbody>';
$output .= '</table>';
$output .= Form::input('submit', null, _lang('global.save'), ['class' => 'button bigger', 'accesskey' => 's']);
$output .= Xsrf::getInput();
$output .= '</form>';
$output .= '</td>';
$output .= '</tr>';
$output .= '</tbody>';
$output .= '</table>';