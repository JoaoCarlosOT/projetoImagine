<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["pergunta"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/faqs.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do FAQ</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 70%;">
                                <div class="txtCampo">Pergunta</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="pergunta" max="255" value="<?php echo !empty($dados["pergunta"])?$dados["pergunta"]:"";?>" >
                                </div>
                            </div>

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
                                <div class="txtCampo">Resposta</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="resposta" name="resposta" style="display: none;"><?php echo isset($dados["resposta"])?$dados["resposta"]:"";?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
                <script type="text/javascript">
                    CKEDITOR.replace( 'resposta', {
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

                        if(vazio(f.pergunta.value)){
                            return exibirAviso('Informe a pergunta!');
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

