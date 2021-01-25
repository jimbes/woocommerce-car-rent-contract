<?php

function listContractAdminFunction() {


	$args   = array(
		'limit'   => 20,
		'return'  => 'ids',
		'status'  => 'Processing',
		"orderby" => 'id',
	);
	$query  = new WC_Order_Query( $args );
	$orders = $query->get_orders();

	?>

    <div>
        <h1>Liste des commandes</h1>
    </div>

    <table class="wp-list-table widefat fixed striped tags">
        <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column">
                <label class="screen-reader-text" for="cb-select-all-1">Tout sélectionner</label>
                <input id="cb-select-all-1" type="checkbox">
            </td>
            <th scope="col" id="idOrder" class="manage-column column-name column-idOrder sortable desc">
                <a href="?page=car_rent_contracts&orderby=idOrder&order=asc">
                    <span>Numéro de la commande</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="client" class="manage-column column-name column-client sortable desc">
                <a href="?page=car_rent_contracts&orderby=client&order=asc">
                    <span>Client</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="orderDate" class="manage-column column-description sortable desc">
                <a href="?page=car_rent_contracts&orderby=orderDate&order=asc">
                    <span>Date de commande</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="rentStartDate" class="manage-column column-description sortable desc">
                <a href="?page=car_rent_contracts&orderby=rentStartDate&order=asc">
                    <span>Date de début de la location</span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
        </tr>
        </thead>

        <tbody id="the-list" data-wp-lists="list:tag">
		<?php
		foreach ( $orders as $order_id ) {
			$order        = wc_get_order( $order_id );
			$customer     = get_user_by( 'id', $order->get_user_id() );
			$articlesDate = "";

			$articles = $order->get_items();
			if ( count( $articles ) > 0 ) {
				foreach ( $articles as $article ) {
					$rangeDate = "<p>";
					$rangeDate .= "Commence le ";
					$rangeDate .= $article->get_meta( 'Date', true );
					$rangeDate .= " <br/>Fini le ";
					$rangeDate .= $article->get_meta( 'Date de retour', true );
					$rangeDate .= "</p>";

					$articlesDate .= $rangeDate;
					//var_dump($article->get_meta_data());
				}



				?>

                <tr id="order-<?php echo $order_id; ?>" class="level-0">
                    <th scope="row" class="check-column">
                        <label class="screen-reader-text"
                               for="cb-select-<?php echo $order_id; ?>">Sélectionner la
                            commande <?php echo $order_id; ?></label>
                        <input type="checkbox" name="delete_tags[]" value="<?php echo $order_id ?>"
                               id="cb-select-<?php echo $order_id ?>">
                    </th>
                    <td class="idOrder column-idOrder" data-colname="idOrder">
                        <a class="row-title"
                           href="?page=car_rent_contract_detail&orderID=<?php echo $order_id ?>">
							<?php echo $order_id ?></a></td>
                    <td class="client column-client has-row-actions column-primary" data-colname="client">
                        <strong>
						 <span aria-hidden="true">
                            <?php echo $customer->user_firstname . " " . $customer->user_lastname ?>
                             </span>
                        </strong>
                    </td>
                    <td class="orderDate column-orderDate" data-colname="orderDate">
                        <span aria-hidden="true"><?php echo $order->order_date ?></span>
                    </td>
                    <td class="rentStartDate column-rentStartDate" data-colname="rentStartDate">
                        <span><?php echo $articlesDate ?></span>
                    </td>
                </tr>

				<?php
			}
		} ?>
        </tbody>
    </table>

	<?php

}