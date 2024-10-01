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
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Configurações Avançadas do Artigo</div>
                    <div class="informacao" id="bloco1">
                        
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor do background do Banner do Topo</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="background_color" value="<?php echo $dados["background_color"];?>">
                                </div>
                            </div>
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Cor da Fonte do Texto que fica no Banner do Topo</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="color" style="height: 45px;" name="font_color" value="<?php echo $dados["font_color"];?>">
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Font family Titulo</div>
                                <div class="input-div">
                                    <select name="font_family_titulo" class="campo-padrao">
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
                                        <option value="headline" <?php echo (isset($dados["font_family_headline"]) && $dados["font_family_headline"] == 'headline' ?'selected=""':'');?>>Headline 1</option>
                                        <option value="headline2" <?php echo (isset($dados["font_family_headline"]) && $dados["font_family_headline"] == 'headline2' ?'selected=""':'');?>>Headline 2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">CTA</div>
                    <div class="informacao" id="bloco2" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Título</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="titulo_cta" value="<?php echo (isset($dados["titulo_cta"])?$dados["titulo_cta"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Nomenclatura do Botão</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="nomenclatura_bt_cta" value="<?php echo (isset($dados["nomenclatura_bt_cta"])?$dados["nomenclatura_bt_cta"]:"");?>" >
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