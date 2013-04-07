<?php
session_start();
ini_set('display_errors', 1);
error_reporting (E_ALL); 

require_once "lib/strpcapi.php";
require_once "lib/invfoxapi.php";


$apidef = array(
		'invoice-sent' => array('select-all', 'insert-into', 'update', 'select-one'),
		'invoice-recv' => array('select-all'),
		'partner' => array('select-all'),
		'preinvoice' => array('select-all')
		); 

if (isset($_POST['setToken'])) { $_SESSION['apitoken'] = $_POST['token']; }
if (isset($_POST['clearToken'])) { unset($_SESSION['apitoken']); unset($_SESSION['history']); }
if (isset($_GET['setDebug'])) { $_SESSION['debugmode'] = 1; }
if (isset($_GET['clearDebug'])) { unset($_SESSION['debugmode']);}

$RESOURCE = isset($_POST['resource']) ? $_POST['resource'] : '';
$METHOD = isset($_POST['method']) ? $_POST['method'] : '';
$ARGS = isset($_POST['args']) ? $_POST['args'] : '';
$FORMAT = isset($_POST['format']) ? $_POST['format'] : '';
$EXPLORE = isset($_POST['explore']) ? $_POST['explore'] : '';

include "style/header.php";

if (isset($_POST['call']) && isset($_SESSION['apitoken'])) {
  $strpc = new StrpcAPI($_SESSION['apitoken'], "www.invoicefox.com", isset($_SESSION['debugmode']));
  $_SESSION['history'][] = array('resource'=>$RESOURCE, 'method'=>$METHOD, 'args'=>$ARGS, 'format'=>$FORMAT, 'explore'=>$EXPLORE);
  $response = $strpc->call($RESOURCE, $METHOD, $ARGS, $FORMAT, $EXPLORE);
  echo "<br clear='both' /><div class='type'>response</div><pre class='resp'>"; print_r($response->res); echo "</pre>";
}

?>
<form method="post">
  <div class="main">
  
  <div>
  <small style="float: right;"><b>examples</b><br/><?php foreach ( $apidef as $res => $meths ) { echo $res . " "; } ?></small>
resource:<br/>
<input name="resource" value="<?= $RESOURCE ?>" /></div>
  
  <div>
  <small style="float: right;"><b>examples</b><br/><?php $b=0; foreach ( $apidef as $res => $meths ) { foreach ( $meths as $meth ) { echo $meth." "; if ($b>=3) break; $b += 1; } if ($b>=3) break;} ?></small>
method: <br/><input name="method"  value="<?= $METHOD ?>"/></div>
  
  <div><small style="float: right;"><b>example</b><br/>prop1:val1<br/>prop2:val2<br/> prop3:val3</small>arguments: <br/>
  <textarea name="args" cols="60" rows="10"><?= $ARGS ?></textarea></div>
  
  <div><label><input type="checkbox" name="explore" value="1"> explore mode</label> </div>
  
  <input type="submit" name="call" value="Call" <?= !isset($_SESSION['apitoken'])?'disabled="disabled"':''; ?> /></div>
  </div>
  </form>
<?php if (isset($_SESSION['history'])) { ?>
<h3>History</h3>
<?php 
  foreach($_SESSION['history'] as $h) {
    echo "<form class='line' method='post'>";
    foreach($h as $n => $v) { echo "<input name='$n' value='$v' /> "; }
    echo "<input type='submit' value='Load' name='load' style='background-color: #d8d8c5' /> <input type='submit' value='Repeat' name='call' style='background-color: #d8d8c5' /></form>";
  }
?>
<?php } ?>
<?php include "style/footer.php"; ?>
