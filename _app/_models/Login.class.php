<?php
/**
 * Login - [Model]
 *
 * Description
 * Gerenciar login no banco.
 *
 * @copyright (c) 2017, Daniel Correa STARTCRIATIVO. 
 **/
class Login {


	/**
	 * Level
	 * @var int
	 */

	private $Level;



	/**
	 * Name
	 * @var string
	 */

	private $Email;


	/**
	 * Password
	 * @var string
	 */

	private $Password;


	/**
	 * Error
	 * @var array
	 */

	private $Error;


	/**
	 * Result
	 * @var array
	 */

	private $Result;





	/**
	 * __construct
	 * @param $Name
	 * @param $Password
	 * @return void
	 */
	public function __construct($Level) {
		$this->Level = (int) $Level;		
	}


	/**
	 * checkLogin
	 * @return boolean
	 */
	public function checkLogin() {
		if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['userlevel'] < $this->Level) {
			unset($_SESSION['userlogin']);
			return false;
		}else{
			return true;
		}
	}


	/**
	 * exeLogin
	 * @param $Data
	 * @return void
	 */
	public function exeLogin(array $Data) {
		$this->Email = (string) strip_tags(trim($Data['name']));
		$this->Password = (string) strip_tags(trim($Data['pass']));
		$this->setLogin();

	}


	/**
	 * getError
	 * @return array
	 */
	public function getError() {
		return $this->Error;
	}

	/**
	 * getResult
	 * @return array
	 */
	public function getResult() {
		return $this->Result;
	}

//PRIVATE METHODS


	/**
	 * setLogin
	 * @return void
	 */
	private function setLogin() {
		if (!$this->Email || !$this->Password || !Check::validaEmail($this->Email)) {
			$this->Error = ['Informe E-mail e Senha. Não deixe campos em branco', WS_ALERT];
			$this->Result = false;
		}elseif (!$this->checkUser()) {
			$this->Error = ['Usuario não está cadastrado no sistema', WS_ALERT];
			$this->Result = false;
		}else{
			$this->execute();
		}
	}


	/**
	 * checkUser
	 * @return void
	 */
	private function checkUser() {
		$user = new Read;
		$user->exeRead("bd_users", "WHERE bd_email = :email AND bd_pass = :pass","email={$this->Email}&pass={$this->Password}");
		if ($user->getResult()) {
			$this->Result = $user->getResult()[0];
			return true;
		}else{
			return false;
		}
	}


	/**
	 * execute
	 * @return void
	 */
	private function execute() {
		if (!session_id()) {
			session_start();
		}
		$_SESSION['userlogin'] = $this->Result;
		$this->Error = [WS_ACCEPT, "Ola {$_SESSION['userlogin']['db_name']} {$_SESSION['userlogin']['db_last_name']} seja bem vindo, Aguarde redirecionamento!"];
		$this->Result = true;
	}

	
} // END Login 
?>