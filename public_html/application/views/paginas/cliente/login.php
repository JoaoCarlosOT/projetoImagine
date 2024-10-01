<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

$configuracao_checkout = $this->dados["dados"]["buscar_configuracao"];

if($configuracao_checkout['cad_cli_cpf_cnpf'] == 2){
    $nomenclatura_cpf_cnpj = "CPF/CNPJ";
    $funcao_validacao_cpf_cnpj = "validarCPFCNPJ";
    $alt_cpf_cnpj = "cpf_cnpj";
}else if($configuracao_checkout['cad_cli_cpf_cnpf'] == 1){
    $nomenclatura_cpf_cnpj = "CNPJ";
    $funcao_validacao_cpf_cnpj = "validarCNPJ";
    $alt_cpf_cnpj = "cnpj";
}else{
    $nomenclatura_cpf_cnpj = "CPF";
    $funcao_validacao_cpf_cnpj = "validarCPF";
    $alt_cpf_cnpj = "cpf";
}

?>
<div class="<?php echo $controller->_classes_pagina(); ?>">

	<div class="container">
        <div class="blocos">
            <div class="bloco">
                <div class="panel-login">
                    <div class="panel-body">
                        <form class="formulario" name="com_form" method="POST" action="">
                            <div class="panel-head">
                                <div class="logomarca-login">
                                    <img src="/arquivos/imagens/logo.png" alt="logo" title="logo">
                                </div>
                            </div>
                            <div class="txt-p-head">Acesse sua conta</div>
                            <div class="linha">
                                <div class="txtCampo">Login</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="usuario" alt="cpf_cnpj" placeholder="Insira seu <?=$nomenclatura_cpf_cnpj?>">
                                </div>
                            </div>
                            <div class="linha">
                                <div class="txtCampo">Senha</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="password" name="senha">
                                </div>
                            </div>
                            <div class="linha textRight esqueceuSenha">
                                <a href="/checkout-recuperar.html" title="Esqueceu sua senha" class="txt1">Esqueceu sua senha?</a>
                            </div>

                            <div class="linha">
                                <span class="botao_padra_1" onclick="fn_com_form();">Entrar</span>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>
            <div class="bloco imagem">
                <div class="base-img">
                    <img src="/arquivos/imagens/cliente/login.jpg" alt="">
                </div>
            </div>
        </div>
	</div>
</div>
<style>
.login{
    position: relative;
    /* padding: 20px 0; */
}
.login .blocos{
    display: flex;
    align-items: center;
}
.login .blocos .bloco{
    width: 50%;
}
.login .blocos .bloco.imagem img{
    width: 100%;
}
.login .blocos .bloco.imagem .base-img{
    position: relative;
}
.login .blocos .bloco.imagem .base-img:after{
    content: "";
    width: 100%;
    height: 100%;
    background: rgba(254, 117, 27, 0.4);
    position: absolute;
    top: 0;
    left: 0;
}
.linha {
    margin-bottom: 15px;
}
.panel-login {
    border: none;
    background-color: #fff;
    max-width: 480px;
    margin: 0 auto;
    -webkit-box-shadow: none;
    box-shadow: none;
}
@media(max-width: 992px){
    .login .blocos{
        flex-direction: column;
    }
    .login .blocos .bloco{
        width: 100%;
    }
}
</style>
<script>
function fn_com_form(){
	var f = document.com_form;
	if(!validarCPFCNPJ(f.usuario.value)) return exibirAviso('Informe seu CPF/CNPJ corretamente!');
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