<?php 
require('./_app/config.inc.php');
$session = new Session;
 ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>-- Screen For git hub --</title>
</head>
<body>
<div class="main">
<?php 
$login = new Login(1);
if ($login->checkLogin()) {
	header('Location: http://www.google.com');
}

$data = filter_input_array( INPUT_POST, FILTER_DEFAULT);

if (!empty($data)) {
	unset($data['postForm']);
	$login->exeLogin($data);
}

var_dump($login);
 ?>
<form name="PostForm" method="post" action="" enctype="multipart/formdata">
	<label>
		<span>Email:</span>
		<input type="text" name="name"/>
	</label>
	<label>
		<span>Senha:</span>
		<input type="text" name="pass"/>
	</label>
	<input type="submit" value="Logar" name="postForm"/>
	<a href="" title="recovery" >Recuperar Senha</a>
</form>
</div>
</body>
</html>