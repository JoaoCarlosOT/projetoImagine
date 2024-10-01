<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["titulo"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/copywriters.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do Copywriter</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Título</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="titulo" max="255" value="<?php echo !empty($dados["titulo"])?$dados["titulo"]:"";?>" >
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo" data-tooltip="Será o texto que irá aparecer abaixo do Título. É recomendado que esse texto seja curto e tenha como objetivo reforçar a mensagem do Título">Headline - Descrição Curta</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="headline" value="<?php echo !empty($dados["headline"])?$dados["headline"]:"";?>" >
                                </div>
                            </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo" data-tooltip="Imagem que irá aparecer no Bloco">Imagem - Foto</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" name="imagem_anexo[]"/>
                                </div>
                            </div>
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Atual</div>
                                <div class="anexos-salvos">
                                    <?php 
                                    if(isset($dados["imagem"]) && $dados["imagem"]):
                                        $dir ='/arquivos/imagens/copywriter/';
                                        $img = $this->lib_imagem->otimizar($dir.$dados["imagem"],180,130,false,true,80);
                                        ?>
                                        <div id="imagem-img" class="img-anexo" style="position: relative;display: flex;">
                                            <img src="<?php echo $img;?>">

                                            <em class="icon-trash icone-td" onclick="jQuery('#imagem').val('');jQuery('#imagem-img').html('');"></em>
                                        </div>
                                    <?php 
                                    endif;
                                    ?>
                                    <input type="hidden" id="imagem" name="imagem" value="<?php echo !empty($dados["imagem"])?$dados["imagem"]:'';?>">
                                </div>
                            </div>    
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo" data-tooltip="Para que o vídeo possa aparecer na exibição desse Bloco, deverá ser colocado abaixo o link do vídeo do YOUTUBE">Vídeo - Link do YouTube</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="link_yt" value="<?php echo !empty($dados["link_yt"])?$dados["link_yt"]:"";?>" >
                                </div>
                            </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo" data-tooltip="Essa descrição será o texto que irá aparecer no detalhamento do Bloco">Descrição</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="descricao" name="descricao" style="display: none;"><?php echo isset($dados["descricao"])?$dados["descricao"]:"";?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">Configurações</div>
                    <div class="informacao" id="bloco2" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
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
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo" data-tooltip="Caso deseje Personalizar a Cor de fundo do Bloco, basta escolher a cor desejada">Background color - Cor de Fundo do Bloco</div>
                                <div class="input-div">
                                    <input type="checkbox" id="background_color_ativado" value="1" style="width: 45px; height: 45px; margin: 0;" <?php echo (!empty($dados["background_color_ativado"])?"checked":"")?>>
                                    <input type="hidden" name="background_color_ativado" value="<?php echo (isset($dados["background_color_ativado"])?$dados["background_color_ativado"]:0);?>">
                                    <input class="campo-padrao" style="height: 45px;" type="color" name="background_color" value="<?php echo !empty($dados["background_color"])?$dados["background_color"]:"#FFFFFF";?>" >
                                </div>
                            </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Classe Título</div>
                                <div class="input-div">
                                    <select name="font_family_titulo" class="campo-padrao">
                                        <option value="">Título Padrão pelo Configuração</option>
                                        <option value="tituloPadrao" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao' ?'selected=""':'');?>>Título 1</option>
                                        <option value="tituloPadrao2" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao2' ?'selected=""':'');?>>Título 2</option>
                                        <option value="tituloPadrao3" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao3' ?'selected=""':'');?>>Título 3</option>
                                        <option value="tituloPadrao4" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao4' ?'selected=""':'');?>>Título 4</option>
                                        <option value="tituloPadrao5" <?php echo (isset($dados["font_family_titulo"]) && $dados["font_family_titulo"] == 'tituloPadrao5' ?'selected=""':'');?>>Título 5</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor da Fonte do Título</div>
                                <div class="input-div">
                                    <input type="checkbox" id="font_color_titulo_ativ" value="1" style="width: 45px; height: 45px; margin: 0;" <?php echo (!empty($dados["font_color_titulo_ativ"])?"checked":"")?>>
                                    <input type="hidden" name="font_color_titulo_ativ" value="<?php echo (isset($dados["font_color_titulo_ativ"])?$dados["font_color_titulo_ativ"]:0);?>">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color_titulo" value="<?php echo !empty($dados["font_color_titulo"])?$dados["font_color_titulo"]:"#000000";;?>">
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd"> 
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Classe Headline</div>
                                <div class="input-div">
                                    <select name="font_family_headline" class="campo-padrao">
                                        <option value="">Headline Padrão pelo Configuração</option>
                                        <option value="headline" <?php echo (isset($dados["font_family_headline"]) && $dados["font_family_headline"] == 'headline' ?'selected=""':'');?>>Headline 1</option>
                                        <option value="headline2" <?php echo (isset($dados["font_family_headline"]) && $dados["font_family_headline"] == 'headline2' ?'selected=""':'');?>>Headline 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor da Fonte Headline</div>
                                <div class="input-div">
                                    <input type="checkbox" id="font_color_headline_ativ" value="1" style="width: 45px; height: 45px; margin: 0;" <?php echo (!empty($dados["font_color_headline_ativ"])?"checked":"")?>>
                                    <input type="hidden" name="font_color_headline_ativ" value="<?php echo (isset($dados["font_color_headline_ativ"])?$dados["font_color_headline_ativ"]:0);?>">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color_headline" value="<?php echo !empty($dados["font_color_headline"])?$dados["font_color_headline"]:"#000000";?>">
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">  
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor da Fonte Descrição</div>
                                <div class="input-div">
                                    <input type="checkbox" id="font_color_descricao_ativ" value="1" style="width: 45px; height: 45px; margin: 0;" <?php echo (!empty($dados["font_color_descricao_ativ"])?"checked":"")?>>
                                    <input type="hidden" name="font_color_descricao_ativ" value="<?php echo (isset($dados["font_color_descricao_ativ"])?$dados["font_color_descricao_ativ"]:0);?>">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color_descricao" value="<?php echo !empty($dados["font_color_descricao"])?$dados["font_color_descricao"]:"#000000";?>">
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 49%;">
                                <div class="txtCampo" data-tooltip="Caso deseje que apareça um botão de ação nesse bloco, deverá ser colocado o texto acima. É recomendado que esse texto seja curto e que incentive o cliente a ação desejada, Exemplo: Solicitar Agora, Solicitar Contato, Saiba Mais, Agendar Visita, Agendar Reunião, Falar pelo WhatsApp, Mais Informações ....">CTA - Qual será o texto do Botão?</div> 
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="text_botao" value="<?php echo !empty($dados["text_botao"])?$dados["text_botao"]:"";?>" >
                                </div>
                            </div>
                            
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Link</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="link" value="<?php echo !empty($dados["link"])?$dados["link"]:"";?>" >
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 32%;">
                                <div class="txtCampo">Posicionamento texto</div>
                                <div class="input-div">
                                    <select name="pos_tex" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["pos_tex"]) && $dados["pos_tex"] == 0 ?'selected=""':'');?>>Esquerdo</option>
                                        <option value="1" <?php echo (isset($dados["pos_tex"]) && $dados["pos_tex"] == 1 ?'selected=""':'');?>>Direito</option>
                                        <option value="2" <?php echo (isset($dados["pos_tex"]) && $dados["pos_tex"] == 2 ?'selected=""':'');?>>Acima</option>
                                        <option value="3" <?php echo (isset($dados["pos_tex"]) && $dados["pos_tex"] == 3 ?'selected=""':'');?>>Acima e texto alinha no centro</option>
                                    </select>                                
                                </div>
                            </div> 

                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Tipo de corte</div>
                                <div class="input-div">
                                    <select name="tipo_corte" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 0 ?'selected=""':'');?>>Reto</option>
                                        
                                        <option value="2" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 2 ?'selected=""':'');?>>Diagonal Esquerda Topo</option>
                                        <option value="4" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 4 ?'selected=""':'');?>>Diagonal Esquerda Inferior</option>
                                        <option value="6" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 6 ?'selected=""':'');?>>Diagonal Esquerda Topo e Inferior</option>

                                        <option value="3" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 3 ?'selected=""':'');?>>Diagonal Direita Topo</option>
                                        <option value="5" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 5 ?'selected=""':'');?>>Diagonal Direita Inferior</option>
                                        <option value="7" <?php echo (isset($dados["tipo_corte"]) && $dados["tipo_corte"] == 7 ?'selected=""':'');?>>Diagonal Direita Topo e Inferior</option>
                                    </select>                                
                                </div>
                            </div>  
                            
                            <div class="linha" style="width: 31%;">
                                <div class="txtCampo">Altura vídeo/imagem</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="number" name="altura" value="<?php echo !empty($dados["altura"])?$dados["altura"]:"";?>" >
                                </div>
                            </div>
                        </div>


                        <div class="linhaCompleta formulario-pd">

                            <div class="linha" style="width: 32%;">
                                <div class="txtCampo">Cortar</div>
                                <div class="input-div">
                                    <select name="cortar" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["cortar"]) && $dados["cortar"] == 0 ?'selected=""':'');?>>Não</option>
                                        <option value="1" <?php echo (isset($dados["cortar"]) && $dados["cortar"] == 1 ?'selected=""':'');?>>Sim</option>
                                    </select>                                
                                </div>
                            </div>  

                            <div class="linha" style="width: 32%;">
                                <div class="txtCampo">Modelo</div>
                                <div class="input-div">
                                    <select name="modelo" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["modelo"]) && $dados["modelo"] == 0 ?'selected=""':'');?>>Padrão</option>
                                        <option value="1" <?php echo (isset($dados["modelo"]) && $dados["modelo"] == 1 ?'selected=""':'');?>>Padrão 2</option>
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
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.titulo.value)){
                            return exibirAviso('Informe o título!');
                        }

                        if(!vazio(f.link_yt.value) && !validarVideo(f.link_yt.value)){
                            return exibirAviso('Informe o link do youtube corretamente!');
                        }

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    } 

                    
                    $('#background_color_ativado').on('change',function(){
                        if($(this).is(':checked')){
                            $('input[name="background_color"]').show()
                            $('input[name="background_color_ativado"]').val(1)
                        }else{
                            $('input[name="background_color"]').hide()
                            $('input[name="background_color_ativado"]').val(0)
                        }
                    }).trigger('change');

                    $('#font_color_titulo_ativ').on('change',function(){
                        if($(this).is(':checked')){
                            $('input[name="font_color_titulo"]').show()
                            $('input[name="font_color_titulo_ativ"]').val(1)
                        }else{
                            $('input[name="font_color_titulo"]').hide()
                            $('input[name="font_color_titulo_ativ"]').val(0)
                        }
                    }).trigger('change')

                    $('#font_color_headline_ativ').on('change',function(){
                        if($(this).is(':checked')){
                            $('input[name="font_color_headline"]').show()
                            $('input[name="font_color_headline_ativ"]').val(1)
                        }else{
                            $('input[name="font_color_headline"]').hide()
                            $('input[name="font_color_headline_ativ"]').val(0)
                        }
                    }).trigger('change')

                    $('#font_color_descricao_ativ').on('change',function(){
                        if($(this).is(':checked')){
                            $('input[name="font_color_descricao"]').show()
                            $('input[name="font_color_descricao_ativ"]').val(1)
                        }else{
                            $('input[name="font_color_descricao"]').hide()
                            $('input[name="font_color_descricao_ativ"]').val(0)
                        }
                    }).trigger('change')
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

