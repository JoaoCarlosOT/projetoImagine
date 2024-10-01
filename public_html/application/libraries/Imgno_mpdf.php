<?php
	// Impede o acesso direto a este arquivo
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// Imports
	require_once('dompdf/Dompdf.php');
	
	// Gera recibos em formato PDF usando a biblioteca Mpdf
	class Imgno_mpdf extends Dompdf {

    }