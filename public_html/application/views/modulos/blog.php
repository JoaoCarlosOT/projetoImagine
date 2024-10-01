<?php
$logo = '/arquivos/imagens/logo.png';

$controller->load->model('modulos/modulos_model_blog', 'model_blog');
$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$artigos = $controller->model_blog->getUltimosArtigos(6);

$dir_artigo = '/arquivos/imagens/blog/';
?>
<div class="blog_bloco caroseulStyle2" id="blogBlocos">
<div class="container">
    <div class="tituloPadrao textCenter">Rota do Sucesso</div>
    <div class="btnsCaroseulContent">
			<div class="owl-carousel">

                    <?php foreach($artigos as $ka => $artigo):
                            if($artigo->imagem){
                                $img_p = $controller->imagineImagem->otimizar($dir_artigo.$artigo->imagem, 460, 400, false, true, 80);
                            }else{
                                // logo
                                $img_p = $controller->imagineImagem->otimizar($logo, 460, 400, false, false, 80);
                            }
                        ?>
                        <div class="item blog-<?=$ka?> ">
                            <a href="/blog/<?=$artigo->alias?>.html" title="<?=$artigo->titulo?>">
                                <div class="img" style="position: relative;overflow: hidden;">
                                    <img src="<?=$img_p; ?>" title="<?=$artigo->titulo?>" alt="<?=$artigo->titulo?>">
                                </div>
                                <div class="tituloPadrao4 titulo"><?= mb_strimwidth($artigo->titulo, 0, 72, "..."); ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    
			</div>
			<div class="btnsCaroseul">
                <div class="next"><em class="icon-right-open"></em></div>
                <div class="prev"><em class="icon-left-open"></em></div>
	        </div>
    </div>

    <div class="textCenter">
        <a href="/blog.html" class="btn2 btn-blog-mais" title="Ver mais matérias">Ver mais matérias!</a>
    </div>
    
</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owlin = jQuery("#blogBlocos .owl-carousel");
		owlin.owlCarousel({
			autoplay:false,
			autoplayTimeout:5000,
			items : 3,
			responsive : {
				0 : { items : 1.4 },
				767: { items : 2 },
				991 : { items : 3 },
			},
			margin:20,
			loop:true,
            dots:false,
			nav:false,
		});
		jQuery("#blogBlocos .btnsCaroseulContent .next").click(function(){
			owlin.trigger('next.owl.carousel');
		});
		jQuery("#blogBlocos .btnsCaroseulContent .prev").click(function(){
			owlin.trigger('prev.owl.carousel');
		});
	});			
</script>