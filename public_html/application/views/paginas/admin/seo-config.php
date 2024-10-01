<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">SEO Configurações</div>
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
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do SEO Configurações</div>
                    <div class="informacao" id="bloco1">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Author</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="author" value="<?php echo $dados["author"];?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Publisher</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="publisher" value="<?php echo $dados["publisher"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Designer</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="designer" value="<?php echo $dados["designer"];?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Copyright</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="copyright" value="<?php echo $dados["copyright"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Reply-to</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="replyto" value="<?php echo $dados["replyto"];?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Generator</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="generator" value="<?php echo $dados["generator"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Go:site_name</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="site_name" value="<?php echo $dados["site_name"];?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 47%;">
                                    <div class="txtCampo">Go:image</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="file" name="imagem_anexo[]" >
                                    </div>
                                </div>
                            <div class="linha" style="width: 49%;margin-left: auto;">
                                <div class="txtCampo">Imagem Atual</div>
                                <div class="anexos-salvos">
                                    <?php 
                                    if(isset($dados["imagem"]) && $dados["imagem"]):
                                        $dir ='/arquivos/imagens/seo/';
                                        $img = $this->lib_imagem->otimizar($dir. $dados["imagem"],180,130,false,false,80);
                                    ?>
                                        <img src="<?php echo $img;?>">
                                    <?php endif;?>
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