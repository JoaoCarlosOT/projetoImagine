<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Boletim informativo</div>
                <div class="botoesTopo">
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
                        <input class="campo_padrao" type="text" name="nome" placeholder="Nome" style="width: 180px;" value="<?php echo (isset($args["nome"])?$args["nome"]:''); ?>">
                        <input class="campo_padrao" type="text" name="email" placeholder="Email" style="width: 180px;" value="<?php echo (isset($args["email"])?$args["email"]:''); ?>">

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
                <div class="blocoTabela">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <!-- <td style="width: 55px;"></td> -->
                                    <td>Nome</td>
                                    <td>Email</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <!-- <td >
                                            <?php echo $item->id;?>
                                        </td> -->
                                        <td><?php echo ($item->nome?$item->nome:'Não informado');?></td>
                                        <td><?php echo ($item->email?$item->email:'Não informado');?></td>
                                        <td><?php echo date('d/m/Y \à\s H:i',strtotime($item->cadastro));?></td>
                                    </tr>
                                <?php endforeach;?>
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
