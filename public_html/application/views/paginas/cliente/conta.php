<?php
$cliente = $this->dados["dados"]["cliente"];

$controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
$modulo_16 = $controller->model_modulo->buscar_modulo(16);

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
<div class="bloco-op-conta">
    <div class="container">
        <div class="blocoIconesInicio">
            <div class="tituloPadrao textCenter">Bem-vindo(a) ao seu ambiente</div>

            <div class="blocos"> 
                <div class="blocoslistasFormularios">

                    <div class="bloco-formulario">
                        <a href="/minha-conta.html" class="btn-voltar-mobile">Voltar</a>

                        <div class='bloco-titulo-form'>Informações cadastrais</div>
                        <form class="blocoform" name="formAtualizar" method="post">
                            <div class="linhaCompleta">
                                <div class="linha" style="width: 100%;">
                                <div class="txtCampo"><?=$nomenclatura_cpf_cnpj?></div>
                                    <input class="campo-padrao" type="text" placeholder="" name="cpf_cnpj" alt="cpf_cnpj" value="<?php echo $cliente->cpf_cnpj;?>">
                                </div>
                            </div>
                            <div class="linhaCompleta">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Nome</div>
                                    <input class="campo-padrao" type="text" placeholder="" name="nome" value="<?php echo $cliente->nome;?>">
                                </div>
                            </div>
                            <div class="linhaCompleta">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Email</div>
                                    <input class="campo-padrao" type="text" placeholder="" name="email" autocomplete="off" value="<?php echo $cliente->email;?>">
                                </div>
                            </div>
                            <div class="linhaCompleta">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Senha</div>
                                    <input class="campo-padrao" type="password" placeholder="" name="senha" autocomplete="off" value="">
                                </div>
                            </div>
                            <div class="linhaCompleta">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Confirme a Senha</div>
                                    <input class="campo-padrao" type="password" placeholder="" name="senha_confirme" autocomplete="off" value="">
                                </div>
                            </div>
                            <div class="linhaCompleta">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Telefone/Celular</div>
                                    <input class="campo-padrao" type="text" placeholder="" name="telefone" alt="phone" value="<?php echo $cliente->telefone;?>">
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
                                            <input type="checkbox" class="checkpadrao" name="concordo" checked="" value="1"> <span> Li e aceito os <span style="cursor: pointer;text-decoration: underline;" onclick="abrirPopup_id('popupAlerta');">termos de uso</span> </span>
                                        </label>
                                    </div>

                                    <div class="linha" style="width: 100%;">
                                        <label class="checkboxLabel" >
                                            <input type="checkbox" class="checkpadrao" <?php echo($cliente->notificacao == 1?'checked=""':''); ?> name="notificacao" value="1"> <span>Receber novidades via E-mail e SMS</span>
                                        </label>
                                    </div>
                            </div>

                            <div class="textCenter" style="margin-top: 10px;">
                                <span class="btn-cadastrar" onclick="salvar_dados();">Salvar</span>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>
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
            function salvar_dados(){
                var f = document.formAtualizar;

                if(!<?=$funcao_validacao_cpf_cnpj?>(f.cpf_cnpj.value)){
                    return exibirAviso('Informe o <?=$nomenclatura_cpf_cnpj?> corretamente');
                }

                if(vazio(f.nome.value)){
                    return exibirAviso('Informe o seu Nome');
                }

                if(!validarEmail(f.email.value)){
                    return exibirAviso('Informe o Email corretamente');
                }

                if(!vazio(f.senha.value)){
                    if(vazio(f.senha_confirme.value)){
                        return exibirAviso('Informe sua confirmação de senha');
                    }

                    if(f.senha.value != f.senha_confirme.value){
                        return exibirAviso('Senhas diferentes!');
                    }
                }
                

                // if(vazio(f.telefone.value) && vazio(f.celular.value)){
                //     return exibirAviso('Informe o Telefone ou Celular');
                // }

                // if(!vazio(f.telefone.value)){
                    if(!validarTelefone(f.telefone.value)){
                        return exibirAviso('Informe o Telefone/Celular corretamente');
                    }
                // } 

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
<script>
    jQuery(document).ready(function(){
        oculto_bloco_responsavel();
    });
    jQuery('#data_nascimento').change(function(){
        oculto_bloco_responsavel();
    });

    function oculto_bloco_responsavel(){

        var data_nascimento = jQuery('#data_nascimento').val();

        if(verificar_idade(data_nascimento) < 18){
            jQuery('#blocoResponsavel').css('display','block');
        }else{
            jQuery('#blocoResponsavel').css('display','none');
        }

    }

    function verificar_idade(data_nasicmento){
        
        var d = new Date,
        ano_atual = d.getFullYear(),
        mes_atual = d.getMonth() + 1,
        dia_atual = d.getDate(),
        split = data_nasicmento.split('-'),

        ano_aniversario = + split[0],
        mes_aniversario = + split[1],
        dia_aniversario = + split[2],

        quantos_anos = ano_atual - ano_aniversario;

        if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
            quantos_anos--;
        }

        return (quantos_anos > 0? quantos_anos : 0 );
    }
</script>