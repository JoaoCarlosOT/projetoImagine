<?php
	// Impede o acesso direto a este arquivo
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// Arquivo de configuração do TCPDF
	
	/*
	 * Setup external configuration options
	 */
	define('K_TCPDF_EXTERNAL_CONFIG', true);

	/*
	 * Path options
	 */
	 
	// DOCUMENT_ROOT fix for IIS Webserver
	if ((!isset($_SERVER['DOCUMENT_ROOT'])) OR (empty($_SERVER['DOCUMENT_ROOT']))) {
		if(isset($_SERVER['SCRIPT_FILENAME'])) {
			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['PHP_SELF'])));
		} elseif(isset($_SERVER['PATH_TRANSLATED'])) {
			$_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0-strlen($_SERVER['PHP_SELF'])));
		} else {
			// define here your DOCUMENT_ROOT path if the previous fails (e.g. '/var/www')
			$_SERVER['DOCUMENT_ROOT'] = '/';
		}
	}

	// be sure that the end slash is present
	$_SERVER['DOCUMENT_ROOT'] = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/');

	// Automatic calculation for the following K_PATH_MAIN constant
	$k_path_main = str_replace( '\\', '/', realpath(substr(dirname(__FILE__), 0, 0-strlen('config'))));
	if (substr($k_path_main, -1) != '/') {
		$k_path_main .= '/';
	}

	/**
	 * Installation path (/var/www/tcpdf/).
	 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
	 */
	define ('K_PATH_MAIN', $k_path_main);

	// Automatic calculation for the following K_PATH_URL constant
	$k_path_url = $k_path_main; // default value for console mode
	if (isset($_SERVER['HTTP_HOST']) AND (!empty($_SERVER['HTTP_HOST']))) {
		if(isset($_SERVER['HTTPS']) AND (!empty($_SERVER['HTTPS'])) AND strtolower($_SERVER['HTTPS'])!='off') {
			$k_path_url = 'https://';
		} else {
			$k_path_url = 'http://';
		}
		$k_path_url .= $_SERVER['HTTP_HOST'];
		$k_path_url .= str_replace( '\\', '/', substr(K_PATH_MAIN, (strlen($_SERVER['DOCUMENT_ROOT']) - 1)));
	}

	/**
	 * URL path to tcpdf installation folder (http://localhost/tcpdf/).
	 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
	 */
	define ('K_PATH_URL', $k_path_url);	 
	
	/*

	// Installation path
	define( "K_PATH_MAIN", JPATH_LIBRARIES . DS .'tcpdf' );

	// URL path
	define( "K_PATH_URL", JPATH_BASE );
	
	*/
	
	error_reporting(E_ALL ^ (E_DEPRECATED));

	// Fonts path
	define( "K_PATH_FONTS", APPPATH .'libraries/tcpdf/pdf_fonts/' );

	// Cache directory path
	define( "K_PATH_CACHE", APPPATH .'libraries/tcpdf/cache/' );

	// Cache URL path
	define( "K_PATH_URL_CACHE", K_PATH_URL .'cache/' );

	// Images path
	define( "K_PATH_IMAGES", APPPATH .'libraries/tcpdf/images/' );

	// Blank image path
	define( "K_BLANK_IMAGE", K_PATH_IMAGES .'_blank.png' );

	/*
	 * Format options
	 */
	
	// Cell height ratio
	define("K_CELL_HEIGHT_RATIO", 1.25);

	// Magnification scale for titles
	define("K_TITLE_MAGNIFICATION", 1.3);

	// Reduction scale for small font
	define("K_SMALL_RATIO", 2/3);

	// Magnication scale for head
	define("HEAD_MAGNIFICATION", 1.1);
	
	/**
	 * page format
	 */
	define ("PDF_PAGE_FORMAT", "A4");
	
	/**
	 * page orientation (P=portrait, L=landscape)
	 */
	define ("PDF_PAGE_ORIENTATION", "P");
	
	/**
	 * header logo image width [mm]
	 */
	define ("PDF_HEADER_LOGO_WIDTH", 30);
	
	/**
	 *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
	 */
	define ("PDF_UNIT", "mm");
	
	/**
	 *  scale factor for images (number of points in user unit)
	 */
	define ("PDF_IMAGE_SCALE_RATIO", 4);
	
	/**
	 * header margin
	 */
	define ("PDF_MARGIN_HEADER", 5);
	
	/**
	 * footer margin
	 */
	define ("PDF_MARGIN_FOOTER", 10);
	
	/**
	 * top margin
	 */
	define ("PDF_MARGIN_TOP", 26);
	
	/**
	 * bottom margin
	 */
	define( "PDF_MARGIN_BOTTOM", 35 );
	
	/**
	 * left margin
	 */
	define ("PDF_MARGIN_LEFT", 10);
	
	/**
	 * right margin
	 */
	define ("PDF_MARGIN_RIGHT", 10);
	
	/**
	 * data font size
	 */
	define ("PDF_FONT_SIZE_DATA", 8);
	
	/**
	 * main font size
	 */
	define ("PDF_FONT_SIZE_MAIN", 10);
	
	/**
	 * main font name
	 */
	define ("PDF_FONT_NAME_MAIN", "freesans"); //freesans
	
	/**
	 * data font name
	 */
	define ("PDF_FONT_NAME_DATA", "freesans"); //freesans
?>
