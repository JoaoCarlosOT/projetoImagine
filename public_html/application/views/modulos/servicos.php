<?php
$logo = '/arquivos/imagens/logo.png';
$this->load->library('Imgno_imagem', '', 'imagineImagem');

$produtos = $this->model->getProdutos(null, null, array('destaque'=>true,'ativarVenda'=>false));
if(!$produtos) return;
$dir_p = '/arquivos/imagens/produto/';
?>
<div class="bloco-lista-servicos">
    <div class="container">
        <h1 class="textCenter tituloPadrao">Lista de Serviços</h1>

        <form class="bloco-busca" name="formBusca" method="post" action="/servicos.html">
            <div class="input_e_btn">
                <input type="text" name="busca" class="inputbox bol_inf_txt" placeholder="Pesquisar Serviços">
                <span  class="btn" onclick="enviar_busca();"><em class="icon-search"></em></span>
            </div>
        </form>
        <script>
            function enviar_busca(){
                var f = document.formBusca;

                if(vazio(f.busca.value)){
                    return exibirAviso('Informe o serviço!');
                }

                carregando();

                setTimeout(function(){f.submit();}, 500);

            }
        </script>  

        <div class="bloco-lista" id="blocoListaServicos">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($produtos as $ka => $produto):
                    if($produto->foto){
                        $img_p = $controller->imagineImagem->otimizar($dir_p.$produto->foto, 345, 455, false, true, 80);
                    }else{
                        // logo
                        $img_p = $controller->imagineImagem->otimizar($logo, 345, 455, false, false, 80);
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
                                <div class="headline2 titulo"><?=$produto->nome?></div>
                                <div class="btn-item"><span class="btn3">Ver</span></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                        
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owlSer = jQuery("#blocoListaServicos .owl-carousel");
		owlSer.owlCarousel({
			autoplay:false,
			autoplayTimeout:5000,
			autoplayHoverPause: true,
			items : 5,
			responsive : {
				0 : { items : 1.4 },
				769: { items : 2 },
				993 : { items : 4 },
			},
			margin:20,
			loop:true,
			dots:false,
			nav:true,
		});
	});			
</script>