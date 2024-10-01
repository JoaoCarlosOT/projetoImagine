
<?php
	$controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config();
?>
<div class="componente com_imaginecontato">

<div class="div-blocos-info-contato">
			<div class="texto container  animation animation--from-left">
				<h1 class="tituloPadrao">Contate-nos</h1>

				<div class="blocos-info-txt">
					<p class="txt1">Fale com a gente!</p>
					<p class="txt2">Fale com a gente, preencha o formulário abaixo:</p>

					
					<div class="blocos-redes">
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
					</div>
				</div>

			</div>
	<div class="bloco-lista-contato animation animation--from-right">
		  <?php if($config['endereco']):?>
          <a title="Traçar rota do endereço" href="<?php echo$config['tracar'];?>" target="_blank"  class="bloco-info-contato">
            <div class="">
				<span class="bloco-icon-info"><em class="icon-location icn"></em></span>
				<h5 class="txt1">Endereço</h5>
				<p class="txt2">
					<span class="bloco-icone">
						<?=$config['endereco']?>
		  			</span>
				</p>
            </div>
          </a>
		  <?php endif;?>
		  <?php if($config['telefone2']):?>
          <a title="Ligue Agora" href="tel:0<?=preg_replace('/[^0-9]/', '', $config['telefone2'])?>" class="bloco-info-contato">
            <div class="">
				<span class="bloco-icon-info"><em class="icon-phone"></em></span>
				<h5 class="txt1">Telefone</h5>
				<p class="txt2">
					<span>
						<?php echo $config['telefone2']; ?>
		  			</span>
              	</p>
            </div>
          </a>
		  <?php endif;?>
		  <?php if($config['telefone1']):?>
          <a title="Ligue Agora" target="_blank" href="https://bit.ly/https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=Olá, Vim do site e gostaria de mais informações" class="bloco-info-contato">
            <div class="">
				<span class="bloco-icon-info"><em class="icon-whatsapp"></em></span>
				<h5 class="txt1">Whatsapp</h5>
				<p class="txt2">
					<span class="bloco-icone">
						<?php echo $config['telefone1']; ?>
		  			</span>
				</p>
            </div>
          </a>
		  <?php endif;?>
		  <?php if($config['email_atendimento']):?>
          <a title="Email" href="mailto:<?=$config['email_atendimento']?>" class="bloco-info-contato">
            <div class="">
				<span class="bloco-icon-info"><em class="icon-mail"></em></span>
				<h5 class="txt1">Email</h5>
				<p class="txt2">
					<span><?=$config['email_atendimento']?></span>
				</p>
            </div>
          </a>
		  <?php endif;?>
    </div>
</div>


<div class="container">
	<div class="corpo-contato animation animation--from-left">
		<div class="flex row">

			<form class="formulario" name="com_form" action="" method="post">
				<div class="linha">
					<label 	class="txt-label">Nome</label>
					<input  type="text" placeholder="" name="nm"  />
				</div>
				<div class="linha" >
					<label 	class="txt-label">Email</label>
					<input type="text" placeholder="" name="em" />
				</div>
				<div class="lh flex row">
					<div class="linha">
						<label 	class="txt-label">Telefone</label>
						<input type="text" value="+55" disabled="" style="min-width: 55px;width: 17%;">
						<input type="text" id="tel_contato_1" name="t1" alt="phone" placeholder="" style="width: 53%;margin-left: 6px;" />
					</div>
					<div class="linha">
						<label 	class="txt-label">Celular</label>
						<input type="text" value="+55" disabled="" style="min-width: 55px;width: 17%;">
						<input  type="text" id="tel_contato_2" name="t2" alt="phone" placeholder="" style="width: 53%;margin-left: 6px;" />
					</div>
				</div>
				<div class="linha">
					<label 	class="txt-label">Mensagem</label>
					<textarea placeholder="" name="msg" style="height: 105px;resize: vertical;"></textarea>
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
					<div class="flex">
						<div class="recap">
							<label for="recap" class="txt-label"><span>Valor de: </span> <?= $n1.' '.$sinal.' '.$n2.' = ';?> </label>
							<input style="width: 100px;" type="text" name="recap" id="recap" maxlength="2" />
							<input type="hidden" name="recap_n1" value="<?= $n1 ?>" />
							<input type="hidden" name="recap_n2" value="<?= $n2 ?>" />
							<input type="hidden" name="recap_sinal" value="<?= $sinal ?>" />
						</div>

						<!-- <div class="g-recaptcha" data-sitekey="<?php echo $config['recaptcha_key']; ?>"></div> -->
						<span type="button" class="btn button fright btn-contato2"  onclick="fn_com_form();"><em class="fa fa-check"></em> Solicitar Contato</span>
					</div>
				</div>
			</form>

			


		</div>
	</div>
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
	if(vazio(jQuery(f.msg).val()) || jQuery(f.msg).hasClass('example')){
		return exibirAviso("Escreva sua mensagem");
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
	if(typeof imgn_cmnc_sender == 'function'){
		var args = [
			{nome:'nome',valor:f.nm.value},
			{nome:'email',valor:f.em.value},
			{nome:'telefone',valor:f.t1.value},
			{nome:'mensagem',valor:jQuery(f.msg).val()},
		];
		imgn_cmnc_sender(args);
	}
	setTimeout(function(){f.submit();}, 500);
}
</script>