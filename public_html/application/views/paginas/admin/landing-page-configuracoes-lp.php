<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Profissionais</div>
                <div class="botoesTopo">
                    <span class="botao_padra_1 btn_vermelho" onclick="excluir();">Excluir</span>
                    <a href="/admin/landing-page/configuracao-lp.html" class="botao_padra_1 btn_verde">Adicionar</a>
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <div class="blocoConteudo">
                <div style="width: 100%;">
                <div class="filtroConteudo">
                   
                    <form method="POST" action="" name="form_filtro"  class="filtroUmaLinha">
                        <input class="campo_padrao" type="text" name="nome" placeholder="Nome" style="width: 300px;" value="<?php echo (isset($args["nome"])?$args["nome"]:''); ?>">

                        <select class="campo_padrao" name="situacao" >
                            <!-- <option value="">Situação</option> -->
                            <option value="1" <?php echo (isset($args["situacao"]) && $args["situacao"] === '1'?'selected=""':"");?> >Habilitado</option>
                            <option value="0" <?php echo (isset($args["situacao"]) && $args["situacao"] === '0'?'selected=""':"");?> >Desabilitado</option>
                        </select>

                        <span class="btn_filtro" onclick="filtro();">Buscar</span>
                    </form>
                    <script>
                        function filtro(){
                            var f = document.form_filtro;

                            carregando();
                            setTimeout(function(){f.submit();}, 300);
                        }
                    </script>
                </div>
                <form class="blocoTabela" action="/admin/Admin_controller_landingpage/configuracao_excluir" name="formTabela" method="post">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <td>Nome</td>
                                    <td>Configurar LP</td>
                                    <td>LPs Geradas</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="listacheck" name="id[]" value="<?php echo $item->id;?>" >
                                        </td>
                                        <td><?php echo $item->{"Palavra-Chave-01"};?></td>
                                        <td><a href="/admin/landing-page/configuracao-lp/<?php echo $item->id;?>.html" title="<?php echo $item->{"Palavra-Chave-01"};?>" >Configurar LP</a></td> 
                                        <td><a href="/admin/landing-page/repositorio-lp-geradas/<?php echo $item->id;?>.html" title="<?php echo $item->{"Palavra-Chave-01"};?>" >LPs Geradas</a></td>
                                        <td><?php echo date('d/m/Y \à\s H:i',strtotime($item->cadastro));?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        
                    <?php else:?>
                        <div class="txt-sem-resultado">Sem resultados</div>
                    <?php endif;?>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
function excluir(){
    var f = document.formTabela;

    var ids_input = $('input[name="id[]"]:checked');
    var ids_input_i = 0;
    ids_input.each(function(){
        if(this.value){
            ids_input_i++;
        }
    });

    if(ids_input_i == 0){
        return exibirAviso('Selecione os itens que serão excluídos!');
    }


    carregando();
    setTimeout(function(){f.submit();}, 300);
}
</script>