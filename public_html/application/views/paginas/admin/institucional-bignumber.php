<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/institucional/bignumbers.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do big number</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (!empty($dados["nome"])?$dados["nome"]:"");?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 12%; <?php echo (!isset($dados["id"])?'display:none;':"");?> ">
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
                            <div class="linha" style="width: 100%;"> 
                                <div onclick="addBigNumber()" class="botao_padra_1 btn_verde" style="display: inline-block; margin: 0;">[+]</div> 
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div id="blocos-bignumber">
                                <?php 
                                    $qnt = 0;
                                    if(!empty($dados["bigNumber"])):
                                        $dados["bigNumber"] = json_decode($dados['bigNumber'], true); 
                                        foreach($dados["bigNumber"] as $key => $bigNumber):
                                            $qnt = $key;
                                        ?>

                                        <div class="linhaCompleta formulario-pd" id="bigNumber-<?=$key?>">
                                            <div class="linha" style="width: 16%;">
                                                <div class="txtCampo">Class fontello:</div>
                                                <div class="input-div">
                                                    <input class="campo-padrao" type="text" name="bigNumber[<?=$key?>][classFontello]" value="<?=$bigNumber['classFontello']?>" >
                                                </div>
                                            </div>

                                            <div class="linha" style="width: 16%;">
                                                <div class="txtCampo">Prefixo:</div>
                                                <div class="input-div">
                                                    <input class="campo-padrao" type="text" name="bigNumber[<?=$key?>][prefixo]" value="<?=$bigNumber['prefixo']?>" >
                                                </div>
                                            </div>

                                            <div class="linha" style="width: 16%;">
                                                <div class="txtCampo">Número:</div>
                                                <div class="input-div">
                                                    <input class="campo-padrao" type="number" name="bigNumber[<?=$key?>][numero]" value="<?=$bigNumber['numero']?>" >
                                                </div>
                                            </div>

                                            <div class="linha" style="width: 16%;">
                                                <div class="txtCampo">Sufixo:</div>
                                                <div class="input-div">
                                                    <input class="campo-padrao" type="text" name="bigNumber[<?=$key?>][sufixo]" value="<?=$bigNumber['sufixo']?>" >
                                                </div>
                                            </div>

                                            <div class="linha" style="width: 16%;">
                                                <div class="txtCampo">Texto de baixo:</div>
                                                <div class="input-div">
                                                    <input class="campo-padrao" type="text" name="bigNumber[<?=$key?>][texto]" value="<?=$bigNumber['texto']?>" >
                                                </div>
                                            </div>

                                            <div class="linha" style="width: 9%;">
                                                <div class="txtCampo"></div>
                                                <div class="input-div">
                                                    <em class="icon-trash icone-td" onclick="jQuery('#bigNumber-<?=$key?>').html('<div style=\'text-align: center;width: 100%;margin-top: 20px;\'>Salve para concluir a exclusão</div>')"></em>
                                                </div>
                                            </div>                         
                                        </div>
                                        
                                    <?php endforeach; $qnt++?>

                                <?php endif;?>
                            </div>
                        </div>
                        <script>
                            var i = <?=$qnt?>;
                            function addBigNumber(){
                                html = '';
                                html += '<div class="linhaCompleta formulario-pd" id="bigNumber-'+i+'">'
                                    html += '<div class="linha" style="width: 16%;">'
                                        html += '<div class="txtCampo">Class fontello:</div>'
                                        html += '<div class="input-div">'
                                            html += '<input class="campo-padrao" type="text" name="bigNumber['+i+'][classFontello]" value="" >'
                                        html += '</div>'
                                    html += '</div>'

                                    html += '<div class="linha" style="width: 16%;">'
                                        html += '<div class="txtCampo">Prefixo:</div>'
                                        html += '<div class="input-div">'
                                            html += '<input class="campo-padrao" type="text" name="bigNumber['+i+'][prefixo]" value="" >'
                                        html += '</div>'
                                    html += '</div>'

                                    html += '<div class="linha" style="width: 16%;">'
                                        html += '<div class="txtCampo">Número:</div>'
                                        html += '<div class="input-div">'
                                            html += '<input class="campo-padrao" type="number" name="bigNumber['+i+'][numero]" value="" >'
                                        html +=  '</div>'
                                    html += '</div>'

                                    html += '<div class="linha" style="width: 16%;">'
                                        html += '<div class="txtCampo">Sufixo:</div>'
                                        html += '<div class="input-div">'
                                            html += '<input class="campo-padrao" type="text" name="bigNumber['+i+'][sufixo]" value="" >'
                                        html += '</div>'
                                    html += '</div>'

                                    html += '<div class="linha" style="width: 16%;">'
                                        html += '<div class="txtCampo">Texto de baixo:</div>'
                                        html += '<div class="input-div">'
                                            html += '<input class="campo-padrao" type="text" name="bigNumber['+i+'][texto]" value="" >'
                                        html += '</div>'
                                    html += '</div>'

                                    html += '<div class="linha" style="width: 9%;">'
                                        html += '<div class="txtCampo"></div>'
                                        html += '<div class="input-div">'
                                            html += '<em class="icon-trash icone-td" onclick="jQuery(\'#bigNumber-'+i+'\').html(\'<div style=\\\'text-align: center;width: 100%;margin-top: 20px;\\\'>Salve para concluir a exclusão</div>\')"></em>'
                                        html += '</div>'
                                    html +=  '</div>'                        
                                html += '</div>' 

                                $("#blocos-bignumber").append(html)
                                i++
                            }
                        </script>

                    </div>
                </div> 

                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.nome.value)){
                            return exibirAviso('Informe o nome!');
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

