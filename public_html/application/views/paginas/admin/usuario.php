<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];


    $lista_permissoes = $this->dados["dados"]["permissoes"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["usuario"]->id)?"Dados do Usuário - ID: ".$dados["usuario"]->id:"Adicionar Novo");?></div>
                <div class="botoesTopo">
                    <a onclick="form_enviar()" class="botao_padra_1 btn_verde">Salvar</a>
                    <a href="/admin/usuarios.html" class="botao_padra_1 btn_cinza">Voltar</a>
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco">Dados do usuário</div>
                    <div class="informacao">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["usuario"]->id)?$dados["usuario"]->id:"");?>">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 58%;">
                                <div class="txtCampo">Nome do Usuário</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["usuario"]->nome)?$dados["usuario"]->nome:"");?>" />
                                </div>
                            </div>
                            <div class="linha" style="width: 38%;">
                                <div class="txtCampo">Situação</div>
                                <div class="input-div">
                                    <select name="situacao" class="campo-padrao">
                                        <option value="1" <?php echo (isset($dados["usuario"]->situacao) && $dados["usuario"]->situacao == 1 ?'selected=""':'');?>>Habilitado</option>
                                        <option value="0" <?php echo (isset($dados["usuario"]->situacao) && $dados["usuario"]->situacao == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="-1" <?php echo (isset($dados["usuario"]->situacao) && $dados["usuario"]->situacao == -1 ?'selected=""':'');?>>Excluído</option>
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

                <div class="blocoFormulario">
                    <div class="tituloBloco">Permissões</div>
                    <div class="informacao">

                        <div class="linhaCompleta formulario-pd">
                            <div class="caixa_checkbox">
                                <div class="grupo">

                                    <?php if($lista_permissoes): ?>
                                        <?php foreach($lista_permissoes as $aba => $permissoes): ?>
                                        <div id="bloco_checks" class="item">

                                            <span class="texto"><?php echo $aba;?></span>
                                            <div id="itens_sub" class="item sub">
                                                <?php foreach($permissoes as $permissao): ?>
                                                <label>
                                                    <input name="permissoes[<?php echo $permissao->valor;?>]" class="checkbox" type="checkbox" value="1" <?php echo(isset($dados["usuario"]->permissoes) && in_array($permissao->valor,$dados["usuario"]->permissoes)?'checked':'');?> >
                                                    <span class="texto"><?php echo $permissao->nome;?></span>
                                                </label>
                                                <?php endforeach; ?>
                                            </div>

                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                   
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
                        <?php if(!isset($dados["usuario"]->id) || !$dados["usuario"]->id):?>
                            if(vazio(f.senha.value)){
                                return exibirAviso("Informe a senha do novo usuário!");
                            }
                        <?php endif;?>

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>
            </form>
        </div>
    </div>
</div>
<style>
    .caixa_checkbox {
        margin-left: 14px;
        padding-top: 5px;
        overflow: auto;
    }
    .caixa_checkbox .grupo {
        float: left;
        padding-right: 10px;
    }
    #bloco_checks {
        margin-bottom: 15px;
    }
    .caixa_checkbox .item .item.sub {
        margin-left: 20px;
    }
    .caixa_checkbox .item .texto {
        cursor: pointer;
    }
    .caixa_checkbox .item .item.sub label {
        display: block;
        margin-bottom: 4px;
    }
</style>