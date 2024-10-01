<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"];

    $args = $this->dados["dados"]["args"];
?>

<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">SEO Mapa</div>
                <div class="botoesTopo">
                    <a href="/admin/Admin_controller_seo/gerar_sitemap" class="botao_padra_1 btn_verde">Gerar Sitemap</a>
                    <a href="<?php echo base_url();?>sitemap.xml" target="_blank" class="botao_padra_1">Visualizar arquivo</a>
                </div>
            </div>
        </div>
    </div>
	<div class="meio-componente">
        <div class="container">
            <div class="blocoConteudo">
                <div style="width: 100%;">
                <form class="blocoTabela" name="formTabela" method="post">
                    <?php if($dados["resultado"]): ?>
                        <table class="tabela_padrao" >
                            <thead>
                                <tr>
                                    <td class="text-center">Mapa atual do site <?php echo  base_url(); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dados["resultado"] as $item):?>
                                    <tr>
                                        <td>
                                            <a href="/<?php echo $item->link != "/"?$item->link:""; ?>" target="_blank" ><?php echo $item->link == '/'?'Página Inicial':$item->link;?></a> => <?php echo $item->title;?></a>
                                        </td>
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