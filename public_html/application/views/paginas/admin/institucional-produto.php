<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
    $categorias = $this->dados["dados"]["categorias"];
    $categorias_selecionadas = $this->dados["dados"]["categorias_selecionadas"];
    $copywriters = $this->dados["dados"]["copywriters"];
        
    if(!empty($dados['copywriters'])){
        $dados['copywriters'] = json_decode($dados['copywriters'], true);
    }else{
        $dados['copywriters'] = array();
    }

    // Array com a ordem dos IDs
    $order_ids = $dados['copywriters']; 

    // Ordenar o array original com base nos IDs especificados
    if($order_ids){
        usort($copywriters, function($a, $b) use ($order_ids) {
            return array_search($a->id, $order_ids) <=> array_search($b->id, $order_ids);
        });
    }
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/institucional/produtos.html" class="botao_padra_1 btn_cinza">Voltar</a>
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
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Título - Nome <?php if(!empty($dados["alias"])):?><a href="/servicos/<?=$dados["alias"]?>.html" target="_blank">(Visualizar página)</a><?php endif;?></div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:'');?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 67%;">
                                <div class="txtCampo">Headline - Descrição curta</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="descricao" ><?php echo (isset($dados["descricao"])?$dados["descricao"]:"");?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <?php if(file_exists(APPPATH .'controllers/admin/Admin_controller_financeiro.php')):?>
                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo">Ativar para venda:</div>
                                    <div class="input-div">
                                        <select name="ativarVenda" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["ativarVenda"]) && $dados["ativarVenda"] == 0 ?'selected=""':'');?>>Nao</option>
                                            <option value="1" <?php echo (isset($dados["ativarVenda"]) && $dados["ativarVenda"] == 1 ?'selected=""':'');?>>Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="linha" style="width: 30%;" id="ativarBtnQtd">
                                    <div class="txtCampo" data-tooltip="Caso a opção escolhida seja Não, sempre será enviado para o carrinho de compra apenas 1 Produto/Serviço. Se a opção escolhida for Sim, o cliente terá a possibilidade de informar na compra desse produto/serviço a Qtd. de produtos/Serviços que ele deseja comprar">Cliente pode escolher a Qtd. de Produto/Serviço?</div>
                                    <div class="input-div">
                                        <select name="ativarBtnQtd" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["ativarBtnQtd"]) && $dados["ativarBtnQtd"] == 0 ?'selected=""':'');?>>Nao</option>
                                            <option value="1" <?php echo (isset($dados["ativarBtnQtd"]) && $dados["ativarBtnQtd"] == 1 ?'selected=""':'');?>>Sim</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="linha" style="width: 30%;" id="assinatura">
                                    <div class="txtCampo">Esse Produtos/Serviços é assinatura?</div>
                                    <div class="input-div">
                                        <select name="assinatura" class="campo-padrao">
                                            <option value="0" <?php echo (isset($dados["assinatura"]) && $dados["assinatura"] == 0 ?'selected=""':'');?>>Nao</option>
                                            <option value="1" <?php echo (isset($dados["assinatura"]) && $dados["assinatura"] == 1 ?'selected=""':'');?>>Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="linha" style="width: 30%;" id="frequencia_assina">
                                    <div class="txtCampo">Frequência cobrança da assinatura</div>
                                    <div class="input-div">
                                        <select name="frequencia_assina" class="campo-padrao">
                                            <?php foreach($controller->asaas->getCicloAssinatura() as $key => $ciclo):?>
                                                <option value="<?=$key?>" <?php echo (isset($dados["frequencia_assina"]) && $dados["frequencia_assina"] == $key ?'selected=""':'');?>><?=$ciclo?></option> 
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div> 

                                <script>
                                    $('select[name="ativarVenda"], select[name="assinatura"]').on('change',function(){
                                        var tipo_ativo = $('select[name="ativarVenda"]').val();
                                        var tipo_assinatura = $('select[name="assinatura"]').val();

                                        if(tipo_ativo == 1){
                                            $('#ativarBtnQtd').show() 
                                            $('#assinatura').show() 

                                            if(tipo_assinatura == 1){
                                                $('#frequencia_assina').show() 
                                            }else{
                                                $('#frequencia_assina').hide() 
                                            }
                                        }else{
                                            $('#ativarBtnQtd').hide() 
                                            $('#assinatura').hide() 
                                            $('#frequencia_assina').hide() 
                                        }
                                    }) 

                                    $('select[name="ativarVenda"]').trigger('change')
                                </script>

                                <?php else:?>
                                    <input type="hidden" name="ativarVenda" value="0">
                                    <input type="hidden" name="ativarBtnQtd" value="0">
                                    <input type="hidden" name="frequencia_assina" value="">
                                <?php endif;?>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo" data-tooltip="Caso deseje realizar uma promoção e exibir o Preço De: ..... e o Preço Por: ..... Você deverá preencher esse campo Preço De:">Preço De:</div>
                                    <div class="input-div">
                                        <input class="campo-padrao dinheiro" type="text" name="preco_de" value="<?php echo (isset($dados["preco_de"])?$dados["preco_de"]:"");?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 30%;">
                                    <div class="txtCampo" data-tooltip="Preço real do Produto/Serviço que será exibido para o Cliente">Preço Por:</div>
                                    <div class="input-div">
                                        <input class="campo-padrao dinheiro" type="text" name="preco" value="<?php echo (isset($dados["preco"])?$dados["preco"]:"");?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 30%;">
                                <div class="txtCampo" data-tooltip="Caso deseje categorizar esse Produto/Serviço, deverá selecionar a opção SIM, com isso irá aparecer as categorias para que você possa selecionar. Caso ainda não tenha sido criado a categoria desejada, você deverá ir no menu .... Institucional -> Categorias .... e efetuar a criação da categoria desejada.">Deseja Habilitar a Seleção de Categorias?</div>
                                <div class="input-div">
                                    <select class="campo-padrao" name="categorias_yes" id="categorias_yes">
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd" id="bloco-categorias_yes">
                            <div class="linha" style="width: 40%;">
                                <div class="input-div">
                                    <?php if($categorias): ?>
                                        <div>
                                            <div>
                                                <div class="caixa_rolagem" id="caixa_rolagem" style="margin-left: 25px;"><?php
                                                    $selecionados = $categorias_selecionadas;
                                                    $pais = $categorias[0];
                                                    $campos_check=1;
                                                    foreach($pais as $pai):
                                                        if(isset($categorias[$pai->id]) && $categorias[$pai->id]):?>
                                                            <div>- <?php echo $pai->nome; ?></div>
                                                            <div style="">
                                                                <?php foreach($categorias[$pai->id] as $filho):?>
                                                                    <div style="margin-left: 15px;"><input class="checkbox" id="checkbox-<?php echo($campos_check); ?>" style="vertical-align:-2px;" type="checkbox" name="categorias[]"  disabled value="<?php echo $filho->id; ?>" <?php if($selecionados && @in_array($filho->id, $selecionados)) echo 'checked="checked"'; ?> /> <?php echo $filho->nome; ?></div><?php
                                                                    $campos_check++;
                                                                endforeach;?>
                                                            </div>
                                                        <?php else:?>
                                                            <div>
                                                                <input class="checkbox" style="vertical-align:-2px;" id="checkbox-<?php echo($campos_check); ?>" type="checkbox" name="categorias[]"  disabled value="<?php echo $pai->id; ?>" <?php if($selecionados && @in_array($pai->id, $selecionados)) echo 'checked="checked"'; ?> /> <?php echo $pai->nome; ?>
                                                                
                                                            </div>
                                                        <?php
                                                            $campos_check++;
                                                        endif;
                                                    endforeach; ?>
                                                </div>
                                                </div>
                                        </div>
                                    <?php endif; ?>
                    
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            jQuery('#categorias_yes').change(function(){
                                var ativar = document.getElementById('categorias_yes');

                                if(ativar.value == 1){
                                    $("#bloco-categorias_yes").show()
                                    <?php
                                        for($i = 1; $i <= $campos_check; $i++):
                                    ?>
                                        document.getElementById("checkbox-<?php echo($i); ?>").disabled = false;
                                    <?php
                                        endfor;
                                    ?>
                                }
                                else{
                                    $("#bloco-categorias_yes").hide()
                                    <?php
                                        for($i = 1; $i <= $campos_check; $i++):
                                    ?>
                                        document.getElementById("checkbox-<?php echo($i); ?>").disabled = true;
                                    <?php
                                        endfor;
                                    ?>
                                }
                            });
                            jQuery('#categorias_yes').trigger("change")
                        </script>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 67%;">
                                    <div class="txtCampo" data-tooltip="Para que o vídeo possa aparecer na exibição do Produto/Serviço, deverá ser colocado abaixo o link do vídeo do YOUTUBE">Vídeo - Link do YouTube</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="video" value="<?php echo (isset($dados["video"])?$dados["video"]:"");?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Imagem</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" name="imagem_anexo[]"/>
                                </div>
                            </div>
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Fotos Atuais</div>
                                <div class="anexos-salvos">
                                    <?php 
                                    if(isset($dados["imagens"]) && $dados["imagens"]):
                                        $dir ='/arquivos/imagens/produto/';
                                        foreach($dados["imagens"] as $imagem):
                                        $img = $this->lib_imagem->otimizar($dir.$imagem['imagem'],180,130,false,false,80);
                                        ?>
                                        <div id="imagem-img-<?php echo $imagem['id'];?>" class="img-anexo">
                                            <img src="<?php echo $img;?>">
                                            <input type="hidden" id="imagem-<?php echo $imagem["id"];?>" name="ids_imagem[]" value="<?php echo $imagem["id"];?>">
                                            <em class="icon-trash icone-td" onclick="jQuery('#imagem-<?php echo $imagem['id'];?>').val('');jQuery('#imagem-img-<?php echo $imagem['id'];?>').html('<div style=\'text-align: center;width: 100%;margin-top: 20px;\'>Salve para concluir a exclusão</div>')"></em>
                                        </div>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                    
                                </div>
                            </div>    
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição detalhada do Produto/Serviço</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" id="descricao1" name="descricao1" style="display: none;"><?php echo (isset($dados["descricao1"])?$dados["descricao1"]:"");?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">Configurações</div>
                    <div class="informacao" id="bloco2" style="display: none;">
                        <div class="linhaCompleta formulario-pd">

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Alias</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="alias" value="<?php echo (isset($dados["alias"])?$dados["alias"]:"");?>">
                                </div>
                            </div>

                            <div class="linha" style="width: 23%; <?php echo (!isset($dados["id"])?'display:none;':"");?> ">
                                <div class="txtCampo">Situação</div>
                                <div class="input-div">
                                    <select name="situacao" class="campo-padrao">
                                        <option value="1" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 1 ?'selected=""':'');?>>Habilitado</option>
                                        <option value="0" <?php echo (isset($dados["situacao"]) && $dados["situacao"] == 0 ?'selected=""':'');?>>Desabilitado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="linha" style="width: 23%;">
                                <div class="txtCampo">Destaque</div>
                                <div class="input-div">
                                    <select name="destaque" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["destaque"]) && $dados["destaque"] == 0 ?'selected=""':'');?>>Não</option>
                                        <option value="1" <?php echo (isset($dados["destaque"]) && $dados["destaque"] == 1 ?'selected=""':'');?>>Sim</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Meta descrição</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="meta_desc" ><?php echo (isset($dados["meta_desc"])?$dados["meta_desc"]:"");?></textarea>
                                </div>
                            </div>

                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Palavra-chaves</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="meta_words" ><?php echo (isset($dados["meta_words"])?$dados["meta_words"]:"");?></textarea>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Banner</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="file" name="banner[]"/>
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Atual</div>
                                    <div class="anexos-salvos" id="banner-anexo">
                                        <?php 
                                        if(isset($dados["banner"]) && $dados["banner"]):
                                            $dir = '/arquivos/imagens/produto/banner/';
                                            ?>
                                            <div class="img-anexo" style="position: relative;display: flex;"> 
                                                <img src="<?php echo $this->lib_imagem->otimizar($dir.$dados["banner"],210,75,false,true,80);?>" style="max-height: 80px;">
                                                <em class="icon-trash icone-td" onclick="jQuery('#banner_val').val('');jQuery('#banner-anexo').html('<div style=\'text-align: center;width: 100%;margin-top: 20px;\'>Salve para concluir a exclusão</div>');"></em>
                                            </div>
                                        <?php 
                                        endif;
                                        ?>
                                    </div>

                                    <input type="hidden" name="banner" id="banner_val" value="<?php echo isset($dados["banner"])?$dados["banner"]:'';?>">
                                </div>    
                            </div>
                        </div>

                        <!-- <div>
                            <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Icone</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="file" name="icone[]"/>
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Atual</div>
                                    <div class="anexos-salvos" id="icone-anexo">
                                        <?php 
                                        if(false && isset($dados["icone"]) && $dados["icone"]):
                                            $dir = '/arquivos/imagens/produto/icone/';
                                            ?>
                                            <div class="img-anexo" style="position: relative;display: flex;">
                                                <img src="<?php echo $this->lib_imagem->otimizar($dir.$dados["icone"],80,80,false,true,80);?>" style="max-height: 80px;">
                                                <em class="icon-trash icone-td" onclick="jQuery('#icone').val('');jQuery('#icone-anexo').html('<div style=\'text-align: center;width: 100%;margin-top: 20px;\'>Salve para concluir a exclusão</div>');"></em>
                                            </div>
                                        <?php 
                                        endif;
                                        ?>
                                    </div>

                                    <input type="hidden" name="icone" id="icone" value="<?php echo (isset($dados["icone"])?$dados["icone"]:"");?>">
                                </div>    
                            </div>
                        </div> -->


                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 20%;">
                                <div class="txtCampo">Class fontello para icone do menu</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="icone" value="<?php echo (isset($dados["icone"])?$dados["icone"]:"");?>">
                                </div>
                            </div> 
                        </div>



                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Copywriters</div>
                                <div class="input-div">
                                    <select name="copywriters[]" class="campo-padrao" id="copywriters_selectize" multiple> 
                                        <?php if($copywriters):?>
                                            <?php foreach($copywriters as $copywriter):?>
                                                <option value="<?=$copywriter->id?>" <?=!empty($dados['copywriters']) && in_array($copywriter->id, $dados['copywriters'])?"selected":""?>><?=$copywriter->titulo?></option>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 

                <script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
                <script type="text/javascript">
                    CKEDITOR.replace( 'descricao1', {
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

                    $("#copywriters_selectize").selectize({
                        plugins: ["drag_drop", "remove_button"],
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

