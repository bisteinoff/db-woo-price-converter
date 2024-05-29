<?php // THE SETTINGS PAGE

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$db_converter = new DB_WOO_CONVERTER_Init();
	$d = $db_converter->thisdir();

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

	$currency_from	=			esc_html( sanitize_text_field( get_option( 'db_woo_converter_currency_from' ) ) );
	$currency_to	=			esc_html( sanitize_text_field( get_option( 'db_woo_converter_currency_to'	) ) );
	$date			=			esc_html( sanitize_text_field( get_option( 'db_woo_converter_date'			) ) );
	$date_cbr		=			esc_html( sanitize_text_field( get_option( 'db_woo_converter_date_cbr'		) ) );
	$rate_cbr		= (float)	esc_html( sanitize_text_field( get_option( 'db_woo_converter_rate_cbr'		) ) );
	$rate			= (float)	esc_html( sanitize_text_field( get_option( 'db_woo_converter_rate'			) ) );
	$if_cbr			=			esc_html( sanitize_text_field( get_option( 'db_woo_converter_if_cbr'		) ) );
	$margin			= (float)	esc_html( sanitize_text_field( get_option( 'db_woo_converter_margin'		) ) );
	$round			= (int)		esc_html( sanitize_text_field( get_option( 'db_woo_converter_round'			) ) );
	$if_change		= false; // if the currency has changed it is true


	// form submit
	if ( isset ( $_POST['submit'] ) && 
	isset( $_POST[ $d . '_nonce' ] ) &&
	wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $d . '_nonce' ] ) ), sanitize_text_field( $d ) ) )
	{

		if ( function_exists( 'current_user_can' ) &&
			 !current_user_can( 'manage_options' ) )
				die( esc_html_e( 'Error: You do not have the permission to update the value', 'db-woo-price-converter' ) );

		if ( $_POST['currency_from'] !== $currency_from || $_POST['currency_to'] !== $currency_to ) $if_change = true;

		// Currency from
		if ( !empty ( $_POST['currency_from'] ) )
		{
			$currency_from = sanitize_text_field( $_POST['currency_from'] );
			update_option ( 'db_woo_converter_currency_from', $currency_from );
		}
		else
			update_option ( 'db_woo_converter_currency_from', 'USD' );

		// Currency to
		if ( !empty ( $_POST['currency_to'] ) )
		{
			$currency_to = sanitize_text_field( $_POST['currency_to'] );
			update_option ( 'db_woo_converter_currency_to', $currency_to );
		}
		else
			update_option ( 'db_woo_converter_currency_to', 'RUR' );

		// Enable Exchange Rate of CBR
		$if_cbr = sanitize_text_field( $_POST['if_cbr'] );
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

		// Rounding
		if ( !empty ( $_POST['round'] ) )
		{
			$round = (int) $_POST['round'];
			update_option ( 'db_woo_converter_round', $round );
		}
		else
			update_option ( 'db_woo_converter_round', '0' );

	}


	$now = date( "ymdH" );

	if ( $date < $now - 3 || $if_change === true )
	{
		$db_converter->currency( $currency_from, $now );
		$date_cbr = sanitize_text_field( get_option( 'db_woo_converter_date_cbr' ) );
		$rate_cbr = (float) get_option( 'db_woo_converter_rate_cbr' );
	}

?>
<div class='wrap db-woo-converter-admin'>

	<h1><?php esc_html_e( 'DB Woocommerce Price Converter', 'db-woo-price-converter' ) ?></h1>

	<div class="db-woo-converter-description">
		<p><?php esc_html_e( 'The plugin is used for converting the prices from one currency to another', 'db-woo-price-converter' ) ?></p>
	</div>

	<h2><?php esc_html_e( 'Settings', 'db-woo-price-converter' ) ?></h2>

	<form name="db-woo-converter" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?page=<?php echo $d; ?>&amp;updated=true">

		<table class="form-table db-woo-converter-table" width="100%">
			<tr valign="top">
				<th scope="col" width="25%">
					<?php esc_html_e( 'Parameter', 'db-woo-price-converter' ) ?>
				</th>
				<th scope="col" width="25%">
					<?php esc_html_e( 'Value', 'db-woo-price-converter' ) ?>
				</th>
				<th scope="col" width="50%">
					<?php esc_html_e( 'Current Exchange Rate', 'db-woo-price-converter' ) ?>
				</th>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Convert from', 'db-woo-price-converter' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'The currency of the prices in WooCommerce', 'db-woo-price-converter' ) ?>
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
						<?php esc_html_e( 'Exchange Rate of CBR', 'db-woo-price-converter' ) ?>: <span><?php echo $rate_cbr; ?></span>
					</div>
					<div class="db-woo-converter-date-cbr">
						<?php esc_html_e( 'Date', 'db-woo-price-converter' ) ?>: <span><?php echo $date_cbr; ?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Convert to', 'db-woo-price-converter' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'The currency of the prices shown on the website', 'db-woo-price-converter' ) ?>
					</div>
				</th>
				<td>
					<select type="text" name="currency_to" id="db_woo_converter_currency_to">
						<option value="RUR" <?php selected( $currency_to, 'RUR' ); ?>>₽ - <?php esc_html_e( 'Russian Ruble' , 'db-woo-price-converter' ) ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Custom Exchange Rate', 'db-woo-price-converter' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'You can set your custom exchange rate', 'db-woo-price-converter' ) ?>
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
						<label for="db_woo_converter_if_cbr"><?php esc_html_e( 'Enable', 'db-woo-price-converter' ) ?></label>
					</p>
				</td>
				<td rowspan="3">
					<div class="db-woo-converter-rate-website">
						<?php esc_html_e( 'Exchange Rate On Your Website', 'db-woo-price-converter' ) ?>: <span><?php
							echo ( $if_cbr === 'on' ? $rate + $margin : $rate_cbr + $margin );
						?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Margin', 'db-woo-price-converter' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'You can set a margin. It will be added to the amount of your exchange rate', 'db-woo-price-converter' ) ?>
					</div>
				</th>
				<td>
					<input type="text" name="margin" id="db_woo_converter_margin"
							size="15" value="<?php echo $margin; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Rounding', 'db-woo-price-converter' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'You can set the way the prices will be rounded', 'db-woo-price-converter' ) ?>
					</div>
				</th>
				<td>
					<select type="text" name="round" id="db_woo_converter_round">
						<option value="0"  <?php selected( $round, '0'  ); ?>>1&nbsp;234.56</option>
						<option value="1"  <?php selected( $round, '1'  ); ?>>1&nbsp;234.60</option>
						<option value="2"  <?php selected( $round, '2'  ); ?>>1&nbsp;235</option>
						<option value="3"  <?php selected( $round, '3'  ); ?>>1&nbsp;230</option>
						<option value="4"  <?php selected( $round, '4'  ); ?>>1&nbsp;200</option>
						<option value="5"  <?php selected( $round, '5'  ); ?>>1&nbsp;000</option>
						<option value="6"  <?php selected( $round, '6'  ); ?>>1&nbsp;234.99</option>
						<option value="7"  <?php selected( $round, '7'  ); ?>>1&nbsp;234.59</option>
						<option value="8"  <?php selected( $round, '8'  ); ?>>1&nbsp;234.90</option>
						<option value="9"  <?php selected( $round, '9'  ); ?>>1&nbsp;229</option>
						<option value="10" <?php selected( $round, '10' ); ?>>1&nbsp;199</option>
						<option value="11" <?php selected( $round, '11' ); ?>>999</option>
					</select>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'Choose the example, how 1&nbsp;234.56 should be rounded', 'db-woo-price-converter' ) ?>
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />

		<?php $nonce = wp_create_nonce( $d ); ?>

		<input type="hidden" name="<?php echo esc_html( sanitize_text_field( $d ) ) ?>_nonce" value="<?php echo esc_html( sanitize_text_field( $nonce ) ) ?>" />

		<?php submit_button(); ?>

	</form>

</div>