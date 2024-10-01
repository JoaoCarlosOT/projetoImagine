<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    $copywriters = $this->dados["dados"]["copywriters"];
        
    if(!empty($dados['copywriters'])){
        $dados['copywriters'] = json_decode($dados['copywriters'], true);
    }else{
        $dados['copywriters'] = array();
    }

    // Array com a ordem dos IDs
    $order_ids = $dados['copywriters']; 

    // Ordenar o array original com base nos IDs especificados
    if($order_ids){
        usort($copywriters, function($a, $b) use ($order_ids) {
            return array_search($a->id, $order_ids) <=> array_search($b->id, $order_ids);
        });
    }
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["Palavra-Chave-01"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/landing-page/configuracoes-lp.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Tags</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <?php if(isset($dados["id"])):?>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Banner Principal <a href="/admin/banner/<?php echo $dados["id_banner"];?>/slides.html" target="_blank">(Visualizar)</a></div>
                            </div>
                        </div>
                        <?php endif;?>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 01: [Palavra-Chave-01]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-01" value="<?php echo (isset($dados["Palavra-Chave-01"])?$dados["Palavra-Chave-01"]:"");?>" <?php echo isset($dados["id"])?"readonly":""?>>
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 02: [Palavra-Chave-02]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-02" value="<?php echo (isset($dados["Palavra-Chave-02"])?$dados["Palavra-Chave-02"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 03: [Palavra-Chave-03]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-03" value="<?php echo (isset($dados["Palavra-Chave-03"])?$dados["Palavra-Chave-03"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 04: [Palavra-Chave-04]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-04" value="<?php echo (isset($dados["Palavra-Chave-04"])?$dados["Palavra-Chave-04"]:"");?>" >
                                </div>
                            </div>
                        </div>  

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 05: [Palavra-Chave-05]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-05" value="<?php echo (isset($dados["Palavra-Chave-05"])?$dados["Palavra-Chave-05"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 06: [Palavra-Chave-06]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-06" value="<?php echo (isset($dados["Palavra-Chave-06"])?$dados["Palavra-Chave-06"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 07: [Palavra-Chave-07]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-07" value="<?php echo (isset($dados["Palavra-Chave-07"])?$dados["Palavra-Chave-07"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Palavra Chave 08: [Palavra-Chave-08]</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Palavra-Chave-08" value="<?php echo (isset($dados["Palavra-Chave-08"])?$dados["Palavra-Chave-08"]:"");?>" >
                                </div>
                            </div>
                        </div>  
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descriação para o SEO das LP: [Descricao-SEO]</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="Descricao-SEO"><?php echo (isset($dados["Descricao-SEO"])?$dados["Descricao-SEO"]:"");?></textarea>
                                </div>
                            </div>
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">[Solicitar-Orcamento-Zap]: Substitui pelo botão de Solicitar orçamento com formatação do WhatsApp e direcionando para o WhatsApp</div>
                                <div class="txtCampo">Telefone/WhatsApp: </div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="telefone1" alt="phone" value="<?php echo (isset($dados["telefone1"])?$dados["telefone1"]:"");?>" >
                                </div>
                            </div>
                        </div> 

                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco5');">Fotos</div>
                    <div class="informacao" id="bloco5" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Título Foto:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="Titulo-Foto" value="<?php echo (isset($dados["Titulo-Foto"])?$dados["Titulo-Foto"]:"");?>" >
                                </div>
                            </div>
                        </div>  

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Fotos</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" name="imagem_anexo[]" multiple />
                                </div>
                            </div>
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Imagem Atual</div>
                                <div class="anexos-salvos" id="imagens_atuais">
                                    <?php 
                                    if(isset($dados["imagens"]) && $dados["imagens"]):
                                        $dir ='/arquivos/imagens/LP/galeria/';
                                        foreach($dados["imagens"] as $imagem):
                                        $img = $this->lib_imagem->otimizar($dir.$imagem['imagem'],180,130,false,false,80);
                                        ?>
                                        <div id="imagem_item-<?php echo $imagem['id'];?>" class="img-anexo">
                                            <img src="<?php echo $img;?>">
                                            <!-- <input type="text" name="txt1_imagem[<?php echo $imagem["id"];?>]" value="<?php echo $imagem["txt1"];?>" placeholder="Texto"> -->
                                            <!-- <input type="text" name="txt2_imagem[<?php echo $imagem["id"];?>]" value="<?php echo $imagem["txt2"];?>" placeholder="Texto "> -->
                                            <!-- <input type="number" name="ordem_imagem[<?php echo $imagem["id"];?>]" value="<?php echo $imagem["ordem"];?>" placeholder="Ordem" style="margin-top: 5px;"> -->
                                            <!-- <div style="text-align: left;width: 100%;">Atualizar Imagem:</div> -->
                                            <!-- <input type="file" name="nova_imagem[<?php echo $imagem["id"];?>]" value=""  /> -->

                                            <input type="hidden" id="imagem-<?php echo $imagem["id"];?>" name="ids_imagem[]" value="<?php echo $imagem["id"];?>">
                                            <em class="icon-trash icone-td" onclick="jQuery('#imagem-<?php echo $imagem['id'];?>').val('');jQuery('#imagem_item-<?php echo $imagem['id'];?>').html('<div style=\'text-align: center;width: 100%;margin-top: 20px;\'>Salve para concluir a exclusão</div>')"></em>
                                        </div>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                    
                                </div>
                            </div>    
                        </div>      
                        
                        

                    </div>    
                </div>   

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco10');">BigNumber</div>
                    <div class="informacao" id="bloco10" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;"> 
                                <div onclick="addBigNumber()" class="botao_padra_1 btn_verde" style="display: inline-block; margin: 0;">[+]</div> 
                            </div>
                        </div>

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


                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');" >Copywriter</div>
                    <div class="informacao" id="bloco2"  style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Copywriters</div>
                                <div class="input-div">
                                    <select name="copywriters[]" class="campo-padrao" id="copywriters_selectize" multiple> 
                                        <?php if($copywriters):?>
                                            <?php foreach($copywriters as $copywriter):?>
                                                <option value="<?=$copywriter->id?>" <?=!empty($dados['copywriters']) && in_array($copywriter->id, $dados['copywriters'])?"selected":""?>><?=$copywriter->titulo?></option>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>
                        </div>   

                    </div>
                </div>   

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco6');">CTA 1</div>
                    <div class="informacao" id="bloco6" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Situação:</div>
                                <div class="input-div">
                                    <select name="situacao_cta_1" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["situacao_cta_1"]) && $dados["situacao_cta_1"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["situacao_cta_1"]) && $dados["situacao_cta_1"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 76%;">
                                <div class="txtCampo">Título: </div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="titulo_cta_1" value="<?php echo (isset($dados["titulo_cta_1"])?$dados["titulo_cta_1"]:"");?>" >
                                </div>
                            </div>
                        </div>     
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Nome - Situação:</div>
                                <div class="input-div">
                                    <select name="nome_situacao_cta_1" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["nome_situacao_cta_1"]) && $dados["nome_situacao_cta_1"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["nome_situacao_cta_1"]) && $dados["nome_situacao_cta_1"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Nome - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nome_nomenclatura_cta_1" value="<?php echo (isset($dados["nome_nomenclatura_cta_1"])?$dados["nome_nomenclatura_cta_1"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Nome - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="nome_ordem_cta_1" value="<?php echo (isset($dados["nome_ordem_cta_1"])?$dados["nome_ordem_cta_1"]:"");?>" >
                                </div>
                            </div> 
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Email - Situação:</div>
                                <div class="input-div">
                                    <select name="email_situacao_cta_1" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["email_situacao_cta_1"]) && $dados["email_situacao_cta_1"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["email_situacao_cta_1"]) && $dados["email_situacao_cta_1"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Email - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="email_nomenclatura_cta_1" value="<?php echo (isset($dados["email_nomenclatura_cta_1"])?$dados["email_nomenclatura_cta_1"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Email - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="email_ordem_cta_1" value="<?php echo (isset($dados["email_ordem_cta_1"])?$dados["email_ordem_cta_1"]:"");?>" >
                                </div>
                            </div> 
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Telefone - Situação:</div>
                                <div class="input-div">
                                    <select name="telefone_situacao_cta_1" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["telefone_situacao_cta_1"]) && $dados["telefone_situacao_cta_1"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["telefone_situacao_cta_1"]) && $dados["telefone_situacao_cta_1"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Telefone - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="telefone_nomenclatura_cta_1" value="<?php echo (isset($dados["telefone_nomenclatura_cta_1"])?$dados["telefone_nomenclatura_cta_1"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Telefone - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="telefone_ordem_cta_1" value="<?php echo (isset($dados["telefone_ordem_cta_1"])?$dados["telefone_ordem_cta_1"]:"");?>" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Aberto - Situação:</div>
                                <div class="input-div">
                                    <select name="aberto_situacao_cta_1" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["aberto_situacao_cta_1"]) && $dados["aberto_situacao_cta_1"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["aberto_situacao_cta_1"]) && $dados["aberto_situacao_cta_1"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Aberto - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="aberto_nomenclatura_cta_1" value="<?php echo (isset($dados["aberto_nomenclatura_cta_1"])?$dados["aberto_nomenclatura_cta_1"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Aberto - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="aberto_ordem_cta_1" value="<?php echo (isset($dados["aberto_ordem_cta_1"])?$dados["aberto_ordem_cta_1"]:"");?>" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Nomenclatura do Botão:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nomenclatura_bt_cta_1" value="<?php echo (isset($dados["nomenclatura_bt_cta_1"])?$dados["nomenclatura_bt_cta_1"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Texto do WhatsApp</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="txt_zap_cta_1" value="<?php echo (isset($dados["txt_zap_cta_1"])?$dados["txt_zap_cta_1"]:"");?>" >
                                </div>
                            </div>
                        </div>         
                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco7');">CTA 2</div>
                    <div class="informacao" id="bloco7" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Situação:</div>
                                <div class="input-div">
                                    <select name="situacao_cta_2" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["situacao_cta_2"]) && $dados["situacao_cta_2"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["situacao_cta_2"]) && $dados["situacao_cta_2"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 76%;">
                                <div class="txtCampo">Título: </div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="titulo_cta_2" value="<?php echo (isset($dados["titulo_cta_2"])?$dados["titulo_cta_2"]:"");?>" >
                                </div>
                            </div>
                        </div>     
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Nome - Situação:</div>
                                <div class="input-div">
                                    <select name="nome_situacao_cta_2" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["nome_situacao_cta_2"]) && $dados["nome_situacao_cta_2"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["nome_situacao_cta_2"]) && $dados["nome_situacao_cta_2"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Nome - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nome_nomenclatura_cta_2" value="<?php echo (isset($dados["nome_nomenclatura_cta_2"])?$dados["nome_nomenclatura_cta_2"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Nome - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="nome_ordem_cta_2" value="<?php echo (isset($dados["nome_ordem_cta_2"])?$dados["nome_ordem_cta_2"]:"");?>" >
                                </div>
                            </div> 
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Email - Situação:</div>
                                <div class="input-div">
                                    <select name="email_situacao_cta_2" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["email_situacao_cta_2"]) && $dados["email_situacao_cta_2"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["email_situacao_cta_2"]) && $dados["email_situacao_cta_2"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Email - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="email_nomenclatura_cta_2" value="<?php echo (isset($dados["email_nomenclatura_cta_2"])?$dados["email_nomenclatura_cta_2"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Email - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="email_ordem_cta_2" value="<?php echo (isset($dados["email_ordem_cta_2"])?$dados["email_ordem_cta_2"]:"");?>" >
                                </div>
                            </div> 
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Telefone - Situação:</div>
                                <div class="input-div">
                                    <select name="telefone_situacao_cta_2" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["telefone_situacao_cta_2"]) && $dados["telefone_situacao_cta_2"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["telefone_situacao_cta_2"]) && $dados["telefone_situacao_cta_2"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Telefone - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="telefone_nomenclatura_cta_2" value="<?php echo (isset($dados["telefone_nomenclatura_cta_2"])?$dados["telefone_nomenclatura_cta_2"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Telefone - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="telefone_ordem_cta_2" value="<?php echo (isset($dados["telefone_ordem_cta_2"])?$dados["telefone_ordem_cta_2"]:"");?>" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Aberto - Situação:</div>
                                <div class="input-div">
                                    <select name="aberto_situacao_cta_2" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["aberto_situacao_cta_2"]) && $dados["aberto_situacao_cta_2"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["aberto_situacao_cta_2"]) && $dados["aberto_situacao_cta_2"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Aberto - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="aberto_nomenclatura_cta_2" value="<?php echo (isset($dados["aberto_nomenclatura_cta_2"])?$dados["aberto_nomenclatura_cta_2"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Aberto - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="aberto_ordem_cta_2" value="<?php echo (isset($dados["aberto_ordem_cta_2"])?$dados["aberto_ordem_cta_2"]:"");?>" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Nomenclatura do Botão:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nomenclatura_bt_cta_2" value="<?php echo (isset($dados["nomenclatura_bt_cta_2"])?$dados["nomenclatura_bt_cta_2"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Texto do WhatsApp</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="txt_zap_cta_2" value="<?php echo (isset($dados["txt_zap_cta_2"])?$dados["txt_zap_cta_2"]:"");?>" >
                                </div>
                            </div>
                        </div>         
                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco8');">CTA 3</div>
                    <div class="informacao" id="bloco8" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Situação:</div>
                                <div class="input-div">
                                    <select name="situacao_cta_3" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["situacao_cta_3"]) && $dados["situacao_cta_3"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["situacao_cta_3"]) && $dados["situacao_cta_3"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 76%;">
                                <div class="txtCampo">Título: </div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="titulo_cta_3" value="<?php echo (isset($dados["titulo_cta_3"])?$dados["titulo_cta_3"]:"");?>" >
                                </div>
                            </div>
                        </div>     
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Nome - Situação:</div>
                                <div class="input-div">
                                    <select name="nome_situacao_cta_3" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["nome_situacao_cta_3"]) && $dados["nome_situacao_cta_3"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["nome_situacao_cta_3"]) && $dados["nome_situacao_cta_3"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Nome - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nome_nomenclatura_cta_3" value="<?php echo (isset($dados["nome_nomenclatura_cta_3"])?$dados["nome_nomenclatura_cta_3"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Nome - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="nome_ordem_cta_3" value="<?php echo (isset($dados["nome_ordem_cta_3"])?$dados["nome_ordem_cta_3"]:"");?>" >
                                </div>
                            </div> 
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Email - Situação:</div>
                                <div class="input-div">
                                    <select name="email_situacao_cta_3" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["email_situacao_cta_3"]) && $dados["email_situacao_cta_3"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["email_situacao_cta_3"]) && $dados["email_situacao_cta_3"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Email - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="email_nomenclatura_cta_3" value="<?php echo (isset($dados["email_nomenclatura_cta_3"])?$dados["email_nomenclatura_cta_3"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Email - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="email_ordem_cta_3" value="<?php echo (isset($dados["email_ordem_cta_3"])?$dados["email_ordem_cta_3"]:"");?>" >
                                </div>
                            </div> 
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Telefone - Situação:</div>
                                <div class="input-div">
                                    <select name="telefone_situacao_cta_3" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["telefone_situacao_cta_3"]) && $dados["telefone_situacao_cta_3"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["telefone_situacao_cta_3"]) && $dados["telefone_situacao_cta_3"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Telefone - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="telefone_nomenclatura_cta_3" value="<?php echo (isset($dados["telefone_nomenclatura_cta_3"])?$dados["telefone_nomenclatura_cta_3"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Telefone - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="telefone_ordem_cta_3" value="<?php echo (isset($dados["telefone_ordem_cta_3"])?$dados["telefone_ordem_cta_3"]:"");?>" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Campo Aberto - Situação:</div>
                                <div class="input-div">
                                    <select name="aberto_situacao_cta_3" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["aberto_situacao_cta_3"]) && $dados["aberto_situacao_cta_3"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["aberto_situacao_cta_3"]) && $dados["aberto_situacao_cta_3"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 59%;">
                                <div class="txtCampo">Campo Aberto - Nomenclatura:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="aberto_nomenclatura_cta_3" value="<?php echo (isset($dados["aberto_nomenclatura_cta_3"])?$dados["aberto_nomenclatura_cta_3"]:"");?>" >
                                </div>
                            </div> 

                            <div class="linha" style="width: 15%;">
                                <div class="txtCampo">Campo Aberto - Ordem:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="aberto_ordem_cta_3" value="<?php echo (isset($dados["aberto_ordem_cta_3"])?$dados["aberto_ordem_cta_3"]:"");?>" >
                                </div>
                            </div> 
                        </div>
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Nomenclatura do Botão:</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nomenclatura_bt_cta_3" value="<?php echo (isset($dados["nomenclatura_bt_cta_3"])?$dados["nomenclatura_bt_cta_3"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Texto do WhatsApp</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="txt_zap_cta_3" value="<?php echo (isset($dados["txt_zap_cta_3"])?$dados["txt_zap_cta_3"]:"");?>" >
                                </div>
                            </div>
                        </div>         
                    </div>
                </div>
                
                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f['Palavra-Chave-01'].value)){
                            exibirAviso('Informe a Palavra Chave 01!');
                        } 
                        
                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>

            </form>
        </div>
    </div>
</div>

<script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
<script type="text/javascript">
    // CKEDITOR.replace( 'Texto-01', {
    //     width: 800,
    //     height: 400,
    //     filebrowserBrowseUrl: '/arquivos/javascript/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor',
    //     filebrowserWindowWidth: '1000',
    //     filebrowserWindowHeight: '700'
    // });
</script>

<script>
    function sanfona(elemento){
        $('.informacao').slideUp();
        $(elemento).slideToggle();
    }
    $("#copywriters_selectize").selectize({
        plugins: ["drag_drop", "remove_button"],
    });
</script>

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
    flex-wrap: wrap;
    border: 1px solid #bbb9b9;
    width: 175px;
    padding: 2px 6px;
    margin: 4px;
    justify-content: flex-end;
    background: #fff;
    cursor: grab;
}

.img-anexo input[type="text"] {
    height: 25px;
    margin-top: 5px;
}
</style>

