<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Minha Conta</div>
                <div class="botoesTopo">
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco">Dados da conta</div>
                    <div class="informacao">
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 58%;">
                                <div class="txtCampo">Nome do Usuário</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nome"  value="<?php echo (isset($dados["usuario"]->nome)?$dados["usuario"]->nome:"");?>" />
                                </div>
                            </div>
                            <div class="linha" style="width: 38%;">
                                <div class="txtCampo">Situação</div>
                                <div class="input-div">
                                    <select name="situacao" class="campo-padrao">
                                        <option value="1" <?php echo (isset($dados["usuario"]->situacao) && $dados["usuario"]->situacao == 1 ?'selected=""':'');?>>Habilitado</option>
                                        <option value="0" <?php echo (isset($dados["usuario"]->situacao) && $dados["usuario"]->situacao == 0 ?'selected=""':'');?>>Desabilitado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="email" value="<?php echo (isset($dados["usuario"]->email)?$dados["usuario"]->email:"");?>" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco">Dados de acesso</div>
                    <div class="informacao">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 50%;">
                                <div class="txtCampo">Usuário</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="usuario" value="<?php echo (isset($dados["usuario"]->usuario)?$dados["usuario"]->usuario:"");?>" />
                                </div>
                            </div>
                            <div class="linha" style="width: 46%;">
                                <div class="txtCampo">Senha</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="password" name="senha" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.nome.value)){
                            return exibirAviso("Informe o nome do usuário!","erro");
                        }
                        if(vazio(f.usuario.value)){
                            return exibirAviso("Informe o usuário!");
                        }

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>

            </form>
        </div>
    </div>
</div>