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

		$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "wcc_contracts_image " . $filter . " ORDER BY typeImage", OBJECT );

		//$wpdb->show_errors();
		if ( $result == null || sizeof( $result ) == 0 ) {
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
				if ( $row->getTypeImage() == $type && $row->getImageValidation() == $imageValidation ) {
					return $row;
				}
			}
		}
		return null;
	}
	public function getImageByValidation( $array = null, $imageValidation = 1 ) {
		$arr = $array;
		$arrayFinal = array();
		if ( $array == null ) {
			$arr = $this->getImagesByContractID();
		}
		if ( $arr != null ) {
			foreach ( $arr as $row ) {
				if ($row->getImageValidation() == $imageValidation ) {
					array_push($arrayFinal,$row);
				}
			}
		}
		return $arrayFinal;
	}

	public static function getNumberType() {
		return 8;
	}

	public function uploadAndAdd( $fileObject, $orderID ) {

		$uploads_dir = "/contracts/images/";

		$source      = $fileObject['tmp_name'];
		$nameFile   = "Contract ".$orderID." - ".random_int(0, 10000)." - ".$fileObject['name'];
		$destination = trailingslashit( plugin_dir_path( __DIR__ ).$uploads_dir ) . $nameFile;
		$this->resize_crop_image( 900,675, $source, $destination );
		//move_uploaded_file( $source, $destination );

		$this->setUrl( trailingslashit( $uploads_dir ) .$nameFile );

		$this->addImage();
	}

	//resize and crop image by center
	private function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];

		switch($mime){
			case 'image/gif':
				$image_create = "imagecreatefromgif";
				$image = "imagegif";
				break;

			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 7;
				break;

			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 80;
				break;

			default:
				return false;
				break;
		}

		$dst_img = imagecreatetruecolor($max_width, $max_height);
		$src_img = $image_create($source_file);

		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			//cut point by height
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			//cut point by width
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}

		$image($dst_img, $dst_dir, $quality);

		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
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