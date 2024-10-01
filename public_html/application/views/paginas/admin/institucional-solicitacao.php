<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo ($dados["id"]?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/institucional/solicitacoes.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados da solicitação</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo ($dados["id"]?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 77%;">
                                    <div class="txtCampo">Nome 
                                        <?php if(!empty($dados["url"])):?>
                                            <a href="/<?=$dados["url"]?>" target="_blank">(Visualizar página)</a>
                                        <?php endif;?>
                                    </div>
                                    <div class="input-div">
                                        <input disabled class="campo-padrao" type="text" name="nome" value="<?php echo $dados["nome"];?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 20%;">
                                    <div class="txtCampo">Situação</div>
                                    <div class="input-div">
                                        <select name="situacao" class="campo-padrao">
                                            <option value="1" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Email</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" disabled name="email" value="<?php echo $dados["email"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Telefone 1</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" disabled value="<?php echo (isset($dados["telefone1"])?'+ '.$dados["dialCode"].' '.$dados["telefone1"]:"");?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Telefone 2</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" disabled value="<?php echo (isset($dados["telefone2"])?'+ '.$dados["dialCode2"].' '.$dados["telefone2"]:"");?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Produto</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" disabled name="nome_produto" value="<?php echo $dados["nome_produto"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Preço</div>
                                    <div class="input-div">
                                        <input class="campo-padrao dinheiro" type="text" disabled name="preco" value="<?php echo $controller->model->moeda($dados["preco"],true);?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Observações da solicitação</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="observacoes" disabled ><?php echo $dados["observacoes"];?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Observações para o cliente</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="observacoes_cliente" name="observacoes_cliente" ><?php echo $dados["observacoes_cliente"];?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    function form_enviar(){
                        var f = document.form;

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>

            </form>
        </div>
    </div>
</div>

<script>
    function sanfona(elemento){
        $('.informacao').slideUp();
        $(elemento).slideToggle();
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".dinheiro").inputmask('decimal', {
                    'alias': 'numeric',
                    'groupSeparator': '.',
                    'autoGroup': true,
                    'digits': 2,
                    'radixPoint': ",",
                    'digitsOptional': false,
                    'allowMinus': false,
                    'prefix': 'R$ ',
                    'placeholder': '0,00'
        });
    });
</script>

