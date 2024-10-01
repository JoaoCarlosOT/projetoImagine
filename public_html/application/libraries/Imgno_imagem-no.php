<?php
	/*
	* Versão 2.0
	* Atualizado em 01/03/2010 às 18:00
	*/
	
	// Impede o acesso direto a este arquivo
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// Classe de imagens
	class Imgno_imagem {
	
		// Gera uma imagem em vazia ou transparente
		static function blank(){
			$blankImage = imagecreatetruecolor(1, 1);
			$color = imagecolorallocatealpha($blankImage, 72, 12, 31, 127);
			imagefill($blankImage, 0, 0, $color);
			imagecolortransparent($blankImage, $color);
			header('Content-type: image/png');
			imagepng($blankImage);
			imagedestroy($blankImage);
			exit;
		}
	}
?>