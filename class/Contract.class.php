<?php

require('pdf/FPDM.php');
class Contract {

	private $id;

	private $createDate;
	private $update;
	private $fkIdCommande;
	private $fkIdUser;
	private $status;  // 1:Start; 2:On going; 3:End

	// User
	private $fname;
	private $lname;
	private $dob;
	private $driverlicense;
	private $driverdate;
	private $address;

	// Propiétaire
	private $proprioName;

	// Car
	private $model;
	private $idCar;
	private $fuel;
	private $kilVoiture;

	// Location
	private $startDate;
	private $endDate;
	private $deliveryAddress;
	private $kilometers;
	private $levelFuel;
	private $price;

	// Option
	private $insurance;

	// Checkup
	private $front;
	private $imgFront;
	private $left;
	private $imgLeft;
	private $back;
	private $imgBack;
	private $right;
	private $imgRight;

	private $kmFinal;
	private $fuelFinal;
	private $dateFinal;
	private $carBroken;

	//
	private $urlContractPDF;

	/**
	 * Contract constructor.
	 */
	public function __construct() {
	}


	public function createContract(){
		global $wpdb;
		$result = $wpdb->insert($wpdb->prefix . 'wcc_contracts', [
			'fkOrder'       => $this->getFkIdCommande(),
			'fkUser'        => $this->getFkIdUser(),
			'createDate'    => $this->getCreateDate(),
			'updateDate'    => $this->getUpdate(),
			'data'          => $this->getThisToJson()
		]);
	}

	public function updateContract(){
		global $wpdb;
		$result = $wpdb->update($wpdb->prefix, [
			'fkOrder'       => $this->getFkIdCommande(),
			'fkUser'        => $this->getFkIdUser(),
			'createDate'    => $this->getCreateDate(),
			'updateDate'    => $this->getUpdate(),
			'data'          => $this->getThisToJson()
		], [
			'id' => $this->getId()
		], [
			'%d',
			'%d',
			'%s',
			'%s',
			'%s'
		], [
			'%d'
		]);
	}

	public function getContractByID($id){
		global $wpdb;

		$filter = "WHERE id = " . $id;

		$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wcc_contracts " . $filter . " LIMIT 2 ", OBJECT);

		if ($result[0] == null || sizeof($result) == 0) {
			return null;
		}

		$this->setId($result[0]->id);
		$this->setFkIdCommande($result[0]->fkOrder);
		$this->setFkIdUser($result[0]->fkUser);
		$this->setCreateDate($result[0]->createDate);
		$this->setUpdate($result[0]->updateDate);
		$this->setJsonToThis($result->data);

		return $this;
	}

	public function getContractByOrderID($idOrder){
		global $wpdb;

		$filter = "WHERE fkOrder = " . $idOrder;

		$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."wcc_contracts " . $filter . " LIMIT 2 ", OBJECT);

		if ($result[0] == null || sizeof($result) == 0) {
			return null;
		}

		$this->setId($result[0]->id);
		$this->setFkIdCommande($result[0]->fkOrder);
		$this->setFkIdUser($result[0]->fkUser);
		$this->setCreateDate($result[0]->createDate);
		$this->setUpdate($result[0]->updateDate);
		$this->setJsonToThis($result[0]->data);

		return $this;
	}
	public function getThisToArray(){
		return get_object_vars ($this);
	}
	public function setArrayToThis($array){
		$this->setLname($array['lName']);
		$this->setFname($array['fName']);
		$this->setDob($array['dob']);
		$this->setDriverlicense($array['nPermis']);
		$this->setDriverdate($array['permisDate']);
		$this->setAddress($array['adresse']);
		$this->setProprioName($array['nomProprietaire']);
		$this->setModel($array['model']);
		$this->setIdCar($array['numCar']);
		$this->setFuel($array['fuel']);
		$this->setKilVoiture($array['kilVoiture']);
		$this->setStartDate($array['start']);
		$this->setEndDate($array['end']);
		$this->setDeliveryAddress($array['delivery']);
		$this->setKilometers($array['kilometre']);
		$this->setLevelFuel($array['levelFuel']);
		$this->setPrice($array['price']);
		$this->setFront($array['face']);
		$this->setLeft($array['left']);
		$this->setBack($array['back']);
		$this->setRight($array['right']);
		$this->setInsurance($array['insurance']);
		$this->setKmFinal($array['kmFinal']);
		$this->setFuelFinal($array['fuelFinal']);
		$this->setDateFinal($array['dateFinal']);
		$this->setCarBroken($array['carBroken']);
		$this->setUrlContractPDF($array['pdfLink']);
	}


	public function getThisToJson(){
		$array = array(
			"lName"             => $this->getLname(),
			"fName"             => $this->getFname(),
			"dob"               => $this->getDob(),
			"nPermis"           => $this->getDriverlicense(),
			"permisDate"        => $this->getDriverdate(),
			"adresse"           => $this->getAddress(),
			"nomProprietaire"   => $this->getProprioName(),
			"model"             => $this->getModel(),
			"numCar"            => $this->getIdCar(),
			"fuel"              => $this->getFuel(),
			"kilVoiture"        => $this->getKilVoiture(),
			"start"             => $this->getStartDate(),
			"end"               => $this->getEndDate(),
			"delivery"          => $this->getDeliveryAddress(),
			"kilometre"         => $this->getKilometers(),
			"levelFuel"         => $this->getLevelFuel(),
			"price"             => $this->getPrice(),
			"insurance"         => $this->getInsurance(),
			"face"              => $this->getFront(),
			"left"              => $this->getLeft(),
			"back"              => $this->getBack(),
			"right"             => $this->getRight(),
			"pdfLink"           => $this->getUrlContractPDF(),
			"kmFinal"           => $this->getKmFinal(),
			"fuelFinal"         => $this->getFuelFinal(),
			"dateFinal"         => $this->getDateFinal(),
			"carBroken"         => $this->getCarBroken()
		);
		return json_encode($array);
	}
	public function setJsonToThis($json){
		$this->setArrayToThis(json_decode($json, true));
	}

	public function generatePDF(){

		$fields = array(
			'nPermis'       => $this->getDriverlicense(),
			'permisDate'    => $this->getDriverdate(),
			'fullname'      => $this->getFname()." ".$this->getLname(),
			'dob'           => $this->getDob(),
			'adresse'       => $this->getAddress(),
			'model'         => $this->getModel(),
			'numCar'        => $this->getIdCar(),
			'fuel'          => $this->getFuel(),
			'start'         => $this->getStartDate(),
			'end'           => $this->getEndDate(),
			'delivery'      => $this->getDeliveryAddress(),
			'kilometre'     => $this->getKilometers(),
			'levelFuel'     => $this->getLevelFuel(),
			'price'         => $this->getPrice(),
			'insurance'     => $this->getInsurance(),
			'kmFinal'       => $this->getKmFinal(),
			'distance'      => '',
			'fuelFinal'     => '',
			'dateFinal'     => '',
			'comments'      => '',
			'cgv'           => true,
			'brokenYes'     => false,
			'brokenNo'      => false,
		);
		/*if($this->getCarBroken()){
			array_push($fields, array("brokenYes" => true));
		}else{
			array_push($fields, array("brokenYes" => false));
		}*/
		$pdf = new FPDM(dirname(__FILE__).'/template/Contract-de-Location-Formulaire-repair.pdf');
		$pdf->useCheckboxParser = true; // Checkbox parsing is ignored (default FPDM behaviour) unless enabled with this setting
		$pdf->Load($fields, true);
		$pdf->Merge();
		//$pdf->SetFontSize(40);
		$pathToContract = dirname(dirname(__FILE__))."/contracts";
		$UrlToContract = plugin_dir_url(dirname(__FILE__))."contracts";
		$nameFile = "contract de loction n°".$this->getFkIdCommande().".pdf";
		$file = $pdf->Output('', "S");

		$handle = fopen($pathToContract."/".$nameFile, 'w') or die('Cannot open file:  '.$nameFile); //implicitly creates file
		fwrite($handle,$file);
		fclose($handle);
		$this->setUrlContractPDF($UrlToContract."/".$nameFile);
	}

	/**
	 * @return mixed
	 */
	public function getCreateDate() {
		return $this->createDate;
	}

	/**
	 * @param mixed $createDate
	 */
	public function setCreateDate( $createDate ): void {
		$this->createDate = $createDate;
	}

	/**
	 * @return mixed
	 */
	public function getUpdate() {
		return $this->update;
	}

	/**
	 * @param mixed $update
	 */
	public function setUpdate( $update ): void {
		$this->update = $update;
	}

	/**
	 * @return mixed
	 */
	public function getFkIdCommande() {
		return $this->fkIdCommande;
	}

	/**
	 * @param mixed $fkIdCommande
	 */
	public function setFkIdCommande( $fkIdCommande ): void {
		$this->fkIdCommande = $fkIdCommande;
	}

	/**
	 * @return mixed
	 */
	public function getFkIdUser() {
		return $this->fkIdUser;
	}

	/**
	 * @param mixed $fkIdUser
	 */
	public function setFkIdUser( $fkIdUser ): void {
		$this->fkIdUser = $fkIdUser;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus( $status ): void {
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	public function getFname() {
		return $this->fname;
	}

	/**
	 * @param mixed $fname
	 */
	public function setFname( $fname ): void {
		$this->fname = $fname;
	}

	/**
	 * @return mixed
	 */
	public function getLname() {
		return $this->lname;
	}

	/**
	 * @param mixed $lname
	 */
	public function setLname( $lname ): void {
		$this->lname = $lname;
	}

	/**
	 * @return mixed
	 */
	public function getDob() {
		return $this->dob;
	}

	/**
	 * @param mixed $dob
	 */
	public function setDob( $dob ): void {
		$this->dob = $dob;
	}

	/**
	 * @return mixed
	 */
	public function getDriverlicense() {
		return $this->driverlicense;
	}

	/**
	 * @param mixed $driverlicense
	 */
	public function setDriverlicense( $driverlicense ): void {
		$this->driverlicense = $driverlicense;
	}

	/**
	 * @return mixed
	 */
	public function getDriverdate() {
		return $this->driverdate;
	}

	/**
	 * @param mixed $driverdate
	 */
	public function setDriverdate( $driverdate ): void {
		$this->driverdate = $driverdate;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress( $address ): void {
		$this->address = $address;
	}

	/**
	 * @return mixed
	 */
	public function getProprioName() {
		return $this->proprioName;
	}

	/**
	 * @param mixed $proprioName
	 */
	public function setProprioName( $proprioName ): void {
		$this->proprioName = $proprioName;
	}

	/**
	 * @return mixed
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @param mixed $model
	 */
	public function setModel( $model ): void {
		$this->model = $model;
	}

	/**
	 * @return mixed
	 */
	public function getIdCar() {
		return $this->idCar;
	}

	/**
	 * @param mixed $idCar
	 */
	public function setIdCar( $idCar ): void {
		$this->idCar = $idCar;
	}

	/**
	 * @return mixed
	 */
	public function getFuel() {
		return $this->fuel;
	}

	/**
	 * @param mixed $fuel
	 */
	public function setFuel( $fuel ): void {
		$this->fuel = $fuel;
	}

	/**
	 * @return mixed
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @param mixed $startDate
	 */
	public function setStartDate( $startDate ): void {
		$this->startDate = $startDate;
	}

	/**
	 * @return mixed
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param mixed $endDate
	 */
	public function setEndDate( $endDate ): void {
		$this->endDate = $endDate;
	}

	/**
	 * @return mixed
	 */
	public function getDeliveryAddress() {
		return $this->deliveryAddress;
	}

	/**
	 * @param mixed $deliveryAddress
	 */
	public function setDeliveryAddress( $deliveryAddress ): void {
		$this->deliveryAddress = $deliveryAddress;
	}

	/**
	 * @return mixed
	 */
	public function getKilometers() {
		return $this->kilometers;
	}

	/**
	 * @param mixed $kilometers
	 */
	public function setKilometers( $kilometers ): void {
		$this->kilometers = $kilometers;
	}

	/**
	 * @return mixed
	 */
	public function getLevelFuel() {
		return $this->levelFuel;
	}

	/**
	 * @param mixed $levelFuel
	 */
	public function setLevelFuel( $levelFuel ): void {
		$this->levelFuel = $levelFuel;
	}

	/**
	 * @return mixed
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param mixed $price
	 */
	public function setPrice( $price ): void {
		$this->price = $price;
	}

	/**
	 * @return mixed
	 */
	public function getInsurance() {
		return $this->insurance;
	}

	/**
	 * @param mixed $insurance
	 */
	public function setInsurance( $insurance ): void {
		$this->insurance = $insurance;
	}

	/**
	 * @return mixed
	 */
	public function getFront() {
		return $this->front;
	}

	/**
	 * @param mixed $front
	 */
	public function setFront( $front ): void {
		$this->front = $front;
	}

	/**
	 * @return mixed
	 */
	public function getImgFront() {
		return $this->imgFront;
	}

	/**
	 * @param mixed $imgFront
	 */
	public function setImgFront( $imgFront ): void {
		$this->imgFront = $imgFront;
	}

	/**
	 * @return mixed
	 */
	public function getLeft() {
		return $this->left;
	}

	/**
	 * @param mixed $left
	 */
	public function setLeft( $left ): void {
		$this->left = $left;
	}

	/**
	 * @return mixed
	 */
	public function getImgLeft() {
		return $this->imgLeft;
	}

	/**
	 * @param mixed $imgLeft
	 */
	public function setImgLeft( $imgLeft ): void {
		$this->imgLeft = $imgLeft;
	}

	/**
	 * @return mixed
	 */
	public function getBack() {
		return $this->back;
	}

	/**
	 * @param mixed $back
	 */
	public function setBack( $back ): void {
		$this->back = $back;
	}

	/**
	 * @return mixed
	 */
	public function getImgBack() {
		return $this->imgBack;
	}

	/**
	 * @param mixed $imgBack
	 */
	public function setImgBack( $imgBack ): void {
		$this->imgBack = $imgBack;
	}

	/**
	 * @return mixed
	 */
	public function getRight() {
		return $this->right;
	}

	/**
	 * @param mixed $right
	 */
	public function setRight( $right ): void {
		$this->right = $right;
	}

	/**
	 * @return mixed
	 */
	public function getImgRight() {
		return $this->imgRight;
	}

	/**
	 * @param mixed $imgRight
	 */
	public function setImgRight( $imgRight ): void {
		$this->imgRight = $imgRight;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ): void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getUrlContractPDF() {
		return $this->urlContractPDF;
	}

	/**
	 * @param mixed $urlContractPDF
	 */
	public function setUrlContractPDF( $urlContractPDF ): void {
		$this->urlContractPDF = $urlContractPDF;
	}

	/**
	 * @return mixed
	 */
	public function getKilVoiture() {
		return $this->kilVoiture;
	}

	/**
	 * @param mixed $kilVoiture
	 */
	public function setKilVoiture( $kilVoiture ): void {
		$this->kilVoiture = $kilVoiture;
	}

	/**
	 * @return mixed
	 */
	public function getKmFinal() {
		return $this->kmFinal;
	}

	/**
	 * @param mixed $kmFinal
	 */
	public function setKmFinal( $kmFinal ): void {
		$this->kmFinal = $kmFinal;
	}

	/**
	 * @return mixed
	 */
	public function getFuelFinal() {
		return $this->fuelFinal;
	}

	/**
	 * @param mixed $fuelFinal
	 */
	public function setFuelFinal( $fuelFinal ): void {
		$this->fuelFinal = $fuelFinal;
	}

	/**
	 * @return mixed
	 */
	public function getDateFinal() {
		return $this->dateFinal;
	}

	/**
	 * @param mixed $dateFinal
	 */
	public function setDateFinal( $dateFinal ): void {
		$this->dateFinal = $dateFinal;
	}

	/**
	 * @return mixed
	 */
	public function getCarBroken() {
		return $this->carBroken;
	}

	/**
	 * @param mixed $carBroken
	 */
	public function setCarBroken( $carBroken ): void {
		$this->carBroken = $carBroken;
	}




}