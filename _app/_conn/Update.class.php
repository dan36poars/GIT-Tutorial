<?php
/**
 * Update - [TClass]
 *
 * Description
 * Responsável em atualizar os dados.
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 **/
class Update extends Conn {


	/**
	 * Tabela
	 * @var string
	 */

	private $Tabela;


	/**
	 * Dados
	 * @var string
	 */

	private $Dados;


	/**
	 * Termos
	 * @var string
	 */

	private $Termos;



	/**
	 * Dados
	 * @var datatype
	 */

	private $Places;


	/**
	 * Result
	 * @var datatype
	 */

	private $Result;



	/**
	 * Create
	 * @var PDOStatament
	 */

	private $Update;



	/**
	 * Conn
	 * @var PDO
	 */

	private $Conn;


	/**
	 * exeCreate
	 * @param string $Tabela
	 * @param array $Dados
	 * @return void
	 */
	public function exeUpdate($Tabela, array $Dados, $Termos, $ParseString) {
		$this->Tabela = (string) $Tabela;
		$this->Dados = $Dados;
		$this->Termos = (string) $Termos;
		parse_str($ParseString, $this->Places);
		$this->getSyntax();
		$this->execute();
 	}


	/**
	 * getRowCount
	 * @return void
	 */
	public function getRowCount() {
		return $this->Update->rowCount();
	}


	/**
	 * getResult
	 * @return retorna o resultado da atualização
	 */
	public function getResult() {
		return $this->Result;
	}


	/**
	 * setPlaces
	 * @param $ParseString
	 * @return void
	 */
	public function setPlaces($ParseString) {
		parse_str($ParseString, $this->Places);
		$this->getSyntax();
		$this->execute();
	}

/**
 * PRIVATE METHODS
 */


	/**
	 * connect
	 * @return void
	 */
	private function connect() {
		$this->Conn = parent::getConn();
		$this->Update = $this->Conn->prepare($this->Update);
	}


	/**
	 * getSyntax
	 * @return void
	 */
	private function getSyntax() {
		foreach ($this->Dados as $key => $value) {
			$Places[] = $key.' = :'.$key;
		}
		$Places = implode(', ',$Places);
		$this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";
	}


	/**
	 * execute
	 * @return void
	 */
	private function execute() {
		$this->connect();
		try {
			$this->Update->execute(array_merge($this->Dados, $this->Places));
			$this->Result = true;
		} catch (PDOException $e) {
			$this->Result = null;
			WS_Error($e->getCode(), "<b>Erro ao cadastrar: {$e->getMessage()}</b>");
		}

	}



} // END Create 
?>