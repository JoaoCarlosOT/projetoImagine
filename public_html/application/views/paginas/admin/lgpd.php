<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">LGPD</div>
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
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">POLÍTICA DE PRIVACIDADE</div>
                    <div class="informacao" id="bloco1" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição:</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="des_politica_privacidade" name="des_politica_privacidade" style="display: none;"><?php echo $dados["des_politica_privacidade"];?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">TERMOS DE USO</div>
                    <div class="informacao" id="bloco2" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição:</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="des_termos_uso" name="des_termos_uso" style="display: none;"><?php echo $dados["des_termos_uso"];?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco3');">POLÍTICA DE COOKIES</div>
                    <div class="informacao" id="bloco3" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição:</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="des_politica_cookies" name="des_politica_cookies" style="display: none;"><?php echo $dados["des_politica_cookies"];?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco4');">CONSENTIMENTO PARA O PROCESSAMENTO DE DADOS E COMUNICAÇÃO</div>
                    <div class="informacao" id="bloco4" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição:</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="des_consentimento" name="des_consentimento" style="display: none;"><?php echo $dados["des_consentimento"];?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
                <script type="text/javascript">
                    CKEDITOR.replace( 'des_politica_privacidade', {
                        width: 800,
                        height: 400,
                        filebrowserBrowseUrl: '/arquivos/javascript/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor',
                        filebrowserWindowWidth: '1000',
                        filebrowserWindowHeight: '700'
                    });

                    CKEDITOR.replace( 'des_termos_uso', {
                        width: 800,
                        height: 400,
                        filebrowserBrowseUrl: '/arquivos/javascript/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor',
                        filebrowserWindowWidth: '1000',
                        filebrowserWindowHeight: '700'
                    });

                    CKEDITOR.replace( 'des_politica_cookies', {
                        width: 800,
                        height: 400,
                        filebrowserBrowseUrl: '/arquivos/javascript/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor',
                        filebrowserWindowWidth: '1000',
                        filebrowserWindowHeight: '700'
                    });

                    CKEDITOR.replace( 'des_consentimento', {
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