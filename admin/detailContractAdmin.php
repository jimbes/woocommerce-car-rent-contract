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
		$rent_startDate = $item->get_meta( 'Date', true );;
		$rent_endDate  = $item->get_meta( 'Date de retour', true );
		$rent_delivery = $item->get_meta( 'Point de retour', true );
		$forfait_km    = $item->get_meta( 'Forfait kilométrique', true );
		$assurance     = $item->get_meta( 'Assurances', true );
		//var_dump($item->get_meta_data());
	}

	?>

    <div>
        <a href="?page=car_rent_contracts">Revenir à la liste des commandes</a>
        <h1>Le détail de la commande <?php echo $order->get_id(); ?></h1>
    </div>


    <div id="contract">
        <div class="debut" >
            <h2>Demande de la location</h2>
            <div class="locataire block">
                <h3>Locataire</h3>
                <table>
                    <tr class="lName text">
                        <td>Nom</td>
                        <td><?php echo $customer->user_lastname ?></td>
                    </tr>
                    <tr class="fName text">
                        <td>Prénom</td>
                        <td><?php echo $customer->user_firstname ?></td>
                    </tr>
                    <tr class="dob date">
                        <td>Né.e le</td>
                        <td><?php echo get_field( 'date_de_naissance', 'user_' . $customer->ID ); ?></td>
                    </tr>
                    <tr class="nPermis text">
                        <td>Numéro de permis</td>
                        <td><?php echo get_field( 'numero_du_permis_de_conduire', 'user_' . $customer->ID ); ?></td>
                    </tr>
                    <tr class="permis date">
                        <td>Obtention du permis</td>
                        <td><?php echo get_field( 'date_dobtention_du_permis_b', 'user_' . $customer->ID ); ?></td>
                    </tr>
                    <tr class="adresse text">
                        <td>Adresse</td>
                        <td><?php
							echo $order->get_billing_address_1() . " " .
							     $order->get_billing_address_2() . ", " .
							     $order->get_billing_city() . " " .
							     $order->get_billing_postcode();
							?>
                        </td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                    <button class="validate_block">Confirmer</button>
                </div>
            </div>
            <div class="proprietaire block">
                <h3>Proriétaire</h3>
                <table>
                    <tr class="nomProprietaire text">
                        <td>Nom</td>
                        <td>DJOMAN LOCATION</td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                    <button class="validate_block">Confirmer</button>
                </div>
            </div>
            <div class="voiture block">
                <h3>Voiture</h3>
                <table>
                    <tr class="model text">
                        <td>Modèle</td>
                        <td><?php echo get_field( 'modele', 'post_' . $car->get_id() ); ?></td>
                    </tr>
                    <tr class="numCar text">
                        <td>Numéro d'immatriculation</td>
                        <td><?php echo get_field( 'plaque_immatriculation', 'post_' . $car->get_id() ); ?></td>
                    </tr>
                    <tr class="fuel text">
                        <td>Type de carburant</td>
                        <td><?php echo get_field( 'type_essence', 'post_' . $car->get_id() ); ?></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                    <button class="validate_block">Confirmer</button>
                </div>
            </div>
            <div class="location block">
                <h3>Location</h3>
                <table>
                    <tr class="start datetime-local">
                        <td>Début</td>
                        <td><?php echo $rent_startDate ?></td>
                    </tr>
                    <tr class="end datetime-local">
                        <td>Fin</td>
                        <td><?php echo $rent_endDate ?></td>
                    </tr>
                    <tr class="delivery text">
                        <td>Récupération de la voiture</td>
                        <td><?php echo $rent_delivery; ?></td>
                    </tr>
                    <tr class="kilometre text">
                        <td>Kilomètre inclus</td>
                        <td><?php echo $forfait_km; ?></td>
                    </tr>
                    <tr class="levelFuel text">
                        <td>Niveau de carburant</td>
                        <td></td>
                    </tr>
                    <tr class="price">
                        <td>Prix de la location</td>
                        <td><?php echo $order->get_formatted_order_total(); ?></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                    <button class="validate_block">Confirmer</button>
                </div>
            </div>
            <div class="sinistre block">
                <h3>Location</h3>
                <table>
                    <tr class="insurance text">
                        <td>Franchise</td>
                        <td><?php echo $assurance ?></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                    <button class="validate_block">Confirmer</button>
                </div>
            </div>
            <div class="exterieur block">
                <h3>État extérieur</h3>
                <table>
                    <tr class="face textarea">
                        <td>Face</td>
                        <td></td>
                    </tr>
                    <tr class="left textarea">
                        <td>Gauche</td>
                        <td></td>
                    </tr>
                    <tr class="back textarea">
                        <td>Derrière</td>
                        <td></td>
                    </tr>
                    <tr class="right textarea">
                        <td>Droite</td>
                        <td></td>
                    </tr>
                </table>
                <div class="action">
                    <button class="modify_block">Modifier</button>
                    <button class="validate_block">Confirmer</button>
                </div>
            </div>
        </div>
        <a id="save">Generate</a>
        <div class="fin">
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