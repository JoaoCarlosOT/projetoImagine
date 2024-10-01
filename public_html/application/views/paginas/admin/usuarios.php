<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    
    $args = $this->dados["dados"]["args"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Usuários</div>
                <div class="botoesTopo">
                    
                    <span class="botao_padra_1 btn_vermelho" onclick="excluir();">Excluir</span>

                    <a href="/admin/usuario.html" class="botao_padra_1 btn_verde">Novo usuario</a>
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
                <form class="blocoTabela" action="/admin/Admin_controller_admin/admin_excluir" name="formTabela" method="post">
                <?php if($dados["usuarios"]): ?>
                    <table class="tabela_padrao">
                        <thead>
                            <tr>
                                <td></td>
                                <td>Nome</td>
                                <td>Email</td>
                                <td>Situação</td>
                                <td>Último acesso</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($dados["usuarios"] as $usuario):?>
                        <tr>
                            <td>
                                <input type="checkbox" class="listacheck" name="id[]" value="<?php echo $usuario->id;?>" >
                            </td>
                            <td><a href="/admin/usuario/<?php echo $usuario->id;?>.html"><?php echo $usuario->nome;?></a></td>
                            <td class="text-center"><?php echo $usuario->email;?></td>

                            <?php if($usuario->situacao == 0):?>
                                <td class="text-center">Desabilitado</td>
                            <?php elseif($usuario->situacao == 1):?>
                                <td class="text-center">Habilitado</td>
                            <?php else:?>
                                <td class="text-center"><?php echo $usuario->situacao;?></td>
                            <?php endif;?>

                            <td class="text-center"><?php echo ($usuario->ultimo_login != "0000-00-00 00:00:00" && $usuario->ultimo_login != null? date('d/m/Y \à\s H:i',strtotime($usuario->ultimo_login)):'Não acessou');?></td>
                        </tr>
                    <?php endforeach;?>  

                        </tbody>
                    </table>
                    <?php else:?>
                        <div class="txt-sem-resultado">Não há nenhum resultado corresponde à sua consulta</div>
                    <?php endif;?>
                </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    td.contrato a,td.servico a {
    text-decoration: underline;
    color: #0047af !important;
}
</style>


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