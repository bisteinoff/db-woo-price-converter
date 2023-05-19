<?php // THE SETTINGS PAGE

	$db_converter = new dbWooConverter();
	$d = $db_converter -> thisdir(); // domain for translate.wordpress.org

	$currencies = array(
		'USD' => array ( '$', 'Доллар США' ),
		'EUR' => array ( '€', 'Евро' ),
		'GBP' => array ( '£', 'Британский фунт стерлингов' ),
		'CNY' => array ( '¥', 'Китайский юань' ),
		'JPY' => array ( '¥', 'Японская иена' ),
		'AUD' => array ( 'AUD', 'Австралийский доллар' ),
		'AZN' => array ( 'AZN', 'Азербайджанский манат' ),
		'AMD' => array ( 'AMD', 'Армянских драмов' ),
		'BYN' => array ( 'BYN', 'Белорусский рубль' ),
		'BGN' => array ( 'BGN', 'Болгарский лев' ),
		'BRL' => array ( 'BRL', 'Бразильский реал' ),
		'HUF' => array ( 'HUF', 'Венгерских форинтов' ),
		'VND' => array ( 'VND', 'Вьетнамских донгов' ),
		'HKD' => array ( 'HKD', 'Гонконгский доллар' ),
		'GEL' => array ( 'GEL', 'Грузинский лари' ),
		'DKK' => array ( 'DKK', 'Датская крона' ),
		'AED' => array ( 'AED', 'Дирхам ОАЭ' ),
		'EGP' => array ( 'EGP', 'Египетских фунтов' ),
		'INR' => array ( 'INR', 'Индийских рупий' ),
		'IDR' => array ( 'IDR', 'Индонезийских рупий' ),
		'KZT' => array ( 'KZT', 'Казахстанских тенге' ),
		'CAD' => array ( 'CAD', 'Канадский доллар' ),
		'QAR' => array ( 'QAR', 'Катарский риал' ),
		'KGS' => array ( 'KGS', 'Киргизских сомов' ),
		'MDL' => array ( 'MDL', 'Молдавских леев' ),
		'NZD' => array ( 'NZD', 'Новозеландский доллар' ),
		'NOK' => array ( 'NOK', 'Норвежских крон' ),
		'PLN' => array ( 'PLN', 'Польский злотый' ),
		'RON' => array ( 'RON', 'Румынский лей' ),
		'XDR' => array ( 'XDR', 'СДР' ),
		'SGD' => array ( 'SGD', 'Сингапурский доллар' ),
		'TJS' => array ( 'TJS', 'Таджикских сомони' ),
		'THB' => array ( 'THB', 'Таиландских батов' ),
		'TRY' => array ( 'TRY', 'Турецких лир' ),
		'TMT' => array ( 'TMT', 'Новый туркменский манат' ),
		'UZS' => array ( 'UZS', 'Узбекских сумов' ),
		'UAH' => array ( 'UAH', 'Украинских гривен' ),
		'CZK' => array ( 'CZK', 'Чешских крон' ),
		'SEK' => array ( 'SEK', 'Шведских крон' ),
		'CHF' => array ( 'CHF', 'Швейцарский франк' ),
		'RSD' => array ( 'RSD', 'Сербских динаров' ),
		'ZAR' => array ( 'ZAR', 'Южноафриканских рэндов' ),
		'KRW' => array ( 'KRW', 'Вон Республики Корея' )
	);

	$currency_from = sanitize_text_field ( get_option( 'db_woo_converter_currency_from' ) );
	$currency_to = sanitize_text_field ( get_option( 'db_woo_converter_currency_to' ) );
	$date =  sanitize_text_field ( get_option( 'db_woo_converter_date' ) );
	$date_cbr =  sanitize_text_field ( get_option( 'db_woo_converter_date_cbr' ) );
	$rate_cbr = (float) get_option( 'db_woo_converter_rate_cbr' );
	$rate = (float) get_option( 'db_woo_converter_rate' );
	$if_cbr = sanitize_text_field ( get_option( 'db_woo_converter_if_cbr' ) );
	$margin = (float) get_option( 'db_woo_converter_margin' );
	$if_change = false; // if the currency has changed it is true


	if ( isset ( $_POST['submit'] ) )
	{

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e( "Error: You do not have the permission to update the value" , $d ) );

		if ( function_exists('check_admin_referrer') )
			check_admin_referrer( $d . '_form' );

		if ( $_POST['currency_from'] !== $currency_from || $_POST['currency_to'] !== $currency_to ) $if_change = true;

		// Currency from
		if ( !empty ( $_POST['currency_from'] ) )
		{
			$currency_from = sanitize_text_field ( $_POST['currency_from'] );
			update_option ( 'db_woo_converter_currency_from', $currency_from );
		}
		else
			update_option ( 'db_woo_converter_currency_from', 'USD' );

		// Currency to
		if ( !empty ( $_POST['currency_to'] ) )
		{
			$currency_to = sanitize_text_field ( $_POST['currency_to'] );
			update_option ( 'db_woo_converter_currency_to', $currency_to );
		}
		else
			update_option ( 'db_woo_converter_currency_to', 'RUR' );

		// Enable Exchange Rate of CBR
		$if_cbr = sanitize_text_field ( $_POST['if_cbr'] );
		update_option ( 'db_woo_converter_if_cbr', $if_cbr );

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


	$now = date("ymdH");

	if ( $date < $now - 3 || $if_change === true )
	{
		$db_converter -> currency( $currency_from, $now );
		$date_cbr =  sanitize_text_field ( get_option( 'db_woo_converter_date_cbr' ) );
		$rate_cbr = (float) get_option( 'db_woo_converter_rate_cbr' );
	}

?>
<div class='wrap db-woo-converter-admin'>

	<h1><?php _e("DB Woocommerce Price Converter", $d ) ?></h1>

	<div class="db-woo-converter-description">
		<p><?php _e("The plugin is used for converting the prices from one currency to another", $d ) ?></p>
	</div>

	<h2><?php _e( "Settings", $d ) ?></h2>

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
						<?php _e( "The currency of the prices in WooCommerce", $d ) ?>
					</div>
				</th>
				<td>
					<select type="text" name="currency_from" id="db_woo_converter_currency_from">
						<?php
							foreach ($currencies as $value => $currency)
							{
						?>
						<option value="<?php echo $value; ?>" <?php selected( $currency_from, $value ); ?>>
							<?php echo $currency[0] ?> - <?php echo $currency[1] ?></option>
						<?php
							}
						?>
					</select>
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
						<?php _e( "The currency of the prices shown on the website", $d ) ?>
					</div>
				</th>
				<td>
					<select type="text" name="currency_to" id="db_woo_converter_currency_to">
						<option value="RUR" <?php selected( $currency_to, 'RUR' ); ?>>₽ - <?php _e( 'Russian Ruble' , $d ) ?></option>
					</select>
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
					<p>
						<input type="text" name="rate" id="db_woo_converter_rate"
							size="15" value="<?php echo $rate; ?>" />
					</p>
					<p>
						<input type="checkbox" name="if_cbr" id="db_woo_converter_if_cbr"
							<?php if ( $if_cbr === 'on') { ?>checked<?php } ?> />
						<label for="db_woo_converter_if_cbr"><?php _e( "Enable", $d ) ?></label>
					</p>
				</td>
				<td rowspan="2">
					<div class="db-woo-converter-rate-website">
						<?php _e( "Exchange Rate On Your Website", $d ) ?>: <span><?php
							echo ( $if_cbr === 'on' ? $rate + $margin : $rate_cbr + $margin );
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
							size="15" value="<?php echo $margin; ?>" />
				</td>
			</tr>
		</table>
<?php 
		$db_converter -> currency( $currency_from, $now );
		?>
		<input type="hidden" name="action" value="update" />

		<input type="hidden" name="page_options" value="db_woo_converter_cols" />

		<?php submit_button(); ?>

	</form>

</div>