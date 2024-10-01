<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Configurações de emails</div>
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
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados</div>
                    <div class="informacao" id="bloco1">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Contato</div>
                                <div class="input-div">
                                   <textarea class="campo-padrao" name="mensagem_contato"><?php echo $dados["mensagem_contato"];?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Trabalhe Conosco</div>
                                <div class="input-div">
                                   <textarea class="campo-padrao" name="mensagem_trabalhe"><?php echo $dados["mensagem_trabalhe"];?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Boletim Informativo</div>
                                <div class="input-div">
                                   <textarea class="campo-padrao" name="mensagem_depoimento"><?php echo $dados["mensagem_depoimento"];?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Depoimento</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="mensagem_boletim"><?php echo $dados["mensagem_boletim"];?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Solicitação</div>
                                <div class="input-div">
                                <textarea class="campo-padrao" name="mensagem_solicitacao"><?php echo $dados["mensagem_solicitacao"];?></textarea>
                                </div>
                            </div>
                        </div>

                        <?php if(file_exists(APPPATH .'controllers/admin/Admin_controller_financeiro.php')):?>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Confirmação de Compra</div>
                                <div class="input-div">
                                <textarea class="campo-padrao" name="mensagem_confirmacao_compra"><?php echo $dados["mensagem_confirmacao_compra"];?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php else:?>
                            <input type="hidden" name="mensagem_confirmacao_compra" value="">
                        <?php endif;?>

                        <?php if(file_exists(APPPATH .'controllers/admin/Admin_controller_landingpage.php')):?>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Email de Solicitação Landing Page</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="mensagem_solicitacao_lp"><?php echo $dados["mensagem_solicitacao_lp"];?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php else:?>
                            <input type="hidden" name="mensagem_solicitacao_lp" value="">
                        <?php endif;?>

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

