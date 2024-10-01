<?php
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
<style>
.bg-email{
    display: flex;
    flex-wrap: wrap;
}
.bloco-email .bloco-centro {
    max-width: 700px;
    margin: 0 auto;
}

.bloco-email .bloco-centro .input_e_btn {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    max-width: 510px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 40px;
}
.bloco-email .bloco-centro .campo-padrao {
    width: 62%;
    color: #7095ab;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
}

.bloco-email .bloco-centro .btn-email {
    padding: 10px 0px;
    margin: 0 auto;
    width: 38%;
    text-align: center;
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
}

.bloco-email {
    /* padding: 62px 10px; */
    min-height: 300px;
    margin: 62px auto;
}

.bloco-email .bloco-centro {
    background: #fff;
    padding: 20px 20px;
}

.bloco-email .img {
    text-align: center;
    margin: 40px 0;
}

.bloco-email .img img {
    width: 120px;
    margin-bottom: 9px;
}
.bloco-email .txt1 {
    color: #7095ab;
    text-decoration: underline;
    font-size: 15px;
}

.input_e_btn2 {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 10px;
    max-width: 510px;
    margin-left: auto;
    margin-right: auto;
}
@media(max-width:767px){
    .boletim-footer {
        display: none;
    }
}
</style>
<div class="bg-email">
<div class="bloco-email">

    <form class="bloco-centro" name="formLogin" method="post">
        <div class="img"><img src="/arquivos/imagens/logo.png" title="checkout email"></div>
        <div class="tituloPadrao textCenter">Para continuar, informe seu <?=$nomenclatura_cpf_cnpj?></div>

        <div class="input_e_btn">
            <input type="text" name="cpf_cnpj" class="inputbox campo-padrao" placeholder="Digite aqui seu <?=$nomenclatura_cpf_cnpj?>" alt="cpf_cnpj">
            <span class="btn btn-email" onclick="verificar_usuario();">Continuar</span>
        </div>

        <div class="input_e_btn2" style="display: none;">
            <input type="password" name="senha" class="inputbox campo-padrao" placeholder="Senha" >
            <span class="btn btn-email" onclick="login();">Login</span>
        </div>

        <div class="textCenter" style="margin-top: 20px;">
            <?php /*<a href="/checkout-cadastrar.html" class="txt1" title="Ainda não é cliente?">Ainda não é cliente? Cadastre sua conta.</a>*/ ?>
            <a href="/checkout-recuperar.html" title="Esqueceu sua senha" class="txt1">Esqueceu sua senha, clique aqui.</a>
        </div>
    </form>

</div>
</div>

<script>

function verificar_usuario(){
    var f = document.formLogin;

    if(!validarCPFCNPJ(f.cpf_cnpj.value)){
        return exibirAviso('Informe o CPF/CNPJ corretamente');
    }

    carregando();

    jQuery.ajax({
        url: '/cliente/Cliente_controller_cliente/verificar_usuario_ajax',
        type: 'GET',
        dataType: 'json',
        data: {
            query: f.cpf_cnpj.value
        },
        error: function() {
        },
        success: function(res) {
            
            if(res['retorno'] == 1){
                jQuery('.input_e_btn .btn-email').css('display','none');
                jQuery('.input_e_btn input').css('width','100%');
                jQuery('.input_e_btn2').css('display','flex');
            }else{
                if(!<?=$funcao_validacao_cpf_cnpj?>(f.cpf_cnpj.value)){
                    carregado()
                    return exibirAviso('<?=$nomenclatura_cpf_cnpj?> Inválido, Favor cadastrar um <?=$nomenclatura_cpf_cnpj?> válido');
                } else{
                    window.location.href = "/checkout.html";
                }
            }

            carregado();
        }
    });
}

function login(){
    var f = document.formLogin;

    if(!validarCPFCNPJ(f.cpf_cnpj.value)){
        return exibirAviso('Informe o CPF/CNPJ corretamente');
    }

    if(vazio(f.senha.value)){
        return exibirAviso('Informe a Senha');
    }

    carregando();
	setTimeout(function(){f.submit();}, 500);
}

</script>