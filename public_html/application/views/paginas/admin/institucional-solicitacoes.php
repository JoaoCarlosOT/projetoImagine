<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Solicitações</div>
                <div class="botoesTopo">
                    <span class="botao_padra_1 btn_vermelho" onclick="excluir();">Excluir</span>
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
                        <select class="campo_padrao" name="situacao" >
                            <option value="">Situação</option>
                            <option value="1" <?php echo ($args["situacao"] === '1'?'selected=""':"");?> >Habilitado</option>
                            <option value="0" <?php echo ($args["situacao"] === '0'?'selected=""':"");?> >Desabilitado</option>
                        </select>
                        
                        <input class="campo_padrao" type="text" name="nome" placeholder="Nome"  value="<?php echo (isset($args["nome"])?$args["nome"]:''); ?>">
                        <input class="campo_padrao" type="text" name="email" placeholder="Email" value="<?php echo (isset($args["email"])?$args["email"]:''); ?>">
                        <input class="campo_padrao" type="text" name="codigo" placeholder="Código"  value="<?php echo (isset($args["codigo"])?$args["codigo"]:''); ?>">

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
                <form class="blocoTabela" action="/admin/Admin_controller_institucional/solicitacao_excluir" name="formTabela" method="post">
                    <div class="blocoTabela">
                        <?php if($dados["resultado"]): ?>
                            <table class="tabela_padrao" >
                                <thead>
                                    <tr>
                                        <td style="width: 30px;"></td>
                                        <td>Nome</td>
                                        <td style="width: 150px;">Cadastro</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($dados["resultado"] as $item):?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="listacheck" name="id[]" value="<?php echo $item->id;?>" >
                                            </td>
                                            <td><a href="/admin/institucional/solicitacao/<?php echo $item->id;?>.html" title="<?php echo $item->nome;?>" ><?php echo $item->nome;?></a></td>
                                        

                                            <td><?php echo date('d/m/Y \à\s H:i',strtotime($item->cadastro));?></td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                            
                        <?php else:?>
                            <div class="txt-sem-resultado">Sem resultados</div>
                        <?php endif;?>
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