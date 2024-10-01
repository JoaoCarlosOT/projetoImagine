<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];

    $args = $this->dados["dados"]["args"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Logs</div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <div class="blocoConteudo">
                <div style="width: 100%;">
                <div class="filtroConteudo">
                   
                    <form method="POST" action="" name="form_filtro"  class="filtroUmaLinha">
                        <select name="id_usuario" class="campo_padrao">
                            <option value="">Usuário</option>
                            <?php foreach($this->dados["dados"]['usuarios'] as $usuario):?>
                                <option value="<?php echo $usuario["id"];?>" <?php echo (isset($args["id_usuario"]) && $args["id_usuario"] === ''.$usuario["id"] ?'selected=""':'');?>><?php echo $usuario["nome"];?></option>
                            <?php endforeach;?>
                        </select>

                        <input class="campo_padrao" type="number" name="id_cliente" placeholder="Num. do cliente" value="<?php echo(isset($args["id_cliente"])?$args["id_cliente"]:'');?>">

                        <input class="campo_padrao" type="date" name="data_ini" style="margin-left: 40px;" value="<?php echo(isset($args["data_ini"])?$args["data_ini"]:'');?>">
                        <span style="padding: 12px 5px 0;">até</span>
                        <input class="campo_padrao" type="date" name="data_fim" value="<?php echo(isset($args["data_fim"])?$args["data_fim"]:'');?>">

                        <span class="btn_filtro" onclick="filtro();">Buscar</span>
                    </form>
                </div>
                <script>
                        function filtro(){
                            var f = document.form_filtro;

                            carregando();
                            setTimeout(function(){f.submit();}, 300);
                        }
                </script>
                <div class="blocoTabela">
                    
                        <table class="tabela_padrao">
                            <thead>
                                <tr>
                                    <td style="width: 80px;">Num. Log</td>
                                    <td style="width: 200px;">Usuário</td>
                                    <td>Log</td>
                                    <td style="width: 145px;">Cadastrado</td>
                                </tr>
                            </thead>
                            <?php if($dados): ?>
                            <tbody>
                                <?php foreach($dados as $log):?>
                                    <tr>
                                        <td><?php echo $log["id"];?></td>
                                        <td><?php echo $log["usuario_nome"];?></td>
                                        <td>
                                            <?php echo $log["log"];?>

                                            <?php if(!empty($log["id_cliente"])):?>
                                                <br>
                                                Cliente: <?php echo ($log["id_cliente"]?'(#'.$log["id_cliente"].')':'');?> <?php echo ($log["cliente_nome"]?$log["cliente_nome"]:'');?>
                                            <?php endif;?>
                                        </td>
                                        <td><?php echo date('d/m/Y \à\s H:i',strtotime($log["cadastrado"]));?></td>
                                    </tr>
                                <?php endforeach;?>

                            </tbody>
                            <?php endif;?>
                        </table>

                    <?php if(!$dados): ?>
                        <div class="txt-sem-resultado">Não há nenhum resultado corresponde à sua consulta</div>
                    <?php endif;?>
                </div>
                </div>
            </div>

        </div>
    </div>
</div>