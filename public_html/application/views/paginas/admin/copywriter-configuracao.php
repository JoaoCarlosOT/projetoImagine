<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
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
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Configurações Avançadas do Copywriter</div>
                    <div class="informacao" id="bloco1"> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo" data-tooltip="Caso deseje Personalizar a Cor de fundo do Bloco, basta escolher a cor desejada">Background color - Cor de Fundo do Bloco</div>
                                <div class="input-div">
                                    <input type="checkbox" id="background_color_ativ" value="1" style="width: 45px; height: 45px; margin: 0;" <?php echo (!empty($dados["background_color_ativ"])?"checked":"")?>>
                                    <input type="hidden" name="background_color_ativ" value="<?php echo (isset($dados["background_color_ativ"])?$dados["background_color_ativ"]:0);?>">
                                    <input class="campo-padrao" style="height: 45px;" type="color" name="background_color" value="<?php echo !empty($dados["background_color"])?$dados["background_color"]:"#FFFFFF";?>" >
                                </div>
                            </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Classe Título</div>
                                <div class="input-div">
                                    <select name="font_family_titulo" class="campo-padrao">
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
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color_titulo" value="<?php echo $dados["font_color_titulo"];?>">
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd"> 
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Classe Headline</div>
                                <div class="input-div">
                                    <select name="font_family_headline" class="campo-padrao">
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
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color_headline" value="<?php echo $dados["font_color_headline"];?>">
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

    $('#background_color_ativ').on('change',function(){
        if($(this).is(':checked')){
            $('input[name="background_color"]').show()
            $('input[name="background_color_ativ"]').val(1)
        }else{
            $('input[name="background_color"]').hide()
            $('input[name="background_color_ativ"]').val(0)
        }
    }).trigger('change')

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
</script>

<style>
.popupSistema1{
    width: 600px;
}
.popupSistema1 .txt-aviso {
    text-align: center;
    font-size: 15px;
    color: #848688;
    font-weight: bold;
}

.popupSistema1  .linhaCompleta {
    margin: 0;
}
.botao-popup {
    padding: 10px 7px;
    display: block;
    width: 280px;
    background: #41c731;
    text-align: center;
    color: #fff;
    margin-top: 20px;
    margin-left: auto;
    margin-right: auto;
    cursor: pointer;
}
.botao-popup:hover {
    background: #70fb5f;
}
.botao-popup.c2 {
    background: #0fda0b;
}
.botao-popup.c2:hover {
    background: #0ec30b;
}
</style>