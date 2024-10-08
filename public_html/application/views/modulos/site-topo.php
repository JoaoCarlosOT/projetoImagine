<?php
    $controller->load->library('Imgno_imagem', '', 'imagineImagem');

    $controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config();

    if($config['campos']){
        $config['campos'] = json_decode($config['campos'],true);
    }

    $controller->load->model('site/Site_model_paginas', 'model_site');
    $menusCategoria = $controller->model_site->getMenuInstitucionalCategoria();

    // $servicos = $controller->model_site->getProdutosMenu();
    $servicos = false;
?> 

<div class="container">
	<div class="btnMenuMobile" onclick="menuMobile()"><div class="iconeHamburger"><span></span></div></div>
	<div class="menus-topo">
		<div class="logomarca">
			<a href="/" title="logo">
                <img src="/arquivos/imagens/logo_despacho_rapido.png" alt="logo" title="logo" />
            </a>
        </div>
		<div class="menuPrincipal"> 

			<nav class="nav_menu">			
                <div id="megamenu" class="">
                    <ul>
                        <!-- <li><a href="/" title="Início" class="mobile-menu">Início</a></li> -->
                        <li><a href="/quem-somos.html" title="quem somos">Simular envio</a></li>

                        <!-- <li class="parent parent2">
                            <a href="/servicos.html" title="Serviços">Serviços <em class="icon-down-open"></em></a>
                            <?php if($servicos):?>
                            <ul class="nav-child">
                                <?php foreach($servicos as $servico):?>
                                    <li><a href="/servicos/<?=$servico->alias?>.html" title="<?=$servico->nome?>"><?=$servico->nome?></a></li>
                                <?php endforeach;?>
                            </ul>
                            <?php endif;?>
                        </li> -->



                        <?php if($menusCategoria):?>
                            <?php foreach($menusCategoria as  $menuCategoria):?>

                                <?php if($menuCategoria->produtos):?>
                                    <li class="parent parent2">   
                                        <a href="#" title="categoria <?=$menuCategoria->nome?>"><?=$menuCategoria->nome?> <em class="icon-down-open"></em></a>
                                        <ul class="nav-child <?=($menuCategoria->imagem?"item-img":"")?>">
                                            <?php if($menuCategoria->imagem):?>
                                                <li class="imagem-menu">
                                                    <picture>
                                                        <source media="(max-width: 768px)" srcset="#" alt="imagem produto <?=$menuCategoria->nome?>">
                                                        <img src="<?=$controller->imagineImagem->otimizar('/arquivos/imagens/institucional/categoria/'.$menuCategoria->imagem, 350, 250, false, true, 80); ?>" title="imagem produto <?=$menuCategoria->nome?>" alt="imagem produto <?=$menuCategoria->nome?>">
                                                    </picture>
                                                </li>
                                            <?php endif;?>
                                            
                                            <?php $i = 0; foreach($menuCategoria->produtos as $produto): $i++;?>
                                                <li>
                                                    <a href="/servicos/<?=$produto->alias?>.html" title="produto <?=$produto->nome?>">
                                                        <?php if($produto->icone):?>
                                                            <em class="<?=$produto->icone?>"></em>
                                                        <?php endif;?>
                                                        
                                                        <?=$produto->nome?>
                                                    </a>
                                                </li>
                                            <?php endforeach;?> 

                                            <?php if($i == 5):?>
                                                <li class="ver-mais">
                                                    <a href="/servicos-categoria/<?=$menuCategoria->alias?>.html"><em class="icon-plus"></em> Ver mais</a>
                                                </li>
                                            <?php endif;?>

                                        </ul>
                                    </li>
                                <?php else:?>
                                    <li><a href="/servicos-categoria/<?=$menuCategoria->alias?>.html" title="categoria <?=$menuCategoria->nome?>"><?=$menuCategoria->nome?></a></li>
                                <?php endif;?>
                                
                            <?php endforeach;?>
                        <?php endif;?>



                        <li><a href="/blog.html" title="Blog">Rota do Sucesso</a></li>
                        <li><a href="/contato.html" title="Contato">Contato</a></li>

                        <!-- <li><a href="/blog.html" title="Blog" class="link">Rastrear</a></li>
                        <li><a href="/contato.html" title="Contato">Sou cliente</a></li> -->
                        <!-- <li><a href="https://api.whatsapp.com/send?1=pt_BR&phone=55<//?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=Olá, Vim do site e gostaria de mais informações" target="_blank" title="WhatsApp" class="whatsapp"><em class="icon-whatsapp"></em>Fale conosco</a></li> -->
                    </ul>
                </div>

                <div class="idiomas">
                    <div class="gtranslate_wrapper"></div>
                </div>
                <div class="btnDestaque">
                    <a title="telefone" class="btn btn_ligamos" onclick="popupformulario();"> <em class="icon-phone"></em> Ligamos para você</a>
                    <a href="#" class="btn" title="Solicitar orçamento"><em class="icon-whatsapp"></em> Solicitar orçamento</a>
                </div>
            </nav> 

            <div class="blocos-redes-mobile">
                <?php if($config['redes_facebook']): ?>
                    <a target="_blank" class="facebook" title="Facebook" href="<?php echo $config['redes_facebook']; ?>">
                        <em class="icon-facebook icn"></em>
                    </a>											
                <?php endif; ?>

                <?php if($config['redes_instagram']): ?>
                    <a target="_blank" class="instagram" title="Instagram" href="<?php echo $config['redes_instagram']; ?>">
                        <em class="icon-instagram icn"></em>
                    </a>
                <?php endif; ?>

                <?php if($config['redes_twitter']): ?>
                    <a target="_blank" class="twitter" title="twitter" href="<?php echo $config['redes_twitter']; ?>">
                        <em class="icon-twitter icn"></em>
                    </a>
                <?php endif; ?>

                <?php if($config['redes_youtube']): ?>
                    <a target="_blank" class="youtube" title="youtube" href="<?php echo $config['redes_youtube']; ?>">
                        <em class="icon-youtube icn"></em>
                    </a>
                <?php endif; ?> 

                <?php if($config['redes_linkedin']): ?>
                    <a target="_blank" class="linkedin" title="linkedin" href="<?php echo $config['redes_linkedin']; ?>">
                        <em class="icon-linkedin icn"></em>
                    </a>
                <?php endif; ?>

                <?php if($config['redes_pinterest']): ?>
                    <a target="_blank" class="pinterest" title="pinterest" href="<?php echo $config['redes_pinterest']; ?>">
                        <em class="icon-pinterest icn"></em>
                    </a>
                <?php endif; ?>

                <?php if($config['redes_tiktok']): ?>
                    <a target="_blank" class="tiktok" title="tiktok" href="<?php echo $config['redes_tiktok']; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><path fill="#fff" d="M12.525.02c1.31-.02 2.61-.01 3.91-.02c.08 1.53.63 3.09 1.75 4.17c1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97c-.57-.26-1.1-.59-1.62-.93c-.01 2.92.01 5.84-.02 8.75c-.08 1.4-.54 2.79-1.35 3.94c-1.31 1.92-3.58 3.17-5.91 3.21c-1.43.08-2.86-.31-4.08-1.03c-2.02-1.19-3.44-3.37-3.65-5.71c-.02-.5-.03-1-.01-1.49c.18-1.9 1.12-3.72 2.58-4.96c1.66-1.44 3.98-2.13 6.15-1.72c.02 1.48-.04 2.96-.04 4.44c-.99-.32-2.15-.23-3.02.37c-.63.41-1.11 1.04-1.36 1.75c-.21.51-.15 1.07-.14 1.61c.24 1.64 1.82 3.02 3.5 2.87c1.12-.01 2.19-.66 2.77-1.61c.19-.33.4-.67.41-1.06c.1-1.79.06-3.57.07-5.36c.01-4.03-.01-8.05.02-12.07z"></path></svg>
                    </a>
                <?php endif; ?>
            </div>

        </div>

        <div class="header_buttons">
                <nav>
                    <button class="button1_header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                        <a href="#" class="link1">RASTREAR</a>
                    </button>

                    <button class="button2_header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                        <a href="#" class="link2">SOU CLIENTE</a>
                    </button>
                </nav>
        </div> 
        
    </div>
</div>
<!-- 
<script>window.gtranslateSettings = {"default_language":"pt","native_language_names":true,"languages":["pt","en","es"],"wrapper_selector":".gtranslate_wrapper","flag_size":32,"alt_flags":{"en":"usa","pt":"brazil","es":"mexico"}}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/flags.js" defer></script> -->

<script type="text/javascript">
	jQuery(window).scroll(function(){
		var scroll = jQuery(window).scrollTop();
		if(scroll > 10){
			jQuery('.modulo-site-topo').addClass('scroll-on');
		}else{
			jQuery('.modulo-site-topo').removeClass('scroll-on');
		}
	});

    $('.parent > a').on('click', function(event){
        if($(window).width() >= 768) return;
        event.preventDefault(); 
    
        $(this).next('.nav-child').slideToggle();
    })
    
    $(document).ready(function() {
        if($('#banner > *').length == 0) {
            $('.modulo-site-topo').addClass('static');
        }
    });

</script>


<style>

    
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
    *{
        font-family: "Montserrat", sans-serif;
    }

    .link{
        margin-left: 15px;
    }
    .header_buttons{
        display: flex;
        align-items: center;
        justify-content: space-around;
        width: 200px;
    }
    .header_buttons>nav{
        display: flex;
        align-items: center;
        justify-content: space-around;
        gap: 12px;
    }
    .header_buttons>nav>button{
        width: 162px;
    height: 42px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border: none;
    }
    .header_buttons>nav>button>a{
        text-decoration: none;
    color: #fff;
    font-weight: 600;
    font-size: 13px;
    LETTER-SPACING: 3PX;
    }

    .button1_header{
        margin-left: 14px;
        background-color: #26a5d7;
    }

    .button2_header{
        background-color: #df2326;
    }

    .menus-topo{
        display: flex;
    align-items: center;
    padding: 20px 0;
    justify-content: space-around;
    flex-direction: row;
    }

    svg{
        color: #fff;
    }

    

</style> 