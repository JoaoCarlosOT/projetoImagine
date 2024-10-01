<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    $categorias = $this->dados["dados"]["categorias"];
    $categorias_selecionadas = $this->dados["dados"]["categorias_selecionadas"];

    $categorias_produto = $this->dados["dados"]["categorias_produto"];
    $categorias_selecionadas_produto = $this->dados["dados"]["categorias_selecionadas_produto"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["titulo"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/blog-artigos.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do Artigo</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Título <?php if(!empty($dados["alias"])):?><a href="/blog/<?=$dados["alias"]?>.html" target="_blank">(Visualizar página)</a><?php endif;?></div>
                                    <div class="input-div input-div-cont">
                                        <input class="campo-padrao" type="text" maxlength="75" name="titulo" value="<?php echo (isset($dados["titulo"])?$dados["titulo"]:"");?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 10%;display:none;">
                                    <div class="txtCampo">Ordem</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="number" name="ordem" value="<?php echo (isset($dados["ordem"])?$dados["ordem"]:"");?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">SubTítulo</div>
                                <div class="input-div input-div-cont">
                                    <input class="campo-padrao" type="text" maxlength="100" name="subtitulo" value="<?php echo (isset($dados["subtitulo"])?$dados["subtitulo"]:"");?>" >
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Imagem</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" name="imagem_anexo[]"/>
                                </div>
                            </div>
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Atual</div>
                                <div class="anexos-salvos">
                                    <?php 
                                    if(isset($dados["imagem"]) && $dados["imagem"]):
                                        $dir ='/arquivos/imagens/blog/';
                                        $img = $this->lib_imagem->otimizar($dir.$dados["imagem"],180,130,false,true,80);
                                        ?>
                                        <div id="imagem-img" class="img-anexo" style="position: relative;display: flex;">
                                            <img src="<?php echo $img;?>">

                                            <em class="icon-trash icone-td" onclick="jQuery('#imagem').val('');jQuery('#imagem-img').html('');"></em>
                                        </div>
                                    <?php 
                                    endif;
                                    ?>
                                    <input type="hidden" id="imagem" name="imagem" value="<?php echo (isset($dados["imagem"])?$dados["imagem"]:'');?>">
                                </div>
                            </div>    
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 32%;">
                                <div class="txtCampo" data-tooltip="Caso deseje categorizar esse blog, deverá selecionar a opção SIM, com isso irá aparecer as categorias para que você possa selecionar. Caso ainda não tenha sido criado a categoria desejada, você deverá ir no menu .... Blog -> Categorias .... e efetuar a criação da categoria desejada.">Deseja Habilitar a Seleção de Categorias do blog?</div>
                                <div class="input-div">
                                    <select class="campo-padrao" name="categorias_yes" id="categorias_yes">
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd" id="bloco-categorias_yes">
                            <div class="linha" style="width: 40%;">
                                <!-- <div class="txtCampo">Categorias Blog
                                    <input title="Selecione para ativar a modificação da(s) categoria(s)" value="categorias_yes" class="checkbox" style="vertical-align:-2px;" type="checkbox" name="categorias_yes" id="categorias_yes"/> 
                                </div> -->
                                
                                <div class="input-div">
                                    <?php if($categorias): ?>
                                        <div>
                                            <div>
                                                <div class="caixa_rolagem" id="caixa_rolagem" style="margin-left: 25px;"><?php
                                                    $selecionados = $categorias_selecionadas;
                                                    $pais = $categorias[0];
                                                    $campos_check=1;
                                                    foreach($pais as $pai):
                                                        if(isset($categorias[$pai->id]) && $categorias[$pai->id]):?>
                                                            <div>- <?php echo $pai->nome; ?></div>
                                                            <div style="display: flex;flex-wrap: wrap;margin-top: 5px;">
                                                                <?php foreach($categorias[$pai->id] as $filho):?>
                                                                    <div style="margin-left: 15px;">- <?php echo $filho->nome; ?> <input class="checkbox" id="checkbox-<?php echo($campos_check); ?>" style="vertical-align:-2px;" type="checkbox" name="categorias[]"  disabled value="<?php echo $filho->id; ?>" <?php if($selecionados && @in_array($filho->id, $selecionados)) echo 'checked="checked"'; ?> /></div><?php
                                                                    $campos_check++;
                                                                endforeach;?>
                                                            </div>
                                                        <?php else:?>
                                                            <div>
                                                                - <?php echo $pai->nome; ?>

                                                                <input class="checkbox" style="vertical-align:-2px;" id="checkbox-<?php echo($campos_check); ?>" type="checkbox" name="categorias[]"  disabled value="<?php echo $pai->id; ?>" <?php if($selecionados && @in_array($pai->id, $selecionados)) echo 'checked="checked"'; ?> /> 
                                                                
                                                            </div>
                                                        <?php
                                                            $campos_check++;
                                                        endif;
                                                    endforeach; ?>
                                                </div>
                                                </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            jQuery('#categorias_yes').change(function(){
                                var ativar = document.getElementById('categorias_yes');

                                if(ativar.value == 1){
                                    $("#bloco-categorias_yes").show()
                                    <?php
                                        for($i = 1; $i <= $campos_check; $i++):
                                    ?>
                                        document.getElementById("checkbox-<?php echo($i); ?>").disabled = false;
                                    <?php
                                        endfor;
                                    ?>
                                }
                                else{
                                    $("#bloco-categorias_yes").hide()
                                    <?php
                                        for($i = 1; $i <= $campos_check; $i++):
                                    ?>
                                        document.getElementById("checkbox-<?php echo($i); ?>").disabled = true;
                                    <?php
                                        endfor;
                                    ?>
                                }
                            }).trigger("change")
                        </script>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Vínculo Produtos/Serviços</div>
                                <div class="input-div">
                                    <select name="tipo_vinculo_produto" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["tipo_vinculo_produto"]) && $dados["tipo_vinculo_produto"] == 0 ?'selected=""':'');?>>Todos</option>
                                        <option value="1" <?php echo (isset($dados["tipo_vinculo_produto"]) && $dados["tipo_vinculo_produto"] == 1 ?'selected=""':'');?>>Categorias Produtos/serviços</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha tipo_vinculo_produto tipo_vinculo_produto_1" style="width: 32%;">
                                <div class="txtCampo" data-tooltip="Caso deseje categorizar esse blog ao Produto/Serviço, deverá selecionar a opção SIM, com isso irá aparecer as categorias para que você possa selecionar. Caso ainda não tenha sido criado a categoria desejada, você deverá ir no menu .... Institucional -> Categorias .... e efetuar a criação da categoria desejada.">Deseja Habilitar a Seleção de Categorias do Produtos/serviços?</div>
                                <div class="input-div">
                                    <select class="campo-padrao" name="categorias_yes_produto" id="categorias_yes_produto">
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd tipo_vinculo_produto tipo_vinculo_produto_1" id="bloco-categorias_yes_produto">
                            <div class="linha" style="width: 40%;"> 
                                
                                <div class="input-div">
                                    <?php if($categorias_produto): ?>
                                        <div>
                                            <div>
                                                <div class="caixa_rolagem" id="caixa_rolagem" style="margin-left: 25px;"><?php
                                                    $selecionados = $categorias_selecionadas_produto;
                                                    $pais = $categorias_produto[0];
                                                    $campos_check=1;
                                                    foreach($pais as $pai):
                                                        if(isset($categorias_produto[$pai->id]) && $categorias_produto[$pai->id]):?>
                                                            <div>- <?php echo $pai->nome; ?></div>
                                                            <div style="display: flex;flex-wrap: wrap;margin-top: 5px;">
                                                                <?php foreach($categorias_produto[$pai->id] as $filho):?>
                                                                    <div style="margin-left: 15px;">- <?php echo $filho->nome; ?> <input class="checkbox-produto" id="checkbox-produto-<?php echo($campos_check); ?>" style="vertical-align:-2px;" type="checkbox" name="categorias_produto[]"  disabled value="<?php echo $filho->id; ?>" <?php if($selecionados && @in_array($filho->id, $selecionados)) echo 'checked="checked"'; ?> /></div><?php
                                                                    $campos_check++;
                                                                endforeach;?>
                                                            </div>
                                                        <?php else:?>
                                                            <div>
                                                                - <?php echo $pai->nome; ?>

                                                                <input class="checkbox-produto" style="vertical-align:-2px;" id="checkbox-produto-<?php echo($campos_check); ?>" type="checkbox" name="categorias_produto[]"  disabled value="<?php echo $pai->id; ?>" <?php if($selecionados && @in_array($pai->id, $selecionados)) echo 'checked="checked"'; ?> /> 
                                                                
                                                            </div>
                                                        <?php
                                                            $campos_check++;
                                                        endif;
                                                    endforeach; ?>
                                                </div>
                                                </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            jQuery('#categorias_yes_produto').change(function(){
                                var ativar = document.getElementById('categorias_yes_produto');
                                var tipo_vinculo = $('select[name="tipo_vinculo_produto"]').val();
                                
                                if(ativar.value == 1 && tipo_vinculo == 1){
                                    $("#bloco-categorias_yes_produto").show()
                                    <?php
                                        for($i = 1; $i <= $campos_check; $i++):
                                    ?>
                                        document.getElementById("checkbox-produto-<?php echo($i); ?>").disabled = false;
                                    <?php
                                        endfor;
                                    ?>
                                }
                                else{
                                    $("#bloco-categorias_yes_produto").hide()

                                    <?php
                                        for($i = 1; $i <= $campos_check; $i++):
                                    ?>
                                        document.getElementById("checkbox-produto-<?php echo($i); ?>").disabled = true;
                                    <?php
                                        endfor;
                                    ?>
                                }
                            }).trigger("change");
                        </script>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="descricao" name="descricao" style="display: none;"><?php echo (isset($dados["descricao"])?$dados["descricao"]:'');?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">Configurações Avançadas do Artigo</div>
                    <div class="informacao" id="bloco2" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Alias</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="alias" value="<?php echo (isset($dados["alias"])?$dados["alias"]:"");?>">
                                </div>
                            </div>

                            <div class="linha" style="width: 14%; <?php echo (!isset($dados["id"])?'display:none;':"");?> ">
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
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor do background do Banner do Topo</div>
                                <div class="input-div">
                                    <input type="checkbox" id="background_color_ativado" value="1" style="width: 45px; height: 45px; margin: 0;" <?php echo (!empty($dados["background_color_ativado"])?"checked":"")?>>
                                    <input type="hidden" name="background_color_ativado" value="<?php echo (isset($dados["background_color_ativado"])?$dados["background_color_ativado"]:0);?>">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="background_color" value="<?php echo (isset($dados["background_color"])?$dados["background_color"]:"#FFFFFF");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor da Fonte do Texto que fica no Banner do Topo</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color" value="<?php echo (isset($dados["font_color"])?$dados["font_color"]:"#000000");?>">
                                </div>
                            </div>

                            <script>
                                $('#background_color_ativado').on('change',function(){
                                    background_color_ativado()
                                })
                                background_color_ativado();
                                function background_color_ativado(){
                                    if($('#background_color_ativado').is(':checked')){
                                        $('input[name="background_color"]').show()
                                        $('input[name="font_color"]').show()
                                        $('input[name="background_color_ativado"]').val(1)
                                    }else{
                                        $('input[name="background_color"]').hide()
                                        $('input[name="font_color"]').hide()
                                        $('input[name="background_color_ativado"]').val(0)
                                    }
                                }
                            </script>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Font family Titulo</div>
                                <div class="input-div">
                                    <select name="font_family_titulo" class="campo-padrao">
                                        <option value="">Título Padrão pelo Configuração</option>
                                        <option value="tituloPadrao" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao' ?'selected=""':'');?>>Título 1</option>
                                        <option value="tituloPadrao2" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao2' ?'selected=""':'');?>>Título 2</option>
                                        <option value="tituloPadrao3" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao3' ?'selected=""':'');?>>Título 3</option>
                                        <option value="tituloPadrao4" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao4' ?'selected=""':'');?>>Título 4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Font family Headline</div>
                                <div class="input-div">
                                    <select name="font_family_headline" class="campo-padrao">
                                        <option value="">Headline pelo Configuração</option>
                                        <option value="headline" <?php echo (isset($dados["font_family_headline"]) && $dados["font_family_headline"] == 'headline' ?'selected=""':'');?>>Headline 1</option>
                                        <option value="headline2" <?php echo (isset($dados["font_family_headline"]) && $dados["font_family_headline"] == 'headline2' ?'selected=""':'');?>>Headline 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
                <script type="text/javascript">
                    CKEDITOR.replace( 'descricao', {
                        width: 800,
                        height: 400,
                        filebrowserBrowseUrl: '/arquivos/javascript/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor',
                        filebrowserWindowWidth: '1000',
                        filebrowserWindowHeight: '700'
                    });
                </script>

                <script> 

                    $('select[name="tipo_vinculo_produto"]').on('change', function(){
                        var tipo = $('select[name="tipo_vinculo_produto"]').val();

                        $('.tipo_vinculo_produto').hide()
                        $('.tipo_vinculo_produto_'+tipo).show()

                        jQuery('#categorias_yes_produto').trigger("change")
                    }).trigger("change")

                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.titulo.value)){
                            exibirAviso('Informe o titulo!');
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
.input-div-cont{
    position: relative;
}
.input-div-cont:after{
    content: attr(data-content);
    position: absolute;
    z-index: 999999;
    bottom: -15px;
    right: 0;
    font-size: 12px;
}
</style>
<script>
    $('.input-div-cont > input').on('input', function(){
        var maxLength = $(this).attr('maxlength');
        var length = $(this).val().length;
        var remainingCharacters = maxLength - length;
        
        $(this).parent('.input-div-cont').attr('data-content', remainingCharacters+' caracteres restantes (máximo '+maxLength+')');
        
    });

    $('.input-div-cont > input').trigger('input');

    function sanfona(elemento){
        $('.informacao').slideUp();
        $(elemento).slideToggle();
    }
</script>

