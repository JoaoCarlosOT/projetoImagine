<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe de geração de links de paginação
class Imgno_paginacao {
	// Gera o HTML com os links de paginação
	public static function links($link, $total, $limite, $paginaAtual, $info_add){ return self::html(self::paginas($link, $total, $limite, $paginaAtual), $info_add); }
	
	// HTML das páginas obtidas
	private static function html($paginas, $info_add) {
		//print_r('<pre>');print_r($paginas);print_r('</pre>');exit;
		if($paginas):
		$html = '<div class="paginacao">';
			foreach($paginas as $k => $pag):
				if(isset($pag->link)) $html .= '<a class="item link'. (empty($paginas[$k+1]) ? ' ultimo' : '') .'" href="'. base_url($pag->link).''.($info_add?$info_add:'').'">'. $pag->texto .'</a>';
				else $html .= '<span class="item texto'. (empty($paginas[$k+1]) ? ' ultimo' : '') .'">'. $pag->texto .'</span>';
			endforeach; 
		$html .= '</div>';
		return $html;
		else : return NULL;
		endif;
	}
	
	// Gera um objeto de paginação, que pode ser usado para gerar links de paginação
	private static function paginas($link, $total, $limite, $paginaAtual) {
		$paginaAtual = 1 + $paginaAtual;

		$link .= '.html';
		if(($total < 2) || (!$limite) || (($numPaginas = ceil($total / $limite)) < 2)) return ;
		switch($numPaginas) {
			case 2:{
				// Caso haja apenas duas páginas
				$paginas = array();
				$paginas[0] = new stdClass();
				$paginas[0]->texto = '<span class="seta">«</span>';
				$paginas[0]->classe = 'primeira';
				$paginas[1] = new stdClass();
				$paginas[1]->texto = '<span class="seta">»</span>';
				$paginas[1]->classe = 'última';
				if($paginaAtual == 1) $paginas[1]->link = $link .'?pag='. $limite;
				else $paginas[0]->link = $link;
				break;
			} default:{
				// Caso haja mais de duas páginas
				$paginas = array();
				$paginas[0] = new stdClass();				
				$paginas[0]->texto = '<span class="seta">«</span>';
				$paginas[0]->classe = 'primeira';
				$paginas[1] = new stdClass();
				$paginas[1]->texto = '<span class="seta">‹</span>';
				$paginas[1]->classe = 'anterior';
				if($paginaAtual > 1) {
					$paginas[0]->link = $link;
					$paginas[1]->link = $link . (($k = $limite * ($paginaAtual - 2))? '?pag='. $k : '');
				}
				if($paginaAtual < 5){
					$start = 2;
					$stop = 8;
				} elseif($numPaginas - $paginaAtual < 3){
					$start = $numPaginas - 6;
					$stop = $numPaginas;
				} else {
					$start = $paginaAtual - 3;
					$stop = $paginaAtual + 3;
				}
				for($i = max(2, $start); ($i <= $stop) && ($i <= $numPaginas); $i++){
					$pagina = new StdClass();
					$pagina->texto = $i;
					if($i != $paginaAtual) $pagina->link = $link .'?pag='. ($limite * ($i - 1));
					$paginas[] = $pagina;
				}
				
				// Próxima página
				$pagina = new StdClass();
				$pagina->texto = '<span class="seta">›</span>';
				$pagina->classe = 'próxima';
				if($paginaAtual != $numPaginas) $pagina->link = $link .'?pag='. ($limite * $paginaAtual);
				$paginas[] = $pagina;
				
				// Última página
				$pagina = new StdClass();
				$pagina->texto = '<span class="seta">»</span>';
				$pagina->classe = 'última';
				if($paginaAtual != $numPaginas) $pagina->link = $link .'?pag='. ($limite * ($numPaginas - 1));
				$paginas[] = $pagina;
			}
		}
		return $paginas;
	}
}
?>