<?php

function detailContractAdminFunction() {

	$order    = new WC_Order( $_GET["orderID"] );
	$customer = get_user_by( 'id', $order->get_user_id() );


	$car            = null;
	$rent_startDate = "";
	$rent_endDate   = "";
	$rent_delivery  = "";
	$forfait_km     = "";
	$assurance      = "";
	foreach ( $order->get_items() as $item ) {
		$car                = new WC_Product( intval( $item->get_data()["product_id"] ) );
		$rent_startDate     = $item->get_meta( 'Date', true );;
		$rent_endDate       = $item->get_meta( 'Date de retour', true );
		$rent_delivery      = $item->get_meta( 'Point de retour', true );
		$forfait_km         = $item->get_meta( 'Forfait kilométrique', true );
		$assurance          = $item->get_meta( 'Assurances', true );
		//var_dump($item->get_meta_data());
	}

	$nomProprietaire = "DJOMAN LOCATION";

	$contract = new Contract();

	$today = new DateTime();
	if ( $contract->getContractByOrderID( $_GET["orderID"] ) == null ) {
		$contract->setFkIdCommande( $_GET["orderID"] );
		$contract->setFkIdUser( $order->get_user_id() );
		$contract->setCreateDate( $today->format( 'Y-m-d H:i:s' ) );
		$contract->setUpdate( $today->format( 'Y-m-d H:i:s' ) );
		$contract->setStatus( 1 );
		$array = array(
			"lName"           => $customer->user_lastname,
			"fName"           => $customer->user_firstname,
			"dob"             => get_field( 'date_de_naissance', 'user_' . $customer->ID ),
			"nPermis"         => get_field( 'numero_du_permis_de_conduire', 'user_' . $customer->ID ),
			"permisDate"      => get_field( 'date_dobtention_du_permis_b', 'user_' . $customer->ID ),
			"adresse"         => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() . ', ' . $order->get_billing_city() . ' ' . $order->get_billing_postcode(),
			"nomProprietaire" => $nomProprietaire,
			"model"           => get_field( 'modele', 'post_' . $car->get_id() ),
			"numCar"          => get_field( 'plaque_immatriculation', 'post_' . $car->get_id() ),
			"fuel"            => get_field( 'type_essence', 'post_' . $car->get_id() ),
			"start"           => $rent_startDate,
			"end"             => $rent_endDate,
			"delivery"        => $rent_delivery,
			"kilometre"       => $forfait_km,
			"levelFuel"       => "",
			"price"           => $order->get_formatted_order_total(),
			"insurance"       => $assurance,
			"kilVoiture"      => get_field( 'kilometrage_reel', 'post_' . $car->get_id() ),
			"face"            => "",
			"left"            => "",
			"back"            => "",
			"right"           => ""
		);
		$contract->setArrayToThis( $array );
	} else {
		$contract->setStatus( 2 );
		$contract->setUpdate( $today->format( 'Y-m-d H:i:s' ) );
	}


	?>

    <div>
        <a href="?page=car_rent_contracts">Revenir à la liste des commandes</a>
        <h1>Le détail de la commande <?php echo $order->get_id(); ?></h1>
    </div>


    <div id="contract">
        <input type="hidden" name="fkOrder" value="<?php echo $contract->getFkIdCommande() ?>"/>
        <input type="hidden" name="fkUser" value="<?php echo $contract->getFkIdUser() ?>"/>

        <input type="hidden" name="createDate" value="<?php echo $contract->getCreateDate() ?>"/>
        <input type="hidden" name="updateDate" value="<?php echo $contract->getUpdate() ?>"/>

		<?php if ( $contract->getStatus() < 2 ) { ?>
            <input type="hidden" name="status" value="<?php echo $contract->getStatus() ?>"/>
		<?php } else { ?>
            <input type="hidden" name="status" value="<?php echo $contract->getStatus() ?>"/>
            <input type="hidden" name="idContract" value="<?php echo $contract->getId() ?>"/>
		<?php } ?>

        <div class="debut <?php if ( $contract->getStatus() > 1 )
			echo 'hidden' ?>">
            <h2>Demande de la location</h2>
            <div class="locataire block">
                <h3>Locataire</h3>
                <table>
                    <tr class="lName text">
                        <td>Nom</td>
                        <td><?php echo $contract->getLname() ?></td>
                        <td><input type="hidden" name="lName" value="<?php echo $contract->getLname() ?>"/></td>
                    </tr>
                    <tr class="fName text">
                        <td>Prénom</td>
                        <td><?php echo $contract->getFname() ?></td>
                        <td><input type="hidden" name="fName" value="<?php echo $contract->getFname() ?>"/></td>
                    </tr>
                    <tr class="dob date">
                        <td>Né.e le</td>
                        <td><?php echo $contract->getDob() ?></td>
                        <td><input type="hidden" name="dob" value="<?php echo $contract->getDob() ?>"/></td>
                    </tr>
                    <tr class="nPermis text">
                        <td>Numéro de permis</td>
                        <td><?php echo $contract->getDriverlicense() ?></td>
                        <td><input type="hidden" name="nPermis" value="<?php echo $contract->getDriverlicense() ?>"/>
                        </td>
                    </tr>
                    <tr class="permis date">
                        <td>Obtention du permis</td>
                        <td><?php echo $contract->getDriverdate() ?></td>
                        <td><input type="hidden" name="permisDate" value="<?php echo $contract->getDriverdate() ?>"/>
                        </td>
                    </tr>
                    <tr class="adresse text">
                        <td>Adresse</td>
                        <td><?php echo $contract->getAddress() ?></td>
                        <td><input type="hidden" name="adresse" value="<?php echo $contract->getAddress() ?>"/></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="proprietaire block">
                <h3>Proriétaire</h3>
                <table>
                    <tr class="nomProprietaire text">
                        <td>Nom</td>
                        <td><?php echo $contract->getProprioName() ?></td>
                        <td><input type="hidden" name="nomProprietaire"
                                   value="<?php echo $contract->getProprioName() ?>"/></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="voiture block">
                <h3>Voiture</h3>
                <table>
                    <tr class="model text">
                        <td>Modèle</td>
                        <td><?php echo $contract->getModel() ?></td>
                        <td><input type="hidden" name="model" value="<?php echo $contract->getModel() ?>"/></td>

                    </tr>
                    <tr class="numCar text">
                        <td>Numéro d'immatriculation</td>
                        <td><?php echo $contract->getIdCar() ?></td>
                        <td><input type="hidden" name="numCar" value="<?php echo $contract->getIdCar() ?>"/></td>

                    </tr>
                    <tr class="fuel text">
                        <td>Type de carburant</td>
                        <td><?php echo $contract->getFuel() ?></td>
                        <td><input type="hidden" name="fuel" value="<?php echo $contract->getFuel() ?>"/></td>
                    </tr>
                    <tr class="fuel text">
                        <td>Kilométrage de la voiture actuellement</td>
                        <td><?php echo $contract->getKilVoiture() ?></td>
                        <td><input type="hidden" name="kilVoiture" value="<?php echo $contract->getKilVoiture() ?>"/></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="location block">
                <h3>Location</h3>
                <table>
                    <tr class="start datetime-local">
                        <td>Début</td>
                        <td><?php echo $contract->getStartDate() ?></td>
                        <td><input type="hidden" name="start" value="<?php echo $contract->getStartDate() ?>"/></td>
                    </tr>
                    <tr class="end datetime-local">
                        <td>Fin</td>
                        <td><?php echo $contract->getEndDate() ?></td>
                        <td><input type="hidden" name="end" value="<?php echo $contract->getEndDate() ?>"/></td>
                    </tr>
                    <tr class="delivery text">
                        <td>Récupération de la voiture</td>
                        <td><?php echo $contract->getDeliveryAddress(); ?></td>
                        <td><input type="hidden" name="delivery" value="<?php echo $contract->getDeliveryAddress() ?>"/>
                        </td>
                    </tr>
                    <tr class="kilometre text">
                        <td>Kilomètre inclus</td>
                        <td><?php echo $contract->getKilometers(); ?></td>
                        <td><input type="hidden" name="kilometre" value="<?php echo $contract->getKilometers() ?>"/>
                        </td>
                    </tr>
                    <tr class="levelFuel text">
                        <td>Niveau de carburant</td>
                        <td><?php echo $contract->getLevelFuel() ?></td>
                        <td><input type="hidden" name="levelFuel" value="<?php echo $contract->getLevelFuel() ?>"/></td>
                    </tr>
                    <tr class="price">
                        <td>Prix de la location</td>
                        <td><?php echo $contract->getPrice() ?></td>
                        <td><input type="hidden" name="price" value="<?php echo $contract->getPrice() ?>"/></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="sinistre block">
                <h3>Location</h3>
                <table>
                    <tr class="insurance text">
                        <td>Franchise</td>
                        <td><?php echo $contract->getInsurance() ?></td>
                        <td><input type="hidden" name="insurance" value="<?php echo $contract->getInsurance() ?>"/></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="exterieur block">
                <h3>État extérieur</h3>
                <table>
                    <tr class="face textarea">
                        <td>Face</td>
                        <td><?php echo $contract->getFront() ?></td>
                        <td><input type="hidden" name="face" value="<?php echo $contract->getFront() ?>"/></td>
                    </tr>
                    <tr class="left textarea">
                        <td>Gauche</td>
                        <td><?php echo $contract->getLeft() ?></td>
                        <td><input type="hidden" name="face" value="<?php echo $contract->getLeft() ?>"/></td>
                    </tr>
                    <tr class="back textarea">
                        <td>Derrière</td>
                        <td><?php echo $contract->getBack() ?></td>
                        <td><input type="hidden" name="face" value="<?php echo $contract->getBack() ?>"/></td>
                    </tr>
                    <tr class="right textarea">
                        <td>Droite</td>
                        <td><?php echo $contract->getRight() ?></td>
                        <td><input type="hidden" name="face" value="<?php echo $contract->getRight() ?>"/></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="generateContract">
                <a href="javascript:callSubmit('<?php echo admin_url( 'admin-post.php' ) ?>','contract')">Créer un
                    contrat</a>
            </div>
        </div>

        <div class="contractPDF">
            <p>Le lien de Contrat</p>
			<?php if ( ! empty( $contract->getUrlContractPDF() ) ) { ?>
                <a href="<?php echo $contract->getUrlContractPDF() ?>">Lien</a>
			<?php } else { ?>
                <a href="">Generer le PDF</a>
			<?php } ?>
        </div>

        <div class="fin <?php if ( $contract->getStatus() < 2 )
			echo 'hidden' ?>">
        <h2>Fin de la location</h2>
        <div class="compte-rendu block">
            <h3>Compte rendu</h3>
            <table>
                <tr>
                    <td>Distance parcourue</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Niveau de carburant</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Voiture endommagée</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Restitution de la voiture</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    </div>

	<?php

}