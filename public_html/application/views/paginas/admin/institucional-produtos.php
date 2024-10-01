<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];

    // $categorias = $this->dados["dados"]["categorias"];
    $categorias = $this->dados["dados"]["categorias_arvore"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Produtos/Serviços</div>
                <div class="botoesTopo">
                    <span class="botao_padra_1 btn_vermelho" onclick="excluir();">Excluir</span>
                    <a href="/admin/institucional/produto.html" class="botao_padra_1 btn_verde">Adicionar</a>
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
                            <option value="">Todos Situação</option>
                            <option value="1" <?php echo (isset($args["situacao"]) && $args["situacao"] == '1'?'selected=""':"");?> >Habilitado</option>
                            <option value="0" <?php echo (isset($args["situacao"]) && $args["situacao"] == '0'?'selected=""':"");?> >Desabilitado</option>
                        </select>
                        <select class="campo_padrao" name="categoria" >
                            <option value="">Categoria</option>

                            <?php 
                                if( $categorias ): 
                                foreach($categorias as $c) $cat[$c->categoria_pai][] = $c;

                                $link = '/admin/institucional/categoria/';
                                $link2 = '/admin/institucional/categoria-ordem/';
                    
                                $k = 1; $i = 0; $it = 0; $n = count($cat[0]);
                                foreach( $cat[0] as $c):?>

                                    <option value="<?php echo $c->id; ?>" <?php echo (isset($args["categoria"])&& $args["categoria"] == $c->id?'selected=""':"");?>><?php echo $c->nome; ?></option>
                                    
                                    <?php if(isset($cat[$c->id])):?>
                                        <?php foreach($cat[$c->id] as $sub): ?>
                                            <option value="<?php echo $sub->id; ?>" <?php echo (isset($args["categoria"])&& $args["categoria"] == $sub->id?'selected=""':"");?>>- <?php echo $sub->nome; ?></option>
                                        
                                            <?php if(isset($cat[$sub->id])):
                                                    foreach($cat[$sub->id] as $sub2): ?>
                                                        <option value="<?php echo $sub2->id; ?>" <?php echo (isset($args["categoria"])&& $args["categoria"] == $sub2->id?'selected=""':"");?>>-- <?php echo $sub2->nome; ?></option>
                                                    <?php endforeach; ?>
                                             <?php endif; ?>

                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    
                            <?php 
                                endforeach; 
                            endif;    
                            ?>
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
                <form class="blocoTabela" action="/admin/institucional/produtos/excluir.html" name="formTabela" method="post">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <td>Nome</td>
                                    <td style="width: 150px;">Categorias</td>
                                    <td style="width: 150px;">Situação</td>
                                    <td style="width: 150px;">Atualizado</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="listacheck" name="id[]" value="<?php echo $item->id;?>" >
                                        </td>
                                        <td><a href="/admin/institucional/produto/<?php echo $item->id;?>.html" title="<?php echo $item->nome;?>" ><?php echo $item->nome;?></a></td>

                                        <td>
                                            <?php
                                                $cat_retorno = $this->model->bucarCategoriasPaiFilhaProduto($item->id);
                                                
                                                if($cat_retorno):
                                            ?>

                                            <?php foreach($cat_retorno as $cat): ?>
                                                <?php if(isset($cat->pai) && $cat->pai):?>
                                                    <div><?php echo ''.$cat->pai->nome;?></div>
                                                    <div><?php echo '-- '.$cat->nome;?></div>
                                                <?php else:?>
                                                    <div><?php echo $cat->nome;?></div>
                                                <?php endif;?>

                                            <?php endforeach; ?>

                                            <?php endif; ?>
                                        </td>

                                        <?php if($item->situacao == 0):?>
                                            <td class="text-center">Desabilitado</td>
                                        <?php elseif($item->situacao == 1):?>
                                            <td class="text-center">Habilitado</td>
                                        <?php else:?>
                                            <td class="text-center"></td>
                                        <?php endif;?>
                                        
                                        <td><?php echo date('d/m/Y \à\s H:i',strtotime($item->atualizado));?></td>
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