<?php
$controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
$modulo_16 = $controller->model_modulo->buscar_modulo(16);
?>
<div class="bloco-cadastrar">
    <h1 class="tituloPadrao textCenter" title="Cadastre sua conta">Cadastre sua conta</h1>

    <form class="blocoform" name="formCadastrar" method="post" autocomplete="off">
        <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <input class="campo-padrao" type="text" placeholder="CPF" name="cpf_cnpj" alt="cpf"  autocomplete="off">
            </div>
        </div>
        <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <input class="campo-padrao" type="text" placeholder="Nome" name="nome">
            </div>
        </div>
        <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <input class="campo-padrao" type="text" placeholder="Email" name="email" autocomplete="off">
            </div>
        </div>
        <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <input class="campo-padrao" type="password" placeholder="Senha" name="senha" autocomplete="off">
            </div>
        </div>
        <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <input class="campo-padrao" type="text" placeholder="Telefone/Celular" name="telefone" alt="phone">
            </div>
        </div>
        <!-- <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <input class="campo-padrao" type="text" placeholder="Celular" name="celular" alt="phone">
            </div>
        </div> -->

        <div class="linhaCompleta">
            <div class="linha" style="width: 100%;">
                <label class="checkboxLabel" >
                    <input type="checkbox" class="checkpadrao" name="concordo" value="1"> <span> Li e aceito os <span style="cursor: pointer;text-decoration: underline;" onclick="abrirPopup_id('popupAlerta');">termos de uso</span> </span>
                </label>
            </div>

            <div class="linha" style="width: 100%;">
                <label class="checkboxLabel" >
                    <input type="checkbox" class="checkpadrao" name="notificacao" value="1"> <span>Receber novidades via E-mail e SMS</span>
                </label>
            </div>
        </div>

        <div class="textCenter" style="margin-top: 10px;">
            <span class="btn-cadastrar" onclick="cadastrar();">Cadastrar</span>
        </div>
    </form>

</div>

<div class="popupFundo1" id="popupAlertaFundo" onclick="fecharPopup_id('popupAlerta');"></div>
                <div class="popupSistema1" id="popupAlerta">
                    <div class="popupInformacoes">
                        <div class="tituloAlerta">Termos de uso</div>
                        <div class="descricaoAlerta">
                            <?php echo $modulo_16['descricao']; ?>
                        </div>
                    </div>

</div>
<style>
    .popupSistema1 {
        width: 620px;
        /* top: 35%; */
        
    }
    .popupInformacoes{
        border: 10px solid #7296ac;
    }

    .tituloAlerta {
        color: #7095ab;
        font-size: 23px;
        font-weight: bold;
        font-family: 'AL Nevrada Personal Use Only Regular';
        text-align: center;
    }
    .descricaoAlerta {
        font-size: 16px;
    }
    .descricaoAlerta p {
        margin: 5px 0;
    }
</style>

<script>
	function cadastrar(){
		var f = document.formCadastrar;

        if(!validarCPF(f.cpf_cnpj.value)){
            return exibirAviso('Informe o CPF corretamente');
        }

        if(vazio(f.nome.value)){
            return exibirAviso('Informe o seu Nome');
        }

        if(!validarEmail(f.email.value)){
            return exibirAviso('Informe o Email corretamente');
        }

        if(vazio(f.senha.value)){
            return exibirAviso('Informe a Senha');
        }

        if(vazio(f.telefone.value) && vazio(f.celular.value)){
            return exibirAviso('Informe o Telefone ou Celular');
        }

        if(!vazio(f.telefone.value)){
            if(!validarTelefone(f.telefone.value)){
                return exibirAviso('Informe o Telefone/Celular corretamente');
            }
        }

        // if(!vazio(f.celular.value)){
        //     if(!validarTelefone(f.celular.value)){
        //         return exibirAviso('Informe o Celular corretamente');
        //     }
        // }

        if(f.concordo.checked == false){
            return exibirAviso('Aceite os termos de uso');
        }

        carregando();

		setTimeout(function(){f.submit();}, 500);
		
	}
</script>

<style>

.bloco-cadastrar {
    background: url(/arquivos/imagens/background-quem-somos.jpg) no-repeat center center/100% 100%;
    padding: 62px 0;
}

.blocoform {
    margin: 0 auto;
    max-width: 370px;
    padding: 20px;
    background: #f1af7b;
}

.checkboxLabel span {
    font-size: 14px;
}
.checkboxLabel {
    display: inline-flex;
    color: #fff;
    cursor: pointer;
}

.checkpadrao {
    border: none !important;
    width: 20px;
    margin-top: 3px;
}

.btn-cadastrar {
    padding: 10px 50px;
    font-size: 17px;
    background: #7095ab;
    color: #fff;
    display: inline-block;
    cursor:pointer;
}
</style>