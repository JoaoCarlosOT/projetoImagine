
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

<!-- <div class="bloco-email">

    <form class="bloco-centro" name="formRecu" method="post">
        <div class="tituloPadrao textCenter"></div>

        <div class="textCenter" style="margin-top: 20px;">
            <a href="/checkout-email.html" title="Voltar para a tela anterior" class="txt1">Voltar para a tela anterior.</a>
        </div>
    </form>

</div> -->



<div class="bloco-layout-2">
    <form class="bloco-centro" name="formRecu" method="post">
        <div class="titulo">Informe seu <?=$nomenclatura_cpf_cnpj?>, enviaremos pelo email associado um token de acesso para a alteração</div>

        <fieldset>
            <legend>Recuperar senha</legend>
            <div class="linha linha-2">
                <div class="campo">
                    <div class="txtCampo">Digite aqui seu <?=$nomenclatura_cpf_cnpj?></div>
                    <input type="text" name="cpf_cnpj" class="inputbox campo-padrao" placeholder="" alt="cpf_cnpj">
                </div> 
            </div> 

        </fieldset>

        <div class="botao-right">
            <span class="btn-conta" onclick="enviar_form();">Continuar</span>
        </div>
    </form>
</div>




<script>

function enviar_form(){
    var f = document.formRecu;

    if(!validarCPFCNPJ(f.cpf_cnpj.value)){
        return exibirAviso('Informe o CPF corretamente');
    }

    carregando();
	setTimeout(function(){f.submit();}, 500);
}

</script>
