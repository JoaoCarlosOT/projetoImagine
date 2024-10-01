<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    $dir ='/arquivos/imagens/banner/';
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/banner/<?=$this->dados["dados"]['id_banner']?>/slides.html" class="botao_padra_1 btn_cinza">Voltar</a>
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
                                <div class="linha" style="width: 43%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 25%;">
                                    <div class="txtCampo">Tipo de Banner</div>
                                    <div class="input-div">
                                        <select name="tipo_banner" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["tipo_banner"]) && $dados["tipo_banner"] == 0 ?'selected=""':'');?>>Imagem Full</option>
                                            <option value="1" <?php echo (isset($dados["tipo_banner"]) && $dados["tipo_banner"] == 1 ?'selected=""':'');?>>Banner com CTA</option>
                                            <option value="2" <?php echo (isset($dados["tipo_banner"]) && $dados["tipo_banner"] == 2 ?'selected=""':'');?>>Vídeo Full</option>
                                        </select>
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
                                <div class="linha" style="width: 10%;">
                                    <div class="txtCampo">Ordem</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="number" name="ordem" value="<?php echo (isset($dados["ordem"])?$dados["ordem"]:"");?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 43%;">
                                <div class="txtCampo">Link</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="link" value="<?php echo (isset($dados["link"])?$dados["link"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 25%;">
                                <div class="txtCampo">Data Início</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="datetime-local" name="dataInicio" value="<?php echo (isset($dados["dataInicio"])?$dados["dataInicio"]:"");?>" >
                                </div>
                            </div>
                            <div class="linha" style="width: 25%;">
                                <div class="txtCampo">Data Fim</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="datetime-local" name="dataFim" value="<?php echo (isset($dados["dataFim"])?$dados["dataFim"]:"");?>" >
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-0">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Altura para largura 1920px</div>
                                    <div class="input-div">
                                        <select name="fullhd_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["fullhd_status"]) && $dados["fullhd_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["fullhd_status"]) && $dados["fullhd_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>
                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="fullhd[]"/>
                                        
                                        <input style="width: 33%;margin-left: 10px;display:none;" class="campo-padrao" id="fullhd" type="hidden" name="fullhd" value="<?php echo (isset($dados["fullhd"])?$dados["fullhd"]:"");?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["fullhd"]) && $dados["fullhd"]):
                                                

                                                $img = $this->lib_imagem->otimizar($dir.$dados["fullhd"],190,90,false,true,80);
                                                ?>
                                                <div  id="fullhd-img" class="img-anexo" style="position: relative;display: flex;">
                                                    <img src="<?php echo  $img;?>">

                                                    <em class="icon-trash icone-td" onclick="jQuery('#fullhd').val('');jQuery('#fullhd-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-0">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Altura para largura 1440px</div>
                                    <div class="input-div">
                                        <select name="extralarge_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["extralarge_status"]) && $dados["extralarge_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["extralarge_status"]) && $dados["extralarge_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="extralarge[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" name="extralarge" id="extralarge" value="<?php echo (isset($dados["extralarge"])?$dados["extralarge"]:"");?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["extralarge"]) && $dados["extralarge"]):

                                                $img = $this->lib_imagem->otimizar($dir.$dados["extralarge"],190,90,false,true,80);
                                                ?>
                                                <div  id="extralarge-img" class="img-anexo" style="position: relative;display: flex;">
                                                    <img src="<?php echo  $img;?>">

                                                    <em class="icon-trash icone-td" onclick="jQuery('#extralarge').val('');jQuery('#extralarge-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-0">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Altura para largura 1200px</div>
                                    <div class="input-div">
                                        <select name="large_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["large_status"]) && $dados["large_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["large_status"]) && $dados["large_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="large[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="large" name="large" value="<?php echo (isset($dados["large"])?$dados["large"]:"");?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["large"]) && $dados["large"]):
                                                $img = $this->lib_imagem->otimizar($dir.$dados["large"],190,90,false,true,80);
                                                ?>
                                                <div  id="large-img" class="img-anexo" style="position: relative;display: flex;">
                                                    <img src="<?php echo  $img;?>">

                                                    <em class="icon-trash icone-td" onclick="jQuery('#large').val('');jQuery('#large-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-0">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Altura para largura 992px</div>
                                    <div class="input-div">
                                        <select name="medium_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["medium_status"]) && $dados["medium_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["medium_status"]) && $dados["medium_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>
                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="medium[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" name="medium" id="medium" value="<?php echo (isset($dados["medium"])?$dados["medium"]:"");?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["medium"]) && $dados["medium"]):
                                                $img = $this->lib_imagem->otimizar($dir.$dados["medium"],190,90,false,true,80);
                                                ?>
                                                <div  id="medium-img" class="img-anexo" style="position: relative;display: flex;">
                                                    <img src="<?php echo  $img;?>">

                                                    <em class="icon-trash icone-td" onclick="jQuery('#medium').val('');jQuery('#medium-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-0">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Altura para largura 768px (mobile)</div>
                                    <div class="input-div">
                                        <select name="small_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["small_status"]) && $dados["small_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["small_status"]) && $dados["small_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="small[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" name="small" id="small" value="<?php echo (isset($dados["small"])?$dados["small"]:"");?>">
                                    </div>
                                </div>

                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["small"]) && $dados["small"]):
                                                $img = $this->lib_imagem->otimizar($dir.$dados["small"],190,90,false,true,80);
                                                ?>
                                                <div  id="small-img" class="img-anexo" style="position: relative;display: flex;">
                                                    <img src="<?php echo  $img;?>">

                                                    <em class="icon-trash icone-td" onclick="jQuery('#small').val('');jQuery('#small-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-0">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Altura para largura 576px</div>
                                    <div class="input-div">
                                        <select name="extrasmall_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["extrasmall_status"]) && $dados["extrasmall_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["extrasmall_status"]) && $dados["extrasmall_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="extrasmall[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="extrasmall" name="extrasmall" value="<?php echo (isset($dados["extrasmall"])?$dados["extrasmall"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["extrasmall"]) && $dados["extrasmall"]):
                                                $img = $this->lib_imagem->otimizar($dir.$dados["extrasmall"],190,90,false,true,80);
                                                ?>
                                                <div  id="extrasmall-img" class="img-anexo" style="position: relative;display: flex;">
                                                    <img src="<?php echo  $img;?>">

                                                    <em class="icon-trash icone-td" onclick="jQuery('#extrasmall').val('');jQuery('#extrasmall-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-2">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Vídeo altura para largura 1920px</div>
                                    <div class="input-div">
                                        <select name="video_fullhd_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["video_fullhd_status"]) && $dados["video_fullhd_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["video_fullhd_status"]) && $dados["video_fullhd_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="video_fullhd[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="video_fullhd" name="video_fullhd" value="<?php echo (isset($dados["video_fullhd"])?$dados["video_fullhd"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["video_fullhd"]) && $dados["video_fullhd"]): ?>
                                                <div  id="video_fullhd-img" class="img-anexo" style="position: relative;display: flex;">

                                                    <video width="190" controls muted style="aspect-ratio: 16/9;">
                                                        <source src="<?=$dir.$dados["video_fullhd"]?>" type="video/mp4">
                                                    </video>

                                                    <em class="icon-trash icone-td" onclick="jQuery('#video_fullhd').val('');jQuery('#video_fullhd-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-2">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Vídeo altura para largura 1440px</div>
                                    <div class="input-div">
                                        <select name="video_extralarge_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["video_extralarge_status"]) && $dados["video_extralarge_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["video_extralarge_status"]) && $dados["video_extralarge_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="video_extralarge[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="video_extralarge" name="video_extralarge" value="<?php echo (isset($dados["video_extralarge"])?$dados["video_extralarge"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["video_extralarge"]) && $dados["video_extralarge"]): ?>
                                                <div  id="video_extralarge-img" class="img-anexo" style="position: relative;display: flex;">

                                                    <video width="190" controls muted style="aspect-ratio: 16/9;">
                                                        <source src="<?=$dir.$dados["video_extralarge"]?>" type="video/mp4">
                                                    </video>

                                                    <em class="icon-trash icone-td" onclick="jQuery('#video_extralarge').val('');jQuery('#video_extralarge-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div> 
                        
                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-2">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Vídeo altura para largura 1200px</div>
                                    <div class="input-div">
                                        <select name="video_large_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["video_large_status"]) && $dados["video_large_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["video_large_status"]) && $dados["video_large_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="video_large[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="video_large" name="video_large" value="<?php echo (isset($dados["video_large"])?$dados["video_large"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["video_large"]) && $dados["video_large"]): ?>
                                                <div  id="video_large-img" class="img-anexo" style="position: relative;display: flex;">

                                                    <video width="190" controls muted style="aspect-ratio: 16/9;">
                                                        <source src="<?=$dir.$dados["video_large"]?>" type="video/mp4">
                                                    </video>

                                                    <em class="icon-trash icone-td" onclick="jQuery('#video_large').val('');jQuery('#video_large-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>
                        
                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-2">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Vídeo altura para largura 992px</div>
                                    <div class="input-div">
                                        <select name="video_medium_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["video_medium_status"]) && $dados["video_medium_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["video_medium_status"]) && $dados["video_medium_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="video_medium[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="video_medium" name="video_medium" value="<?php echo (isset($dados["video_medium"])?$dados["video_medium"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["video_medium"]) && $dados["video_medium"]): ?>
                                                <div  id="video_medium-img" class="img-anexo" style="position: relative;display: flex;">

                                                    <video width="190" controls muted style="aspect-ratio: 16/9;">
                                                        <source src="<?=$dir.$dados["video_medium"]?>" type="video/mp4">
                                                    </video>

                                                    <em class="icon-trash icone-td" onclick="jQuery('#video_medium').val('');jQuery('#video_medium-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>
                        
                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-2">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Vídeo altura para largura 768px (mobile)</div>
                                    <div class="input-div">
                                        <select name="video_small_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["video_small_status"]) && $dados["video_small_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["video_small_status"]) && $dados["video_small_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="video_small[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="video_small" name="video_small" value="<?php echo (isset($dados["video_small"])?$dados["video_small"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["video_small"]) && $dados["video_small"]): ?>
                                                <div  id="video_small-img" class="img-anexo" style="position: relative;display: flex;">

                                                    <video width="190" controls muted style="aspect-ratio: 16/9;">
                                                        <source src="<?=$dir.$dados["video_small"]?>" type="video/mp4">
                                                    </video>

                                                    <em class="icon-trash icone-td" onclick="jQuery('#video_small').val('');jQuery('#video_small-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div>
                        
                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-2">
                                <div class="linha" style="width: 50%;">
                                    <div class="txtCampo">Vídeo altura para largura 576px</div>
                                    <div class="input-div">
                                        <select name="video_extrasmall_status" class="campo-padrao" style="max-width: 200px;">
                                            <option value="1" <?php echo (isset($dados["video_extrasmall_status"]) && $dados["video_extrasmall_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                            <option value="0" <?php echo (isset($dados["video_extrasmall_status"]) && $dados["video_extrasmall_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        </select>

                                        <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="video_extrasmall[]"/>
                                        <input style="max-width: 200px;margin-left: 10px;" class="campo-padrao" type="hidden" id="video_extrasmall" name="video_extrasmall" value="<?php echo (isset($dados["video_extrasmall"])?$dados["video_extrasmall"]:"");?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 200px;">
                                        <?php 
                                            if(isset($dados["video_extrasmall"]) && $dados["video_extrasmall"]): ?>
                                                <div  id="video_extrasmall-img" class="img-anexo" style="position: relative;display: flex;">

                                                    <video width="190" controls muted style="aspect-ratio: 16/9;">
                                                        <source src="<?=$dir.$dados["video_extrasmall"]?>" type="video/mp4">
                                                    </video>

                                                    <em class="icon-trash icone-td" onclick="jQuery('#video_extrasmall').val('');jQuery('#video_extrasmall-img').html('');"></em>
                                                </div>
                                        <?php 
                                            endif;
                                        ?>
                                </div>
                        </div> 

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-1">
                            <div class="linha" style="width: 40%;">
                                <div class="txtCampo">Cor de fundo</div>
                                <div class="input-div">
                                    <input class="campo-padrao"style="height: 45px;" type="color" name="color" value="<?php echo isset($dados["color"])?$dados["color"]:'#FFFFFF';?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 40%;">
                                <div class="txtCampo">Cor de fonte</div>
                                <div class="input-div">
                                    <input class="campo-padrao"style="height: 45px;" type="color" name="colorFonte" value="<?php echo isset($dados["colorFonte"])?$dados["colorFonte"]:'#000000';?>" >
                                </div>
                            </div>
                        </div>
                        

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-1">
                            <div class="linha" style="width: 50%;">
                                <div class="txtCampo">Imagem do banner</div>
                                <div class="input-div">
                                    <select name="imagemBanner_status" class="campo-padrao" style="max-width: 200px;">
                                        <option value="1" <?php echo (isset($dados["imagemBanner_status"]) && $dados["imagemBanner_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                        <option value="0" <?php echo (isset($dados["imagemBanner_status"]) && $dados["imagemBanner_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                    </select>
                                    <input class="campo-padrao" type="file" style="width: 100%;margin-left: 10px;" name="imagemBanner[]"/>
                                    
                                    <input style="width: 33%;margin-left: 10px;display:none;" class="campo-padrao" id="imagemBanner" type="hidden" name="imagemBanner" value="<?php echo (isset($dados["imagemBanner"])?$dados["imagemBanner"]:"");?>">
                                </div>
                            </div>

                            <div class="linha" style="width: 200px;">
                                <?php 
                                    if(isset($dados["imagemBanner"]) && $dados["imagemBanner"]):
                                        

                                        $img = $this->lib_imagem->otimizar($dir.$dados["imagemBanner"],190,90,false,true,80);
                                        ?>
                                        <div  id="imagemBanner-img" class="img-anexo" style="position: relative;display: flex;">
                                            <img src="<?php echo  $img;?>">

                                            <em class="icon-trash icone-td" onclick="jQuery('#imagemBanner').val('');jQuery('#imagemBanner-img').html('');"></em>
                                        </div>
                                <?php 
                                    endif;
                                ?>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd bloco-tipos bloco-tipo-1">
                            <div class="linha" style="width: 14%; max-width: 200px;">
                                <div class="txtCampo">CTA</div>
                                <div class="input-div">
                                    <select name="cta_status" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["cta_status"]) && $dados["cta_status"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                        <option value="1" <?php echo (isset($dados["cta_status"]) && $dados["cta_status"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 40%;">
                                <div class="txtCampo">Título</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="cta_titulo" value="<?php echo (isset($dados["cta_titulo"])?$dados["cta_titulo"]:"");?>" >
                                </div>
                            </div>

                            <div class="linha" style="width: 40%;">
                                <div class="txtCampo">Nomenclatura do Botão</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="cta_btn_nomenclatura" value="<?php echo (isset($dados["cta_btn_nomenclatura"])?$dados["cta_btn_nomenclatura"]:"");?>" >
                                </div>
                            </div>
                        </div>


                        <div class="linhaCompleta formulario-pd">
                            
                            <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Texto botão ou título</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="texto_btn" value="<?php echo (isset($dados["texto_btn"])?$dados["texto_btn"]:"");?>" >
                                    </div>
                            </div>
                            
                            <div class="linha" style="width: 14%; ">
                                    <div class="txtCampo">Modelo do texto</div>
                                    <div class="input-div">
                                        <select name="tipo_texto" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["tipo_texto"]) && $dados["tipo_texto"] == 0 ?'selected=""':'');?>>Padrão</option>
                                            <option value="1" <?php echo (isset($dados["tipo_texto"]) && $dados["tipo_texto"] == 1 ?'selected=""':'');?>>Centralizado</option>
                                            <option value="2" <?php echo (isset($dados["tipo_texto"]) && $dados["tipo_texto"] == 2 ?'selected=""':'');?>>Centralizado no topo</option>
                                        </select>
                                    </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="descricao" name="descricao" style="display: none;"><?php echo (isset($dados["descricao"])?$dados["descricao"]:"");?></textarea>
                                </div>
                            </div>
                        </div>
                        

                    </div>
                </div>
                

                <script>
                    function form_enviar(){
                        var f = document.form;

                        if(vazio(f.nome.value)){
                            return exibirAviso('Informe o nome!');
                        }

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }

                </script>

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
        
    $(document).ready(function() {
        var tipo = $('select[name="tipo_banner"]').val();

        $('.bloco-tipos').hide()
        $('.bloco-tipo-'+tipo).show()
    });

    $('select[name="tipo_banner"]').change(function(){
        var tipo = $('select[name="tipo_banner"]').val();

        $('.bloco-tipos').slideUp()
        $('.bloco-tipo-'+tipo).slideDown()
    }) 

</script>