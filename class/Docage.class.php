<?php


class Docage {

	private $mainUrl;
	private $request;

	private $username;
	private $apiKey;

	private $isTest;


	/**
	 * Docage constructor.
	 */
	public function __construct() {
		$this->setMainUrl( "https://api.docage.com" );
		$this->isTest = "true";
		$this->request = curl_init();
		$this->setUsername( "contact@djomanlocation.com" );
		$this->setApiKey( "19c26d56-bfe3-45e0-8265-7d1649700cd3" );

		$this->connectAPI();
		$this->standartHeader();


	}

	private function connectAPI() {
		curl_setopt( $this->request, CURLOPT_HTTPHEADER, array(
			"Authorization: Basic " . base64_encode($this->getUsername() . ":" . $this->getApiKey()),
			"Content-Type: multipart/form-data")
		);
	}


	private function standartHeader() {
		curl_setopt( $this->request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->request, CURLOPT_ENCODING, '' );
		curl_setopt( $this->request, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $this->request, CURLOPT_TIMEOUT, 0 );
		curl_setopt( $this->request, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $this->request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
	}



	public function createSignature($contract,$user,$doc) {
		curl_setopt( $this->request, CURLOPT_URL, $this->getMainUrl() . '/Transactions/CreateFullTransaction' );
		curl_setopt( $this->request, CURLOPT_CUSTOMREQUEST, 'POST' );


		$TransactionArray = array(
				"Name"                  => $contract["Name"],
				"IsTest"                => $this->isTest,
				"TransactionFiles"      => array(array("FileName" => $doc["fileName"], "FriendlyName" => $doc["FriendlyName"])),
				"TransactionMembers"    => array(array("FriendlyName" => "Client_".$contract["ID"]))
			);

		$clientInformation  = array(
				"Email"                 => $user["email"],
				"FirstName"             => $user["FName"],
				"LastName"              => $user["LName"],
				"Address1"              => $user["Address1"],
				"Address2"              => $user["Address2"],
				"City"                  => $user["City"],
				"State"                 => $user["State"],
				"ZipCode"               => $user["Zip"],
				"Country"               => $user["Pays"],
				"Notes"                 => $user["Notes"],
				"Phone"                 => $user["Phone"],
				"Mobile"                => $user["Mobile"],
				"Company"               => $user["Company"]
		);

		$postFields = array(
			'Transaction' => json_encode($TransactionArray),
			'Client_'.$contract["ID"] => json_encode($clientInformation),
			$doc["FriendlyName"] => new CURLFILE( $doc["linkAbsolute"] ),
			'Client_'.$contract["ID"].', '.$doc["FriendlyName"] => '{ "Pages": "1", "Coordinates": "0, 0, 150, 220" }'
		);


		curl_setopt( $this->request, CURLOPT_POSTFIELDS , $postFields );
		$response = curl_exec($this->request);
		if (curl_errno($this->request)) {
			$error_msg = curl_error($this->request);
			echo $error_msg;
		}
		curl_close($this->request);

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