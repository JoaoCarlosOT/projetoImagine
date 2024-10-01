<div class="bloco-layout-2">
    <form class="bloco-centro" name="formRecu" method="post">
        <div class="titulo">Redefinir senha</div>

        <fieldset>
            <legend>NOVA SENHA</legend>
            <div class="linha linha-2">
                <div class="campo">
                    <div class="txtCampo">Nova senha</div>
                    <input type="password" name="senha" class="inputbox campo-padrao" style="width: 100%;" placeholder="">
                </div>

                <div class="campo">
                    <div class="txtCampo">Confirme a nova senha</div>
                    <input type="password" name="senha_confirme" class="inputbox campo-padrao" placeholder="">
                </div>
            </div> 

            <input type="hidden" value="<?php echo $this->dados["dados"]["token"]; ?>" name="token">
        </fieldset>

        <div class="botao-right">
            <span class="btn-conta" onclick="enviar_form();">Alterar</span>
        </div>
    </form>
</div>

<script>

function enviar_form(){
    var f = document.formRecu;

    if(vazio(f.senha.value)){
        return exibirAviso('Informe sua senha');
    }

    if(vazio(f.senha_confirme.value)){
        return exibirAviso('Informe sua confirmação da nova senha');
    }

    if(f.senha.value != f.senha_confirme.value){
        return exibirAviso('Senhas diferentes!');
    }

    carregando();
	setTimeout(function(){f.submit();}, 500);
}

</script>
