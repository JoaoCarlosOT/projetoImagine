<?php
    // if(empty($this->dados['copywriter']['ids'])) return;
    // $key = array_key_first($this->dados['copywriter']['ids']);
    // $id = $this->dados['copywriter']['ids'][$key];
    // unset($this->dados['copywriter']['ids'][$key]);

    $controller->load->library('Imgno_util', '', 'ImgnoUtil');
    $controller->load->model('modulos/modulos_model_copywriter', 'model_copywriter');
    $config_copy = $controller->model_copywriter->buscar_config();
    $copywriter = $controller->model_copywriter->buscar_copywriter(7);
    if(!$copywriter) return;

    // background color do bloco
    if($copywriter['background_color_ativado']){
        $background_color = "background-color:".$copywriter['background_color'].";";
    }else if($config_copy['background_color_ativ']){
        $background_color = "background-color:".$config_copy['background_color'].";";
    }else{
        $background_color = "";
    }

    // classe do titulo
    if($copywriter['font_family_titulo']){
        $classe_titulo = $copywriter['font_family_titulo'];
    }else if($config_copy['font_family_titulo']){
        $classe_titulo = $config_copy['font_family_titulo'];
    }else{
        $classe_titulo = "";
    }

    // Cor do titulo
    if($copywriter['font_color_titulo_ativ']){
        $cor_titulo = "color:".$copywriter['font_color_titulo'].";";
    }else if($config_copy['font_color_titulo_ativ']){
        $cor_titulo = "color:".$config_copy['font_color_titulo'].";";
    }else{
        $cor_titulo = "";
    }

    // classe do headline
    if($copywriter['font_family_titulo']){
        $classe_headline = $copywriter['font_family_headline'];
    }else if($config_copy['font_family_titulo']){
        $classe_headline = $config_copy['font_family_headline'];
    }else{
        $classe_headline = "";
    }

    // Cor do headline
    if($copywriter['font_color_headline_ativ']){
        $cor_headline = "color:".$copywriter['font_color_headline'].";";
    }else if($config_copy['font_color_headline_ativ']){
        $cor_headline = "color:".$config_copy['font_color_headline'].";";
    }else{
        $cor_headline = "";
    }

    // Cor da descricao
    if($copywriter['font_color_descricao_ativ']){
        $cor_descricao = "color:".$copywriter['font_color_descricao'].";";
    }else{
        $cor_descricao = "";
    }

    // Corte
    if($copywriter['tipo_corte']){
        $tipo_corte = 'corte-'.$copywriter['tipo_corte'];
    }else{
        $tipo_corte = "";
    }

    if($copywriter['pos_tex'] == 3){
        $class_pos_tex = 'acima textCenter';
        $class_animation_1 = 'animation--from-top';
        $class_animation_2 = 'animation--from-bottom';
    }elseif($copywriter['pos_tex'] == 2){
        $class_pos_tex = 'acima';
        $class_animation_1 = 'animation--from-top';
        $class_animation_2 = 'animation--from-bottom';
    }elseif($copywriter['pos_tex'] == 1){
        $class_pos_tex = 'direito';
        $class_animation_1 = 'animation--from-right';
        $class_animation_2 = 'animation--from-left';
    }else{
        $class_pos_tex = 'esquerdo';
        $class_animation_1 = 'animation--from-left';
        $class_animation_2 = 'animation--from-right';
    }

    $controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config();

    $controller->load->library('Imgno_imagem', '', 'imagineImagem');

    $dir_p = '/arquivos/imagens/copywriter/';
?>
<div class="copywriter-blocos <?=(!$tipo_corte && $background_color?'back ':($tipo_corte?$tipo_corte:' margin '))?> modelo-<?=$copywriter['modelo']?>" style="<?=($background_color?$background_color:'')?>" id="copywriter-1">
    <div class="container">
        <div class="itens <?=$class_pos_tex?>">
            <div class="item texts animation <?=$class_animation_1?>">
                <?php if($copywriter['titulo']):?><h3 id="title_cop1" class="<?=$classe_titulo?>" style="<?=$cor_titulo?>"><?=$copywriter['titulo']?></h3><?php endif;?>
                <?php if($copywriter['headline']):?><div class="<?=$classe_headline?> subtitulo" style="<?=$cor_headline?>"><?=$copywriter['headline']?></div><?php endif;?>
                <?php if($copywriter['descricao']):?><div class="textoPadrao descricao" style="<?=$cor_descricao?>"><?=$copywriter['descricao']?></div><?php endif;?>
                <?php if($copywriter['text_botao'] && $copywriter['link']):?>
                    <div class="botao">
                        <a href="<?=$copywriter['link']?>" class="button" target="_blank" title="<?=$copywriter['text_botao']?>"><?=$copywriter['text_botao']?></a>
                    </div>
                <?php elseif($copywriter['text_botao']):?>
                    <div class="botao">
                        <a href="https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=Olá, Vim do site e gostaria de mais informações" class="button"  id="botao_copwriter" target="_blank" title="<?=$copywriter['text_botao']?>"><?=$copywriter['text_botao']?></a>
                    </div>
                <?php endif;?>
            </div>
            <div class="item imagem animation <?=$class_animation_2?>">
                <?php if($copywriter['imagem']):?>
                    <img src="<?=$controller->imagineImagem->otimizar($dir_p.$copywriter['imagem'], 700, $copywriter['altura'], true, $copywriter['cortar'], 80); ?>" title="<?=$copywriter['text_botao']?>" alt="<?=$copywriter['text_botao']?>">
                <?php elseif($copywriter['link_yt']):?>

                    <iframe <?=!empty($copywriter['altura'])?'height="'.$copywriter['altura'].'"':''?> title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<?php if(!$copywriter['imagem'] && $copywriter['link_yt']):?>
    <script type="text/javascript">

        var ctdr_copy_<?=$id?> = false;
                
        $(window).scroll(function(){
            if(!ctdr_copy_<?=$id?>){
                var p_copy_<?=$id?> = $('.modulo-<?=$classe?> #copywriter-<?=$id?>').offset().top;
                var s_copy_<?=$id?> = $(window).scrollTop();
                var h_copy_<?=$id?> = jQuery(window).height();

                if(s_copy_<?=$id?> >= (p_copy_<?=$id?> - h_copy_<?=$id?>)){
                    ctdr_copy_<?=$id?> = true;
                    $(".modulo-<?=$classe?> #copywriter-<?=$id?> .imagem iframe").attr('src','<?=$controller->ImgnoUtil->getYoutubeEmbedUrl($copywriter['link_yt'])?>');
                }
            }				
        });	
    </script>
<?php endif;?>

<style>
    .button{
        background-color: #28a6d9;
    width: 252px;
    height: 49px;
    border-radius: 39px;
    margin: 15px;
    align-items: center;
    font-weight: 400;
    justify-content: center;
    display: flex;
    font-size: 19px;
    }

    #title_cop1{
        font-weight: 300;
    }
</style>
