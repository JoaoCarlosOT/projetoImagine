

<?php
	$controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config();
?>

<div class="componente com_imaginecontato">
<div class="container">
	<form name="com_form" action="" method="post" enctype="multipart/form-data">
		<div class="corpo-contato" id="form_dados">
			<div class="flex row">

				<div class="bloco-row texto animation animation--from-left">
					<h1 class="tituloPadrao2">Trabalhe conosco</h1>

					<div class="descricao textoPadrao2">
						<p>Quer fazer parte do nosso time de profissionais? Envie seu curriculo pelo formulário ao lado.</p>
						<p>Boa sorte!</p>
					</div>
				</div>
				<div class="bloco-row formulario animation animation--from-right">				
					<div class="linha">
						<!-- <span class="campo input20">Vaga:</span> -->
						<label for="" class="txt-label">Qual vaga você tem interesse?</label>
						<select class="input100" name="vaga" id="selectVaga">
							<option value="0">Escolha uma opcao:</option>
							<option value="Outras">Outras</option>
						</select>
					</div>
					<div class="linha" style="display: none;" id="inputVaga">
						<label for="" class="txt-label">Informe a vaga que você tem interesse</label>
						<input class="input100" type="text" placeholder="Informe a vaga que você tem interesse" name="inputVaga"  />
					</div>
					<div class="linha">
						<label for="" class="txt-label">Nome</label>
						<input class="input100" type="text" placeholder="" name="nm"  />
					</div>
					<div class="linha" >
						<label for="" class="txt-label">Email</label>
						<input class="input100" type="text" placeholder="" name="em" />
					</div>
					<div class="lh flex row">
						<div class="linha input50">
							<label for="" class="txt-label">Telefone</label>
							<input type="text" name="t1" alt="phone" placeholder=""/>
						</div>
						<div class="linha input50">
							<label for="" class="txt-label">Celular</label>
							<input  type="text" name="t2" alt="phone" placeholder="" />
						</div>
					</div>

					<div class="linha">
						<label for="input-file" class="label_input">
							<span class="btn btn-padrao mgr10 mgl0">Selecionar Curriculo</span>
							<span id="file-name">Selecione seu curriculo</span>
						</label>

						<input id="input-file" style="display: none;" name="imagem_anexo[]" type="file">
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
					<div class="linha-botao">
						<div class="flex" >
							<div class="recap">
								<label class="txt-label"><span>Valor de: <?= $n1.' '.$sinal.' '.$n2.' = ';?> </span></label>
								<input style="width: 100px;"  type="text" class=" " name="recap" maxlength="2" />
								<input type="hidden" name="recap_n1" value="<?= $n1 ?>" />
								<input type="hidden" name="recap_n2" value="<?= $n2 ?>" />
								<input type="hidden" name="recap_sinal" value="<?= $sinal ?>" />
							</div>
							<!-- <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_key']; ?>"></div> -->
							<span type="button" class="btn bot" onclick="fn_com_form();">ENVIAR CURRÍCULO</span>
						</div>
					</div>
				</div>
			</div>
		</div> 
	</form>
</div>
</div>


<!-- <script type="text/javascript">
	var ReCaptchaCallback = function() {
		jQuery('.g-recaptcha').each(function(){
			var el = jQuery(this);
			grecaptcha.render(el.get(0), {'sitekey' : el.data("sitekey")});
		});  
	};
</script>
<script src='https://www.google.com/recaptcha/api.js?onload=ReCaptchaCallback&render=explicit'></script> -->

<script type="text/javascript">
$(document).ready(function() {
	$('#select-button').on('click', function() {
		$('#input-file').click();
	});

	$('#input-file').on('change', function() {
		var fileName = $(this).val().split('\\').pop();
		$('#file-name').text(fileName);

		var fileExtension = fileName.split('.').pop().toLowerCase();
		if (fileExtension !== 'pdf' && fileExtension !== 'docx') {
			$(this).val(''); // Limpa o input file
			$('#file-name').text('Nenhum arquivo selecionado');

			return exibirAviso('Por favor, selecione um arquivo PDF ou do Word (docx).');
		}
	});
});

function fn_com_form(){

	var f = document.com_form; 

	if(vazio(f.nm.value)){
		return exibirAviso("Informe seu nome");
	}
	if(!validarEmail(f.em.value)){
		return exibirAviso("Informe seu email corretamente");
	}
	
	if(!vazio(f.t1.value))
	{
		if(!validarTelefone(f.t1.value)){
			return exibirAviso("Informe seu telefone corretamente");
		}
	}
	else if(!vazio(f.t2.value))
	{
		if(!validarTelefone(f.t2.value)){
			return exibirAviso("Informe seu celular corretamente");
		}
	}
	else
	{
		return exibirAviso("Informe seu telefone ou celular");
	} 

	var files = $('#input-file').get(0).files;
	if (files.length === 0) {
		return exibirAviso("Informe seu currículo");
	} 

	// var response = grecaptcha.getResponse();
	// if(response.length == 0) return exibirAviso('Marque o ReCaptcha para confirmar que você não é um robô');

	if(vazio(f.recap.value)){
		return exibirAviso("Informe o valor de <?= $n1.' '.$sinal.' '.$n2;?>");
	}
	if(f.recap.value != <?=$n3;?>){
		return exibirAviso("Informe corretamente o código de segurança");
	} 

	carregando();

		// if(typeof imgn_cmnc_sender == 'function'){
		// 	var args = [
		// 		{nome:'nome',valor:f.nm.value},
		// 		{nome:'email',valor:f.em.value},
		// 		{nome:'telefone',valor:f.t1.value},
		// 		{nome:'mensagem',valor:jQuery(f.msg).val()},
		// 	];
		// 	imgn_cmnc_sender(args);
		// }
		setTimeout(function(){f.submit();}, 500);
}
</script>