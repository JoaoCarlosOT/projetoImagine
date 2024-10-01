<?php
$dados = $this->dados["dados"]["resultado"];
$artigo_categorias = $this->dados["dados"]["artigo_categorias"];

$controller->load->model('modulos/modulos_model_config', 'model_config');
$config = $controller->model_config->buscar_config('telefone1');

$controller->load->model('modulos/modulos_model_blog', 'model_blog');
$config_blog = $controller->model_blog->buscar_config();
$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$noticias = $controller->model_blog->getUltimosArtigos(6);

$produtosRelacionados = $controller->model_blog->getProdutosRelacionados($dados->id, $dados->tipo_vinculo_produto, 6);

$dir_artigo = '/arquivos/imagens/blog/';
$dir_p = '/arquivos/imagens/produto/';

if(!empty($dados->background_color_ativado)){
	$color = $dados->font_color;
	$background_color = $dados->background_color;
}else{
	$color = $config_blog['font_color'];
	$background_color = $config_blog['background_color'];
}

if(!empty($dados->font_family_titulo)){
	$font_family_titulo = $dados->font_family_titulo;
}else{
	$font_family_titulo = $config_blog['font_family_titulo'];
}

if(!empty($dados->font_family_headline)){
	$font_family_headline = $dados->font_family_headline;
}else{
	$font_family_headline = $config_blog['font_family_headline'];
}
?>
<div class="topo-imagem" style="background-color: <?=$background_color?>">
	<div class="container">
		<div class="blocos">
			<div class="bloco">
				<h1 class="<?=$font_family_titulo?>" style="color: <?=$color?>;"><?=$dados->titulo;?></h1>
				<div class="subtitulo <?=$font_family_headline?>"  style="color: <?=$color?>;"><?=$dados->subtitulo;?></div>
			</div>
			<div class="bloco img">
				<img src="<?=$controller->imagineImagem->otimizar($dir_artigo.$dados->imagem, 720, 500, true, true, 80)?>" alt="">
			</div>
		</div> 

		<form class="formCta" id="formCta" name="formCta" method="post">
			<div class="blocos-form">
				
				<div class="blocos-form-base"></div>
				<div class="blocos-form-base-ciclo"></div>

				<?php if($config_blog["titulo_cta"]):?>
					<div class="form-txt">
						<div class="tituloBarra3"><?=$config_blog["titulo_cta"];?></div>
					</div>
				<?php endif;?>
				
				<div class="corpo-form">

					<input type="hidden" name="url" value="<?=$this->uri->uri_string();?>"> 

					<div class="linha input-container linha-cta">
						<input type="text" placeholder="" name="nm" class="campo-padrao2">
						<label class="textoPadrao txtCampo">Nome</label>
					</div>

					<div class="linha input-container">
						<input type="text" placeholder="" name="t1" id="phone_cta" class="campo-padrao2">
						<label class="textoPadrao txtCampo">Telefone/WhatsApp</label>
						<input type="hidden" name="dialCode">
					</div>

					<div class="linha input-container linha-cta">
						<input type="text" placeholder="" name="em" class="campo-padrao2">
						<label class="textoPadrao txtCampo">E-mail</label>
					</div>

					<div class="linha" >
						<a target="_blank" class="btn" onclick="form_cta(event);"><?=$config_blog["nomenclatura_bt_cta"];?></a>
					</div>
				</div>
				
			</div>
		</form> 
	</div>
</div> 
<link rel="stylesheet" href="/arquivos/css/intl-tel-input/build/css/intlTelInput.min.css">
<script src="/arquivos/css/intl-tel-input/build/js/intlTelInput.min.js"></script>
<script>
// $(document).ready(function() {
	const input = $("#phone_cta");
	const iti_cta = window.intlTelInput(input[0], {
		countrySearch: false,
		initialCountry: "br",
		nationalMode: true,
		preferredCountries: ['br', 'us'],
		utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
	}); 
// });
	function form_cta(event){ 

        var f = document.formCta;

        if(vazio(f.nm.value)){
            return exibirAviso("Por favor, informe seu nome");
        }
        
        if(vazio(f.t1.value)){
            return exibirAviso("Por favor, informe seu telefone");
        }
       
        // if(!validarTelefone(f.t1.value)){
        //     return exibirAviso("Por favor, informe seu telefone corretamente");
        // } 

        const countryData = iti_cta.getSelectedCountryData();        
        f.dialCode.value = countryData.dialCode;
        const isValid = iti_cta.isValidNumber();
        if(!isValid){
            return exibirAviso("Por favor, informe seu telefone corretamente");
        }  

        if(!validarEmail(f.em.value)){
            return exibirAviso("Por favor, informe seu email corretamente");
        }
        
        carregando();
        if(typeof imgn_cmnc_sender == 'function'){
            var args = [
                {nome:'nome',valor:f.nm.value},
                {nome:'email',valor:f.em.value},
                {nome:'telefone',valor:f.t1.value},
            ];
            imgn_cmnc_sender(args);
        }

		var dados = jQuery(f).serialize();

        jQuery.ajax({
            type: 'POST',
            url: "/ajax/salvar_solicitacao",
            data: dados,
            success: function(response) {
                console.log(response)
                carregado(); 

                if(response == 'ok') {
					f.reset();
					exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
				}
                else {
					exibirAviso('Não enviado');
					return;
				}
            }
        });

		window.open("https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=<?=urlencode('Olá, vim do site e gostaria de mais informações')?>", '_blank');
    }
</script>


<div class="container">
<div class="flexPrinc" id="bloco_conteudo">
<div class="col1 conteudo">
	<div class="item-page conteudoArtigo">
		<div>
			<div class="plg_link_externo_facebook">
				<div class="tit">Compartilhar</div>
				<div class="redesSociaisArtigo"> 
					<a onclick="window.open('https://api.whatsapp.com/send?text='+encodeURIComponent(location.href),'whatsapp-share-dialog','width=626,height=386');return false;" href="#" title="Compartilhar via whatsapp" class="botao-whatsapp">
						<em class="icon-whatsapp"></em>
					</a> 

					<a onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=386');return false;" href="#" title="Compartilhar via Facebook" class="botao-facebook">
						<em class="icon-facebook"></em>
					</a> 
					<a onclick="window.open('https://twitter.com/share?&url='+encodeURIComponent(location.href)+'&counturl='+encodeURIComponent(location.href),'twitter-share-dialog','width=626,height=436');return false;" href="#" title="Compartilhar via Twitter" class="botao-twitter">
						<!-- <em class="icon-twitter"></em> -->
						<svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><path fill="#000" d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
					</a>
					<a onclick="window.open('https://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;" href="#" title="Compartilhar via Linkedin" class="botao-twitter">
						<em class="icon-linkedin"></em>
					</a> 
				</div>
			</div>
			
			<?=$dados->descricao;?>
		</div>
	</div>
</div> 
<div class="main-right hidden-xs hidden-sm">
	<div id="div_mov">
		<?php if($artigo_categorias):?>
		<div class="navegueCategorias">
			<div class="moduletitle">Navegue Por categoria</div>
			<div class="lista">
				<?php foreach($artigo_categorias as $artigo_cat):?>
					<a href="/blog.html?id_categoria=<?=$artigo_cat->id?>"><?=$artigo_cat->nome?></a>
				<?php endforeach;?>
			</div>
		</div>
		<?php endif;?>
		<div class="noticias_mais_lidas">
			<div class="moduletitle">Outras Notícias</div><?php
			foreach($noticias as $k => $row){ ?>
				<div class="noticia">
					<div class="flex">
						<div class="bc1">
							<a href="/blog/<?=$row->alias?>.html" title="<?=$row->titulo?>">
								<div class="img"><?php echo '<img title="'.$row->titulo.'" alt="'.$row->titulo.'" src="'.$controller->imagineImagem->otimizar($dir_artigo.$row->imagem, 300, 260, false, true, 80).'">'; ?></div>
							</a>
						</div>
						<div class="bc2">
							<a href="/blog/<?=$row->alias?>.html" title="<?=$row->titulo?>"><span class="titulo"><?php echo $row->titulo; ?></span></a>
						</div>
					</div>
				</div>
				<?php
			} ?>
		</div>
	</div>
</div>

</div>
</div>

<script type="text/javascript">
	let old = jQuery(window).scrollTop();
	let order = 'down';
	jQuery(window).scroll(function(){
		if(jQuery(window).width() <= 992) return;
		let top_content = jQuery('#conteudo #bloco_conteudo').offset().top;
		let h_content = jQuery('#conteudo #bloco_conteudo').height();
		let bot_content = top_content + h_content;
		
		let top_block = jQuery('#div_mov').offset().top;
		let h_block = jQuery('#div_mov').height();
		let bot_block = top_block + h_block;
		
		let menu_h = 80;
		bot_content -= 100;
		
		let top_window = jQuery(window).scrollTop() + menu_h;
		let h_window = jQuery(window).height();
		let bot_window = top_window + h_window;
		
		if(order == 'down'){
			if(old > top_window){
				order = 'up';
			}
		}else{
			if(old < top_window){
				order = 'down';
			}
		}
		old = top_window;
		let top = jQuery('#div_mov').css('top');
		if(order == 'down'){

			if(bot_window >= (bot_block+15)){
				if(bot_content <= bot_block) return;
				x = bot_window - (h_block+15) - top_content;

				console.log(x)
				

				if(h_block < h_window){
					x = x - (h_window - h_block) + 15;
					if(x < 0) x = 0;
				}
				jQuery('#div_mov').css('top',x);
			}
		}else{
			if(top_window <= (top_block+15)){
				if(top_content >= top_block) return;
				x = top_window - top_content;
				if(x < 0) x = 0;
				jQuery('#div_mov').css('top',x);
			}	
		}
	});	
</script>

<?php if($produtosRelacionados):?>
<div class="bloco-lista-servicos-2">
    <div class="container">
		<h3 class="textCenter tituloPadrao">Serviços Relacionados</h3>
        <div class="bloco-lista-2" id="blocoListaServicos2">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($produtosRelacionados as $ka => $produto):
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
                                <div class="tituloPadrao2 titulo"><?=$produto->nome?></div>
                                <div class="headline2 subtitulo"><?=$produto->descricao?></div>
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
<?php endif;?>