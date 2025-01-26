<?php
use Sunlight\Util\Form;
use Sunlight\Xsrf;
use Sunlight\Router;
use Sunlight\Database\Database as DB;

defined('SL_ROOT') or exit;

$message = '';
if (!isset($_GET['id'])) {
  $output .= Message::error(_lang('global.badinput'));
  return;
}

$id = $_GET['id'];
$query = DB::queryRow('SELECT `name` FROM '.DB::table('cookies_scripts').' WHERE id='.$id);

// delete
if (isset($_POST['delete_confirmed'])) {
  DB::delete('cookies_scripts', 'id='.$id);

  // redirect
  $_admin->redirect(Router::admin('cookie-consent', ['query' => ['script_deleted' => 1]]));
  return;
}

// output
$output .= '
<form class="cform" method="post">
'.Form::input('hidden', 'delete_confirmed', '1').'

<h4>'._lang('cookies.delete.confirm').'</h4><br>
<h2>'.$query['name'].'?</h2><br>
'.Form::input('submit', null, _lang('global.confirmdelete')).
Xsrf::getInput().'</form>';