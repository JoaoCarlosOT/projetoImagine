<?php
	$controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config('telefone1,telefone2,recaptcha_key');

?>

<style>
.imagineContatoFormulario .div-form {
    display: flex;
    justify-content: center;
}
.imagineContatoFormulario .corpo-form {
    min-width: 51%;
    justify-content: space-between;
}
.imagineContatoFormulario input {
    height: 45px;
    padding: 5px 10px !important;
}
.imagineContatoFormulario .linha input,.imagineContatoFormulario .linha textarea {
    margin-bottom: 10px;
}
/* .imagineContatoFormulario textarea,.imagineContatoFormulario input {
    border: 1px solid #ccc;
    width: 100%;
    padding: 0px 15px;
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.5;
    letter-spacing: normal;
} */
.imagineContatoFormulario .row-cell {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
@media( max-width: 767px)
{
	.row-cell div
	{
		width: 100%;
	}
	.imagineContatoFormulario {
	    padding: 60px 0;
	}
	.imagineContatoFormulario .linha-botao .flex {
	    justify-content: center;
	}

}
@media( min-width: 768px)
{
	.imagineContatoFormulario {
	    padding: 60px 0;
	}
	.linha-tel {
	    min-width: 46%;
	    flex-basis: 46%;
	}
	.linha-nm {
		min-width: 30%;
		flex-basis: 64%;
	}

	.linha-nm:nth-child(2) {
		flex-basis: 30%;
	}
	.imagineContatoFormulario .linha-botao .flex {
	    justify-content: space-between;
	}
	span.btn.button.fright.btn.btn-contato {
	    margin-left: 4px;
	}
}
.form-back {
    background: url(/arquivos/imagens/img-form2.jpg)center center/ 100% 100%;
    z-index: -2;
}
.tituloBarra {
    color: #fff;
    font-size: 35px;
    font-weight: 600;
}
.subtituloBarra {
    font-size: 1.4em;
    color: #FFF;
    font-family: 'Roboto';
    font-weight: 500;
}
.form-txt
{
	margin-bottom: 5px;
	position: relative;
}
.imagineContatoFormularioP {
    z-index: 2;
    position: relative;
}
.imagineContatoFormulario {
    z-index: 2;
    position: relative;
}
.btnSolicitar {
    background: #733631;
    border-radius: 8px;
    padding: 10px 0px;
    width: 80%;
}
.sombra-form
{
	background: #040404b5;
	background: #8667e8;
    width: 100%;
    height: 100%;
    position: absolute;
}
.imagineContatoFormulario .linha-botao .flex {
    align-items: center;
    flex-direction: row;
    flex-wrap: wrap;
}
.btn.btn.btn-contato {
	/* padding: 15px 15px; */
	font-size: 14px;
	margin: 15px 0px;
	/* font-weight: bold; */
	color: #fff;
	/* border-radius: inherit; */
}
.btn.btn.btn-contato:hover{
	/* color: #a7a7a7; */
}
.txtCampo {
    color: #fff;
    font-weight: 600;
    display: block;
    font-size: 15px;
    padding: 0 15px;
	margin-bottom: 8px;
}
</style>

<div class="imagineContatoFormularioP" id="solicitar-orcamento">
<div class="form-back">
<div class="sombra-form"></div>
<div class="imagineContatoFormulario">
	<form class="formulario101" id="formulario101" name="formulario101"  method="post">
		<div class="container">
			<div class="col-md-6 offset-md-3">
				<div class="form-txt">
					<div class="tituloBarra textCenter">Fale Conosco</div>
				</div>
				<div class="div-form">
					<div class="corpo-form">
							
						<div class="linha">
							<div class="txtCampo">Nome</div>
							<input type="text" placeholder="" name="nm" class="campo-padrao2"/>
						</div>
						<div class="linha">
							<div class="txtCampo">Email</div>
							<input type="text" placeholder="" name="em" class="campo-padrao2"/>
						</div>
						<div class="linha row-cell">
							<div class="linha-tel">
								<div class="txtCampo">Telefone</div>
								<input class="campo-padrao2" alt="phone" type="text" name="t1" placeholder=""/>
							</div>
							<div class="linha-tel">
								<div class="txtCampo">Celular</div>
								<input class="campo-padrao2" alt="phone" type="text" name="t2" placeholder=""/>
							</div>
						</div>
						<div class="linha">
							<div class="txtCampo">Mensagem</div>
							<textarea class="campo-padrao2" style="height:120px;resize: vertical;" placeholder="" name="msg"></textarea>
						</div>
						<div class="linha-botao botao_check textCenter">
							<div class="flex space-between">
								<div class="g-recaptcha" data-sitekey="<?php echo $config["recaptcha_key"]; ?>"></div>
								<span class="btn button fright btn btn-contato"  onclick="fn_com_form22();">Solicitar Contato</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</form>
	<script type="text/javascript">
	var ReCaptchaCallback = function() {
		jQuery('.g-recaptcha').each(function(){
			var el = jQuery(this);
			grecaptcha.render(el.get(0), {'sitekey' : el.data("sitekey")});
		});  
	};
</script>
	<script type="text/javascript">
		function fn_com_form22(){
			var f = document.formulario101;

			if(vazio(f.nm.value)){
				return exibirAviso("Informe o campo Nome");
			}
			if(!validarEmail(f.em.value)){
				return exibirAviso("Informe o campo Email");
			}
			

			if(!vazio(f.t1.value))
			{
				if(!validarTelefone(f.t1.value)){
					return exibirAviso("Informe o campo Telefone");
				}
			}
			else if(!vazio(f.t2.value))
			{
				if(!validarTelefone(f.t2.value)){
					return exibirAviso("Informe o campo Celular");
				}
			}
			else
			{
				return exibirAviso("Informe o campo Telefone/Celular");
			}
			if(vazio(jQuery(f.msg).val()) || jQuery(f.msg).hasClass('example')){
				return exibirAviso("Informe o campo Mensagem");
			}
			
			var response = grecaptcha.getResponse();
			if(response.length == 0) return exibirAviso('Marque o ReCaptcha para confirmar que você não é um robô');
	
			if(typeof imgn_cmnc_sender == 'function'){
				var args = [
					{nome:'nome',valor:f.nm.value},
					{nome:'email',valor:f.em.value},
					{nome:'telefone',valor:f.t1.value},
					{nome:'telefone2',valor:f.t2.value},
					{nome:'mensagem',valor:jQuery(f.msg).val()},
				];
				imgn_cmnc_sender(args);
			}
			carregando();
			var dados = jQuery('#formulario101').serialize();

			jQuery.ajax({
                type: 'POST',
                url: "ajax/salvar_contato",
                data: dados,
                success: function(response) {
                    
                    carregado();

                    f.reset();
                    if(response == 'ok') return exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
                    else exibirAviso('Não enviado');
                }
            });
		}
	</script>
		
	<script src='https://www.google.com/recaptcha/api.js?onload=ReCaptchaCallback&render=explicit'></script>
</div>
</div>
</div>