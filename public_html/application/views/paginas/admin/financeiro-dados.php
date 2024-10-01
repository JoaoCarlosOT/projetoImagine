<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
    $relatorio = $this->dados["dados"]["relatorio"];
    $args = $this->dados["dados"]["args"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Financeiro</div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
                <div class="blocoConteudo-no">
                    <div style="width: 100%;">
                        <div class="filtroConteudo blocoCaixaSty">
                        
                            <div>
                                <form method="POST" action="" name="form_filtro" class="filtroUmaLinha">
                                    <select class="campo_padrao" name="id_cliente" id="id_cliente" style="width:28%;">
                                        <option value="">Cliente</option>
                                    </select>

                                    <select class="campo_padrao" name="tipo_data" style="width:200px;">
                                        <option value="1" <?php echo (isset($args["tipo_data"]) && $args["tipo_data"] == 1?'selected=""':''); ?>>Data de vencimento</option>
                                        <option value="2" <?php echo (isset($args["tipo_data"]) && $args["tipo_data"] == 2?'selected=""':''); ?>>Data de pagamento</option>
                                        <option value="3" <?php echo (isset($args["tipo_data"]) && $args["tipo_data"] == 3?'selected=""':''); ?>>Data de cadastro</option>
                                    </select>

                                    <input class="campo_padrao" type="date" name="data_ini" placeholder="Data início" style="width: 150px;" value="<?php echo (isset($args["data_ini"])?$args["data_ini"]:''); ?>">

                                    <input class="campo_padrao" type="date" name="data_ate" placeholder="Data até" style="width: 150px;" value="<?php echo (isset($args["data_ate"])?$args["data_ate"]:''); ?>"> 


                                    <!-- <select class="campo_padrao" name="mes" id="mes" style="width: 10%;">
                                        <?php
                                            $meses = array(
                                                "01" => 'Janeiro',
                                                "02" => 'Fevereiro',
                                                "03" => 'Março',
                                                "04" => 'Abril',
                                                "05" => 'Maio',
                                                "06" => 'Junho',
                                                "07" => 'Julho',
                                                "08" => 'Agosto',
                                                "09" => 'Setembro',
                                                "10" => 'Outubro',
                                                "11" => 'Novembro',
                                                "12" => 'Dezembro'
                                            );

                                        $mes_selected = $args["mes"];
                                        ?>
                                        <option value="0" <?php echo ($mes_selected == 0 ?'selected=""':''); ?> >Todos</option>
                                        <?php
                                        foreach($meses as $num => $mes):
                                        ?>
                                            <option value="<?php echo $num; ?>" <?php echo ($mes_selected == $num ?'selected=""':''); ?> ><?php echo $mes; ?></option>
                                        <?php 
                                        endforeach; 
                                        ?>
                                    </select>
                                    <select class="campo_padrao" name="ano" id="ano" style="width: 8%;">
                                        <?php

                                        $ano_ini = (int) date('Y', strtotime('2000-01-01'));
                                        $ano_fim = (int) date('Y',strtotime('now +2 years'));

                                        $ano_selected = $args["ano"];
                                        ?>
                                        <option value="0" <?php echo ($ano_selected == 0 ?'selected=""':''); ?> >Todos</option>
                                        <?php
                                        for($ano = $ano_fim; $ano >= $ano_ini; $ano--):
                                        ?>
                                            <option value="<?php echo$ano;?>"  <?php echo ($ano_selected == $ano ?'selected=""':''); ?> > <?php echo$ano;?></option>
                                        <?php 
                                        endfor; 
                                        ?>
                                    </select> -->

                                    <select class="campo_padrao" style="width:9%;" name="assinatura">
                                        <option value="">Assinatura</option>
                                        <option value="0" <?php echo (isset($args["assinatura"]) && $args["assinatura"] == 0?'selected=""':"");?>>Não</option>
                                        <option value="1" <?php echo (isset($args["assinatura"]) && $args["assinatura"] == 1?'selected=""':"");?>>Sim</option>
                                    </select>

                                    <select class="campo_padrao" style="width:14%;" name="forma_pagamento">
                                        <option value="">Forma de pagamento</option> 
                                        <option value="1" <?php echo (isset($args["forma_pagamento"]) && $args["forma_pagamento"] == 1?'selected=""':"");?>>Pix</option>
                                        <option value="2" <?php echo (isset($args["forma_pagamento"]) && $args["forma_pagamento"] == 2?'selected=""':"");?>>Boleto</option>
                                        <option value="3" <?php echo (isset($args["forma_pagamento"]) && $args["forma_pagamento"] == 3?'selected=""':"");?>>Cartáo de crédito</option>
                                    </select> 

                                    <select class="campo_padrao" style="width:9%;" name="status">
                                        <option value="">Status</option>
                                        <option value="1" <?php echo (isset($args["status"]) && $args["status"] == 1?'selected=""':"");?>>Pagemento confirmado</option>
                                        <option value="2" <?php echo (isset($args["status"]) && $args["status"] == 2?'selected=""':"");?>>Aguardando pagamento</option>
                                        <option value="3" <?php echo (isset($args["status"]) && $args["status"] == 3?'selected=""':"");?>>Outros</option>
                                    </select>

                                    <input type="hidden" name="situacao">

                                    <span class="btn_filtro" onclick="filtro()">Buscar</span>
                                </form>
                                <script>
                                    function filtro(){
                                        var f = document.form_filtro;

                                        carregando();
                                        setTimeout(function(){f.submit();}, 300);
                                    }

                                    function mudarSituacao(situacao){
                                        var f = document.form_filtro;
                                        f.situacao.value = situacao;

                                        filtro();
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="blocoResumoFinanceiro">
                            <div class="blocoM1" style="<?php echo ($mes_selected == 0?'opacity: 0;':'');?>">
                                <div class="txtlinha">
                                    <div class="tituloTxt">Resumo <?php echo $relatorio["relatorio_r1"]["data"]; ?></div>
                                    <div class="info">
                                        <div class="txt">Valor a receber: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r1"]["valor_total"],true);?></div></div>
                                        <div class="txt">Valor recebido: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r1"]["valor_pago"],true);?></div></div>
                                        <div class="txt">Valor faltando: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r1"]["valor_nao_pago"],true);?></div></div>
                                    </div>
                                </div>
                                <div class="txtlinha">
                                    <div class="tituloTxt">Resumo <?php echo $relatorio["relatorio_r2"]["data"]; ?></div>
                                    <div class="info">
                                        <div class="txt">Valor a receber: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r2"]["valor_total"],true);?></div></div>
                                        <div class="txt">Valor recebido: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r2"]["valor_pago"],true);?></div></div>
                                        <div class="txt">Valor faltando: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r2"]["valor_nao_pago"],true);?></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="blocoM2">
                                <div class="txtlinha">
                                    <div class="tituloTxt">Resumo <?php echo ($mes_selected == 0?'total':$relatorio["relatorio_atual"]["data"]);?></div>
                                    <div class="info">
                                        <div class="txt">Valor a receber: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_atual"]["valor_total"],true);?></div></div>
                                        <div class="txt">Valor recebido: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_atual"]["valor_pago"],true);?></div></div>
                                        <div class="txt">Valor faltando: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_atual"]["valor_nao_pago"],true);?></div></div>
                                    </div>
                                </div>
                                <div class="txtPagamento">
                                    <div class="tituloTxt">Orientação para Pagamento</div>
                                    <div class="blocosInfoPagamentos">
                                        <div class="bInfoPagamento pag_verde" onclick="mudarSituacao(1)">Pago (<?php echo $relatorio["relatorio_atual"]["num_pago"];?>)</div>
                                        <div class="bInfoPagamento pag_laranja" onclick="mudarSituacao(2)">Atrasado (<?php echo $relatorio["relatorio_atual"]["num_atrasado"];?>)</div>
                                        <div class="bInfoPagamento pag_cinza"onclick="mudarSituacao(3)" >No prazo (<?php echo $relatorio["relatorio_atual"]["num_prazo"];?>)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="blocoM1" style="<?php echo ($mes_selected == 0?'opacity: 0;':'');?>">
                                <div class="txtlinha">
                                    <div class="tituloTxt">Resumo <?php echo $relatorio["relatorio_r3"]["data"]; ?></div>
                                    <div class="info">
                                        <div class="txt">Valor a receber: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r3"]["valor_total"],true);?></div></div>
                                        <div class="txt">Valor recebido: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r3"]["valor_pago"],true);?></div></div>
                                        <div class="txt">Valor faltando: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r3"]["valor_nao_pago"],true);?></div></div>
                                    </div>
                                </div>
                                <div class="txtlinha">
                                    <div class="tituloTxt">Resumo <?php echo $relatorio["relatorio_r4"]["data"]; ?></div>
                                    <div class="info">
                                        <div class="txt">Valor a receber: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r4"]["valor_total"],true);?></div></div>
                                        <div class="txt">Valor recebido: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r4"]["valor_pago"],true);?></div></div>
                                        <div class="txt">Valor faltando: <div class="valor"><?php echo $controller->model->moeda($relatorio["relatorio_r4"]["valor_nao_pago"],true);?></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="blocoTabela blocoCaixaSty">
                            <table class="tabela_padrao">
                                <thead>
                                    <tr>
                                        <td style="width: 30px"></td>
                                        <td>Cliente</td>
                                        <td>Status</td>
                                        <td style="width: 100px;">Total</td>
                                        <td>Data Vencimento</td>
                                        <td>Data Pagamento</td>
                                        <td>Data Cadastro</td>
                                    </tr>
                                </thead>
                                <?php if($dados["dados"]): ?>
                                <tbody>
                                    <?php foreach($dados["dados"] as $dado):?>
                                        <?php
                                            $situacao = "";
                                            if($dado->data_pagamento):
                                                $situacao = "pag_verde";
                                            elseif(strtotime($dado->data_vencimento) < strtotime(date('Y-m-d'))):
                                                $situacao = "pag_laranja";
                                            else:
                                                $situacao = "pag_cinza";
                                            endif;

                                            // if($dado->status_asaas == 'RECEIVED' || $dado->status_asaas == 'CONFIRMED' || $dado->status_asaas == 'RECEIVED_IN_CASH'){
                                            if($dado->data_pagamento && $dado->data_pagamento != '0000-00-00'){
                                                $status = 'Pagemento confirmado';
                                            // }else if($dado->status_asaas == 'PENDING'){
                                            }else if($dado->status_asaas == 'PENDING' && (!$dado->data_pagamento || $dado->data_pagamento == '0000-00-00')){
                                                $status = 'Aguardando pagamento';
                                            }else{
                                                $status = 'Outros';
                                            } 
                                        ?>
                                        <tr>
                                            <td class="text-center <?php echo $situacao ?>">
                                                <input type="checkbox" name="id[]" value="<?php echo $dado->id;?>">
                                            </td>
                                            <td><?php echo $dado->cliente;?></td>
                                            <td><?php echo $status;?></td>
                                            <td><?php echo $controller->model->moeda($dado->valor_total,true);?></td>
                                            <td><?php echo $dado->data_vencimento?date('d/m/Y',strtotime($dado->data_vencimento)):"";?></td>
                                            <td><?php echo $dado->data_pagamento?date('d/m/Y',strtotime($dado->data_pagamento)):"";?></td>
                                            <td><?php echo date('d/m/Y',strtotime($dado->cadastrado));?></td>
                                        </tr>
                                    <?php endforeach;?>  
                                </tbody>
                                <?php endif;?>
                            </table>
                            <?php if(!$dados["dados"]): ?>
                                <div class="txt-sem-resultado">Não há nenhum resultado corresponde à sua consulta</div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>

<script>
    
    jQuery('#mes').selectize();
    jQuery('#ano').selectize();

    var resultClientes = [];
    var selectizeCliente = jQuery('#id_cliente').selectize({
                        valueField: 'id',
                        labelField: 'nome',
                        searchField: ['nome','cpf','cnpj','email'],
                        options: [],
                        create: false,
                        render: {
                            option: function(item, escape) {
                                var html= '';
                                

                                html = '<div class="option">';
                                if(item.tipo_pessoa == 2){
                                    html += '<em class="icon-building"></em>'+item.nome+' - '+item.cnpj+'';
                                }else{
                                        html += '<em class="icon-user"></em>'+item.nome+' - '+item.cpf+'';
                                        if(item.email){
                                            html += '<div class="email"><em class="icon-mail-alt"></em>'+item.email+'</div>';
                                        }
                                }

                                html += '</div>';

                                return html;
                            }
                        },
                        load: function(query, callback) {
                            if (!query.length) return callback();

                            jQuery.ajax({
                                url: '/usuario/Usuario_controller_financeiro/buscar_cliente_ajax',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    query: query
                                },
                                error: function() {
                                    callback();
                                },
                                success: function(res) {
                                    var dados = [];
                                    
                                    res.forEach(function(a,b){
                                        if(a.nome.length > 30){
                                            a.nome = a.nome.substring(0,30)+"...";
                                       }
                                            
                                        dados.push(a);
                                        resultClientes[a.id] = a;
                                    });

                                    callback(dados);
                                }
                            });
                        }
    });

    jQuery(".selectize-control.id_cliente").on("click",function(){
        selectizeCliente[0].selectize.clear();
        jQuery('#id_cliente').val("");
    });
</script>

<style>
.selectize-control.single .selectize-input, .selectize-control.single .selectize-input input {
    width: 100% ;
}
</style>