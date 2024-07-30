<?php // THE SETTINGS PAGE

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$db_converter = new DB_WOO_CONVERTER_Init();
	$d = $db_converter->thisdir();

	$currencies = array(
		'USD' => array( '$',   esc_html__( 'US Dollar',          'db-price-converter-woocommerce' ) ),
		'EUR' => array( '€',   esc_html__( 'Euro',               'db-price-converter-woocommerce' ) ),
		'GBP' => array( '£',   esc_html__( 'British Pound',      'db-price-converter-woocommerce' ) ),
		'CNY' => array( '¥',   esc_html__( 'Chinese Yuan',       'db-price-converter-woocommerce' ) ),
		'JPY' => array( '¥',   esc_html__( 'Japanese Yen',       'db-price-converter-woocommerce' ) ),
		'AUD' => array( 'AUD', esc_html__( 'Australian Dollar',  'db-price-converter-woocommerce' ) ),
		'AZN' => array( 'AZN', esc_html__( 'Azerbaijani Manat',  'db-price-converter-woocommerce' ) ),
		'AMD' => array( 'AMD', esc_html__( 'Armenian Dram',      'db-price-converter-woocommerce' ) ),
		'BYN' => array( 'BYN', esc_html__( 'Belarusian Ruble',   'db-price-converter-woocommerce' ) ),
		'BGN' => array( 'BGN', esc_html__( 'Bulgarian Lev',      'db-price-converter-woocommerce' ) ),
		'BRL' => array( 'BRL', esc_html__( 'Brazilian Real',     'db-price-converter-woocommerce' ) ),
		'HUF' => array( 'HUF', esc_html__( 'Hungarian Forint',   'db-price-converter-woocommerce' ) ),
		'VND' => array( 'VND', esc_html__( 'Vietnamese Dong',    'db-price-converter-woocommerce' ) ),
		'HKD' => array( 'HKD', esc_html__( 'Hong Kong Dollar',   'db-price-converter-woocommerce' ) ),
		'GEL' => array( 'GEL', esc_html__( 'Georgian Lari',      'db-price-converter-woocommerce' ) ),
		'DKK' => array( 'DKK', esc_html__( 'Danish Krone',       'db-price-converter-woocommerce' ) ),
		'AED' => array( 'AED', esc_html__( 'UAE Dirham',         'db-price-converter-woocommerce' ) ),
		'EGP' => array( 'EGP', esc_html__( 'Egyptian Pound',     'db-price-converter-woocommerce' ) ),
		'INR' => array( 'INR', esc_html__( 'Indian Rupee',       'db-price-converter-woocommerce' ) ),
		'IDR' => array( 'IDR', esc_html__( 'Indonesian Rupiah',  'db-price-converter-woocommerce' ) ),
		'KZT' => array( 'KZT', esc_html__( 'Kazakhstan Tenge',   'db-price-converter-woocommerce' ) ),
		'CAD' => array( 'CAD', esc_html__( 'Canadian Dollar',    'db-price-converter-woocommerce' ) ),
		'QAR' => array( 'QAR', esc_html__( 'Qatari Rial',        'db-price-converter-woocommerce' ) ),
		'KGS' => array( 'KGS', esc_html__( 'Kyrgyz Som',         'db-price-converter-woocommerce' ) ),
		'MDL' => array( 'MDL', esc_html__( 'Moldovan Lei',       'db-price-converter-woocommerce' ) ),
		'NZD' => array( 'NZD', esc_html__( 'New Zealand Dollar', 'db-price-converter-woocommerce' ) ),
		'NOK' => array( 'NOK', esc_html__( 'Norwegian Kroner',   'db-price-converter-woocommerce' ) ),
		'PLN' => array( 'PLN', esc_html__( 'Polish Zloty',       'db-price-converter-woocommerce' ) ),
		'RON' => array( 'RON', esc_html__( 'Romanian Leu',       'db-price-converter-woocommerce' ) ),
		'XDR' => array( 'XDR', esc_html__( 'XDR',                'db-price-converter-woocommerce' ) ),
		'SGD' => array( 'SGD', esc_html__( 'Singapore Dollar',   'db-price-converter-woocommerce' ) ),
		'TJS' => array( 'TJS', esc_html__( 'Tajik Somoni',       'db-price-converter-woocommerce' ) ),
		'THB' => array( 'THB', esc_html__( 'Thai Baht',          'db-price-converter-woocommerce' ) ),
		'TRY' => array( 'TRY', esc_html__( 'Turkish Lira',       'db-price-converter-woocommerce' ) ),
		'TMT' => array( 'TMT', esc_html__( 'New Turkmen Manat',  'db-price-converter-woocommerce' ) ),
		'UZS' => array( 'UZS', esc_html__( 'Uzbek Soum',         'db-price-converter-woocommerce' ) ),
		'UAH' => array( 'UAH', esc_html__( 'Ukrainian Hryvnia',  'db-price-converter-woocommerce' ) ),
		'CZK' => array( 'CZK', esc_html__( 'Czech Crown',        'db-price-converter-woocommerce' ) ),
		'SEK' => array( 'SEK', esc_html__( 'Swedish Kronor',     'db-price-converter-woocommerce' ) ),
		'CHF' => array( 'CHF', esc_html__( 'Swiss Frank',        'db-price-converter-woocommerce' ) ),
		'RSD' => array( 'RSD', esc_html__( 'Serbian Dinar',      'db-price-converter-woocommerce' ) ),
		'ZAR' => array( 'ZAR', esc_html__( 'South African Rand', 'db-price-converter-woocommerce' ) ),
		'KRW' => array( 'KRW', esc_html__( 'South Korean Won',   'db-price-converter-woocommerce' ) )
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
	if ( isset ( $_POST[ 'submit' ] ) && 
	isset( $_POST[ $d . '_nonce' ] ) &&
	wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $d . '_nonce' ] ) ), sanitize_text_field( $d ) ) )
	{

		if ( function_exists( 'current_user_can' ) &&
			 !current_user_can( 'manage_options' ) )
				die( esc_html_e( 'Error: You do not have the permission to update the value', 'db-price-converter-woocommerce' ) );

		if ( $_POST['currency_from'] !== $currency_from || $_POST['currency_to'] !== $currency_to ) $if_change = true;

		// Currency from
		if ( !empty ( $_POST[ 'currency_from' ] ) )
		{
			$currency_from = esc_html( sanitize_text_field( $_POST[ 'currency_from' ] ) );
			update_option( 'db_woo_converter_currency_from', $currency_from );
		}
		else
			update_option( 'db_woo_converter_currency_from', 'USD' );

		// Currency to
		if ( !empty ( $_POST[ 'currency_to' ] ) )
		{
			$currency_to = esc_html( sanitize_text_field( $_POST[ 'currency_to' ] ) );
			update_option( 'db_woo_converter_currency_to', $currency_to );
		}
		else
			update_option( 'db_woo_converter_currency_to', 'RUR' );

		// Enable Exchange Rate of CBR
		$if_cbr = esc_html( sanitize_text_field( $_POST[ 'if_cbr' ] ) );
		update_option( 'db_woo_converter_if_cbr', $if_cbr );

		// Custom Exchange Rate
		if ( !empty ( $_POST[ 'rate' ] ) )
		{
			$rate = (float) esc_html( sanitize_text_field( $_POST[ 'rate' ] ) );
			update_option( 'db_woo_converter_rate', round ( $rate, 2 ) );
		}
		else
			update_option( 'db_woo_converter_rate', '1' );

		// Margin
		if ( !empty ( $_POST[ 'margin' ] ) )
		{
			$margin = (float) esc_html( sanitize_text_field( $_POST[ 'margin' ] ) );
			update_option( 'db_woo_converter_margin', round ( $margin, 2 ) );
		}
		else
			update_option( 'db_woo_converter_margin', '0' );

		// Rounding
		if ( !empty ( $_POST[ 'round' ] ) )
		{
			$round = (int) esc_html( sanitize_text_field( $_POST[ 'round' ] ) );
			update_option( 'db_woo_converter_round', $round );
		}
		else
			update_option( 'db_woo_converter_round', '0' );

	}


	$date_timezone = substr( $date_cbr, -6, 3 );
	$date_span     = (int) $date_timezone;
	$now           = gmdate( "ymdH" );

	if ( $date - $date_span < $now - 3 || $if_change === true )
	{
		$db_converter->currency( $currency_from, $now );
		$date_cbr = sanitize_text_field( get_option( 'db_woo_converter_date_cbr' ) );
		$rate_cbr = (float) get_option( 'db_woo_converter_rate_cbr' );
	}

?>
<div class='wrap db-woo-converter-admin'>

	<h1><?php esc_html_e( 'DB Woocommerce Price Converter', 'db-price-converter-woocommerce' ) ?></h1>

	<div class="db-woo-converter-description">
		<p><?php esc_html_e( 'The plugin is used for converting the prices from one currency to another', 'db-price-converter-woocommerce' ) ?></p>
	</div>

	<h2><?php esc_html_e( 'Settings', 'db-price-converter-woocommerce' ) ?></h2>

	<form name="db-woo-converter" method="post" action="<?php echo esc_html( sanitize_text_field( $_SERVER['PHP_SELF'] ) ) ?>?page=<?php echo esc_html( sanitize_text_field( $d ) ) ?>&amp;updated=true">

		<table class="form-table db-woo-converter-table" width="100%">
			<tr valign="top">
				<th scope="col" width="25%">
					<?php esc_html_e( 'Parameter', 'db-price-converter-woocommerce' ) ?>
				</th>
				<th scope="col" width="25%">
					<?php esc_html_e( 'Value', 'db-price-converter-woocommerce' ) ?>
				</th>
				<th scope="col" width="50%">
					<?php esc_html_e( 'Current Exchange Rate', 'db-price-converter-woocommerce' ) ?>
				</th>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Convert from', 'db-price-converter-woocommerce' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'The currency of the prices in WooCommerce', 'db-price-converter-woocommerce' ) ?>
					</div>
				</th>
				<td>
					<select type="text" name="currency_from" id="db_woo_converter_currency_from">
						<?php
							foreach ($currencies as $value => $currency)
							{
						?>
						<option value="<?php echo esc_html( sanitize_text_field( $value ) ) ?>" <?php selected( $currency_from, $value ); ?>>
							<?php echo esc_html( sanitize_text_field( $currency[ 0 ] ) ) ?> - <?php echo esc_html( sanitize_text_field( $currency[ 1 ] ) ) ?></option>
						<?php
							}
						?>
					</select>
				</td>
				<td rowspan="2">
					<div class="db-woo-converter-rate-cbr">
						<?php esc_html_e( 'Exchange Rate of CBR', 'db-price-converter-woocommerce' ) ?>: <span><?php echo esc_html( sanitize_text_field( $rate_cbr ) ) ?></span>
					</div>
					<div class="db-woo-converter-date-cbr">
						<?php esc_html_e( 'Date', 'db-price-converter-woocommerce' ) ?>: <span><?php echo esc_html( sanitize_text_field( $date_cbr ) ) ?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Convert to', 'db-price-converter-woocommerce' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'The currency of the prices shown on the website', 'db-price-converter-woocommerce' ) ?>
					</div>
				</th>
				<td>
					<select type="text" name="currency_to" id="db_woo_converter_currency_to">
						<option value="RUR" <?php selected( $currency_to, 'RUR' ); ?>>₽ - <?php esc_html_e( 'Russian Ruble' , 'db-price-converter-woocommerce' ) ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Custom Exchange Rate', 'db-price-converter-woocommerce' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'You can set your custom exchange rate', 'db-price-converter-woocommerce' ) ?>
					</div>
				</th>
				<td>
					<p>
						<input type="text" name="rate" id="db_woo_converter_rate"
							size="15" value="<?php echo esc_html( sanitize_text_field( $rate ) ) ?>" />
					</p>
					<p>
						<input type="checkbox" name="if_cbr" id="db_woo_converter_if_cbr"
							<?php if ( $if_cbr === 'on') { ?>checked<?php } ?> />
						<label for="db_woo_converter_if_cbr"><?php esc_html_e( 'Enable', 'db-price-converter-woocommerce' ) ?></label>
					</p>
				</td>
				<td rowspan="3">
					<div class="db-woo-converter-rate-website">
						<?php esc_html_e( 'Exchange Rate On Your Website', 'db-price-converter-woocommerce' ) ?>: <span><?php
							$rate_final = ( $if_cbr === 'on' ? $rate + $margin : $rate_cbr + $margin );
							echo esc_html( sanitize_text_field( $rate_final ) );
						?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Margin', 'db-price-converter-woocommerce' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'You can set a margin. It will be added to the amount of your exchange rate', 'db-price-converter-woocommerce' ) ?>
					</div>
				</th>
				<td>
					<input type="text" name="margin" id="db_woo_converter_margin"
							size="15" value="<?php echo esc_html( sanitize_text_field( $margin ) ) ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Rounding', 'db-price-converter-woocommerce' ) ?>
					<div class="db-woo-converter-field-description">
						<?php esc_html_e( 'You can set the way the prices will be rounded', 'db-price-converter-woocommerce' ) ?>
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
						<?php esc_html_e( 'Choose the example, how 1&nbsp;234.56 should be rounded', 'db-price-converter-woocommerce' ) ?>
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