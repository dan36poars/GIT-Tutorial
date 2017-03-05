<?php
ob_start();
session_start();
require('../_app/config.inc.php');
$login = new Login(2);
 ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>-- Login for the System Painel --</title>
</head>
<body>
<div class="main">
<?php 
if ($login->checkLogin()) {
	header('Location: painel.php');
}

$data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($data['inputPostForm'])) {
	unset($data['inputPostForm']);
	$login->exeLogin($data);
	if (!$login->getResult()) {
		WS_Error($login->getError()[0], $login->getError()[1]);
	}else{
		header('Location: painel.php');
	}
}

$exe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
if (!empty($exe)) {
	if ($exe == 'restrito') {
		WS_Error(WS_ALERT, "<b>Opsss!</b> Você não tem permissão para acessar está área.");
	}elseif($exe == 'logoff'){
		WS_Error(WS_ACCEPT, "Obrigado por se deslogar do sistema, volte sempre!");	
	}
}

 ?>
<form name="PostForm" method="post" action="" enctype="multipart/formdata">
	<label>
		<span>Email:</span>
		<input type="text" name="email"/>
	</label>
	<label>
		<span>Senha:</span>
		<input type="text" name="pass"/>
	</label>
	<input type="submit" value="Logar" name="inputPostForm"/>
	<a href="" title="recovery" >Recuperar Senha</a>
</form>
</div>
</body>
</html>
<?php ob_end_flush(); ?>