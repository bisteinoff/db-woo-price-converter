<?php // THE SETTINGS PAGE

	$db_converter = new dbWooConverter();
	$d = $db_converter->thisdir(); // domain for translate.wordpress.org

	$currancy_from = sanitize_text_field ( get_option( 'db_woo_converter_currancy_from' ) );
	$currancy_to = sanitize_text_field ( get_option( 'db_woo_converter_currancy_to' ) );
	$date =  sanitize_text_field ( get_option( 'db_woo_converter_date' ) );
	$date_cbr =  sanitize_text_field ( get_option( 'db_woo_converter_date_cbr' ) );
	$rate_cbr = (float) get_option( 'db_woo_converter_rate_cbr' );
	$rate = (float) get_option( 'db_woo_converter_rate' );
	$if_cbr = (int) get_option( 'db_woo_converter_if_cbr' );
	$margin = (float) get_option( 'db_woo_converter_margin' );

	// Getting the exchange rates from CBR. Source: https://www.cbr-xml-daily.ru/
	function CBR_XML_Daily_Ru()
	{
		static $rates;
		
		if ($rates === null) {
			$rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'));
		}
		
		return $rates;
	}

	$now = date("ymdH");

	if ( $date < $now - 3 )
	{
		$data = CBR_XML_Daily_Ru();
		$date_cbr = sanitize_text_field ( $data->Date );
		$rate_cbr = round ( $data->Valute->$currancy_from->Value , 2 );
		update_option ( 'db_woo_converter_date_cbr', $date_cbr );
		update_option ( 'db_woo_converter_rate_cbr', $rate_cbr );
		update_option ( 'db_woo_converter_date', $now );
	}

	if ( isset ( $_POST['submit'] ) )
	{

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e( "Error: You do not have the permission to update the value" , $d ) );

		if ( function_exists('check_admin_referrer') )
			check_admin_referrer( $d . '_form' );

		// Currancy from
		if ( !empty ( $_POST['currancy_from'] ) )
		{
			$currancy_from = sanitize_text_field ( $_POST['currancy_from'] );
			update_option ( 'db_woo_converter_currancy_from', $currancy_from );
		}
		else
			update_option ( 'db_woo_converter_currancy_from', 'USD' );

		// Currancy to
		if ( !empty ( $_POST['currancy_to'] ) )
		{
			$currancy_to = sanitize_text_field ( $_POST['currancy_to'] );
			update_option ( 'db_woo_converter_currancy_to', $currancy_to );
		}
		else
			update_option ( 'db_woo_converter_currancy_to', 'RUR' );

		// Custom Exchange Rate
		if ( !empty ( $_POST['rate'] ) )
		{
			$rate = (float) $_POST['rate'];
			update_option ( 'db_woo_converter_rate', round ( $rate, 2 ) );
		}
		else
			update_option ( 'db_woo_converter_rate', '1' );

		// Margin
		if ( !empty ( $_POST['margin'] ) )
		{
			$margin = (float) $_POST['margin'];
			update_option ( 'db_woo_converter_margin', round ( $margin, 2 ) );
		}
		else
			update_option ( 'db_woo_converter_margin', '0' );

	}

?>
<div class='wrap db-woo-converter-admin'>

	<h1><?php _e("DB Woocommerce Price Converter", $d ) ?></h1>

	<div class="db-woo-converter-description">
		<p><?php _e("The plugin is used for converting the prices from one currency to another", $d ) ?></p>
	</div>

	<h2><?php _e( "Settings", $d ) ?></h2>

	<?php

print_r($data);

?>

	<form name="db-woo-converter" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=<?php echo $d; ?>&amp;updated=true">

		<?php
			if (function_exists ( 'wp_nonce_field' ) )
				wp_nonce_field( $d . '_form' );
		?>

		<table class="form-table db-woo-converter-table" width="100%">
			<tr valign="top">
				<th scope="col" width="25%">
					<?php _e("Parameter", $d ) ?>
				</th>
				<th scope="col" width="25%">
					<?php _e("Value", $d ) ?>
				</th>
				<th scope="col" width="50%">
					<?php _e("Current Exchange Rate", $d) ?>
				</th>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php _e( "Convert from", $d ) ?>
					<div class="db-woo-converter-field-description">
						<?php _e( "The currancy of the prices in WooCommerce", $d ) ?>
					</div>
				</th>
				<td>
					<input type="text" name="currancy_from" id="db_woo_converter_currancy_from"
							size="20" value="<?php echo $currancy_from; ?>" />
				</td>
				<td rowspan="2">
					<div class="db-woo-converter-rate-cbr">
						<?php _e( "Exchange Rate of CBR", $d ) ?>: <span><?php echo $rate_cbr; ?></span>
					</div>
					<div class="db-woo-converter-date-cbr">
						<?php _e( "Date", $d ) ?>: <span><?php echo $date_cbr; ?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php _e( "Convert to", $d ) ?>
					<div class="db-woo-converter-field-description">
						<?php _e( "The currancy of the prices shown on the website", $d ) ?>
					</div>
				</th>
				<td>
					<input type="text" name="currancy_to" id="db_woo_converter_currancy_to"
							size="20" value="<?php echo $currancy_to; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php _e( "Custom Exchange Rate", $d ) ?>
					<div class="db-woo-converter-field-description">
						<?php _e( "You can set your custom exchange rate", $d ) ?>
					</div>
				</th>
				<td>
					<input type="text" name="if_cbr" id="db_woo_converter_if_cbr"
							size="20" value="<?php echo $if_cbr; ?>" />
					<input type="text" name="rate" id="db_woo_converter_rate"
							size="20" value="<?php echo $rate; ?>" />
				</td>
				<td rowspan="2">
					<div class="db-woo-converter-rate-website">
						<?php _e( "Exchange Rate On Your Website", $d ) ?>: <span><?php
							echo ( $if_cbr === '1' ? $rate_cbr + $margin : $rate + $margin );
						?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php _e( "Margin", $d ) ?>
					<div class="db-woo-converter-field-description">
						<?php _e( "You can set a margin. It will be added to the amount of your exchange rate", $d ) ?>
					</div>
				</th>
				<td>
					<input type="text" name="margin" id="db_woo_converter_margin"
							size="20" value="<?php echo $margin; ?>" />
				</td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />

		<input type="hidden" name="page_options" value="db_woo_converter_cols" />

		<?php submit_button(); ?>

	</form>

</div>