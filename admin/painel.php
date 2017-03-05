<?php
ob_start();
require('../_app/Config.inc.php');
$session = new Session;
$login = new Login(2);

$logoff = FILTER_INPUT(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);


if (!$login->checkLogin()) {
	unset($_SESSION['userlogin']);
	header("Location: index.php?exe=restrito");
}else{
	$userlogin = $_SESSION['userlogin'];
}

if ($logoff) {
	unset($_SESSION['userlogin']);
	header("Location: index.php?exe=logoff");
}
echo "Você esta logado";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
<a title="deslogar" href="painel.php?logoff=true">Deslogar</a>
</body>
</html>
<?php ob_end_flush(); ?>
