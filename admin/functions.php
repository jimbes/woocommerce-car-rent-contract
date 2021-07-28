<?php

add_action( 'admin_enqueue_scripts', 'load_admin_style' );
function load_admin_style() {
	wp_enqueue_style( 'admin_css_cart_rent_contract', plugins_url('/assets/css/style_admin.css', __FILE__));
	wp_enqueue_script('admin_js_cart_rent_contract', plugins_url('/assets/js/contract_admin.js', __FILE__),array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script('admin_js_pdf', plugins_url('/assets/js/html2pdf.bundle.min.js', __FILE__),array( 'jquery' ), '1.0.0', true );
}


add_action('admin_post_addContract', 'addContract' );
function addContract(){

	$contract = new Contract();
	$contract->setFkIdCommande($_POST["fkOrder"]);
	$contract->setFkIdUser($_POST["fkUser"]);
	$contract->setCreateDate($_POST["createDate"]);
	$contract->setUpdate($_POST["updateDate"]);
	$contract->setStatus($_POST["status"]);

	$contract->setArrayToThis($_POST);

	if($contract->getStatus() < 2) {
		$contract->generatePDF();
		$contract->setStatus(2);
		$contract->createContract();
		if(isset($_FILES) && !empty($_FILES)){
			foreach ($_FILES as $row => $file){
				if($file["error"] == 0) {
					$today = new DateTime();
					$image = new ContractImages();
					$image->setFkContracts( $contract->getFkIdCommande() );
					$image->setTypeImage(substr($row,-1,1));
					$image->setTitleImage($_POST["title-photo-".$image->getTypeImage()]);
					$image->setDescription("");
					$image->setImageValidation(1);
					$image->setCreateDate($today->format( 'Y-m-d H:i:s' ));
					$image->setUpdateDate($today->format( 'Y-m-d H:i:s' ));
					$image->uploadAndAdd($file,$contract->getFkIdCommande());
				}
			}
			$contract->generatePDF();
		}
	}elseif($contract->getStatus() == 2){
		$contract->setId($_POST["idContract"]);
		$contract->generatePDF();
		$contract->setStatus(3);
		$contract->updateContract();
		if(isset($_FILES) && !empty($_FILES)){
			foreach ($_FILES as $row => $file){
				if($file["error"] == 0) {
					$today = new DateTime();
					$image = new ContractImages();
					$image->setFkContracts( $contract->getFkIdCommande() );
					$image->setTypeImage(substr($row,-1,1));
					$image->setTitleImage($_POST["title-photo2-".$image->getTypeImage()]);
					$image->setDescription("");
					$image->setImageValidation(2);
					$image->setCreateDate($today->format( 'Y-m-d H:i:s' ));
					$image->setUpdateDate($today->format( 'Y-m-d H:i:s' ));
					$image->uploadAndAdd($file,$contract->getFkIdCommande());
				}
			}
			$contract->generatePDF();
		}
		update_field( 'kilometrage_reel',  intval($contract->getKmFinal()) , 'post_'.$_POST["carID"]);
	}elseif($contract->getStatus() == 3){

		$contract->getContractByID($_POST["idContract"]);


		$user = get_user_by("id",$contract->getFkIdUser());


		$order = wc_get_order( $contract->getFkIdCommande() );
		$orderArray  = $order->get_data();

		$path = parse_url($contract->getUrlContractPDF(), PHP_URL_PATH);
		$doc = pathinfo($_SERVER['DOCUMENT_ROOT'] . $path);


		$contratArray = array(
							"ID"=>$contract->getId(),
							"Name"=>$doc["filename"]
		);

		$userArray = array(
							"email"=>$user->user_email,
							"FName"=>$user->first_name,
							"LName"=>$user->last_name,
							"Address1"=>$orderArray['billing']['address_1'],
							"Address2"=>$orderArray['billing']['address_2'],
							"City"=>$orderArray['billing']['city'],
							"State"=>$orderArray['billing']['state'],
							"Zip"=>$orderArray['billing']['postcode'],
							"Pays"=>$orderArray['billing']['country'],
							"Notes"=>"",
							"Phone"=>$orderArray['billing']['phone'],
							"Mobile"=>$orderArray['billing']['phone'],
							"Company"=>"",
			);


		$documentArray = array(
			"FriendlyName" => $doc["filename"],
			"fileName" => $doc["basename"],
			"linkAbsolute" => $doc["dirname"]."/".$doc["basename"],

		);

		// request signature
		$signature = new Docage();
		$signature->createSignature($contratArray,$userArray,$documentArray);

	}


	wp_redirect( admin_url( 'admin.php?page=car_rent_contract_detail&orderID='.$contract->getFkIdCommande() ) );

}

add_action('admin_post_updateContract', 'updateContract' );
function updateContract(){

}