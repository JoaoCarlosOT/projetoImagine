<?php

    $controller->load->model('modulos/modulos_model_config', 'model_config');
    $buscar_config = $controller->model_config->buscar_config('popup_cookies');
 
    if(empty($buscar_config['popup_cookies']) || isset($_COOKIE['confimado_cookie'])) return;

    // if(empty($buscar_config['popup_cookies'])) return;
?>

<div id="bloco-termos-aceito" >
    <div id="cookie-banner-lgpd" class="cookie-banner-lgpd-visible cookie-banner-lgpd-animated">
        <div class="cookie-banner-lgpd-container">
            <div class="cookie-banner-lgpd_text-box">
                <div class="titulo">Valorizamos a sua privacidade</div>
                <p class="cookie-banner-lgpd_text" style="margin: 10px 0;">
                    Usamos cookies para aprimorar sua experiência de navegação, veicular anúncios ou conteúdo personalizado e analisar nosso tráfego. Ao clicar em "Aceitar tudo", você concorda com o uso de cookies.
                </p>
            </div>
            <div class="cookie-banner-lgpd_button-box">
                <span class="cookie-banner-lgpd_accept-button" onclick="verificarCookie(true);">Aceitar tudo</span>
            </div>
        </div>
    </div>
</div>

<script>
    function verificarCookie(send = false){
        if(send){
            carregando();
            jQuery.ajax({
                url: '/site/Site_controller_paginas/aceito_cookie',
                type: 'POST',
                dataType: 'json',
                data: {aceito:true},
                xhrFields: {
                    withCredentials: true
                },
                error: function() {},
                success: function(res) {
                    console.log(res)

                    jQuery('#bloco-termos-aceito').hide();

                    carregado();
                }
            });
        }else{
            jQuery.ajax({
                url: '/site/Site_controller_paginas/aceito_cookie',
                type: 'GET',
                dataType: 'json',
                data: {},
                xhrFields: {
                    withCredentials: true
                },
                error: function() {},
                success: function(res) {
                    console.log(res)
                    if(res['aceito'] == false){
                        jQuery('#bloco-termos-aceito').show();
                    }
                }
            });
        }
    }
    // verificarCookie();
</script>