<?php
	// Impede o acesso direto a este arquivo
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Imgno_url {
		// Retorna o objeto DOM equivalente ao conteúdo html fornecido
		private static function toDom( $conteudo ){
			$dom = new DOMDocument();
			@$dom->loadHTML( "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /></head><body>".$conteudo."</body></html>" );
			return $dom;
		}
		
		// Retorna o conteúdo obtido de um objeto DOM em forma de texto html
		private static function getText( $dom ){
			return preg_replace( '/.+<[^\/]*body[^>]*>/is', '', preg_replace( '/<\/\s*body.+/is', '', $dom->saveHTML() ) );
		}
		
		// Retorna o link base de verificação de relatividade
		private static function getLinkBase(){
			return preg_replace( '/(^http:\/\/(www\.)?)|(\/$)/', '', base_url() );
		}
		
		// Retorna o link absoluto
		private static function linkAbsoluto( $link, $link_base ){
			if( preg_match( '/^https?:\/\//', $link ) ) return $link;
			else if( preg_match( '/^www\./', $link ) ) return 'http://'. $link;
			else if( preg_match( '/^((#)|(index)|(\/))/', $link ) ) return base_url() . preg_replace( '/^\//', '', $link );
			else if( strpos( $link, $link_base ) !== false ) return ( $link == $link_base )? base_url() : 'http://'. $link;
			else return base_url() . preg_replace( '/^\//', '', $link );
		}
		
		// Retorna o link base de rastreamento
		private static function getLinkBaseRastreamento(){
			global $option;
			return base_url() .'component/'. substr( $option, 4 ) .'/redirecionar/';
		}
		
		// Rastreia um link
		private static function rastrearLink( $link_rastreamento, $link, $idc, $ida, $token ){
			return $link_rastreamento . $idc .'/'. $ida .'/'. $token .'/'. base64_encode( $link );
		}
		
		// Conserta os links relativos de uma dada tag html e, opcionalmente, adiciona rastreamento
		private static function ajustarLinks( $dom, $tag, $attr, $idc = false, $ida = false, $token = false ){
			if( $links = $dom->getElementsByTagName( $tag ) ){
				$link_base = self::getLinkBase();
				$link_rastreamento = self::getLinkBaseRastreamento();
				foreach( $links as $el ){
					$link = self::linkAbsoluto( $el->getAttribute( $attr ), $link_base );
					$el->setAttribute( $attr, ( $idc && $token )? self::rastrearLink( $link_rastreamento, $link, $idc, $ida, $token ) : $link );
				}
			}
		}
		
		// Prepara conteúdo para envio por email
		static function prepararConteudo( $conteudo, $campanha, $assinante ){
			$dom = self::toDom( $conteudo );
			self::ajustarLinks( $dom, 'img', 'src' );
			self::ajustarLinks( $dom, 'a', 'href', $campanha->id, $assinante->id, $assinante->token );
			self::ajustarLinks( $dom, 'area', 'href', $campanha->id, $assinante->id, $assinante->token );
			return self::getText( $dom );
		}
		
		// Retorna os links absolutos de um dado conteudo html
		static function getLinks( $conteudo ) {
			$dom = self::toDom( $conteudo );
			$link_base = self::getLinkBase();
			if( $links = $dom->getElementsByTagName( 'a' ) ){
				foreach( $links as $el ) $hrefs[ self::linkAbsoluto( $el->getAttribute( 'href' ), $link_base ) ] = true;
			}
			if( $links = $dom->getElementsByTagName( 'area' ) ){
				foreach( $links as $el ) $hrefs[ self::linkAbsoluto( $el->getAttribute( 'href' ), $link_base ) ] = true;
			}
			return !empty($hrefs)? array_keys( $hrefs ) : null;
		}
	}
?>