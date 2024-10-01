<?php
$logo = '/arquivos/imagens/logo.png';

$controller->load->model('modulos/modulos_model_blog', 'model_blog');
$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$artigos = $controller->model_blog->getUltimosArtigos(6);
if(!$artigos) return;
$dir_artigo = '/arquivos/imagens/blog/';
?>
<div class="bloco-lista-blog-2">
    <div class="container">
        <h3 class="tituloPadrao textCenter">Acompanhe o nosso blog</h3>
        <div class="bloco-lista-2" id="blocoListaBlog2">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($artigos as $ka => $artigo):
                    if($artigo->imagem){
                        $img_p = $controller->imagineImagem->otimizar($dir_artigo.$artigo->imagem, 460, 260, false, true, 80);
                    }else{
                        // logo
                        $img_p = $controller->imagineImagem->otimizar($logo, 460, 260, false, false, 80);
                    }

                    ?>
                    <div class="item artigo-<?= $artigo->id?> ">
                        <a href="/blog/<?=$artigo->alias?>.html" title="<?=$artigo->titulo?>">
                            <div class="img">
                                <?php if($img_p):?>
                                    <img src="<?=$img_p?>" title="<?=$artigo->titulo?>" alt="<?=$artigo->titulo?>">
                                <?php endif;?>
                            </div>
                            <div class="texto">
                                <div class="tituloPadrao4 titulo"><?=mb_strimwidth($artigo->titulo, 0, 60, "...");?></div>
                                <div class="textoPadrao cadastro"><?= date("d/m/Y", strtotime($artigo->cadastro))?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                        
                </div>
            </div>

            <div class="botao">
                <a href="/blog.html">Ver mais</a>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owlBlog2 = jQuery("#blocoListaBlog2 .owl-carousel");
		owlBlog2.owlCarousel({
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