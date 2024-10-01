<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">SEO Links</div>
                <div class="botoesTopo">
                    <span class="botao_padra_1 btn_vermelho" onclick="abrirPopup_id('popupConfirmacao');">Redefinir</span>
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <div class="blocoConteudo">
                <div style="width: 100%;">
                <form class="blocoTabela" action="/admin/Admin_controller_seo/excluir_link" name="formTabela" method="post">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <td >Página</td>
                                    <!-- <td style="width: 200px;">Titulo</td>
                                    <td>Descrição</td>
                                    <td style="width: 150px;">Palavras-chave</td> -->
                                    <td style="width: 80px;">Link</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="listacheck" name="id[]" value="<?php echo $item->id;?>" >
                                        </td>
                                        <td>
                                            <!-- <a href="/admin/seo/link/<?php echo $item->id;?>.html" ><?php echo $item->link;?></a> -->

                                            <span onclick="abrirPopup_id('popupLink<?php echo $item->id;?>');" style="text-decoration: underline;cursor: pointer;"><?php echo $item->link == '/'?'Página Inicial':$item->link;?></span>
                                        </td>
                                        <!-- <td><?php echo $item->title;?></td>
                                        <td><?php echo $item->description;?></td>
                                        <td><?php echo $item->keywords;?></td> -->
                                        <td><a href="/<?php echo $item->link != "/"?$item->link:""; ?>" target="_blank" >Visualizar</a></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        
                    <?php else:?>
                        <div class="txt-sem-resultado">Sem resultados</div>
                    <?php endif;?>

                    <div class="popupFundo1" id="popupConfirmacaoFundo" onclick="fecharPopup_id('popupConfirmacao');"></div>
                    <div class="popupSistema1" id="popupConfirmacao">
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

                                <span class="botao-popup" onclick="excluir();">Confirmar</span>
                        </div>
                    </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
function excluir(){
    var f = document.formTabela;

    // var ids_input = $('input[name="id[]"]:checked');
    // var ids_input_i = 0;
    // ids_input.each(function(){
    //     if(this.value){
    //         ids_input_i++;
    //     }
    // });

    // if(ids_input_i == 0){
    //     return exibirAviso('Selecione os itens que serão excluídos!');
    // }


    carregando();
    setTimeout(function(){f.submit();}, 300);
}
</script>

<?php if($dados["resultado"]): ?>
    <?php foreach($dados["resultado"] as $item):?>

        <div class="popupFundo1" id="popupLink<?php echo $item->id;?>Fundo" onclick="fecharPopup_id('popupLink<?php echo $item->id;?>');"></div>
        <div class="popupSistema1" id="popupLink<?php echo $item->id;?>">
            <form class="popupInformacoes" name="formlink<?php echo $item->id;?>" id="formlink<?php echo $item->id;?>" >
                        <div class="tituloTopo text-center" style="margin-bottom: 10px;"><?php echo $item->link == '/'?'Página Inicial':$item->link;?></div>
                        <input type="hidden" name="id" value="<?php echo $item->id;?>" >
                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;"  id="title<?php echo $item->id;?>">
                                    <div class="txtCampo">Titulo (<span><?php echo strlen($item->title);?></span>)</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="title" value="<?php echo $item->title;?>"  onkeyup="contar_caracteres('title<?php echo $item->id;?>')">
                                    </div>
                                </div>
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Canonical</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="canonical" value="<?php echo $item->canonical;?>" >
                                    </div>
                                </div>
                        </div>
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 100%;" id="description<?php echo $item->id;?>">
                                <div class="txtCampo">Descrição (<span><?php echo strlen($item->description);?></span>)</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="description" style="height: 91px;" onkeyup="contar_caracteres('description<?php echo $item->id;?>')" ><?php echo $item->description;?></textarea>
                                </div>
                            </div>
                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Palavras-chave:</div>
                                <div class="input-div">
                                    <textarea class="campo-padrao" name="keywords" ><?php echo $item->keywords;?></textarea>
                                </div>
                            </div>
                        </div>

                <span class="botao-popup c2" onclick="salvar_link( <?php echo $item->id;?> );">Salvar</span>
            </form>
        </div>
    <?php endforeach;?>
<?php endif;?>

<script>
function contar_caracteres(id_input){
    jQuery('#'+id_input+' .txtCampo span').html( jQuery('#'+id_input+' .campo-padrao').val().length );
}
</script>
<script>
function salvar_link(link_id){

    carregando();
    var form_dados = jQuery('#formlink'+link_id).serialize();

    jQuery.ajax({
        url: '/admin/Admin_controller_seo/salvar_link_ajax',
        type: 'POST',
        dataType: 'json',
        data: form_dados,
        error: function() {
            console.log("Erro ao salvar link");
        },
        success: function(res) {
            carregado();
            if(res == true){
                fecharPopup_id('popupLink'+link_id);
                exibirAviso('Salvo com sucesso','ok');
            }else{
                exibirAviso('Erro ao salvar dados da página');
            }

        }
    });
}
</script>

<style>
.popupSistema1{
    width: 600px;
}
.popupSistema1 .txt-aviso {
    text-align: center;
    font-size: 15px;
    color: #b30505;
    font-weight: bold;
}

.popupSistema1  .linhaCompleta {
    margin: 0;
}
.botao-popup {
    padding: 10px 7px;
    display: block;
    width: 280px;
    background: red;
    text-align: center;
    color: #fff;
    margin-top: 20px;
    margin-left: auto;
    margin-right: auto;
    cursor: pointer;
}
.botao-popup:hover {
    background: #d80c0c;
}
.botao-popup.c2 {
    background: #0fda0b;
}
.botao-popup.c2:hover {
    background: #0ec30b;
}
</style>