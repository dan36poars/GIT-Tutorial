<?php 
// CONFIGURAÇÕES DO BANCO
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBSA','bd-01');

//AUTOLOAD TO CLASSES
	/**
	 * __autoload
	 * @return void
	 */
	function __autoload($Class) {
			$ConfigDir = ['_helpers', '_models', '_conn'];
			$IncludeDir = null;
			foreach ($ConfigDir as $DirName) {
				if (!$IncludeDir && file_exists(__DIR__.DIRECTORY_SEPARATOR.$DirName.DIRECTORY_SEPARATOR.$Class.".class.php") && !is_dir($DirName)) {
					include_once(__DIR__.DIRECTORY_SEPARATOR.$DirName.DIRECTORY_SEPARATOR.$Class.".class.php");
					$IncludeDir = true;
				}
			}

			if (!$IncludeDir) {
				trigger_error("Erro de inclusão: {$Class}.class.php", E_USER_ERROR);
				die();
			}
		}

// CONSTANTES DO SITE
define('WS_ACCEPT', 'accept');
define('WS_ALERT', 'alert');
define('WS_INFOR', 'infor');
define('WS_ERROR', 'error');

//TRATAMENTO DE EXCESSOES
	/**
	 * WS_Error
	 * disparar erros para o front
	 * @return void
	 */
	function WS_Error($ErrNo , $Msg, $Die = null) {
		$CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ( $ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo) ));
		echo "<p class=\"trigger $CssClass\">";
		echo "{$Msg}";
		echo "<span class=\"ajax_close\"></span></p>";
		if ($Die) {
			die();
		}
	}


	/**
	 * WS_Php_Error
	 * Erro de excessões dos trigger do php
	 * @return void
	 */
	function WS_Php_Error($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
		$CssClass = ( $ErrNo == E_USER_NOTICE ? WS_INFOR : ( $ErrNo == E_USER_WARNING ? WS_ALERT : ( $ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
		echo "<p class=\"trigger $CssClass\">";
		echo "<b># Linha : {$ErrLine} :: {$ErrMsg}</b><br>";
		echo "<small>{$ErrFile}</small><span class=\"ajax_close\"></span></p>";
		if ($ErrNo == E_USER_ERROR) {
			die();
		}
	}

// SETANDO A FUNÇÃO QUE TRABALHARA COM AS ESCESSÕES DO PHP
	set_error_handler('WS_Php_Error');

 ?>