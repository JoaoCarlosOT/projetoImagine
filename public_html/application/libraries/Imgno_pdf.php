<?php
	// Impede o acesso direto a este arquivo
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// Imports
	require_once('tcpdf_config.php');
	require_once('tcpdf/tcpdf.php');
	
	// Gera recibos em formato PDF usando a biblioteca TCPDF
	class Imgno_pdf extends TCPDF {
		// Construtor
		function __construct(){
			parent::__construct( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
			
			// Metadados
			$this->SetCreator( 'Memorial' );
			$this->SetAuthor( 'Memorial' );
			$this->SetTitle( 'Memorial' );

			
			// Configura a fonte do footer
			// $this->setFooterFont( array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
			
			// Margens
			$this->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
			$this->SetFooterMargin( PDF_MARGIN_FOOTER );
			
			// Configura quebra de páginas automáticas
			$this->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );
			
			// Configura fator de redimensionamento de imagens
			$this->setImageScale( PDF_IMAGE_SCALE_RATIO ); 
			
			// Configura a fonte
			$this->SetFont( 'freesans', '', 12 );
			$this->setRTL( false );
			
			// Configura a fonte do footer
			$this->setFooterFont( array( 'freesans', '', 8 ) );
			
			// Cria uma página
			$this->AliasNbPages();
			$this->AddPage();
		}
		
		// Gera o Output do documento em forma de download
		public function Output_d(){
			// Limpa o buffer de saída do PHP
			//ob_clean();
			
			// Se encessário, finaliza o documento
			if( $this->state < 3 ) $this->Close();
			
			if( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'],'MSIE' ) ){
				// Gera o header de PDF compatível com a família IE
				header('Content-Type: application/force-download');
			} else {
				// Gera o header de PDF compatível com os demais browsers
				header('Content-Type: application/octet-stream');
			}
			/*
			// Verifica se já foi enviado conteúdo
			if( headers_sent() )  return 'Conteúdo inválido está sendo enviado para o navegador';
			
			// Gera os demais headers
			header( 'Content-disposition: attachment; filename="'. $name.'"' );
			echo $this->buffer;
			exit;
			*/
			return $this->buffer;
		}
		
		// Constrói o cabeçalho personalizado
		public function head_padrao_1() {
			$this->image(K_PATH_IMAGES .'memorial-logo-pdf.png', 40, 0, 130, 40, '', '', true);
			$this->SetY(60);
		}
		
		// Constrói o rodapé personalizado
		public function footer_padrao_1() {
			// Guarda as configurações atuais
			$font_family =  $this->FontFamily;
			$font_style = $this->FontStyle;
			$font_size = $this->FontSizePt;
			
			// Imagem de rodapé:
			$this->image( K_PATH_IMAGES .'footer.png', 0, 271.5, 210, 25.5 );
			
			// Número da página
			$this->SetTextColor( 123, 123, 123 );
			$this->SetFont( '', '' , 7 );
			$this->SetXY( 8.5, -28.5 );
			$this->Cell( 0, 10, 'Página '. $this->PageNo() .' de {nb}' );
			
			// Restaura as configurações
			$this->SetFont( $font_family, $font_style, $font_size );
		}
		
		
	}
?>