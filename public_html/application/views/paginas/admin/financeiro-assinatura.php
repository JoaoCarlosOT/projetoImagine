<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    if($dados['status_asaas'] == 'ACTIVE'){
        $status = 'Ativado';
    }else{
        $status = 'Desativado';
    }
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/financeiro/assinaturas.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <?php if(isset($dados["cancelado"]) && $dados["cancelado"] == 0):?>
                        <a href="/admin/financeiro/assinatura-cancelar/<?=$dados["id"]?>.html" class="botao_padra_1 btn_vermelho" onclick="return confirmarCancelar()">Cancelar assinatura</a>
                    <?php endif;?>
                    <!-- <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a> -->
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
        <?php if(isset($dados["cancelado"]) && $dados["cancelado"] == 1):?>
            <h2 style="background: #f53c3c;text-align: center;color: #fff;padding: 10px;">Assinatura Cancelada</h2>
        <?php endif;?>
            
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados da Assinatura</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 70%;">
                                <div class="txtCampo">Assinatura</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Status</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="" value="<?=$controller->asaas->getStatusMappingAssinatura(!empty($dados['status_asaas'])?$dados['status_asaas']:"")?>" >
                                </div>
                            </div>
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Cliente</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="" value="<?php echo (isset($dados["cliente"]["nome"])?$dados["cliente"]["nome"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">CPF/CNPJ</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="" value="<?php echo (isset($dados["cliente"]["cpf_cnpj"])?$dados["cliente"]["cpf_cnpj"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 30%;">
                                <div class="txtCampo">Cliente</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="" value="<?php echo (isset($dados["cliente"]["email"])?$dados["cliente"]["email"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Telefone</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="" value="<?php echo (isset($dados["cliente"]["telefone"])?$dados["cliente"]["telefone"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Celular</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="" value="<?php echo (isset($dados["cliente"]["celular"])?$dados["cliente"]["celular"]:"");?>" >
                                </div>
                            </div>
                        </div> 

                        <div class="linhaCompleta formulario-pd"> 
                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Frequência cobrança da assinatura</div>
                                <div class="input-div">
                                    <select name="frequencia_assina" class="campo-padrao">
                                        <?php foreach($controller->asaas->getCicloAssinatura() as $key => $ciclo):?>
                                            <option value="<?=$key?>" <?php echo (isset($dados["frequencia_assina"]) && $dados["frequencia_assina"] == $key ?'selected=""':'');?>><?=$ciclo?></option> 
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div> 

                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Valor</div>
                                <div class="input-div">
                                    <input class="campo-padrao dinheiro" type="text" name="valor_total" value="<?php echo number_format($dados["valor_total"], 2, ',', '');?>" style="text-align: right;">
                                </div>
                            </div> 

                            <div class="linha" style="width: 30%;">
                                <div class="txtCampo">Data</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="datetime-local" name="cadastro" value="<?php echo $dados["cadastro"];?>">
                                </div>
                            </div> 
                        </div> 

                        <?php if(!empty($dados["lancamentos"])):?>
                            <div class="linhaCompleta formulario-pd"> 
                                <div class="linha" style="width: 100%;">
                                    <!-- <div class="txtCampo">Lançamentos</div> -->
                                    <table class="tabela_padrao">
                                        <thead>
                                            <tr>
                                                <th colspan="5" style="font-size: 20px;padding: 8px 5px;">Lançamentos</th>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td style="width: 100px;">Valor</td>
                                                <td>Data Vencimento</td>
                                                <td>Data Pagamento</td>
                                                <td>Data Cadastro</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($dados["lancamentos"] as $lancamento):
                                                $situacao = "";
                                                if($lancamento['data_pagamento']):
                                                    $situacao = "pag_verde";
                                                elseif(strtotime($lancamento['data_vencimento']) < strtotime(date('Y-m-d'))):
                                                    $situacao = "pag_laranja";
                                                else:
                                                    $situacao = "pag_cinza";
                                                endif;

                                                if($lancamento['status_asaas'] == 'RECEIVED' || $lancamento['status_asaas'] == 'CONFIRMED'){
                                                    $status = 'Pagemento confirmado';
                                                }else if($lancamento['status_asaas'] == 'PENDING'){
                                                    $status = 'Aguardando pagamento';
                                                }else{
                                                    $status = 'Outros';
                                                } 
                                                ?>
                                                <tr>
                                                    <td class="<?=$situacao?>"><?=$status?></td>
                                                    <td><?=$controller->model->moeda($lancamento['valor_total'],true)?></td>
                                                    <td><?php echo $lancamento['data_vencimento']?date('d/m/Y',strtotime($lancamento['data_vencimento'])):"";?></td>
                                                    <td><?php echo $lancamento['data_pagamento']?date('d/m/Y',strtotime($lancamento['data_pagamento'])):"";?></td>
                                                    <td><?php echo date('d/m/Y',strtotime($lancamento['cadastrado']));?></td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                        <?php endif;?>

                        <?php if(isset($dados["cancelado"]) && $dados["cancelado"] == 1):?>
                            <div class="linhaCompleta formulario-pd"> 
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Log</div>
                                    <div class="input-div">
                                        <?php echo $dados["log_texto"];?>
                                    </div>
                                </div> 
                            </div>
                        <?php endif;?>
                    </div>
                </div> 
                
                <script>
                    function confirmarCancelar() {
                        var resposta = confirm("Tem certeza que deseja cancelar a assinatura?");
                        if (resposta) {
                            return true;
                        } else {
                            return false;
                        }
                    }

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