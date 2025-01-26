<?php
use Sunlight\Util\Request;
use Sunlight\Database\Database as DB;
use Sunlight\Util\Form;
use Sunlight\Xsrf;
use Sunlight\Router;
use Sunlight\Admin\Admin;
use Sunlight\Message;
use Sunlight\Util\SelectOption;

$new = true;

$id = null;

$query = [
  'name' => '',
  'code' => '',
  'type' => 0,
  'published' => 1,
  'position' => 1
];

$message = '';
if (isset($_GET['script_created'])) {
  $message = Message::ok(_lang('cookies.created.done'));
} else if(isset($_GET['script_updated'])) {
  $message = Message::ok(_lang('cookies.updated.done'));
}

if (isset($_GET['id'])) {
  $id = (int) Request::get('id');
  $query = DB::queryRow('SELECT * FROM '.DB::table('cookies_scripts').' WHERE id='.$id);
  $new = false;
}

// save
if (!empty($_POST)) {
  $changeset = [];
  $changeset['name'] = Request::post('name');
  $changeset['code'] = Request::post('code');
  $changeset['type'] = Request::post('type');
  $changeset['published'] = Form::loadCheckbox('published');
  $changeset['position'] = Request::post('position');

  if (!$new) {
    DB::update('cookies_scripts', 'id=' . $id, $changeset);

    $_admin->redirect(Router::admin('cookie-script-edit', ['query' => ['id' => $id, 'script_updated' => true]]));
  } else {
    DB::insert('cookies_scripts', $changeset);

    $_admin->redirect(Router::admin('cookie-script-edit', (['query' => ['script_created' => true]])));
  }

  return;
}


if($message) {
  $output .= '<h2>'.$message.'</h2>';
}

$typeChoices = [
  new SelectOption('0', _lang('global.choice'), ['disabled' => 1]),
  new SelectOption('1', _lang('cookies.type.analytics')),
  new SelectOption('2', _lang('cookies.type.marketing'))
];

$output .= '<form method="post" action="'._e(Router::admin('cookie-script-edit', (!$new ? ['query' => ['id' => $id]] : null))).'">';
$output .= '<table class="formtable edittable">';
$output .= '<tbody>';
$output .= '<tr>';
$output .= '<th>'._lang('cookies.list.name').'</th>';
$output .= '<td>'.Form::input('text', 'name', Request::post('name', $query['name']), ['class' => 'inputbig']).'</td>';
$output .= '</tr>';
$output .= '<tr>';
$output .= '<th>'._lang('cookies.list.type').'</th>';
$output .= '<td>'.Form::select('type', $typeChoices, Request::post('type', $query['type']), ['class' => 'inputbig']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.list.position').'</th>';
$output .= '<td>'.Form::select('position', [1 => _lang('cookies.position.head'), 2 => _lang('cookies.position.footer')], Request::post('position', $query['position']), ['class' => 'inputbig']).'</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.list.code').'</th>';
$output .= '<td>'.Admin::editor('code', 'code', $query['code'], ['mode' => 'lite', 'rows' => 9, 'class' => 'areabigperex']) . '</td>';
$output .= '</tr>';

$output .= '<tr>';
$output .= '<th>'._lang('cookies.list.published').'</th>';
$output .= '<td>'.Form::input('checkbox', 'published', 1, ['checked' => Form::loadCheckbox('published', $query['published'], 'published')]).'</td>';
$output .= '</tr>';

$output .= '</tbody></table>';


$output .= Form::input('submit', null, _lang('global.save'), ['class' => 'button bigger', 'accesskey' => 's']);
$output .= Xsrf::getInput();
$output .= '</form>';