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
		$car            = new WC_Product( intval( $item->get_data()["product_id"] ) );
		$rent_startDate = strip_tags( $item->get_meta( 'Date', true ) );;
		$rent_endDate  = strip_tags( $item->get_meta( 'Date de retour', true ) );
		$rent_delivery = strip_tags( $item->get_meta( 'Point de retour', true ) );
		$forfait_km    = strip_tags( $item->get_meta( 'Forfait kilométrique', true ) );
		$assurance     = strip_tags( $item->get_meta( 'Assurances', true ) );
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
			"price"           => strip_tags( $order->get_formatted_order_total() ),
			"insurance"       => $assurance,
			"kilVoiture"      => get_field( 'kilometrage_reel', 'post_' . $car->get_id() ),
		);
		$contract->setArrayToThis( $array );
	} else {
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
        <input type="hidden" name="carID" value="<?php echo $car->get_id() ?>"/>

        <input type="hidden" name="createDate" value="<?php echo $contract->getCreateDate() ?>"/>
        <input type="hidden" name="updateDate" value="<?php echo $contract->getUpdate() ?>"/>

		<?php if ( $contract->getStatus() < 2 ) { ?>
            <input type="hidden" name="status" value="<?php echo $contract->getStatus() ?>"/>
		<?php } else { ?>
            <input type="hidden" name="status" value="<?php echo $contract->getStatus() ?>"/>
            <input type="hidden" name="idContract" value="<?php echo $contract->getId() ?>"/>
		<?php } ?>

        <div class="debut">
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
                <div class="action <?php if ( $contract->getStatus() >= 2 )
					echo 'hidden' ?>">
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
                <div class="action <?php if ( $contract->getStatus() >= 2 )
					echo 'hidden' ?>">
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
                        <td><input type="hidden" name="kilVoiture" value="<?php echo $contract->getKilVoiture() ?>"/>
                        </td>
                    </tr>
                </table>
                <div class="action <?php if ( $contract->getStatus() >= 2 )
					echo 'hidden' ?>">
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

                <div class="action <?php if ( $contract->getStatus() >= 2  )
					echo 'hidden' ?>">
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
                <div class="action <?php if ( $contract->getStatus() >= 2 )
					echo 'hidden' ?>">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
            <div class="exterieur block">
                <h3>État du véhicule</h3>
                <div style="display: flex">
                    <div style="width: 48%">
                        <img style="width: 100%;height:auto;"
                             src="/wp-content/plugins/woocommerce-car-rent-contract/admin/assets/images/coupe-voiture.jpg"/>
                    </div>
                    <table style="margin-left:60px;">
                        <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Texte Photo Avant</th>
                            <th>Photo Avant</th>
                            <th>Texte Photo Après</th>
                            <th>Photo Après</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						$image = new ContractImages();
						$image->setFkContracts( $contract->getFkIdCommande() );
						$images = $image->getImagesByContractID();
						for ( $i = 1; $i <= ContractImages::getNumberType(); $i ++ ) {
							?>
                            <tr>
								<?php
								$tempObject  = $image->getImageByType( $i, $images, 1 );
								$tempObject2 = $image->getImageByType( $i, $images, 2 );

								if ( $tempObject == null ) {
									echo "<td>" . $i . "</td>";
									echo "<td><input type='texte' class='photoElem' name='title-photo-".$i."'></td>";
									echo "<td><input type='file' class='photoElem' name='photo-".$i."'></td>";

								} else {
									echo "<td>" . $tempObject->getTypeImage() . "</td>";
									echo "<td>" . $tempObject->getTitleImage() . "</td>";
									echo "<td><img width=150 src='/wp-content/plugins/woocommerce-car-rent-contract/" . $tempObject->getUrl() . "'/></td>";
								}

								if ( $tempObject2 == null ) {
									echo "<td><input type='texte' class='photoElem' name='title-photo2-".$i."'></td>";
									echo "<td><input type='file' class='photoElem' name='photo2-".$i."'></td>";
								} else {
									echo "<td>" . $tempObject2->getTitleImage() . "</td>";
									echo "<td><img width=150 src='/wp-content/plugins/woocommerce-car-rent-contract/" . $tempObject2->getUrl() . "'/></td>";
								}

								?>
                            </tr>
							<?php
						}
						?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="fin <?php if ( $contract->getStatus() < 2 ) echo 'hidden'; ?>">
            <h2>Fin de la location</h2>
            <div class="compte-rendu block">
                <h3>Compte rendu</h3>
                <table>
                    <tr class="kmFinal text">
                        <td>Relevé Km Après la restitution</td>
                        <td><?php echo $contract->getKmFinal() ?></td>
                        <td><input type="hidden" name="kmFinal" value="<?php echo $contract->getKmFinal() ?>"/></td>
                    </tr>
                    <tr class="fuelFinal text">
                        <td>Niveau de carburant final</td>
                        <td><?php echo $contract->getFuelFinal() ?></td>
                        <td><input type="hidden" name="fuelFinal" value="<?php echo $contract->getFuelFinal() ?>"/></td>
                    </tr>
                    <tr class="carBroken checkbox">
                        <td>La voiture est endommagé</td>
                        <td><?php echo $contract->getCarBrokenText() ?></td>
                        <td><input type="hidden" name="carBroken" value="<?php echo $contract->getCarBroken() ?>"/></td>
                    </tr>
                    <tr class="dateFinal date">
                        <td>Date de la restitution</td>
                        <td><?php echo $contract->getDateFinal() ?></td>
                        <td><input type="hidden" name="dateFinal" value="<?php echo $contract->getDateFinal() ?>"/></td>
                    </tr>
                    <tr class="comment textarea">
                        <td>Commentaire</td>
                        <td><?php echo $contract->getComment() ?></td>
                        <td><input type="hidden" name="comment" value="<?php echo $contract->getComment() ?>"/></td>
                    </tr>
                </table>
                <div class="action <?php if ( $contract->getStatus() == 3 )
					echo 'hidden' ?>">
                    <button class="modify_block">Modifier</button>
                </div>
            </div>
        </div>


        <div class="contractPDF">
			<?php
			if ( ! empty( $contract->getUrlContractPDF() ) ) {
				?>
                <a href="<?php echo $contract->getUrlContractPDF() ?>">Ouvrir le PDF</a>
				<?php if ( $contract->getStatus() != 3 ) { ?>
                    <a href="javascript:callSubmit('<?php echo admin_url( 'admin-post.php' ) ?>','contract')">Clore le
                        contrat et générer le PDF</a>
				<?php } ?>
			<?php } else { ?>
                <a href="javascript:callSubmit('<?php echo admin_url( 'admin-post.php' ) ?>','contract')">Generer le
                    PDF</a>
			<?php } ?>
        </div>

    </div>

	<?php

}