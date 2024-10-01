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
                    <a href="/admin/banners.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do Banner</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 17%;">
                                    <div class="txtCampo">Tipo do Banner</div>
                                    <div class="input-div">
                                        <select name="tipo" class="campo-padrao">
                                            <option value="fixo" <?php echo (isset($dados["tipo"]) && $dados["tipo"] == 'fixo' ?'selected=""':'');?>>Fixo</option>
                                            <option value="responsivo" <?php echo (isset($dados["tipo"]) && $dados["tipo"] == 'responsivo' ?'selected=""':'');?>>Reponsive</option>
                                            <option value="alturamaxima" <?php echo (isset($dados["tipo"]) && $dados["tipo"] == 'alturamaxima' ?'selected=""':'');?>>Altura Maxima</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="linha" style="width: 17%; <?php echo (!isset($dados["id"])?'display:none;':"");?> ">
                                    <div class="txtCampo">Situação</div>
                                    <div class="input-div">
                                        <select name="situacao" class="campo-padrao">
                                            <option value="1" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="linha" style="width: 17%;">
                                    <div class="txtCampo">Exibir Banner Mobile</div>
                                    <div class="input-div">
                                        <select name="exibir_banner_mobile" class="campo-padrao">
                                            <option value="1" <?php echo (isset($dados["exibir_banner_mobile"]) && $dados["exibir_banner_mobile"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["exibir_banner_mobile"]) && $dados["exibir_banner_mobile"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Altura para largura 1920px</div>
                                    <div class="input-div">
                                        <select name="fullhd_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["fullhd_status"]) && $dados["fullhd_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["fullhd_status"]) && $dados["fullhd_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="number" name="fullhd" value="<?php echo (isset($dados["fullhd"])?$dados["fullhd"]:"");?>">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Altura para largura 1440px</div>
                                    <div class="input-div">
                                        <select name="extralarge_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["extralarge_status"]) && $dados["extralarge_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["extralarge_status"]) && $dados["extralarge_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="number" name="extralarge" value="<?php echo (isset($dados["extralarge"])?$dados["extralarge"]:"");?>">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Altura para largura 1200px</div>
                                    <div class="input-div">
                                        <select name="large_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["large_status"]) && $dados["large_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["large_status"]) && $dados["large_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="number" name="large" value="<?php echo (isset($dados["large"])?$dados["large"]:"");?>">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Altura para largura 992px</div>
                                    <div class="input-div">
                                        <select name="medium_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["medium_status"]) && $dados["medium_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["medium_status"]) && $dados["medium_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="number" name="medium" value="<?php echo (isset($dados["medium"])?$dados["medium"]:"");?>">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Altura para largura 768px</div>
                                    <div class="input-div">
                                        <select name="small_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["small_status"]) && $dados["small_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["small_status"]) && $dados["small_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="number" name="small" value="<?php echo (isset($dados["small"])?$dados["small"]:"");?>">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Altura para largura 576px</div>
                                    <div class="input-div">
                                        <select name="extrasmall_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["extrasmall_status"]) && $dados["extrasmall_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["extrasmall_status"]) && $dados["extrasmall_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="number" name="extrasmall" value="<?php echo (isset($dados["extrasmall"])?$dados["extrasmall"]:"");?>">
                                    </div>
                                </div>
                        </div>
                        

                    </div>
                </div>
                

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

<script>
    function sanfona(elemento){
        $('.informacao').slideUp();
        $(elemento).slideToggle();
    }
</script>

