<?php
/**
 * Check - [TClass]
 *
 * Description
 * Classe responsável para manipular e validar dados do sistema.
 * Será utilizada por resolução de escopo.
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 **/
class Check {

	/**
	 * Data
	 * @var string
	 */

	private static $Data;


	/**
	 * Format
	 * @var string
	 */

	private static $Format;



	/**
	 * validaEmail
	 * @return void
	 */
	public static function validaEmail($Email) {
		self::$Data = (string) $Email;
		self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/';
		if ( preg_match(self::$Format,  self::$Data) ) {
			return true;
		}else{
			return false;
		}

	}


	/**
	 * Name
	 * transformar um texto em Url amigável
	 * @return void
	 */
	public static function urlfriendly($Name) {
		self::$Format = array();
		self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
		self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

		self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);

		self::$Data = strip_tags(trim(self::$Data));
		self::$Data = str_replace(' ', '-', self::$Data);
		self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);
		return strtolower(utf8_encode(self::$Data));
	}


	/**
	 * dateToTimestamp
	 * transformar uma data para uma timestamp.
	 * @param $Data [data no formato d/m/YYYY H:i:s]
	 * @return void
	 */
	public static function dateToStamp($Data) {
		self::$Format = explode(' ',$Data);
		self::$Data = explode('/', self::$Format['0']);

		if (empty(self::$Format['1'])) {
			self::$Format['1'] = date('H:i:s');
		}

		self::$Data = self::$Data['2'].'-'.self::$Data['1'].'-'.self::$Data['0'].' '.self::$Format['1'];
		return self::$Data;
	}



	/**
	 * limitWords
	 * limitar um determinado texto
	 * @param $String [Texto para ser cortado ou não]
	 * @param $Limit [numero de palavras]
	 * @param $Pointer [pode passar tags ou texto que aponta para um link
	 * este texto aparecerá depois do texto cortado.]
	 * @return void
	 */
	public static function limitWords($String, $Limit, $Pointer = null) {
		self::$Data = strip_tags(trim($String));
		self::$Format = (int) $Limit;

		$arrWords = explode(' ', self::$Data);
		$numWords = count($arrWords);
		$newWords = implode(' ', array_slice($arrWords, 0, self::$Format));

		$Pointer = ($Pointer == null ? '...' : ' '.$Pointer);

		$Result = (self::$Format < $numWords ? $newWords.$Pointer : self::$Data);

		return $Result;
	}


	/**
	 * catByName
	 * restorna o id da categoria
	 * @param (string) $Tabela [nome da tabela]
	 * @param (string) $columName [nome da coluna da
	 *  tabela]
	 * @param (string) $Category [nome da categoria a
	 * ser pesquisada]
	 * @param (string) $backField (escolhe o campo de retorno)
	 * deve ser um dos que existem na tabela. 
	 * @return (int) retorna o backfield escolhido da 
	 * tabela. 
	 */
	public static function catByName($Tabela, $columName, $Category, $backField){
		$read = new Read;
		$read->exeRead($Tabela, "WHERE {$columName} = :name", "name={$Category}");
		if($read->getRowCount()){
			return $read->getResult()[0][$backField];	
		}else{
			WS_Error(WS_ERROR, "A categoria <b>{$Category}</b> não foi encontrada!");
			die();
		}

	}


	/**
	 * usersOnline
	 * verifica o número de usuários online no site.
	 * @param $Tabela [O nome da tabela de usuarios on line]
	 * @param $columName [nome da coluna da tabela]
	 * @return void
	 */
	public static function usersOnline($Tabela, $columName) {
		$now = date('Y-m-d H:i:s');
		$deleteUserOnline = new Delete;
		$deleteUserOnline->exeDelete($Tabela, "WHERE {$columName} < :now", "now={$now}");

		$readUserOnline = new Read;
		$readUserOnline->exeRead($Tabela);
		return $readUserOnline->getRowCount();
	}


	/**
	 * image
	 * essa função precisa do arquiv tim.php na raiz do site para funcionar.
	 * @param $ImageUrl [nome da imagem passando sua extensão também]
	 * @param $ImageDesc [uma descrição da imagem ]
	 * @param $ImageW = null [comprimento pode ser em string ou int ]
	 * @param $ImageH = null [ altura pode serr em string ou int]
	 * @return object [imagem redimensionalizada]
	 */
	public static function image($ImageUrl, $ImageDesc, $ImageW = null, $ImageH = null) {
		self::$Data =$ImageUrl;
		if (file_exists(self::$Data) && !is_dir(self::$Data) ) {
			$path = BASE."/_app/Tim";
			$image = self::$Data;
			return "<img src=\"{$path}/tim.php?src=".BASE."/{$image}&w={$ImageW}&h={$ImageH}\" alt=\"{$ImageDesc}\" title=\"{$ImageDesc}\"/>";
		}else{
			return false;
		}
	}


} // END Check 
?>