<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Docage {

	private $mainUrl;
	private $request;

	private $username;
	private $apiKey;

	/**
	 * Docage constructor.
	 */
	public function __construct() {
		$this->setMainUrl( "https://api.docage.com" );
		$this->request = curl_init();
		$this->setUsername( "test" );
		$this->setApiKey( "test" );

		$this->connectAPI();
		$this->standartHeader();
	}

	private function connectAPI() {
		curl_setopt( $this->request, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
		curl_setopt( $this->request, CURLOPT_USERPWD, '"' . $this->getUsername() . ':' . $this->getApiKey() . '"' );
	}


	private function standartHeader() {
		curl_setopt( $this->request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->request, CURLOPT_ENCODING, '' );
		curl_setopt( $this->request, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $this->request, CURLOPT_TIMEOUT, 0 );
		curl_setopt( $this->request, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $this->request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
	}


	public function createSignature() {
		curl_setopt( $this->request, CURLOPT_URL, $this->getMainUrl() . '/Transactions/CreateFullTransaction' );
		curl_setopt( $this->request, CURLOPT_CUSTOMREQUEST, 'POST' );


		$TransactionArray = array(
				"Name"                  => "Transaction test from API",
				"IsTest"                => "true",
				"TransactionFiles"      => array(array("FileName" => "document.pdf", "FriendlyName" => "Document1")),
				"TransactionMembers"    => array(array("FriendlyName" => "Client"))
			);

		$clientInformation  = array(
				"Email"                 => "user@example.com",
				"FirstName"             => "Steven",
				"LastName"              => "HAWKINS",
				"Address1"              => "42 rue de l\'espace",
				"Address2"              => "",
				"City"                  => "PARIS",
				"State"                 => "",
				"ZipCode"               => "75000",
				"Country"               => "FRANCE",
				"Notes"                 => "",
				"Phone"                 => "+33XXXXXXXXX",
				"Mobile"                => "+33XXXXXXXXX",
				"Company"               => "Docage"
		);

		$postFields = array(
			'Transaction' => json_encode($TransactionArray),
			'Client' => json_encode($clientInformation),
			'Document1' => new CURLFILE( 'C:\wamp64\www\Djomanlocation\wp-content\plugins\woocommerce-car-rent-contract\contracts\3975\contract de loction n°3975.pdf' ),
			'Client, Document1' => '{ "Pages": "1", "Coordinates": "0, 0, 150, 220" }'
		);

		curl_setopt( $this->request, CURLOPT_POSTFIELDS , $postFields );
		$response = curl_exec($this->request);
		var_dump(curl_getinfo($this->request));
		if (curl_errno($this->request)) {
			$error_msg = curl_error($this->request);
			echo $error_msg;
		}
		curl_close($this->request);
		echo "</br></br>Success :". $response;

	}

	/**
	 * @return mixed
	 */
	public function getMainUrl() {
		return $this->mainUrl;
	}

	/**
	 * @param mixed $mainUrl
	 */
	public function setMainUrl( $mainUrl ): void {
		$this->mainUrl = $mainUrl;
	}

	/**
	 * @return mixed
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername( $username ): void {
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	public function getApiKey() {
		return $this->apiKey;
	}

	/**
	 * @param mixed $apiKey
	 */
	public function setApiKey( $apiKey ): void {
		$this->apiKey = $apiKey;
	}


}