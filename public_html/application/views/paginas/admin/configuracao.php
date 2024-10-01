<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Configurações</div>
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
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Site</div>
                    <div class="informacao" id="bloco1">

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Manutecao:</div>
                                <div class="input-div">
                                    <input type="radio" name="manutecao" value="0" <? if($dados['manutecao'] == 0 ){ echo "checked";}?>>Sim
                                    <input type="radio" name="manutecao" value="1" <? if($dados['manutecao'] == 1 ){ echo "checked";}?>>Não
                                   <!--<textarea class="campo-padrao" name="manutecao"><?php //echo $dados["manutecao"];?></textarea>-->
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

            <form name="form_excluir" action="/admin/Admin_controller_configuracao/con_excluir" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">Excluir Componente</div>
                    <div class="informacao" id="bloco2" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Componente</div>
                                <div class="input-div">
                                    <select name="tipo_componente" class="campo-padrao">
                                        <option value=""></option>
                                        <option value="1">Seo</option>
                                        <option value="2">Landing Pages</option>
                                        <option value="3">Loja Virtual</option>
                                    </select>
                                </div>
                            </div>
                        </div> 

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="input-div">
                                    <a onclick="form_con_excluir();" class="botao_padra_1 btn_vermelho" style="margin: 0;">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="popupFundo1" id="popupConfirmacaoExcluirFundo" onclick="fecharPopup_id('popupConfirmacaoExcluir');"></div>
                <div class="popupSistema1" id="popupConfirmacaoExcluir">
                    <div class="popupInformacoes">
                        <div class="txt-aviso">Informe a senha para confirmar</div>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Senha</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="password" name="senha" />
                                </div>
                            </div>
                        </div>       
                        <span class="botao-popup" onclick="form_con_excluir(true);">Confirmar</span>
                    </div>
                </div>

                <script>
                    function form_con_excluir(senha = false){
                        var f = document.form_excluir;

                        if(vazio(f.tipo_componente.value)){
                            return exibirAviso('Por favor, informe o Componente para excluir!');
                        }

                        if(!senha){
                            abrirPopup_id('popupConfirmacaoExcluir');
                            return;
                        }else{
                            fecharPopup_id('popupConfirmacaoExcluir');
                        }

                        if(vazio(f.senha.value)){
                            return exibirAviso('Por favor, informe a senha para excluir!');
                        }

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>
            </form>

            <form name="form_exportar" action="/admin/Admin_controller_configuracao/con_exportar" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco3');">Exportar Componente</div>
                    <div class="informacao" id="bloco3" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Componente</div>
                                <div class="input-div">
                                    <select name="tipo_componente" class="campo-padrao">
                                        <option value=""></option>
                                        <option value="1">Seo</option>
                                        <option value="2">Landing Pages</option>
                                        <!-- <option value="3">Loja Virtual</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="input-div">
                                    <a onclick="form_con_exportar();" class="botao_padra_1 btn_verde" style="margin: 0;">Exportar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="popupFundo1" id="popupConfirmacaoExportarFundo" onclick="fecharPopup_id('popupConfirmacaoExportar');"></div>
                <div class="popupSistema1" id="popupConfirmacaoExportar">
                    <div class="popupInformacoes">
                        <div class="txt-aviso">Informe a senha para confirmar</div>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Senha</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="password" name="senha" />
                                    </div>
                            </div>
                        </div>       
                        <span class="botao-popup" onclick="form_con_exportar(true);">Confirmar</span>
                    </div>
                </div>

                <script>
                    function form_con_exportar(senha = false){
                        var f = document.form_exportar;

                        if(vazio(f.tipo_componente.value)){
                            return exibirAviso('Por favor, informe o Componente para exportar!');
                        }

                        if(!senha){
                            abrirPopup_id('popupConfirmacaoExportar');
                            return;
                        }else{
                            fecharPopup_id('popupConfirmacaoExportar');
                        }

                        if(vazio(f.senha.value)){
                            return exibirAviso('Por favor, informe a senha para exportar!');
                        }

                        carregando();
                        setTimeout(function(){
                            f.submit();
                            carregado();
                            return exibirAviso('Arquivo ZIP para exportação criado com sucesso!','ok');
                        }, 300);
                    }
                </script>
            </form>

            <form name="form_importar" action="/admin/Admin_controller_configuracao/con_importar" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 20px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco4');">Importar Componente</div>
                    <div class="informacao" id="bloco4" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Componente</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" style="width: 100%;" name="arquivo"/>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="input-div">
                                    <a onclick="form_con_importar();" class="botao_padra_1 btn_verde" style="margin: 0;">Importar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="popupFundo1" id="popupConfirmacaoImportarFundo" onclick="fecharPopup_id('popupConfirmacaoImportar');"></div>
                <div class="popupSistema1" id="popupConfirmacaoImportar">
                    <div class="popupInformacoes">
                        <div class="txt-aviso">Informe a senha para confirmar</div>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Senha</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="password" name="senha" />
                                    </div>
                            </div>
                        </div>       
                        <span class="botao-popup" onclick="form_con_importar(true);">Confirmar</span>
                    </div>
                </div>

                <script>
                    function form_con_importar(senha = false){
                        var f = document.form_importar;

                        if(f.arquivo.files.length > 0){
                            var file = f.arquivo.files[0];

                            // Verificar se o arquivo tem a extensão .zip
                            if (!file.name.toLowerCase().endsWith('.zip')) {
                                f.arquivo.value = ''; 
                                return exibirAviso('Por favor, informe um arquivo ZIP válido.'); 
                            }
                        }else{
                            return exibirAviso('Por favor, informe o arquivo para importar!');
                        }

                        if(!senha){
                            abrirPopup_id('popupConfirmacaoImportar');
                            return;
                        }else{
                            fecharPopup_id('popupConfirmacaoImportar');
                        }

                        if(vazio(f.senha.value)){
                            return exibirAviso('Por favor, informe a senha para importar!');
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

<style>
.popupSistema1{
    width: 600px;
}
.popupSistema1 .txt-aviso {
    text-align: center;
    font-size: 15px;
    color: #848688;
    font-weight: bold;
}

.popupSistema1  .linhaCompleta {
    margin: 0;
}
.botao-popup {
    padding: 10px 7px;
    display: block;
    width: 280px;
    background: #41c731;
    text-align: center;
    color: #fff;
    margin-top: 20px;
    margin-left: auto;
    margin-right: auto;
    cursor: pointer;
}
.botao-popup:hover {
    background: #70fb5f;
}
.botao-popup.c2 {
    background: #0fda0b;
}
.botao-popup.c2:hover {
    background: #0ec30b;
}
</style>