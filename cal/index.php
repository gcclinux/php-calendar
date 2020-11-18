<?php
ob_start();
session_start();

$config = include(__DIR__ . '/conf/config.php');
$passLenght = strlen($config['login']);

if (($config['dbtype'] == '') || ($config['dbname'] == '') || ($config['login'] == '') || ($config['setup'] == '')) {
	header("Location: admin/setup.php");
}


if (!ini_get('register_globals')) {
    $superglobals = array($_SERVER, $_ENV,
        $_FILES, $_COOKIE, $_POST, $_GET);
    if (isset($_SESSION)) {
        array_unshift($superglobals, $_SESSION);
    }
    foreach ($superglobals as $superglobal) {
        extract($superglobal, EXTR_SKIP);
    }
}
?>
<html>
<head>
      <title>Calendar Login</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link href="style/main.css" type="text/css" rel="stylesheet" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="js/login.js"></script>
</head>

<body onLoad="emptyCode()">
  <a><img src="img/header.jpg" alt="Header" style="width:100%;height:100px;"></a>
 <!-- Page Content -->
<div class="login-box">
  <div class="login-box-body">
    <!--<form action="" method="post">-->
        <div class="main_panel">
        <form action="session.php" method="POST">
            <div class="input-group">
              <br><br><br><br>
                <input type="text" style="text-align:center;" name="code" maxlength="8" readonly="readonly" class="form-control" placeholder="PIN">
                <br>
                <span class="input-group-btn">
                    <button class="button clear" type="reset">Clear</button>
                </span>
            </div><!-- /input-group -->

            <table id="keypad" cellpadding="5" cellspacing="3">
                <tbody>
                    <tr>
                    	<td onclick="addCode('1');">
                    	    <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>1</span>
                            </div>
                    	</td>
                        <td onclick="addCode('2');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>2</span>
                            </div>
                        </td>
                        <td onclick="addCode('3');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>3</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td onclick="addCode('4');"><div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>4</span>
                            </div>
                        </td>
                        <td onclick="addCode('5');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>5</span>
                            </div>
                        </td>
                        <td onclick="addCode('6');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>6</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                    	<td onclick="addCode('7');">
                    	    <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>7</span>
                            </div>
                        </td>
                        <td onclick="addCode('8');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>8</span>
                            </div>
                        </td>
                        <td onclick="addCode('9');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>9</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td onclick="addCode('0');">
                            <div class="button raised clickable">
                              <input type="checkbox" class="toggle"/>
                              <div class="anim"></div><span>0</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p id="message">CHECKING ACCESS...</p>
        </form>
        </div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
