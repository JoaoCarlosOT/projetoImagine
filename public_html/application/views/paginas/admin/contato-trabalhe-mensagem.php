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
                    <a href="/admin/contato/trabalhe-mensagens.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <!-- <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a> -->
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 71%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" disabled="">
                                    </div>
                                </div>

                                <div class="linha" style="width: 25%;">
                                    <div class="txtCampo">Cadastrado</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" value="<?php echo (isset($dados["cadastro"])?date('d/m/Y \à\s H:i',strtotime($dados["cadastro"])):"");?>" disabled="">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Email</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email" value="<?php echo (isset($dados["email"])?$dados["email"]:"");?>" disabled="">
                                    </div>
                                </div>

                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Telefone 1</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" alt="phone" name="telefone1" value="<?php echo (isset($dados["telefone1"])?$dados["telefone1"]:"");?>" disabled="">
                                    </div>
                                </div>

                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Telefone 2</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" alt="phone" name="telefone2" value="<?php echo (isset($dados["telefone2"])?$dados["telefone2"]:"");?>"  disabled="">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Mensagem</div>
                                <div class="input-div">
                                   <textarea class="campo-padrao" name="obs" disabled=""><?php echo (isset($dados["obs"])?$dados["obs"]:"");?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Anexo</div>
                                <div class="anexos-salvos">
                                    <?php 
                                    if(isset($dados["arquivo"]) && $dados["arquivo"]):
                                        $dir = base_url('arquivos').'/imagens/contato/trabalhe/';
                                        ?>
                                        <a href="<?php echo $dir.$dados["arquivo"];?>" download=""><?php echo $dados["arquivo"];?></a>
                                        <input type="hidden" name="arquivo" value="<?php echo $dados["arquivo"];?>">
                                    <?php
                                    endif;
                                    ?>
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

