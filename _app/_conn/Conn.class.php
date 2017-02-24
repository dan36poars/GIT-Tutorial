<?php
/**
 * Conn - [TClass]
 *
 * Description
 * Classe abstrata de cenexão. Padrão SingleTon
 * Retorna um objeto PDO pelo método estatico getCon();
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 **/
class Conn {

	/**
	 * HOST
	 * @var string
	 */

	private static $HOST = HOST;


	/**
	 * User
	 * @var string
	 */

	private static $User = USER;


	/**
	 * Pass
	 * @var string
	 */

	private static $Pass = PASS;


	/**
	 * Dbsa
	 * @var string
	 */

	private static $Dbsa = DBSA;


	/**
	 * Connect
	 * @var PDO Objetc
	 */

	private static $Connect = null;

// CLASS METHODS


	/**
	 * Conectar
	 * conecta com o banco de dados pattern SingleTon
	 * retorna um objeto PDO
	 * @return 
	 */
	private static function Connectar() {
		try {
			$dsn = 'mysql:host='.self::$HOST.';dbname='.self::$Dbsa;
			$options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
			
			if (self::$Connect == null) {
				self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
				
			}
			
		} catch (PDOException $e) {
			WS_Php_Error($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
			die();
		}

		self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return self::$Connect;

	}


	/**
	 * getConn
	 * @return PDO object patter
	 */
	public static function getConn() {
		return self::Connectar();
	}

} // END Conn 
?>
