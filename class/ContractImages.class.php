<?php


class ContractImages {
	private $id;

	private $fkContracts;

	private $typeImage;
	private $imageValidation;
	private $titleImage;
	private $description;
	private $url;

	private $createDate;
	private $updateDate;

	/**
	 * ContractImages constructor.
	 */
	public function __construct() {
	}

	public function addImage( $idContract = null ) {
		if ( $idContract != null ) {
			$this->setFkContracts( $idContract );
		}

		global $wpdb;
		$wpdb->show_errors();
		$result = $wpdb->insert( $wpdb->prefix . 'wcc_contracts_image', [
			'fkContracts'     => $this->getFkContracts(),
			'typeImage'       => $this->getTypeImage(),
			'imageValidation' => $this->getImageValidation(),
			'titleImage'      => $this->getTitleImage(),
			'description'     => $this->getDescription(),
			'url'             => $this->getUrl(),
			'createDate'      => $this->getCreateDate(),
			'updateDate'      => $this->getUpdateDate()
		] );
	}

	public function updateContract() {

		global $wpdb;
		$result = $wpdb->update( $wpdb->prefix . 'wcc_contracts_image', [
			'fkContracts'     => $this->getFkContracts(),
			'typeImage'       => $this->getTypeImage(),
			'imageValidation' => $this->getImageValidation(),
			'titleImage'      => $this->getTitleImage(),
			'description'     => $this->getDescription(),
			'url'             => $this->getUrl(),
			'createDate'      => $this->getCreateDate(),
			'updateDate'      => $this->getUpdateDate()
		], [
			'id' => $this->getId()
		], [
			'%d',
			'%d',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		], [
			'%d'
		] );
	}

	public function getImagesByContractID( $fkContract = null ) {
		global $wpdb;
		if ( $fkContract != null ) {
			$this->setFkContracts( $fkContract );
		}

		$filter = "WHERE fkContracts = " . $this->getFkContracts();

		$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "wcc_contracts_image " . $filter . "ORDERBY typeImage", OBJECT );

		if ( $result[0] == null || sizeof( $result ) == 0 ) {
			return null;
		}

		$array = array();
		foreach ( $result as $res ) {
			$temp = new ContractImages();
			$temp->setId( $res->id );
			$temp->setFkContracts( $res->fkContracts );
			$temp->setTypeImage( $res->typeImage );
			$temp->setImageValidation( $res->imageValidation );
			$temp->setTitleImage( $res->titleImage );
			$temp->setDescription( $res->description );
			$temp->setUrl( $res->url );
			$temp->setCreateDate( $res->createDate );
			$temp->setUpdateDate( $res->updateDate );
			array_push( $array, $temp );
		}

		return $array;
	}


	public function getImageByType( $type, $array = null, $imageValidation = 1 ) {
		$arr = $array;
		if ( $array == null ) {
			$arr = $this->getImagesByContractID();
		}
		if ( $arr != null ) {
			foreach ( $arr as $row ) {
				if ( $row->getType() == $type && $row->getImageValidation == $imageValidation ) {
					return $row;
				}
			}
		}
		return null;
	}

	public static function getNumberType() {
		return 8;
	}

	public function uploadAndAdd( $fileObject, $orderID ) {

		$uploads_dir = "/contracts/images/";

		$source      = $fileObject['tmp_name'];
		$nameFile   = "Contract ".$orderID." - ".random_int(0, 10000)." - ".$fileObject['name'];
		$destination = trailingslashit( plugin_dir_path( __DIR__ ).$uploads_dir ) . $nameFile;
		move_uploaded_file( $source, $destination );

		$this->setUrl( trailingslashit( $uploads_dir ) .$nameFile );

		$this->addImage();
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
	 * @return mixed
	 */
	public function getFkContracts() {
		return $this->fkContracts;
	}

	/**
	 * @param mixed $fkContracts
	 */
	public function setFkContracts( $fkContracts ): void {
		$this->fkContracts = $fkContracts;
	}

	/**
	 * @return mixed
	 */
	public function getTypeImage() {
		return $this->typeImage;
	}

	/**
	 * @param mixed $typeImage
	 */
	public function setTypeImage( $typeImage ): void {
		$this->typeImage = $typeImage;
	}

	/**
	 * @return mixed
	 */
	public function getImageValidation() {
		return $this->imageValidation;
	}

	/**
	 * @param mixed $imageValidation
	 */
	public function setImageValidation( $imageValidation ): void {
		$this->imageValidation = $imageValidation;
	}


	/**
	 * @return mixed
	 */
	public function getTitleImage() {
		return $this->titleImage;
	}

	/**
	 * @param mixed $titleImage
	 */
	public function setTitleImage( $titleImage ): void {
		$this->titleImage = $titleImage;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ): void {
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param mixed $url
	 */
	public function setUrl( $url ): void {
		$this->url = $url;
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
	public function getUpdateDate() {
		return $this->updateDate;
	}

	/**
	 * @param mixed $updateDate
	 */
	public function setUpdateDate( $updateDate ): void {
		$this->updateDate = $updateDate;
	}


}