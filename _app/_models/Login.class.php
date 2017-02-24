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

	private $Name;


	/**
	 * Password
	 * @var string
	 */

	private $Password;



	/**
	 * __construct
	 * @param $Name
	 * @param $Password
	 * @return void
	 */
	public function __construct($Level) {
		$this->Level = (int) $Level;		
	}

//PRIVATE METHODS

	
} // END Login 
?>