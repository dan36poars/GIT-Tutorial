<?php
/**
 * Create - [TClass]
 *
 * Description
 * Responsável em cadastrar os dados.
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 **/
class Create extends Conn {

	/**
	 * Tabela
	 * @var datatype
	 */

	private $Tabela;


	/**
	 * Dados
	 * @var datatype
	 */

	private $Dados;


	/**
	 * Result
	 * @var datatype
	 */

	private $Result;



	/**
	 * Create
	 * @var PDOStatament
	 */

	private $Create;



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
	public function exeCreate($Tabela, array $Dados) {
		$this->Tabela = (string) $Tabela;
		$this->Dados = $Dados;
		$this->getSyntax();
		$this->execute();
	}


	/**
	 * getResult
	 * @return retorna o resultado do cadastro
	 */
	public function getResult() {
		return $this->Result;
	}


	/**
	 * getRowCount
	 * retorna o numerro de registros realizados
	 * @return void
	 */
	public function getRowCount() {
		return $this->Create->RowCount();
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
		$this->Create = $this->Conn->prepare($this->Create);
	}


	/**
	 * getSyntax
	 * @return void
	 */
	private function getSyntax() {
		$Fields = implode(', ', array_keys($this->Dados));
		$Places = ':'.implode(', :', array_keys($this->Dados));
		$this->Create = "INSERT INTO {$this->Tabela} ({$Fields}) VALUES ({$Places})";
	}


	/**
	 * execute
	 * @return void
	 */
	private function execute() {
		$this->connect();
		try {
			$this->Create->execute($this->Dados);
			$this->Result = $this->Conn->lastInsertId();			
		} catch (Exception $e) {
			$this->Result = null;
			WS_Error($e->getCode(), "<b>Erro ao cadastrar: {$e->getMessage()}</b>");
		}
	}



} // END Create 
?>