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
		
	}

	
} // END Login 
?>