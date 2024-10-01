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
                    <a href="/admin/institucional/profissionais.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do Produto/Serviço</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" >
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
                                <div class="linha" style="width: 31%;">
                                    <div class="txtCampo">Texto 1</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="txt1" value="<?php echo (isset($dados["txt1"])?$dados["txt1"]:"");?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31%;">
                                    <div class="txtCampo">Texto 2</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="txt2" value="<?php echo (isset($dados["txt2"])?$dados["txt2"]:"");?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31%;">
                                    <div class="txtCampo">Categoria</div>
                                    <div class="input-div">
                                        <select name="txt3" class="campo-padrao">
                                            <option value="0">Sem categoria</option>
                                            <option value="1" <?php echo (isset($dados["txt3"]) && $dados["txt3"] == "1" ?'selected=""':'');?>>Depoimentos</option>
                                            
                                        </select>
                                    </div>
                                </div>
                        </div>
                        
                        <div>
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
                                            $dir = base_url('arquivos').'/imagens/profissional/';
                                            ?>
                                            <div class="img-anexo">
                                                <img src="<?php echo $dir.$dados["imagem"];?>" style="max-height: 80px;">
                                            </div>
                                        <?php 
                                        endif;
                                        ?>

                                        <input type="hidden" name="imagem" value="<?php echo (isset($dados["imagem"])?$dados["imagem"]:"");?>">
                                    </div>
                                </div>    
                            </div>
                        </div>


                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="descricao" name="descricao" style="display: none;"><?php echo (isset($dados["descricao"])?$dados["descricao"]:"");?></textarea>
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

                        if(vazio(f.nome.value)){
                            exibirAviso('Informe o nome!');
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

