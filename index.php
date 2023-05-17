<?php
/*
Plugin Name: DB Woocommerce Price Converter
Plugin URI: https://github.com/bisteinoff/db-woo-price-converter
Description: The plugin is used for converting the prices from one currency to another
Version: 1.0
Author: Denis Bisteinov
Author URI: https://bisteinoff.com
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : bisteinoff@gmail.com)
 
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

	class dbWooConverter
	{

		function thisdir()
		{
			return basename( __DIR__ );
		}

		function dbWooConverter()
		{

			add_option( 'db_woo_converter_currancy_from', 'USD' );
			add_option( 'db_woo_converter_currancy_to', 'RUR' );
			add_option( 'db_woo_converter_date' ); // the date when the exchange rates were uploaded from CBR
			add_option( 'db_woo_converter_date_cbr' ); // the date of update by CBR
			add_option( 'db_woo_converter_rate_cbr' ); // the exchange rate from CBR
			add_option( 'db_woo_converter_rate', '1' ); // the exchange rate established manually
			add_option( 'db_woo_converter_if_cbr', '1' ); // if true than use the exchange rate of CBR for calculations, else the exchange rate established manually will be used
			add_option( 'db_woo_converter_margin', '0' );

			add_filter( 'plugin_action_links_' . $this->thisdir() . '/index.php', array(&$this, 'db_settings_link') );
			add_action( 'admin_menu', array (&$this, 'admin') );

			add_action( 'admin_footer', function() {
							wp_enqueue_style( $this->thisdir() . '-admin', plugin_dir_url( __FILE__ ) . 'css/admin.css' );
						},
						99
			);

		}

		function admin() {

			if ( function_exists('add_menu_page') )
			{

				$svg = new DOMDocument();
				$svg -> load( plugin_dir_path( __FILE__ ) . 'img/icon.svg' );
				$icon = $svg -> saveHTML( $svg -> getElementsByTagName('svg')[0] );

				add_menu_page(
					__('DB Woocommerce Price Converter' , $this->thisdir() ),
					__('Price Converter' , $this->thisdir() ),
					'manage_options',
					$this->thisdir(),
					array (&$this, 'admin_page_callback'),
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

	}

	$db_converter = new dbWooConverter();