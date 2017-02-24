<?php
/**
 * Read - [TClass]
 *
 * Description
 * Responsável em ler os dados.
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 **/
class Read extends Conn {


	/**
	 * Tabela
	 * @var datatype
	 */

	private $Select;


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

	private $Read;



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
	public function exeRead($Tabela, $Termos = null, $parseString = null) {
		if (!empty($parseString)) {
			$this->Places = $parseString;
			parse_str($parseString, $this->Places);
		}

		$this->Select = "SELECT * FROM {$Tabela} {$Termos}";
		$this->execute();
	}


	/**
	 * fullRead
	 * faz a leitura passando uma string com todos os paramentros
	 * de uma leitura no banco de dados.
	 * @param $Termos
	 * @return void
	 */
	public function fullRead($Termos, $ParseString = null) {
		$this->Select = (string) $Termos;
		 if (!empty($ParseString)):
            parse_str($ParseString, $this->Places);
        endif;
		$this->execute();
	}

	/**
	 * getRowCount
	 * @return void
	 */
	public function getRowCount() {
		return $this->Read->rowCount();
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
	 * setando novos parametros de busca utilizando a 
	 * mesma Query. 
	 * @param $ParseString
	 * @return void
	 */
	public function setPlaces($ParseString) {
		parse_str($ParseString, $this->Places);
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
		$this->Read = $this->Conn->prepare($this->Select);
		$this->Read->setFetchMode(PDO::FETCH_ASSOC);
	}


	/**
	 * getSyntax
	 * @return void
	 */
	private function getSyntax() {
		if ($this->Places) {
			foreach ($this->Places as $key => $value) {
				if ($key == 'limit' || $key == 'offset') {
					$value = (int) $value;
				}
				$this->Read->bindValue(":{$key}" , $value , (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
			}
		}
	}


	/**
	 * execute
	 * @return void
	 */
	private function execute() {
		$this->connect();
		try {
			$this->getSyntax();
			$this->Read->execute();
			$this->Result = $this->Read->fetchAll();			
		} catch (Exception $e) {
			$this->Result = null;
			WS_Error($e->getCode(), "<b>Erro ao cadastrar: {$e->getMessage()}</b>");
		}
	}



} // END Create 
?>