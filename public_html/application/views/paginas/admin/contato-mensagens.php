<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">CRM - Mensagens registradas</div>
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

                        <input class="campo_padrao" type="date" name="data_ini" placeholder="Data início" value="<?php echo (isset($args["data_ini"])?$args["data_ini"]:'');?>">
                        <input class="campo_padrao" alt="phone" type="text" name="telefone" placeholder="Telefone" value="<?php echo (isset($args["telefone"])?$args["telefone"]:'');?>">

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
                                    <td style="width: 225px;">Email</td>
                                    <td style="width: 136px;">Telefone 1</td>
                                    <td style="width: 136px;">Telefone 2</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <!-- <td >
                                            <?php echo $item->id;?>
                                        </td> -->
                                        <td><a href="/admin/contato/mensagem/<?php echo $item->id;?>.html" title="<?php echo $item->nome;?>" ><?php echo ($item->nome?$item->nome:'Sem nome');?></a></td>
                                        
                                        <?php if($item->email):?>
                                            <td><?php echo $item->email;?></td>
                                        <?php else:?>
                                            <td>Não Informado</td>
                                        <?php endif;?>
                                        
                                        <?php if($item->telefone1):?>
                                            <td><?php echo '+ '.$item->dialCode.' '.$item->telefone1;?></td>
                                        <?php else:?>
                                            <td>Não Informado</td>
                                        <?php endif;?>
                                        <?php if($item->telefone2):?>
                                            <td><?php echo '+ '.$item->dialCode2.' '.$item->telefone2;?></td>
                                        <?php else:?>
                                            <td>Não Informado</td>
                                        <?php endif;?>

                                        <td><?php echo date('d/m/Y \à\s H:i',strtotime($item->datahora_cadastro));?></td>
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
