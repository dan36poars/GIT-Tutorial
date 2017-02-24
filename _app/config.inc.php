<?php 
// CONFIGURAÇÕES DO BANCO
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DBSA','PDO1');

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

 ?>