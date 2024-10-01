<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Cupons de Desconto</div>
                <div class="botoesTopo">
                    <span class="botao_padra_1 btn_vermelho" onclick="excluir();">Excluir</span>
                    <a href="/admin/financeiro/cupom.html" class="botao_padra_1 btn_verde">Adicionar</a>
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
                        <input class="campo_padrao" type="text" name="codigo_cupom" placeholder="Código Cupom" style="width: 300px;" value="<?php echo (isset($args["codigo_cupom"])?$args["codigo_cupom"]:''); ?>">

                        <select class="campo_padrao" name="situacao" >
                            <!-- <option value="">Situação</option> -->
                            <option value="1" <?php echo ($args["situacao"] === '1'?'selected=""':"");?> >Habilitado</option>
                            <option value="0" <?php echo ($args["situacao"] === '0'?'selected=""':"");?> >Desabilitado</option>
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
                <form class="blocoTabela" action="/admin/Admin_controller_financeiro/cupom_excluir" name="formTabela" method="post">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <td>Nome</td>
                                    <td style="width: 150px;">Situação</td>
                                    <td style="width: 150px;">Cadastro</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="listacheck" name="id[]" value="<?php echo $item->id;?>" >
                                        </td>
                                        <td><a href="/admin/financeiro/cupom/<?php echo $item->id;?>.html" title="<?php echo $item->codigo_cupom;?>" ><?php echo $item->codigo_cupom;?></a></td>
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