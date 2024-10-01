<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    $estados = $this->dados["dados"]["estados"];
    $regioes = $this->dados["dados"]["regioes"]; 
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo"><?php echo (isset($dados["id"])?"Nº: ".$dados["id"].' - '.mb_strimwidth($dados["nome"], 0, 35, "..."):"Adicionar");?></div>
                <div class="botoesTopo">
                    <a href="/admin/landing-page/estados-cidades.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Dados do Estado/Cidade</div>
                    <div class="informacao" id="bloco1">
                        <input type="hidden" name="id" value="<?php echo (isset($dados["id"])?$dados["id"]:"");?>">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Nome</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome" value="<?php echo (isset($dados["nome"])?$dados["nome"]:"");?>" >
                                    </div>
                                </div>

                                <div class="linha" style="width: 25%;">
                                            <div class="txtCampo">Alias</div>
                                            <div class="input-div">
                                                <input class="campo-padrao" type="text" name="alias" value="<?php echo (isset($dados["alias"])?$dados["alias"]:"");?>">
                                            </div>
                                </div>

                                <div class="linha" style="width: 15%;">
                                    <div class="txtCampo">UF</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="uf" value="<?php echo (isset($dados["uf"])?$dados["uf"]:"");?>" >
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
                            <div class="linha" style="width: 40%;">
                                    <div class="txtCampo">Categoria</div>
                                    <div class="input-div">
                                        <select name="categoria_pai" class="campo-padrao">
                                            <option value="0"> - Categorias</option>
                                            <?php if( $estados ) foreach( $estados as $c ): ?>
                                                <?php if($c->id != $dados['id'] ):?>
                                                    <option value="<?php echo $c->id; ?>" <?php if( isset($dados['categoria_pai']) && $c->id == $dados['categoria_pai'] ) echo 'selected="selected"'; ?>>&nbsp;&nbsp;&nbsp;&nbsp; - <?php echo $c->nome; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                            </div>

                            <div class="linha blocoRegiao" style="width: 20%;">
                                    <div class="txtCampo">Região</div>
                                    <div class="input-div">
                                        <select name="regiao" class="campo-padrao">
                                            <option></option>
                                            <?php if( $regioes ) foreach( $regioes as $regiao ): ?>
                                                <option value="<?php echo $regiao->id; ?>" <?php if( isset($dados['regiao']) && $regiao->id == $dados['regiao'] ) echo 'selected="selected"'; ?>><?php echo $regiao->nome; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                            </div>

                            <div class="linha" style="width: 12%; ">
                                <div class="txtCampo">Capital</div>
                                <div class="input-div">
                                    <select name="capital" class="campo-padrao">
                                        <option value="0" <?php echo (isset($dados["capital"]) && $dados["capital"] == 0 ?'selected=""':'');?>>Não</option>
                                        <option value="1" <?php echo (isset($dados["capital"]) && $dados["capital"] == 1 ?'selected=""':'');?>>Sim</option>
                                    </select>
                                </div>
                            </div>

                            <div class="linha" style="width: 20%;">
                                    <div class="txtCampo">Código IBGE:</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="number" name="codigo_ibge" value="<?php echo (isset($dados["codigo_ibge"])?$dados["codigo_ibge"]:"");?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Title (SEO)</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="text" name="title_seo" value="<?php echo isset($dados["id"])?$dados["title_seo"]:"[Palavra-Chave-01] em [Cidade]";?>" >
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Descrição (SEO)</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="descricao_seo"><?php echo isset($dados["id"])?$dados["descricao_seo"]:"[Descricao-SEO]";?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Keywords (SEO)</div>
                                <div class="input-div"> 
                                    <textarea class="campo-padrao" name="keywords_seo"><?php echo isset($dados["id"])?$dados["keywords_seo"]:'[Palavra-Chave-01] em [Cidade], [Palavra-Chave-02] em [Cidade], [Palavra-Chave-03] em [Cidade], [Palavra-Chave-04] em [Cidade]';?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script src="/arquivos/javascript/ckeditor/ckeditor.js" type="text/javascript"></script>
                <script type="text/javascript">
                    // CKEDITOR.replace( 'descricao', {
                    //     width: 800,
                    //     height: 400,
                    //     filebrowserBrowseUrl: '/arquivos/javascript/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor',
                    //     filebrowserWindowWidth: '1000',
                    //     filebrowserWindowHeight: '700'
                    // });
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

                    $(document).ready( 
                        function(){
                            regiaoVerificar();
                        }
                    )

                    $('select[name="categoria_pai"]').on("change", function(){
                        regiaoVerificar();
                    })

                    function regiaoVerificar(){
                        var res = parseInt($('select[name="categoria_pai"]').val())
                        if(!res){
                            $('.blocoRegiao').show();
                        }else{
                            $('.blocoRegiao').hide();
                        }
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

