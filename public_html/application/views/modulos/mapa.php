<?php
	$controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config('endereco,tracar,nome_empresa,iframe_google_maps');

    // Use a expressão regular para extrair o valor do atributo "src"
    preg_match('/src="(.*?)"/', $config["iframe_google_maps"], $matches);
    $src_value_ifreme = !empty($matches[1])?$matches[1]:"";

?>

<div class="blocoInfoMapa">

        <div class="bloco-localizacao">
            <div class="tituloPadrao"><?=$config['nome_empresa']?></div>
            <div class="headline txt2"><?=$config['endereco']?></div>

            <div class="textCenter">
                <a href="<?=$config['tracar']?>" class="btn3 btnLocalizacao" target="_blank" title="Traçar Rota">Traçar Rota</a>
            </div>
        </div>

    <div class="bloco-iframe">
        <iframe style="width:100%;" src="" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
</div>

<script type="text/javascript">
    var ctdrMap = false;
    var urlMap = '<?=$src_value_ifreme?>';
    var identDivMap = '.bloco-iframe';
    var hMap = jQuery(window).height();
    var pMap = $(identDivMap).offset().top;
    if(pMap <= hMap){
        ctdrMap = true;
        setTimeout(function(){
            $(identDivMap+" iframe").attr('src',urlMap)
        }, 5000)
    }

    var ctdrMap = false;
    $(window).scroll(function(){
        if(!ctdrMap){
            var p = $('.bloco-iframe').offset().top;
            var s = $(window).scrollTop();
            var h = jQuery(window).height();

            if(s >= (p - h) && pMap > hMap){
                ctdrMap = true;
                $(".bloco-iframe iframe").attr('src',urlMap)
            }
        }else{
            
        }				
    });			
</script>