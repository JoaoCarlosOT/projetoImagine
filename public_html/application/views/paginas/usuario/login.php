<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
	<div class="meio-componente">
		<div class="panel-login">
			<div class="panel-head">
                <div class="logomarca text-center">
                    <img src="/arquivos/imagens/logo-essence.png">
                </div>
            </div>
			<div class="panel-body">
				<form class="formulario" name="com_form" method="POST" action="">
					<div class="txt-p-head">Acesso Usúario</div>
                    <div class="linha">
                        <div class="txtCampo">Usuário</div>
                        <div class="input-div">
                            <input class="campo-padrao" type="text" name="usuario">
                        </div>
                    </div>
                    <div class="linha">
                        <div class="txtCampo">Senha</div>
                        <div class="input-div">
                            <input class="campo-padrao" type="password" name="senha">
                        </div>
                    </div>

					<div class="text-right">
						<span class="botao_padra_1" style="display: inline-flex;" onclick="fn_com_form();">Acessar</span>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
function fn_com_form(){
	var f = document.com_form;
	if(!f.usuario.value) return exibirAviso('Informe seu usuário!');
	if(!f.senha.value) return exibirAviso('Informe sua senha!');
	carregando();
	setTimeout(function(){f.submit();}, 500);
}
<?php if($mensagem = $controller->mensagem_aviso()): 
	$mensagem = explode('#', $mensagem); ?>
	jQuery(document).ready(function(){ 
		exibirAviso('<?php echo $mensagem[0]; ?>', '<?php echo $mensagem[1]; ?>');
	});
<?php endif; ?>
</script>