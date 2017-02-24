<?php
/**
 * Delete - [TClass]
 *
 * Description
 * Responsável em deletar os dados.
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 **/
class Delete extends Conn {


	/**
	 * Tabela
	 * @var string
	 */

	private $Tabela;


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

	private $Delete;



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
	public function exeDelete($Tabela, $Termos, $ParseString) {
		$this->Tabela = (string) $Tabela;
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
		return $this->Delete->rowCount();
	}


	/**
	 * getResult
	 * @return retorna o resultado do cadastro
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
		$this->Delete = $this->Conn->prepare($this->Delete);
	}


	/**
	 * getSyntax
	 * @return void
	 */
	private function getSyntax() {
		$this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";

	}


	/**
	 * execute
	 * @return void
	 */
	private function execute() {
			$this->connect();
		try {
			$this->Delete->execute($this->Places);
			$this->Result = true;
		} catch (PDOException $e) {
			$this->Result = null;
			WS_Error($e->getCode(), "<b>Erro ao cadastrar: {$e->getMessage()}</b>");
		}

	}



} // END Create 
?>