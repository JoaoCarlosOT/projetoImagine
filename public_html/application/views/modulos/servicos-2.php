<?php
$logo = '/arquivos/imagens/logo.png';
$this->load->library('Imgno_imagem', '', 'imagineImagem');
$produtos = $this->model->getProdutos(null, null, array('destaque'=>true,'ativarVenda'=>false));
if(!$produtos) return;
$dir_p = '/arquivos/imagens/produto/';
?>
<div class="bloco-lista-servicos-2">
    <div class="container">
        <div class="bloco-lista-2" id="blocoListaServicos2">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($produtos as $ka => $produto):
                    if($produto->foto){
                        $img_p = $controller->imagineImagem->otimizar($dir_p.$produto->foto, 460, 460, false, true, 80);
                    }else{
                        // logo
                        $img_p = $controller->imagineImagem->otimizar($logo, 460, 460, false, false, 80);
                    }

                    $cat_id = 0;
                    ?>
                    <div class="item cat-<?= $cat_id; ?> produto-<?= $produto->id?> ">
                        <a href="/servicos/<?=$produto->alias?>.html" title="<?=$produto->nome?>">
                            <div class="img">
                                <?php if($img_p):?>
                                    <img src="<?=$img_p?>" title="<?=$produto->nome?>" alt="<?=$produto->nome?>">
                                <?php endif;?>
                            </div>
                            <div class="texto">
                                <div class="tituloPadrao5 titulo"><?=$produto->nome?></div>
                                <div class="headline subtitulo"><?=$produto->descricao?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                        
                </div>
            </div>

            <div class="botao">
                <a href="/servicos.html">Ver mais</a>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owlSer2 = jQuery("#blocoListaServicos2 .owl-carousel");
		owlSer2.owlCarousel({
			autoplay:false,
			autoplayTimeout:5000,
			autoplayHoverPause: true,
			items : 5,
			responsive : {
				0 : { items : 1.4 },
				769: { items : 2 },
				993 : { items : 3 },
			},
			margin:40,
			loop:true,
			dots:false,
			nav:true,
		});
	});			
</script>