<?php
$logo = '/arquivos/imagens/logo.png';

$artigos = $this->dados["dados"]["resultado"];
$dir_artigo = '/arquivos/imagens/blog/';

?>


<div class="bloco-lista-blog-2" id="blogs_listageem">
    <div class="container">
        <h1 class="tituloPadrao">Rota do Sucesso</h1>

        <div class="bloco-lista-2" id="blocoListaBlog2">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($artigos as $ka => $artigo):
                    if($artigo->imagem){
                        $img_p = $controller->imagineImagem->otimizar($dir_artigo.$artigo->imagem, 460, 260, false, true, 80);
                    }else{
                        // logo
                        $img_p = $controller->imagineImagem->otimizar($logo, 460, 260, false, false, 80);
                    }

                    ?>
                    <div class="item artigo-<?= $artigo->id?> ">
                        <a href="/blog/<?=$artigo->alias?>.html" title="<?=$artigo->titulo?>">
                            <div class="img">
                                <?php if($img_p):?>
                                    <img src="<?=$img_p?>" title="<?=$artigo->titulo?>" alt="<?=$artigo->titulo?>">
                                <?php endif;?>
                            </div>
                            <div class="texto">
                                <div class="tituloPadrao4 titulo"><?=mb_strimwidth($artigo->titulo, 0, 60, "...");?></div>
                                <div class="textoPadrao cadastro"><?= date("d/m/Y", strtotime($artigo->cadastro))?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                        
                </div>
            </div>

            <?php 
                echo $controller->gerar_paginacao('blog');
            ?>
        </div>

    </div>
</div>