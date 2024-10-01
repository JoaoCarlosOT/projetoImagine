<?php
	$controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config('telefone1,telefone2,recaptcha_key');
?> 
<div class="modal_box_fundo" onclick="rem_popupformulario();"></div>
<div class="modal_box" id="callme">
    <div class="box_header">
        <p class="title">Nós Ligamos para você</p>
    </div>
    <div class="box_content">
        <p>Deixe seus dados para ligarmos para você.</p>
        <form class="formulario101" id="formulario101" name="formulario101" method="post">
            <input type="hidden" name="tipo_form" value="3">
            <div class="formflex">
			    <input type="text" placeholder="Nome" name="nm" class="campo-padrao3 campo-tm"/>
                <input class="campo-padrao3 campo-tm2" alt="phone" type="text" name="t1" placeholder="Telefone"/>
            </div>
      
            <?php
                $n1 = mt_rand(0,10);
                $n2 = mt_rand(0,10);

                if($n1 > $n2):
                    $sinal = '-';
                    $n3 = $n1 - $n2;
                else :
                    $sinal = '+';
                    $n3 = $n1 + $n2;
                endif;
            ?>

			<div class="linha-botao botao_check textCenter">
				<div class="flex space-between">
                    <div class="recap">
                        <span>Valor de: </span> <?= $n1.' '.$sinal.' '.$n2.' = ';?> <input  type="text" class="campo-padrao3 " name="recap" maxlength="2" />
                        <input type="hidden" name="recap_n1" value="<?= $n1 ?>" />
						<input type="hidden" name="recap_n2" value="<?= $n2 ?>" />
						<input type="hidden" name="recap_sinal" value="<?= $sinal ?>" />
                    </div>
					<!--<div class="g-recaptcha" data-sitekey="<?php echo $config["recaptcha_key"]; ?>"></div>-->
					<span class="btn button fright btn btn-contato"  onclick="fn_com_form22();">Solicitar Contato</span>
				</div>
			</div>
        </form>
    </div>
</div>

<!--
<script type="text/javascript">
	var ReCaptchaCallback = function() {
		jQuery('.g-recaptcha').each(function(){
			var el = jQuery(this);
			grecaptcha.render(el.get(0), {'sitekey' : el.data("sitekey")});
		});  
	};
</script>-->
	<script type="text/javascript">
		function fn_com_form22(){
			var f = document.formulario101;

			if(vazio(f.nm.value)){
				return exibirAviso("Informe o campo Nome");
			}
            if(!validarTelefone(f.t1.value)){
				return exibirAviso("Informe o campo Telefone");
			}

            if(vazio(f.recap.value)){
				return exibirAviso("Informe o valor de <?= $n1.' '.$sinal.' '.$n2;?>");
			}
			if(f.recap.value != <?=$n3;?>){
				return exibirAviso("Informe corretamente o código de segurança");
			}
			
            /*
			var response = grecaptcha.getResponse();
			if(response.length == 0) return exibirAviso('Marque o ReCaptcha para confirmar que você não é um robô');*/


	
			if(typeof imgn_cmnc_sender == 'function'){
				var args = [
					{nome:'nome',valor:f.nm.value},
					{nome:'telefone',valor:f.t1.value},
				];
				imgn_cmnc_sender(args);
			}
			carregando();
            rem_popupformulario();

			var dados = jQuery('#formulario101').serialize();

			jQuery.ajax({
                type: 'POST',
                url: "/ajax/salvar_solicitacao",
                data: dados,
                success: function(response) {
                    console.log('response',response);
                    carregado();

                    f.reset();
                    if(response == 'ok') return exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
                    else exibirAviso('Não enviado');
                }
            });
		}
	</script>
    <!--
	<script src='https://www.google.com/recaptcha/api.js?onload=ReCaptchaCallback&render=explicit'></script>-->

<script>
    function popupformulario(){
        jQuery('#callme').addClass('active');
        jQuery('.modal_box_fundo').css('display','block');
    }
    function rem_popupformulario(){
        jQuery('#callme').removeClass('active');
        jQuery('.modal_box_fundo').css('display','none');
    }
</script>