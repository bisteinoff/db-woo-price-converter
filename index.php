<?php
/*
Plugin Name: DB Woocommerce Price Converter
Plugin URI: https://github.com/bisteinoff/db-woo-price-converter
Description: The plugin is used for converting the prices from one currency to another
Version: 1.3
Author: Denis Bisteinov
Author URI: https://bisteinoff.com
License: GPL2
*/

/*  Copyright 2024  Denis BISTEINOV  (email : bisteinoff@gmail.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class dbWooConverter
	{

		function thisdir()
		{
			return basename( __DIR__ );
		}

		function dbWooConverter()
		{

			add_option( 'db_woo_converter_currency_from', 'USD' );
			add_option( 'db_woo_converter_currency_to', 'RUR' );
			add_option( 'db_woo_converter_date' ); // the date when the exchange rates were uploaded from CBR
			add_option( 'db_woo_converter_date_cbr' ); // the date of update by CBR
			add_option( 'db_woo_converter_rate_cbr' ); // the exchange rate from CBR
			add_option( 'db_woo_converter_rate', '1' ); // the exchange rate established manually
			add_option( 'db_woo_converter_if_cbr' ); // if ON than the exchange rate established manually will be used, else use the exchange rate of CBR for calculations
			add_option( 'db_woo_converter_margin', '0' ); // the amount will be added to the exchange rate
			add_option( 'db_woo_converter_status', '1' ); // 0 - the data from CBR is not received, 1 - the data from CBR received
			add_option( 'db_woo_converter_round', '0' ); // price rounding

			add_filter( 'plugin_action_links_' . $this->thisdir() . '/index.php', array(&$this, 'db_settings_link') );
			add_action( 'admin_menu', array( &$this, 'admin' ) );

			add_action( 'admin_footer', function() {
							wp_enqueue_style( $this->thisdir() . '-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css' );
						},
						99
			);

			$date =  sanitize_text_field ( get_option( 'db_woo_converter_date' ) );
			$now = date("ymdH");

			if ( $date < $now - 3 )
			{
				$currency_from = sanitize_text_field ( get_option( 'db_woo_converter_currency_from' ) );
				$this -> currency( $currency_from, $now );
			}

			add_filter( 'woocommerce_product_get_price', array( &$this, 'convert_price' ), 10, 2 );
			add_filter( 'woocommerce_get_regular_price', array( &$this, 'convert_price' ), 10, 2 );

			add_action( 'wpseo_register_extra_replacements', array( &$this, 'db_register_yoast_vars' ) );
		}

		function admin() {

			if ( function_exists( 'add_menu_page' ) )
			{

				$svg = new DOMDocument();
				$svg -> load( plugin_dir_path( __FILE__ ) . 'img/icon.svg' );
				$icon = $svg -> saveHTML( $svg -> getElementsByTagName('svg')[0] );

				add_menu_page(
					esc_html__('DB Woocommerce Price Converter' , $this->thisdir() ),
					esc_html__('Price Converter' , $this->thisdir() ),
					'manage_options',
					$this->thisdir(),
					array( &$this, 'admin_page_callback' ),
					'data:image/svg+xml;base64,' . base64_encode( $icon ),
					27
					);

			}

		}

		function admin_page_callback()
		{

			require_once('inc/admin.php');

		}

		function db_settings_link( $links )
		{

			$url = esc_url ( add_query_arg (
				'page',
				$this->thisdir(),
				get_admin_url() . 'index.php'
			) );

			$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

			array_push(
				$links,
				$settings_link
			);

			return $links;

		}

		// Getting the exchange rates from CBR. Source: https://www.cbr-xml-daily.ru/
		function CBR_XML_Daily_Ru()
		{
			static $rates;
			
			if ($rates === null) {
				$rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'));
			}
			
			return $rates;
		}

		function currency( $currency, $time )
		{
			$data = $this -> CBR_XML_Daily_Ru();
			$count = count( (array)$data );
			$status_old = (int) get_option( 'db_woo_converter_status' );

			if ( !empty($data) && $count > 0 )
			{
				$date_cbr = sanitize_text_field ( $data->Date );
				$rate_cbr = (float) $data->Valute->$currency->Value;

				if ( !empty( $date_cbr ) && $rate_cbr > 0 )
				{
					if ( $rate_cbr >= 0.005 ) $rate_cbr = round ( $rate_cbr , 2 );

					update_option ( 'db_woo_converter_date_cbr', $date_cbr );
					update_option ( 'db_woo_converter_rate_cbr', $rate_cbr );
					update_option ( 'db_woo_converter_date', $time );
					update_option ( 'db_woo_converter_status', '1' );

					$status_new = 1;
				}
				else
					$status_new = 0;
			}
			else
				$status_new = 0;

			$this -> status( $status_old, $status_new );
		}

		function custom_price_decimals( $decimals )
		{
			global $product;
		
			if( is_a( $product, 'WC_Product' ) ){
				$decimals = 2;
			}
			return $decimals;
		}

		function convert_price( $price, $product )
		{	
			$if_cbr = get_option( 'db_woo_converter_if_cbr' );
			$rate = ( $if_cbr === 'on' ? get_option( 'db_woo_converter_rate' ) : get_option( 'db_woo_converter_rate_cbr' ) );
			$margin = get_option( 'db_woo_converter_margin' );

			$price = $price * ( $rate + $margin );

			$round = (int) get_option( 'db_woo_converter_round' );
			switch ($round)
			{
				case 0  :
					$price = round( $price , 2 );
					break;
				case 1  :
					$price = round( $price , 1 );
					break;
				case 2  :
					$price = round ( $price , 0 );
					break;
				case 3  :
					$price = round( $price , -1 );
					break;
				case 4  :
					$price = round( $price , -2 );
					break;
				case 5  :
					$price = round( $price , -3 );
					break;
				case 6  :
					$price = round( $price , 0 ) - 0.01;
					add_filter( 'wc_get_price_decimals', array( &$this, 'custom_price_decimals' ), 10, 1 );
					break;
				case 7  :
					$price = round( $price , 1 ) - 0.01;
					add_filter( 'wc_get_price_decimals', array( &$this, 'custom_price_decimals' ), 10, 1 );
					break;
				case 8  :
					$price = round( $price , 0 ) - 0.1;
					add_filter( 'wc_get_price_decimals', array( &$this, 'custom_price_decimals' ), 10, 1 );
					break;
				case 9  :
					$price = round( $price , -1 ) - 1;
					break;
				case 10 :
					$price = round( $price , -2 ) - 1;
					break;
				case 11 :
					$price = round( $price , -3 ) - 1;
					break;
			}

			return $price;
		}

		function status( $old, $new )
		{
			$date =  sanitize_text_field ( get_option( 'db_woo_converter_date' ) );
			$now = date("ymdH");
			$dif = $now - $date; // the mail will be sent only after at least 24 hours of inaccessibility to the data from CBR

			if ( $old !== $new && $dif > 24 )
			{
				update_option ( 'db_woo_converter_status', '0' );
				$message = ( $new === 1 ? 'fixed' : 'error' );
				$this -> mail( $message );
			}
		}

		function mail( $arg )
		{
			if ( function_exists( 'mail' ) )
			{
				$email = array();
				$d = $this -> thisdir();
				$site_url = get_site_url();
				$site_url = trim( str_replace( array( 'http://', 'https://' ), '', $site_url ) );
				if ( substr( $site_url, 0, 4 ) === 'www.' )
					$site_url = substr( $site_url , 4 );

				switch ( $arg )
				{
					case 'fixed' :

						$email['subject'] = 
							__( "Problem fixed" , $d ) . ": " .
							__("The data from CBR is received", $d ) . " | " .
							$site_url;

						$email['message'] = 
							"<h2>" . __("DB Woocommerce Price Converter", $d ) . "</h2>" .
							"<p><strong>" . __( "The data from the CBR is received" , $d ) . ". " .
							__( "The problem was fixed" , $d ) . ".</strong></p> " .
							"<p>" . __( "Now you can use the exchange rate from CBR for converting the prices again" , $d ) . ".</p>";

						break;

					case 'error' :

						$email['subject'] = 
							__( "Error" , $d ) . ": " .
							__("The data from CBR is inaccessible for more than 24 hours", $d ) . " | " .
							$site_url;

						$email['message'] = 
							"<h2>" . __("DB Woocommerce Price Converter", $d ) . "</h2>" .
							"<p><strong>" . __( "The API of CBR used in the plugin doesn't work correctly any longer" , $d ) . ".</strong></p> " .
							"<p>" . __( "If the problem is fixed by CBR you will get another message from us" , $d ) . ".</p>" .
							"<p>" . __( "The latest exchange rate is fixed in the database" , $d ) . ". " .
							__( "You can still use it or change it to your own custom exchange rate" , $d ) . ".</p>" .
							"<p>" . __( "You can also contact us and ask to fix the problem" , $d ) . 
							": <a href='mailto:bisteinoff@gmail.com'>bisteinoff@gmail.com</a>.</p>";

						break;
				}

				if ( !empty( $email ) )
				{
					// sending a message
					$email['to'] = get_bloginfo('admin_email');
					$email['from'] = 'no-reply@' . $site_url;
					$email['message'] .=
						"<hr />" .
						"<p>" . __( "Denis BISTEINOV" , $d ) . "<br />" . 
						"<a href='https://bisteinoff.com' target='_blank'>bisteinoff.com</a></p>";

					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					$headers[] = 'From: Wordpress <' . $email['from'] . '>';

					mail( $email['to'], $email['subject'], $email['message'], implode( "\r\n", $headers ) );

				}
			}
		}

		function db_register_yoast_vars() {

			/**
			 * @param %%wc_price%% - change the variable for the calculated price in snippet
			 */
			if ( function_exists( 'wpseo_register_var_replacement' ) )
				wpseo_register_var_replacement( '%%wc_price%%', function() {
					global $product;
					$price = $product->get_price();
					return $price;
				}, 'advanced', 'Variable for the calculated price in snippet' );

		}

	}

	$db_converter = new dbWooConverter();