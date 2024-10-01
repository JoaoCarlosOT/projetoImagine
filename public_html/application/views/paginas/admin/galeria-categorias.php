<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Categorias</div>
                <div class="botoesTopo">
                    <a href="/admin/galeria/categoria.html" class="botao_padra_1 btn_verde">Adicionar</a>
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <div class="blocoConteudo">
                <div style="width: 100%;">
                <div class="blocoTabela">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <!-- <td style="width: 55px;"></td> -->
                                    <!-- <td>Ordenar</td> -->
                                    <td>Nome</td>
                                    <td style="width: 150px;">Situação</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach($dados["resultado"] as $c) $cat[$c->categoria_pai][] = $c;

                                $link = '/admin/galeria/categoria/';
                                $link2 = '/admin/galeria/categoria-ordem/';
                    
                                $k = 1; $i = 0; $it = 0; $n = count($cat[0]);
                                foreach( $cat[0] as $c):?>
                                    <tr class="row<?php echo $k = 1 - $k; ?>">
                                        <!-- <td><?php echo $c->id; ?></td> -->
                                        <!-- <td><a href="<?php echo $link2 . $c->id; ?>.html">Ordenar</a></td> -->
                                        <td>
                                            <a href="<?php echo $link . $c->id; ?>.html"><?php echo ($c->categoria_pai)?'--- ':''; ?><?php echo $c->nome; ?></a>
                                        </td>
                                        
                                        <td><?php echo $c->situacao; ?></td>
                                        <td><?php echo date('d/m/Y \à\s H:i',strtotime($c->cadastro)); ?></td>
                                    </tr><?php $i++; $it++;
                                    if(isset($cat[$c->id])):
                                        $j = 0;
                                        $ns = count($cat[$c->id]);
                                        foreach($cat[$c->id] as $sub): ?>
                                        <tr class="row<?php echo ($k = 1 - $k); ?>">
                                            <!-- <td><?php echo $sub->id; ?></td> -->
                                            <!-- <td><a href="<?php echo $link2 . $sub->id; ?>.html">Ordenar</a></td> -->
                                            <td>
                                                <a href="<?php echo $link . $sub->id; ?>.html"><?php echo ($sub->categoria_pai)?'--- ':''; ?><?php echo $sub->nome; ?></a>
                                            </td>
                                            <td><?php echo $sub->situacao; ?></td>
                                            <td><?php echo date('d/m/Y \à\s H:i',strtotime($sub->cadastro)); ?></td>
                                        </tr>
                                        <?php $j++; $it++;
                                        if(isset($cat[$sub->id])):
                                            $j2 = 0;
                                            $ns = count($cat[$sub->id]);
                                            foreach($cat[$sub->id] as $sub2): ?>
                                            <tr class="row<?php echo ($k = 1 - $k); ?>">

                                                <!-- <td><?php echo $sub2->id; ?></td> -->
                                                <!-- <td><a href="<?php echo $link2 . $sub2->id; ?>.html">Ordenar</a></td> -->
                                                <td>
                                                    <a href="<?php echo $link . $sub2->id; ?>.html"><?php echo ($sub2->categoria_pai)?'--- --- ':''; ?><?php echo $sub2->nome; ?></a>
                                                </td>
                                                <td><?php echo $sub2->situacao; ?></td>
                                                <td><?php echo date('d/m/Y \à\s H:i',strtotime($sub2->cadastro)); ?></td>
                                            </tr><?php $j2++; $it++;
                                            endforeach;
                                        endif;

                                        endforeach;
                                    endif;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                        
                    <?php else:?>
                        <div class="txt-sem-resultado">Sem resultados</div>
                    <?php endif;?>
                </div>
                </div>
            </div>

        </div>
    </div>
</div>
