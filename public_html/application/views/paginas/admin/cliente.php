<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (!empty($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/clientes.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do Cliente</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (!empty($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 70%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (!empty($dados["nome"])?$dados["nome"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 12%; <?php echo (!isset($dados["id"])?'display:none;':"");?> ">
                                    <div class="txtCampo">Situação</div>
                                    <div class="input-div">
                                        <select name="situacao" class="campo-padrao">
                                            <option value="1" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                            <option value="-1" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == -1 ?'selected=""':'');?>>Excluído</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">CPF</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="cpf_cnpj" value="<?php echo (!empty($dados["cpf_cnpj"])?$dados["cpf_cnpj"]:'');?>" alt="cpf_cnpj">
                                    </div>
                                </div>

                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Senha</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="password" name="senha" >
                                    </div>
                                </div>

                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 38%;">
                                    <div class="txtCampo">Email</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email" value="<?php echo (!empty($dados["email"])?$dados["email"]:'');?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 28%;">
                                    <div class="txtCampo">Telefone</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="telefone" alt="phone" value="<?php echo (!empty($dados["telefone"])?$dados["telefone"]:'');?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 28%;">
                                    <div class="txtCampo">Celular</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="celular" alt="phone" value="<?php echo (!empty($dados["celular"])?$dados["celular"]:'');?>">
                                    </div>
                                </div>

                        </div>
                        

                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">Informações Extras</div>
                    <div class="informacao" id="bloco2" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">RG</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="rg" value="<?php echo (!empty($dados["rg"])?$dados["rg"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo">Data de Nacimento</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="date" name="data_nascimento" value="<?php echo (!empty($dados["data_nascimento"])?$dados["data_nascimento"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 20%;">
                                    <div class="txtCampo">Sexo</div>
                                    <div class="input-div">
                                        <select name="sexo" class="campo-padrao">
                                            <option value="M" <?php echo (isset($dados["sexo"]) && $dados["sexo"] == 'M' ?'selected=""':'');?>>Masculino</option>
                                            <option value="F" <?php echo (isset($dados["sexo"]) && $dados["sexo"] == 'F' ?'selected=""':'');?>>Feminino</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Responsável</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="responsavel" value="<?php echo (!empty($dados["responsavel"])?$dados["responsavel"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 20%;">
                                    <div class="txtCampo">CPF do Responsável</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="responsavel_cpf" value="<?php echo (!empty($dados["responsavel_cpf"])?$dados["responsavel_cpf"]:'');?>" alt="cpf">
                                    </div>
                                </div>

                                <div class="linha" style="width: 12%;">
                                    <div class="txtCampo">Receber Email</div>
                                    <div class="input-div">
                                        <select name="receber_email" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["receber_email"]) && $dados["receber_email"] == 0 ?'selected=""':'');?>>Não</option>
                                            <option value="1" <?php echo (isset($dados["receber_email"]) && $dados["receber_email"] == 1 ?'selected=""':'');?>>Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="linha" style="width: 12%;">
                                    <div class="txtCampo">Receber SMS</div>
                                    <div class="input-div">
                                        <select name="receber_sms" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["receber_sms"]) && $dados["receber_sms"] == 0 ?'selected=""':'');?>>Não</option>
                                            <option value="1" <?php echo (isset($dados["receber_sms"]) && $dados["receber_sms"] == 1 ?'selected=""':'');?>>Sim</option>
                                        </select>
                                    </div>
                                </div>
                        </div>


                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco3');">Endereço</div>
                    <div class="informacao" id="bloco3" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo">CEP</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="cep" value="<?php echo (!empty($dados["cep"])?$dados["cep"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Logradouro</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="logradouro" value="<?php echo (!empty($dados["logradouro"])?$dados["logradouro"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 20%;">
                                    <div class="txtCampo">Número</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="numero" value="<?php echo (!empty($dados["numero"])?$dados["numero"]:'');?>" >
                                    </div>
                                </div>
                        </div>
                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo">Complemento</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="complemento" value="<?php echo (!empty($dados["complemento"])?$dados["complemento"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo">Bairro</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="bairro" value="<?php echo (!empty($dados["bairro"])?$dados["bairro"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 20%;">
                                    <div class="txtCampo">Municipio</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="municipio" value="<?php echo (!empty($dados["municipio"])?$dados["municipio"]:'');?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 10%;">
                                    <div class="txtCampo">UF</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="uf" value="<?php echo (!empty($dados["uf"])?$dados["uf"]:'');?>" >
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.nome.value)){
                            return exibirAviso('Informe o nome!');
                        }

                        if(!validarCPFCNPJ(f.cpf_cnpj.value)){
                            return exibirAviso('Informe o CPF/CNPJ corretamente!');
                        }

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>

            </form>
        </div>
    </div>
</div>
<style>
.icone-td {
    font-size: 18px;
    color: #113075;
    cursor: pointer;
    margin: 11px 5px;
    padding-top: 20px;
}
.anexos-salvos{
    display: flex;
    flex-wrap: wrap;
}
.img-anexo {
    position: relative;
    display: flex;
    border: 1px solid #bbb9b9;
    width: 210px;
    padding: 2px 6px;
    margin: 0 4px;
}
</style>
<script>
    function sanfona(elemento){
        $('.informacao').slideUp();
        $(elemento).slideToggle();
    }
</script>

