<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Tipo de Vagas</div>
                <div class="botoesTopo">
                    
                    <a href="/admin/contato/trabalhe-mensagens.html" class="botao_padra_1 btn_cinza">Voltar</a>
                    <a href="/admin/contato/vaga.html" class="botao_padra_1 btn_verde">Adicionar</a>
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
                                    <td style="width: 150px;">Situação</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <!-- <td >
                                            <?php echo $item->id;?>
                                        </td> -->
                                        <td><a href="/admin/contato/vaga/<?php echo $item->id;?>.html" title="<?php echo $item->nome;?>" ><?php echo ($item->nome?$item->nome:'Sem nome');?></a></td>

                                        <?php if($item->situacao == 0):?>
                                            <td class="text-center">Desabilitado</td>
                                        <?php elseif($item->situacao == 1):?>
                                            <td class="text-center">Habilitado</td>
                                        <?php else:?>
                                            <td class="text-center"></td>
                                        <?php endif;?>
                                        
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
