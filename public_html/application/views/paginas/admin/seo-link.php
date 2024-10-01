<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo ($dados["id"]?'<a href="/'.$dados["link"].'" target="_blank">/'.$dados["link"].'</a>':"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/seo/links.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do SEO</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo ($dados["id"]?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 45%;">
                                    <div class="txtCampo">Titulo</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="title" value="<?php echo $dados["title"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 45%;">
                                    <div class="txtCampo">Canonical</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="canonical" value="<?php echo $dados["canonical"];?>" >
                                    </div>
                                </div>
                        </div>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 45%;">
                                <div class="txtCampo">Descrição</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="description" ><?php echo $dados["description"];?></textarea>
                                </div>
                            </div>
                            <div class="linha" style="width: 45%;">
                                <div class="txtCampo">Palavras-chave:</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="keywords" ><?php echo $dados["keywords"];?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.title.value)){
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