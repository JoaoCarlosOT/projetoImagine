<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    if(isset($dados['formas_pagamento'])) $dados['formas_pagamento'] = json_decode($dados['formas_pagamento'],true);
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Configurações</div>
                <div class="botoesTopo">
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Integraca Asaas e Forma de Pagamento </div>
                    <div class="informacao" id="bloco1">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Token API Key</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="assas_tokey" value="<?php echo $dados["assas_tokey"];?>" >
                                </div>
                            </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Formas de Pagamento</div>
                                <div class="input-div">
                                
                                    <div style="display: flex;flex-wrap: wrap;margin-top: 5px;flex-direction: column;">
                                        <div style="margin-left: 15px;">
                                            <input class="checkbox" id="checkbox-1" style="vertical-align:-2px;" type="checkbox" name="formas_pagamento[]" value="1" <?=in_array(1, $dados['formas_pagamento'])?"checked":""?>> PIX
                                        </div>  
                                        <div style="margin-left: 15px;">
                                            <input class="checkbox" id="checkbox-2" style="vertical-align:-2px;" type="checkbox" name="formas_pagamento[]" value="2" <?=in_array(2, $dados['formas_pagamento'])?"checked":""?>> Boleto
                                        </div>   
                                        <div style="margin-left: 15px;">
                                            <input class="checkbox" id="checkbox-3" style="vertical-align:-2px;" type="checkbox" name="formas_pagamento[]" value="3" <?=in_array(3, $dados['formas_pagamento'])?"checked":""?>> Cartão de Crédito
                                        </div>                                        
                                    </div>

                                </div>
                            </div> 

                            <div class="linha" style="width: 20%;" id="blocoParcela" style="display: none;">
                                <div class="txtCampo">Parcelar em até</div>
                                <div class="input-div">
                                    <select name="parcela" class="campo-padrao">
                                        <?php for ($i=1; $i <=10 ; $i++):?>
                                            <option value="<?=$i?>" <?php echo (isset($dados["parcela"]) && $dados["parcela"] == $i ?'selected=""':'');?>><?=$i?></option> 
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div> 

                            <div class="linha" style="width: 20%;" id="blocoPix" style="display: none;">
                                <div class="txtCampo">Desconto em porcentagem PIX</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="descontoPix" value="<?php echo $dados["descontoPix"];?>" onchange="this.value < 0?this.value='0':this.value > 100?this.value='100':''" min="0"  max="100">
                                </div>
                            </div> 

                            <script>
                                $(document).ready(function() {
                                    ativar_parcela();
                                })

                                $('input[name="formas_pagamento[]"]').on('change', function(){
                                    ativar_parcela();
                                })
                                function ativar_parcela(){
                                    var checkboxCartao3 = $('input[name="formas_pagamento[]"][value="3"]');
                                    if(checkboxCartao3.is(':checked')){
                                        $("#blocoParcela").show();
                                    }else{
                                        $("#blocoParcela").hide();
                                    }

                                    var checkboxCartao1 = $('input[name="formas_pagamento[]"][value="1"]');
                                    if(checkboxCartao1.is(':checked')){
                                        $("#blocoPix").show();
                                    }else{
                                        $("#blocoPix").hide();
                                    }
                                }
                                
                            </script>

                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Frequência cobrança da assinatura</div>
                                <div class="input-div">
                                    <select name="frequencia_assina" class="campo-padrao">
                                        <?php foreach($controller->asaas->getCicloAssinatura() as $key => $ciclo):?>
                                            <option value="<?=$key?>" <?php echo (isset($dados["frequencia_assina"]) && $dados["frequencia_assina"] == $key ?'selected=""':'');?>><?=$ciclo?></option> 
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Cadastro de Clientes</div>
                                <div class="input-div">
                                    <select name="cad_cli_cpf_cnpf" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["cad_cli_cpf_cnpf"]) && $dados["cad_cli_cpf_cnpf"] == 0 ?'selected=""':'');?>>Somente CPF</option>
                                        <option value="1" <?php echo (isset($dados["cad_cli_cpf_cnpf"]) && $dados["cad_cli_cpf_cnpf"] == 1 ?'selected=""':'');?>>Somente CNPJ</option>
                                        <option value="2" <?php echo (isset($dados["cad_cli_cpf_cnpf"]) && $dados["cad_cli_cpf_cnpf"] == 2 ?'selected=""':'');?>>CPF e CNPJ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Manutecao:</div>
                                <div class="input-div">
                                    <input type="radio" name="manutecao" value="0" <? if($dados['manutecao'] == 0 ){ echo "checked";}?>>Sim
                                    <input type="radio" name="manutecao" value="1" <? if($dados['manutecao'] == 1 ){ echo "checked";}?>>Não
                                </div>
                            </div>
                        </div> -->

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

