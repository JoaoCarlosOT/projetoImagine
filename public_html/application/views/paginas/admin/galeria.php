<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
    $categorias = $this->dados["dados"]["categorias"];
    $categorias_selecionadas = $this->dados["dados"]["categorias_selecionadas"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/galeria/galerias.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados da Galeria</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 25%;" style="display: none;">
                                            <div class="txtCampo">Alias</div>
                                            <div class="input-div">
                                                <input class="campo-padrao" type="text" name="alias" value="<?php echo (isset($dados["alias"])?$dados["alias"]:"");?>">
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
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Headline</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="headline" value="<?php echo (isset($dados["headline"])?$dados["headline"]:"");?>" >
                                    </div>
                                </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Categorias <input title="Selecione para ativar a modificação da(s) categoria(s)" value="categorias_yes" class="checkbox" style="vertical-align:-2px;" type="checkbox" name="categorias_yes" id="categorias_yes"/> </div>
                                    <div class="input-div">
                                    <?php
					if($categorias): ?>
					<div>
						<div>
							<div class="caixa_rolagem" id="caixa_rolagem" style="margin-left: 25px;"><?php
								$selecionados = $categorias_selecionadas;
								$pais = $categorias[0];
								$campos_check=1;
								foreach($pais as $pai):
                                    if(isset($categorias[$pai->id]) && $categorias[$pai->id]):?>
                                        <div>- <?php echo $pai->nome; ?></div>
                                        <div style="display: flex;flex-wrap: wrap;margin-top: 5px;">
                                            <?php foreach($categorias[$pai->id] as $filho):?>
                                                <div style="margin-left: 15px;"><input class="checkbox" id="checkbox-<?php echo($campos_check); ?>" style="vertical-align:-2px;" type="checkbox" name="categorias[]"  disabled value="<?php echo $filho->id; ?>" <?php if($selecionados && @in_array($filho->id, $selecionados)) echo 'checked="checked"'; ?> /> <?php echo $filho->nome; ?></div><?php
                                                $campos_check++;
                                            endforeach;?>
                                        </div>
                                    <?php else:?>
                                        <div>
                                            - <?php echo $pai->nome; ?>

                                            <input class="checkbox" style="vertical-align:-2px;" id="checkbox-<?php echo($campos_check); ?>" type="checkbox" name="categorias[]"  disabled value="<?php echo $pai->id; ?>" <?php if($selecionados && @in_array($pai->id, $selecionados)) echo 'checked="checked"'; ?> /> 
                                            
                                        </div>
                                    <?php
										$campos_check++;
									endif;
								endforeach; ?>
							</div>
                            </div>
                    </div><?php
					endif; ?>
					<script type="text/javascript">
						jQuery('#categorias_yes').click(function(){
							var ativar = document.getElementById('categorias_yes');
							if(ativar.checked == true){
								<?php
                                    if(isset($campos_check)):
									for($i = 1; $i <= $campos_check; $i++):
								?>
									document.getElementById("checkbox-<?php echo($i); ?>").disabled = false;
								<?php
									endfor;
                                    endif;
								?>
							}
							else{
								<?php
                                    if(isset($campos_check)):
									for($i = 1; $i <= $campos_check; $i++):
								?>
									document.getElementById("checkbox-<?php echo($i); ?>").disabled = true;
								<?php
									endfor;
                                    endif;
								?>
							}
						});
                    </script>
                    
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Imagens para upload</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" name="imagem_anexo[]" multiple />
                                </div>
                            </div>
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Imagem Atual</div>
                                <div class="anexos-salvos" id="imagens_atuais">
                                    <?php 
                                    if(isset($dados["imagens"]) && $dados["imagens"]):
                                        $dir ='/arquivos/imagens/galeria/';
                                        foreach($dados["imagens"] as $imagem):
                                        $img = $this->lib_imagem->otimizar($dir.$imagem['imagem'],180,130,false,false,80);
                                        ?>
                                        <div id="imagem_item-<?php echo $imagem['id'];?>" class="img-anexo">
                                            <img src="<?php echo $img;?>">
                                            <input type="text" name="txt1_imagem[<?php echo $imagem["id"];?>]" value="<?php echo $imagem["txt1"];?>" placeholder="Texto">
                                            <input type="text" name="txt2_imagem[<?php echo $imagem["id"];?>]" value="<?php echo $imagem["txt2"];?>" placeholder="Texto ">
                                            <!-- <input type="number" name="ordem_imagem[<?php echo $imagem["id"];?>]" value="<?php echo $imagem["ordem"];?>" placeholder="Ordem" style="margin-top: 5px;"> -->

                                            <div style="text-align: left;width: 100%;">Atualizar Imagem:</div>
                                            <input type="file" name="nova_imagem[<?php echo $imagem["id"];?>]" value=""  />

                                            <input type="hidden" id="imagem-<?php echo $imagem["id"];?>" name="ids_imagem[]" value="<?php echo $imagem["id"];?>">
                                            <em class="icon-trash icone-td" onclick="jQuery('#imagem-<?php echo $imagem['id'];?>').val('');jQuery('#imagem_item-<?php echo $imagem['id'];?>').html('<div style=\'text-align: center;width: 100%;margin-top: 20px;\'>Salve para concluir a exclusão</div>')"></em>
                                        </div>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                    
                                </div>
                            </div>    
                        </div>

                    </div>
                </div>
                <script src="/arquivos/javascript/jquery-ui.js"></script>
                
			<script type="text/javascript" >		
				$(function() {
				$('#imagens_atuais').sortable({
					update: function( event, ui ) {

						var itens = $(this).sortable('serialize');
						var galId = <?php echo (int)(isset($dados["id"])?$dados["id"]:"");?>;

                        jQuery.ajax({
                            url: '/admin/Admin_controller_galeria/galeria_ordem',
                            type: 'POST',
                            dataType: 'json',
                            data: {itens:itens,id:galId},
                            error: function(e) {console.log('erro',e)},
                            success: function(res) {
                                // sucesso
                                exibirAviso('Ordenado com sucesso!','ok');
                            }
                        });
					}
				});
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
.anexos-salvos{
    display: flex;
    flex-wrap: wrap;
}
.img-anexo {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    border: 1px solid #bbb9b9;
    width: 175px;
    padding: 2px 6px;
    margin: 4px;
    justify-content: flex-end;
    background: #fff;
    cursor: grab;
}

.img-anexo input[type="text"] {
    height: 25px;
    margin-top: 5px;
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

