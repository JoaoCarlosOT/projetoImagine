<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];

    $situacao = array(
        0 => 'Desabilitado',
        1 => 'Habilitado',
    )
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Estados/Cidades</div>
                <div class="botoesTopo">
                    <a href="/admin/landing-page/estado-cidade.html" class="botao_padra_1 btn_verde">Adicionar</a>
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
                        <table class="tabela_padrao tabela_padrao2" >
                            <thead>
                                <tr>
                                    <!-- <td style="width: 55px;"></td> -->
                                    <!-- <td>Ordenar</td> -->
                                    <td>Nome</td>
                                    <td style="width: 150px;">UF</td>
                                    <td style="width: 150px;">Região</td>
                                    <td style="width: 150px;">Situação</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach($dados["resultado"] as $c) $cat[$c->categoria_pai][] = $c;

                                $link = '/admin/landing-page/estado-cidade/';
                                $link2 = '/admin/landing-page/categoria-ordem/';
                    
                                $k = 1; $i = 0; $it = 0; $n = count($cat[0]);
                                foreach( $cat[0] as $c):?>
                                    <tr class="row<?php echo $k = 1 - $k; ?>">
                                        <!-- <td><?php echo $c->id; ?></td> -->
                                        <!-- <td><a href="<?php echo $link2 . $c->id; ?>.html">Ordenar</a></td> -->
                                        <td>
                                            <a href="<?php echo $link . $c->id; ?>.html"><?php echo ($c->categoria_pai)?'--- ':''; ?><?php echo $c->nome; ?><?php echo ($c->categoria_pai)?' [Cidade]':' [Estado]'; ?></a>
                                        </td>
                                        
                                        <td><?php echo $c->uf; ?></td>
                                        <td><?php echo $c->nome_regiao?$c->nome_regiao:$c->nome_regiao_filho; ?></td>
                                        <td><?php echo $situacao[$c->situacao]; ?></td>
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
                                                <a href="<?php echo $link . $sub->id; ?>.html"><?php echo ($sub->categoria_pai)?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':''; ?><?php echo $sub->nome; ?><?php echo ($sub->categoria_pai)?' [Cidade]':' [Estado]'; ?></a>
                                            </td>
                                            <td><?php echo $sub->uf; ?></td>
                                            <td><?php echo $sub->nome_regiao?$sub->nome_regiao:$sub->nome_regiao_filho; ?></td>
                                            <td><?php echo $situacao[$sub->situacao]; ?></td>
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
                                                    <a href="<?php echo $link . $sub2->id; ?>.html"><?php echo ($sub2->categoria_pai)?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;':''; ?><?php echo $sub2->nome; ?></a>
                                                </td>
                                                <td><?php echo $sub2->uf; ?></td>
                                                <td><?php echo $sub2->nome_regiao?$sub2->nome_regiao:$sub2->nome_regiao_filho; ?></td>
                                                <td><?php echo $situacao[$sub2->situacao]; ?></td>
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
