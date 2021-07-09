<?php

require( 'pdf/FPDM.php' );
require( 'pdf/fpdf.php' );

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

require( 'pdf/FPDI/autoload.php' );

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

	private $kmFinal;
	private $fuelFinal;
	private $dateFinal;
	private $carBroken;

	private $comment;

	//
	private $urlContractPDF;

	/**
	 * Contract constructor.
	 */
	public function __construct() {
	}


	public function createContract() {
		global $wpdb;
		$result = $wpdb->insert( $wpdb->prefix . 'wcc_contracts', [
			'fkOrder'    => $this->getFkIdCommande(),
			'fkUser'     => $this->getFkIdUser(),
			'createDate' => $this->getCreateDate(),
			'updateDate' => $this->getUpdate(),
			'data'       => $this->getThisToJson()
		] );
		$this->setId( $wpdb->insert_id );
	}

	public function updateContract() {


		global $wpdb;
		$result = $wpdb->update( $wpdb->prefix . 'wcc_contracts', [
			'fkOrder'    => $this->getFkIdCommande(),
			'fkUser'     => $this->getFkIdUser(),
			'createDate' => $this->getCreateDate(),
			'updateDate' => $this->getUpdate(),
			'data'       => $this->getThisToJson()
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
		] );
	}

	public function getContractByID( $id ) {
		global $wpdb;

		$filter = "WHERE id = " . $id;

		$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "wcc_contracts " . $filter . " LIMIT 2 ", OBJECT );

		if ( $result[0] == null || sizeof( $result ) == 0 ) {
			return null;
		}

		$this->setId( $result[0]->id );
		$this->setFkIdCommande( $result[0]->fkOrder );
		$this->setFkIdUser( $result[0]->fkUser );
		$this->setCreateDate( $result[0]->createDate );
		$this->setUpdate( $result[0]->updateDate );
		$this->setJsonToThis( $result->data );

		return $this;
	}

	public function getContractByOrderID( $idOrder ) {
		global $wpdb;

		$filter = "WHERE fkOrder = " . $idOrder;

		$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "wcc_contracts " . $filter . " LIMIT 2 ", OBJECT );

		if ( $result[0] == null || sizeof( $result ) == 0 ) {
			return null;
		}

		$this->setId( $result[0]->id );
		$this->setFkIdCommande( $result[0]->fkOrder );
		$this->setFkIdUser( $result[0]->fkUser );
		$this->setCreateDate( $result[0]->createDate );
		$this->setUpdate( $result[0]->updateDate );
		$this->setJsonToThis( $result[0]->data );

		return $this;
	}

	public function getThisToArray() {
		return get_object_vars( $this );
	}

	public function setArrayToThis( $array ) {
		$this->setLname( strip_tags( $array['lName'] ) );
		$this->setFname( strip_tags( $array['fName'] ) );
		$this->setDob( strip_tags( $array['dob'] ) );
		$this->setDriverlicense( strip_tags( $array['nPermis'] ) );
		$this->setDriverdate( strip_tags( $array['permisDate'] ) );
		$this->setAddress( strip_tags( $array['adresse'] ) );
		$this->setProprioName( strip_tags( $array['nomProprietaire'] ) );
		$this->setModel( strip_tags( $array['model'] ) );
		$this->setIdCar( strip_tags( $array['numCar'] ) );
		$this->setFuel( strip_tags( $array['fuel'] ) );
		$this->setKilVoiture( strip_tags( $array['kilVoiture'] ) );
		$this->setStartDate( strip_tags( $array['start'] ) );
		$this->setEndDate( strip_tags( $array['end'] ) );
		$this->setDeliveryAddress( strip_tags( $array['delivery'] ) );
		$this->setKilometers( strip_tags( $array['kilometre'] ) );
		$this->setLevelFuel( strip_tags( $array['levelFuel'] ) );
		$this->setPrice( strip_tags( $array['price'] ) );
		$this->setInsurance( strip_tags( $array['insurance'] ) );
		$this->setKmFinal( strip_tags( $array['kmFinal'] ) );
		$this->setFuelFinal( strip_tags( $array['fuelFinal'] ) );
		$this->setDateFinal( strip_tags( $array['dateFinal'] ) );
		$this->setCarBroken( strip_tags( $array['carBroken'] ) );
		$this->setComment( strip_tags( $array['comment'] ) );
		$this->setUrlContractPDF( strip_tags( $array['pdfLink'] ) );
		$this->setStatus( strip_tags( $array['status'] ) );
	}


	public function getThisToJson() {
		$array = array(
			"lName"           => $this->getLname(),
			"fName"           => $this->getFname(),
			"dob"             => $this->getDob(),
			"nPermis"         => $this->getDriverlicense(),
			"permisDate"      => $this->getDriverdate(),
			"adresse"         => $this->getAddress(),
			"nomProprietaire" => $this->getProprioName(),
			"model"           => $this->getModel(),
			"numCar"          => $this->getIdCar(),
			"fuel"            => $this->getFuel(),
			"kilVoiture"      => $this->getKilVoiture(),
			"start"           => $this->getStartDate(),
			"end"             => $this->getEndDate(),
			"delivery"        => $this->getDeliveryAddress(),
			"kilometre"       => $this->getKilometers(),
			"levelFuel"       => $this->getLevelFuel(),
			"price"           => $this->getPrice(),
			"insurance"       => $this->getInsurance(),
			"pdfLink"         => $this->getUrlContractPDF(),
			"kmFinal"         => $this->getKmFinal(),
			"fuelFinal"       => $this->getFuelFinal(),
			"dateFinal"       => $this->getDateFinal(),
			"carBroken"       => $this->getCarBroken(),
			"comment"         => $this->getComment(),
			"status"          => $this->getStatus()
		);

		return json_encode( $array );
	}

	public function setJsonToThis( $json ) {
		$this->setArrayToThis( json_decode( $json, true ) );
	}

	public function generatePDF() {

		$fields = array(
			'numContract' => $this->getFkIdCommande(),
			'nPermis'     => $this->getDriverlicense(),
			'permisDate'  => $this->getDriverdate(),
			'fullname'    => $this->getFname() . " " . $this->getLname(),
			'dob'         => $this->getDob(),
			'adresse'     => $this->getAddress(),
			'model'       => $this->getModel(),
			'numCar'      => $this->getIdCar(),
			'fuel'        => $this->getFuel(),
			'start'       => $this->getStartDate(),
			'end'         => $this->getEndDate(),
			'delivery'    => $this->getDeliveryAddress(),
			'kilometre'   => $this->getKilometers(),
			'levelFuel'   => $this->getLevelFuel(),
			'price'       => $this->getPrice(),
			'insurance'   => $this->getInsurance(),
			'kmFinal'     => $this->getKmFinal(),
			'distance'    => $this->getDistance(),
			'fuelFinal'   => $this->getFuelFinal(),
			'dateFinal'   => $this->getDateFinal(),
			'comments'    => $this->getComment(),
			'broken'      => $this->getCarBrokenText()
		);


		/*
		$pdfInit = new FPDM(dirname(__FILE__).'/template/Contract-de-Location-Formulaire-V2-repair.pdf');
		$pdfInit->useCheckboxParser = true; // Checkbox parsing is ignored (default FPDM behaviour) unless enabled with this setting
		$pdfInit->Load($fields, true);
		$pdfInit->Merge();
		*/

		$pdfInit = new Fpdi();
		$pdfInit->AddFont( 'roboto', '', 'roboto.php' );
		$pdfInit->AddPage();
		$pdfInit->setSourceFile( dirname( __FILE__ ) . '/template/Contract-de-Location-Page1.pdf' );
		$tppl = $pdfInit->importPage( 1 );
		$pdfInit->useTemplate( $tppl, 0, 0, 210 );


		$pdfInit->SetFont( 'roboto', '', 18 );
		$pdfInit->SetXY( 145, 45 );
		$pdfInit->SetTextColor( 255, 255, 255 );
		$pdfInit->Write( 0, $this->getFkIdCommande() );

		$pdfInit->SetFont( 'roboto', '', 13 );
		$pdfInit->SetTextColor( 102, 102, 102 );

		$pdfInit->SetXY( 45, 74.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getDriverlicense() ) );
		$pdfInit->SetXY( 51, 82.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getDriverdate() ) );
		$pdfInit->SetXY( 124, 74.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getFname() . " " . $this->getLname() ) );
		$pdfInit->SetXY( 152, 82.5 );
		$pdfInit->Write( 0, $this->getDob() );
		$pdfInit->SetXY( 36, 90.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getAddress() ) );


		$pdfInit->SetXY( 130, 109 );
		$pdfInit->Write( 0, utf8_decode( $this->getModel() ) );
		$pdfInit->SetXY( 60, 116.5 );
		$pdfInit->Write( 0, $this->getIdCar() );
		$pdfInit->SetXY( 135, 116.5 );
		$pdfInit->Write( 0, $this->getFuel() );


		$pdfInit->SetXY( 35, 143 );
		$pdfInit->Write( 0, utf8_decode( $this->getStartDate() ) );
		$pdfInit->SetXY( 28, 151 );
		$pdfInit->Write( 0, utf8_decode( $this->getEndDate() ) );
		$pdfInit->SetXY( 50, 159 );
		$pdfInit->Write( 0, utf8_decode( $this->getDeliveryAddress() ) );
		$pdfInit->SetXY( 148, 143 );
		$pdfInit->Write( 0, utf8_decode( $this->getKilometers() ) );
		$pdfInit->SetXY( 155, 151 );
		$pdfInit->Write( 0, $this->getLevelFuel() );
		$pdfInit->SetXY( 151, 159 );
		$pdfInit->Write( 0, utf8_decode( $this->getPrice() ) );


		$pdfInit->SetXY( 38, 178.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getInsurance() ) );


		$pdfInit->SetXY( 57, 197.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getKmFinal() ) );
		$pdfInit->SetXY( 59, 205.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getDistance() ) );
		$pdfInit->SetXY( 61, 213 );
		$pdfInit->Write( 0, utf8_decode( $this->getFuelFinal() ) );
		$pdfInit->SetXY( 136, 197.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getDateFinal() ) );
		$pdfInit->SetXY( 153, 205.5 );
		$pdfInit->Write( 0, utf8_decode( $this->getCarBrokenText() ) );


		$pdfInit->SetXY( 18, 227 );
		$pdfInit->Write( 0, utf8_decode( $this->getComment() ) );


		$pathToContract = dirname( dirname( __FILE__ ) ) . "/contracts";
		$UrlToContract  = plugin_dir_url( dirname( __FILE__ ) ) . "contracts";
		$namePage1      = "Page1-contract de loction n°" . $this->getFkIdCommande() . ".pdf";
		$namePagePhoto  = "PhotoAvant-contract de loction n°" . $this->getFkIdCommande() . ".pdf";
		$nameContract   = "contract de loction n°" . $this->getFkIdCommande() . ".pdf";

		$pdfContract = $pdfInit->Output( '', "S" );

		$dirname = $pathToContract . "/" . $this->getFkIdCommande() . "/";
		if ( ! is_dir( $dirname ) ) {
			mkdir( $dirname, 0755, true );
		}
		$handle = fopen( $dirname . $namePage1, 'w' ) or die( 'Cannot open file:  ' . $namePage1 ); //implicitly creates file
		fwrite( $handle, $pdfContract );
		fclose( $handle );

		$pdfContractPage1Link = dirname( dirname( __FILE__ ) ) . "/contracts/" . $this->getFkIdCommande() . "/" . $namePage1;

		$maxPage1    = 6;
		$imageObject = new ContractImages();
		$images      = $imageObject->getImagesByContractID( $this->getFkIdCommande() );
		$pdfPhoto    = new Fpdi();
		$pdfPhoto->AddPage();
		$pdfPhoto->setSourceFile( dirname( __FILE__ ) . '/template/Contract-de-Location-Photos-Avant.pdf' );

		$pagecount = $pdfPhoto->setSourceFile( dirname( __FILE__ ) . '/template/Contract-de-Location-Photos-Avant.pdf' );
		$tppl      = $pdfPhoto->importPage( 1 );
		$pdfPhoto->useTemplate( $tppl, 0, 0, 210 );


		if ( isset( $images[0] ) && ! empty( $images[0] ) ) {
			$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[0]->getUrl(), 7.5, 60, 90, 67.5 );
		}
		if ( isset( $images[1] ) && ! empty( $images[1] ) ) {
			$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[1]->getUrl(), 112, 60, 90, 67.5 );
		}
		if ( isset( $images[2] ) && ! empty( $images[2] ) ) {
			$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[2]->getUrl(), 7.5, 135, 90, 67.5 );
		}
		if ( isset( $images[3] ) && ! empty( $images[3] ) ) {
			$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[3]->getUrl(), 112, 135, 90, 67.5 );
		}
		if ( isset( $images[4] ) && ! empty( $images[4] ) ) {
			$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[4]->getUrl(), 7.5, 210, 90, 67.5 );
		}
		if ( isset( $images[5] ) && ! empty( $images[5] ) ) {
			$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[5]->getUrl(), 112, 210, 90, 67.5 );
		}


		if ( count( $images ) > 6 ) {
			$pdfPhoto->AddPage();
			$tppl = $pdfPhoto->importPage( 1 );
			$pdfPhoto->useTemplate( $tppl, 0, 0, 210 );

			if ( isset( $images[6] ) && ! empty( $images[6] ) ) {
				$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[6]->getUrl(), 5, 60, 90, 67.5 );
			}
			if ( isset( $images[7] ) && ! empty( $images[7] ) ) {
				$pdfPhoto->Image( dirname( dirname( __FILE__ ) ) . $images[7]->getUrl(), 112, 60, 90, 67.5 );
			}
		}
		$pdfPhotoAvant = $pdfPhoto->Output( '', 'S' );
		$handle = fopen( $dirname . $namePagePhoto, 'w' ) or die( 'Cannot open file:  ' . $namePagePhoto ); //implicitly creates file
		fwrite( $handle, $pdfPhotoAvant );
		fclose( $handle );
		$pdfPhotoAvantLink = dirname( dirname( __FILE__ ) ) . "/contracts/" . $this->getFkIdCommande() . "/" . $namePagePhoto;

		$pdfFinal = new Fpdi();
		$pdfFinal->setSourceFile( $pdfContractPage1Link );
		$tppl = $pdfFinal->importPage( 1, PdfReader\PageBoundaries::MEDIA_BOX );
		$pdfFinal->AddPage();
		$pdfFinal->useImportedPage( $tppl, 0, 0, 210 );

		$pdfFinal->AddPage();
		$pdfFinal->setSourceFile( $pdfPhotoAvantLink );
		$tppl = $pdfFinal->importPage( 1 );
		$pdfFinal->useTemplate( $tppl, 0, 0, 210 );

		if ( count( $images ) > 6 ) {
			$pdfFinal->AddPage();
			$pdfFinal->setSourceFile( $pdfPhotoAvantLink );
			$tppl = $pdfFinal->importPage( 2 );
			$pdfFinal->useTemplate( $tppl, 0, 0, 210 );
		}

		$pdfFinalString = $pdfFinal->Output( '', 'S' );
		$handle = fopen( $dirname . $nameContract, 'w' ) or die( 'Cannot open file:  ' . $nameContract ); //implicitly creates file
		fwrite( $handle, $pdfFinalString );
		fclose( $handle );
		$this->setUrlContractPDF( $UrlToContract . "/" . $this->getFkIdCommande() . "/" . $nameContract );

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

	public function getCarBrokenText() {
		if ( isset( $this->carBroken ) && ! empty( $this->carBroken ) ) {
			if ( $this->carBroken == 1 ) {
				return "Oui";
			} else {
				return "Non";
			}
		}

		return "";
	}

	/**
	 * @param mixed $carBroken
	 */
	public function setCarBroken( $carBroken ): void {
		$this->carBroken = $carBroken;
	}

	/**
	 * @return mixed
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @param mixed $comment
	 */
	public function setComment( $comment ): void {
		$this->comment = $comment;
	}


	public function getDistance() {
		$result = intval( $this->getKmFinal() ) - intval( $this->getKilVoiture() );
		if ( $result > 0 ) {
			return $result;
		}

		return "";
	}


}