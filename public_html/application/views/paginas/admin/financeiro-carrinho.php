<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Carrinho</div>
                <div class="botoesTopo">
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST">

            <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#laBloco');" >Dados do carrinho</div>
                    <div class="informacao" id="laBloco">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 60%;">
                                    <div class="txtCampo">Cliente</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text"  value="<?php echo ($dados["cliente"]?$dados["cliente"]->nome:'Não cadastrado');?>" disabled >
                                    </div>
                                </div>

                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo">CPF</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text"  value="<?php echo ($dados["cliente"]?$dados["cliente"]->cpf_cnpj: ($dados["cpf_cnpj"]?$dados["cpf_cnpj"]:'Não informado') );?>" disabled >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Produtos do carrinho</div>
                                <!-- <div class="input-div"> -->
                                    <table class="tabela_padrao" id="tabela_carrinho">
                                        <thead>
                                            <tr>
                                                <!-- <td style="width: 30px">
                                                    <input type="checkbox" class="checkall" value="1">
                                                </td> -->
                                                <td>Nome</td>
                                                <td style="width: 100px;">Valor</td>
                                                <td style="width: 100px;">Quantidade</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($dados['historicos'] as $historico):?>
                                                <td><?php echo$historico->nome; ?></td>
                                                <td><?php echo $controller->model->moeda($historico->preco,true);?></td>
                                                <td><?php echo$historico->quantidade; ?></td>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                <!-- </div> -->
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo" id="totalCarrinho">Total: <b class="txt1">R$ <?php echo $controller->model->moeda($dados['valor_total']);?></b> </div>
                            </div>
                        </div>
                    </div>
            </div>
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
    function atualizarCampos(){
        console.log('atualizar campos');
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
    }
    $(document).ready(function(){
        atualizarCampos();
    });
</script>