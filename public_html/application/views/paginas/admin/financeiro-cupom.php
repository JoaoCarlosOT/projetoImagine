<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["codigo_cupom"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/financeiro/cupons.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do cupom</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 70%;">
                                <div class="txtCampo">Código cupom</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="codigo_cupom" value="<?php echo (isset($dados["codigo_cupom"])?$dados["codigo_cupom"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 20%; <?php echo (!isset($dados["id"])?'display:none;':"");?> ">
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
                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Tipo de desconto</div>
                                <div class="input-div">
                                    <select name="desconto_tipo" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["desconto_tipo"]) && $dados["desconto_tipo"] == 0 ?'selected=""':'');?>>Fixo</option>
                                        <option value="1" <?php echo (isset($dados["desconto_tipo"]) && $dados["desconto_tipo"] == 1 ?'selected=""':'');?>>Percentual</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Valor</div>
                                <div class="input-div">
                                    <!-- <input class="campo-padrao dinheiro" type="text" name="valor" value="<?php echo number_format($dados["valor"], 2, ',', '');?>" style="text-align: right;"> -->
                                    <input class="campo-padrao" type="number" name="valor" value="<?php echo (isset($dados["valor"])?$dados["valor"]:"");?>">
                                </div>
                            </div>

                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Data de validade</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="date" name="data_validade" value="<?php echo (isset($dados["data_validade"])?$dados["data_validade"]:"");?>">
                                </div>
                            </div>
                        </div> 

                        <?php if(isset($dados["id"]) && $dados["cupom_utilizado"]):?>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Cupom utilizado</div>
                                <div class="input-div">
                                    <table class="tabela_padrao" style="width: ;">
                                        <thead>
                                            <tr>
                                                <td style="width: 150px;">Nome</td>
                                                <td style="width: 150px;">Data</td>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                            <?php foreach($dados["cupom_utilizado"] as $cupom_utilizado):?>
                                                <tr>
                                                    <td><?=$cupom_utilizado['nome_cliente']?></td>
                                                    <td><?= date('d/m/Y \à\s H:i',strtotime($cupom_utilizado['cadastro']));?></td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>


                        <script>
                            validarPorcentagem()

                            function validarPorcentagem(){
                                var desconto_tipo = $("select[name='desconto_tipo']").val();
                                var valor = $("input[name='valor']").val();
                                
                                if(desconto_tipo == 1){
                                    if(valor > 100){

                                    }
                                }else{

                                }
                            }
                        </script>

                    </div>
                </div> 

                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(f.desconto_tipo.value == 1) f.valor.value = parseInt(f.valor.value);

                        if(vazio(f.codigo_cupom.value)){
                            return exibirAviso('Informe o Código cupom!');
                        }

                        if(vazio(f.valor.value) || f.valor.value == 0){
                            return exibirAviso('Informe o Valor!');
                        }

                        if(f.desconto_tipo.value == 1 && f.valor.value > 100){
                            return exibirAviso('Informe o valor sendo maior que 0 e menor ou igual a 100!');
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
.cke_chrome{
    width: 100% !important;
}
</style>
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