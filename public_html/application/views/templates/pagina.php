<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

if(file_exists(APPPATH .'models/site/Site_model_seo.php') && !isset($_GET['seo_keywords_ready']) ){
	$controller->load->model('site/site_model_seo', 'model_seo');
	$controller->model_seo->verificar_cadastrar();
	$seo = $controller->model_seo->dados_pagina_seo();
	$seo_config = $controller->model_seo->dados_pagina_seo_config();
}

$controller->load->model('admin/Admin_model_configuracao', 'model_configuracao');
$manutecao = $controller->model_configuracao->buscar_configuracao();

$controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
$modulo_10 = $controller->model_modulo->buscar_modulo(10);
?>

<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
	<head>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="revisit-after" content="1 days" />
		<meta name="robots" content="index,follow,all,archive" />
		<meta name="googlebot" content="index,follow" />
		<meta name="yahoo-slup" content="index,follow" />
		<meta name="msnboot" content="index,follow" />

		<?php if(!empty($seo_config)): ?>
			<meta name="author" content="<?php echo $seo_config['author'];?>" />
			<meta name="reply-to" content="<?php echo $seo_config['replyto'];?>" />
			<meta name="publisher" content="<?php echo $seo_config['publisher'];?>" />
			<meta name="designer" content="<?php echo $seo_config['designer'];?>" />
			<meta name="copyright" content="<?php echo $seo_config['copyright'];?>" />
			<meta name="og:site_name" content="<?php echo $seo_config['site_name'];?>" />
			<meta name="generator" content="<?php echo $seo_config['generator'];?>" />

			<?php if( $seo_config['imagem']): ?>
				<meta property="og:image" content="<?php echo (!empty($this->dados['og_image'])?$this->dados['og_image']:'/arquivos/imagens/seo/'.$seo_config['imagem']);?>" >
			<?php endif; ?>

		<?php endif; ?>
		
		<?php if(!empty($seo['canonical'])): ?>
			<link rel="canonical" href="<?php echo $seo['canonical'];?>" />
		<?php endif; ?>

		<?php if(!empty($seo['title'])): ?>
			<title><?php echo $seo['title'];?></title>
			<meta name="description" content="<?php echo $seo['description'];?>" />
			<meta name="keywords" content="<?php echo $seo['keywords'];?>" />
		<?php else: ?>
			<title><?php echo isset($this->dados['titulo'])? $this->dados['titulo'] : '' ;?></title>
			<meta name="description" content="<?php echo isset($this->dados['descricao'])? $this->dados['descricao'] : (isset($this->dados['titulo'])? $this->dados['titulo'] : '') ;?>" />
		<?php endif; ?>

		<link rel="shortcut icon" type="image/png" href="/arquivos/imagens/favicon.png"/>

		<?php
		if(!empty($header)):
			if(!empty($header['css'])) foreach($header['css'] as $css) echo $css;
		endif; ?>


		<?php 
			// nesse arquivo o jquery e utilizado na admin, necessita sempre na admin esta nesta posicao 
			if($this->pasta_nome == 'admin'):
		?>
			<script src="/arquivos/javascript/jquery.js" type="text/javascript"></script>
		<?php else:?>
			<script src="/arquivos/javascript/jquery.js" type="text/javascript"></script>
		<?php endif; ?>
		
		<?php
		if(!empty($header)):
			if(!empty($header['js_externo'])) foreach($header['js_externo'] as $js_e) echo $js_e;
			if(!empty($header['javascript'])) foreach($header['javascript'] as $js) echo $js;
		endif; ?>

		<?php if($this->pasta_nome != 'admin'):?>
		<?php endif; ?>
		
	</head>
	<body>
<?php if($manutecao['manutecao'] == 1 || $this->pasta_nome == 'admin' || $this->model->logado('admin')): ?>

		<div id="topo">
			<?php
			// Modulos do topo
			if(isset($modulos['topo'])):
				foreach($modulos['topo'] as $modulo) echo $modulo;
			
			 endif;?>
		</div>

		<div id="banner">
			<?php
			// Modulo banner
			if(isset($modulos['banner'])):
				foreach($modulos['banner'] as $modulo) echo $modulo;
			
			 endif;?>
		</div>

		<div id="meio">
			<div class="">
				<div id="posicao_1"><?php
					
					// Modulos da posicao 1
					if(isset($modulos['posicao_1'])):
						foreach($modulos['posicao_1'] as $modulo) echo $modulo;
					endif;
				?></div>
				<div id="posicao_2"><?php
					
					// Modulos da posicao 1
					if(isset($modulos['posicao_2'])):
						foreach($modulos['posicao_2'] as $modulo) echo $modulo;
					endif;
				?></div>
				<div id="conteudo"<?php echo (isset($modulos['posicao_1']) && isset($modulos['posicao_2'])) ? ' class="posicao_1 posicao_2"': (isset($modulos['posicao_1']) ? ' class="posicao_1"' : (isset($modulos['posicao_2']) ? ' class="posicao_2"' : ''));?>><?php
				
					// Modulos do conteudo 1
					if(isset($modulos['conteudo_1'])):
						?><div id="conteudo_1"><?php
						foreach($modulos['conteudo_1'] as $modulo) echo $modulo;
						?></div><?php
					endif;
					
					// Conteudo 2
					?><div id="conteudo_2"><?php echo $conteudo_2; ?></div><?php
					
					// Modulos do conteudo 3
					if(isset($modulos['conteudo_3'])):
						?><div id="conteudo_3"><?php
						foreach($modulos['conteudo_3'] as $modulo) echo $modulo;
						?></div><?php
					endif;
				?></div>
			</div>
		</div>
		
		<div id="rodape">
			<div class="largura_site">
				
				<?php 
				// Modulos do rodape
				if(isset($modulos['rodape'])):?>
					<?php foreach($modulos['rodape'] as $modulo) echo $modulo;?>
				<?php endif;?>
			</div>
		</div>

		
		<?php
		if(!empty($header)):
			if(!empty($header['css_fim'])) foreach($header['css_fim'] as $css) echo $css;
		endif; ?>

		<!--<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">-->
		<link rel="stylesheet" href="/arquivos/css/fontello/css/fontello.css">
		<!-- <link href="<?php //echo $controller->criar_arquivo_comprimido_css('fontello','fontello/css/',true);?>" rel="stylesheet" type="text/css"></link> -->
		<link href="<?php echo $controller->criar_arquivo_comprimido_css('aviso','',true);?>" rel="stylesheet" type="text/css"></link>

		<script src="/arquivos/javascript/jquery.mask.js" type="text/javascript"></script>
		<script src="/arquivos/javascript/template.js" type="text/javascript"></script>
		<script src="/arquivos/javascript/validacao.js" type="text/javascript"></script>
		<script src="/arquivos/javascript/aviso.js" type="text/javascript"></script>

		<script type="text/javascript">
			jQuery(document).ready(function(){
					jQuery(function(){
						var SPMaskBehavior = function (val) {
							return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
						},
						spOptions = {
							onKeyPress: function(val, e, field, options) {
								field.mask(SPMaskBehavior.apply({}, arguments), options);
							}
						};
						jQuery('input[alt="phone"]').mask(SPMaskBehavior,spOptions);

						var SPMaskBehavior2 = function (val) {

							return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
						},
						spOptions2 = {
							onKeyPress: function(val, e, field, options) {
								field.mask(SPMaskBehavior2.apply({}, arguments), options);
							}
						};
						jQuery('input[alt="cpf_cnpj"]').mask(SPMaskBehavior2,spOptions2);


						var pack_mask = {
							'phone_2' : '0000-0000',
							'date' : '00/00/0000',
							'time' : '00:00:00',
							'date_time' : '00/00/0000 00:00:00',
							'cep' : '00000-000',
							'cpf' : '000.000.000-00',
							'cnpj' : '00.000.000/0000-00',
							'number_credit_card' : '0000 0000 0000 0000',
							'month' : '00',
							'year' : '0000',
							'ccv' : '000',
						};
						
						seletor='input[type="text"][alt]';
						jQuery(seletor).map(function() {
							var h = jQuery(this).attr('alt');
							if(pack_mask[h]){
								jQuery(this).mask(pack_mask[h]);
							}
						});
					});
				});
        </script>

		<script>
			jQuery(document).ready(function(){
				<?php if(isset($this->msg["ok"]) && $this->msg["ok"]):?>
					exibirAviso('<?php echo $this->msg["ok"];?>','ok');
					<?php unset($this->msg["ok"]);?>
				<?php endif;?>
				<?php if(isset($this->msg["erro"]) && $this->msg["erro"]):?>
					exibirAviso('<?php echo $this->msg["erro"];?>','erro');
					<?php unset($this->msg["erro"]);?>
				<?php endif;?>
			});
		</script>

	<?php else:?>

		<div class="manutecao_bloco" style="text-align: center;font-size: 25px;margin-top: 20vh;">
			<?=$modulo_10['descricao'];?>
		</div>

	<?php endif; ?>
	
	</body>
</html>