<?php
/**
 * Session - [HELPERS]
 *
 * Description
 * Gerência as sessões, estatística e atualizações de
 * tráfego do sistema.
 *
 * @copyright (c) 2016, Daniel Correa STARTCRIATIVO. 
 */
class Session {


	/**
	 * Date
	 * @var datatype
	 */

	private $Date;


	/**
	 * Cache
	 * @var datatype
	 */

	private $Cache;


	/**
	 * Traffic
	 * @var datatype
	 */

	private $Traffic;


	/**
	 * Browser
	 * @var datatype
	 */

	private $Browser;


	/**
	 * __construct
	 * @param $Cache = null
	 * @return void
	 */
	public function __construct($Cache = null) {
		session_start();
		$this->CheckSession($Cache);
	}

// PRIVATE METHODS

	/**
	 * CheckSession
	 * @param $Cache = null
	 * @return void
	 */
	private function CheckSession($Cache = null) {
		$this->Date = date('Y-m-d');
		$this->Cache = ( (int) $Cache ? $Cache : 20);

		if (empty($_SESSION['useronline'])) {
			$this->setTraffic();
			$this->setSession();
			$this->checkBrowser();
			$this->setUsuario();
			$this->browserUpdate();
		}else{

			$this->trafficUpdate();
			$this->sessionUpdate();
			$this->checkBrowser();
			$this->updateUsuario();
		}

		$this->Date = null;
	}


	/**
	 * TrafficUpdate
	 * @return void
	 */
	private function trafficUpdate() {
		$this->getTraffic();
		$ArrSiteViews = ['siteviews_pages' => $this->Traffic['siteviews_pages'] + 1 ];
		$updatePageViews = new Update;
		$updatePageViews->exeUpdate('ws_siteviews', $ArrSiteViews, "WHERE siteviews_date = :date", "date={$this->Date}");
		$this->Traffic = null;
	}


	/**
	 * sessionUpdate
	 * @return void
	 */
	private function sessionUpdate() {
		$_SESSION['useronline']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
		$_SESSION['useronline']['online_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);

	}


	/**
	 * setSession
	 * @return void
	 */
	private function setSession() {
		$_SESSION['useronline'] = [
			'online_session' => session_id(),
			'online_startview' => date('Y-m-d H:i:s'),
			'online_endview' => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
			'online_ip' => filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP),
			'online_url' => filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT),
			'online_agent' => filter_input(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_DEFAULT)

		];
	}


	/**
	 * setTraffic
	 * @return void
	 */
	private function setTraffic() {
		$this->getTraffic();
		if (!$this->Traffic) {
			$ArrSiteViews = ['siteviews_date' => $this->Date, 'siteviews_users' => 1, 'siteviews_views' => 1, 'siteviews_pages' => 1 ];
			$create = new Create;
			$create->exeCreate('ws_siteviews', $ArrSiteViews);
		}else{
			if (!$this->getCookie()) {
				$ArrSiteViews = ['siteviews_users' => $this->Traffic['siteviews_users'] + 1, 'siteviews_views' => $this->Traffic['siteviews_views'] + 1, 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1 ];
			}else{
				$ArrSiteViews = ['siteviews_views' => $this->Traffic['siteviews_views'] + 1, 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1 ];
			}

			$updateSiteViews = new Update;
			$updateSiteViews->exeUpdate('ws_siteviews', $ArrSiteViews, "WHERE siteviews_date = :date", "date={$this->Date}");

		}
	}


	/**
	 * getTraffic
	 * lendo tabela ws_siteviews
	 * @return void
	 */
	private function getTraffic() {
		$readSiteViews = new Read;
		$readSiteViews->exeRead('ws_siteviews', 'WHERE siteviews_date = :date', "date={$this->Date}");
		if ($readSiteViews->getRowcount()) {
			$this->Traffic = $readSiteViews->getResult()[0];
		}
	}
	

	/**
	 * getCookie
	 * @return void
	 */
	private function getCookie() {
		$Cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
		setcookie("useronline", base64_encode("criativo45"), time() + 86400);
		if (!$Cookie) {
			return false;
		}else{
			return true;
		}

	}


	/**
	 * checkBrowser
	 * @return void
	 */
	private function checkBrowser() {
		if (empty($this->Browser = $_SESSION['useronline']['online_agent'])) {
			$this->Browser = $_SESSION['useronline']['online_agent'];		
		}
		if (strpos($this->Browser, 'Chrome')) {
			$this->Browser = 'Chrome';
		}elseif (strpos($this->Browser, 'Firefox')) {
			$this->Browser = 'FireFox';
		}elseif (strpos($this->Browser, 'MSIE') || strpos($this->Browser, 'Trident/')) {
			$this->Browser = 'Internet Explorer';
		}else{
			$this->Browser = 'Outros';
		}
	}


	/**
	 * browserUpdate
	 * @return void
	 */
	private function browserUpdate() {
		$readAgent = new Read;
		$readAgent->exeRead('ws_siteviews_agent', "WHERE agent_name = :agent", "agent={$this->Browser}");
		if (!$readAgent->getRowcount()) {
			$ArrAgent = ['agent_name' => $this->Browser, 'agent_views' => 1];
			$agentCreate = new Create;
			$agentCreate->exeCreate('ws_siteviews_agent', $ArrAgent);
		}else{
			$ArrAgent = ['agent_views' => $readAgent->getResult()[0]['agent_views'] + 1];
			$agentUpdate = new Update;
			$agentUpdate->exeUpdate('ws_siteviews_agent', $ArrAgent, "WHERE agent_name = :agent", "agent={$this->Browser}");
		}
	}


	/**
	 * setUsuario
	 * @return void
	 */
	private function setUsuario() {
		$sesOnline = $_SESSION['useronline'];
		$sesOnline['agent_name'] = $this->Browser;
		$userCreate = new Create;
		$userCreate->exeCreate('ws_siteviews_online', $sesOnline);
	}


	/**
	 * updateUsuario
	 * @return void
	 */
	private function updateUsuario() {
		$ArrOnline = ['online_endview' => $_SESSION['useronline']['online_endview'] , 'online_url' => $_SESSION['useronline']['online_url']	];

		$userUpdate = new Update;
		$userUpdate->exeUpdate('ws_siteviews_online', $ArrOnline, "WHERE online_session = :ses ", "ses={$_SESSION['useronline']['online_session']}");
		if (!$userUpdate->getRowcount()) {
			$readSes = new Read;
			$readSes->exeRead('ws_siteviews_online', "WHERE online_session = :onses ", "onses={$_SESSION['useronline']['online_session']}");
			if (!$readSes->getRowcount()) {
				$this->setUsuario();
			}
		}
	}

} // END Session 
?>