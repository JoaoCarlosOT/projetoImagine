<?php

$produtos = $this->dados["dados"]["resultado"];
// $categorias = $this->dados["dados"]["categorias"];
$dir_p = '/arquivos/imagens/produto/';

$busca = '';
if(isset($_POST['busca']) && $_POST['busca']){
    $busca = $_POST['busca'];
}
?>

<style>
.bloco-lista-consultas {
    padding: 20px 0;
    padding-bottom: 80px;
}

.bloco-categorias-lista {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 10px 0 25px;
}

.bloco-categorias-lista .bloco {
    margin: 5px 5px;
    background: #ffc298;
    color: #7095ab;
    padding: 5px 19px;
    border: 1px solid #7095ab;
    font-size: 13px;
    cursor: pointer;
    transition: 0.3s all;
}

.bloco-categorias-lista .bloco:hover,.bloco-categorias-lista .bloco.ativo {
    background: #e49b69;
}

#blocoListaServicos .item {
    width: 260px;
    margin-left: auto;
    margin-right: auto;
    border: 1px solid #7095ab;
    /* background: #7095ab; */
    position: relative;
}

#blocoListaServicos .item .img {
    /* height: 350px; */
    background: #7095ab;
}

#blocoListaServicos .item .texto {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 10px 0;
    /* background: #7095ab; */
    background: rgb(112,149,171);
    background: linear-gradient(180deg, rgb(112 149 171 / 22%) 12%, rgb(112 149 171 / 82%) 58%);
    box-shadow: 0px -20px 20px 0px rgb(112 149 171 / 0.2);
    min-height: 131px;
    padding-bottom: 61px;
}


#blocoListaServicos .item .titulo {
    color: #fff;
    text-align: center;
    font-size: 20px;
    margin-bottom: 10px;
    font-family: 'AL Nevrada Personal Use Only Regular';
}

#blocoListaServicos .item .btn-item span {
    color: #fff;
    border: 1px solid #ffff;
    padding: 10px 30px;
    display: inline-block;
}

#blocoListaServicos .item .btn-item {
    text-align: center;
    position: absolute;
    bottom: 10px;
    width: 100%;
}

#blocoListaConsulta .owl-carousel .owl-nav button.owl-prev {
    left: -60px;
}
#blocoListaConsulta .owl-carousel .owl-nav button.owl-next {
    right: -60px;
}

@media(max-width:767px){
    #blocoListaServicos .item {
        width: auto;
    }

    #blocoListaServicos .item .titulo {
        /* font-size: 16px; */
    }

    #blocoListaServicos .item .btn-item span {
        /* font-size: 12px;
        padding: 9px; */
    }
}

</style>

<div class="bloco-lista-consultas">
    <div class="container">
        <h1 class="textCenter tituloPadrao">Serviços</h1>

        <form class="bloco-busca" name="formBusca" method="post">
            <div class="input_e_btn">
                <input type="text" name="busca" class="inputbox bol_inf_txt" value="<?php echo $busca; ?>" placeholder="Pesquisar">
                <span  class="btn" onclick="enviar_busca();"><em class="icon-search"></em></span>
            </div>
        </form>
        <script>
            function enviar_busca(){
                var f = document.formBusca;

                // if(vazio(f.busca.value)){
                //     return exibirAviso('Informe a consulta');
                // }

                carregando();

                setTimeout(function(){f.submit();}, 500);

            }
        </script>
        <?php /*
        <div class="bloco-categorias-lista">
            <?php foreach($categorias as $kc => $cat):?>
                <div class="bloco" alt="<? echo $cat->id; ?>"><? echo $cat->nome; ?></div>
            <?php endforeach; ?>
        </div>
        */?>

        <div class="bloco-lista" id="blocoListaServicos">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($produtos as $ka => $produto):
                    $img_p = '/arquivos/imagens/logo-essence.png';
                    if($produto->foto){
                       $img_p = $dir_p.$produto->foto;
                    }

                    // $cat_id = $controller->model->getIdCategoriaProduto($produto->id);
                    $cat_id = 0;
                    ?>
                    <div class="item cat-<?= $cat_id; ?> produto-<?= $produto->id?> ">
                        <a href="/servicos/<?=$produto->alias?>.html" title="<?=$produto->nome?>">
                            <div class="img" style="position: relative;overflow: hidden;">
                                <?php if($img_p):?>
                                    <img src="<?= $controller->imagineImagem->otimizar( $img_p, 265 ,  350, false, true, 80); ?>" title="<?=$produto->nome?>" alt="<?=$produto->nome?>">
                                <?php endif;?>
                            </div>
                            <div class="texto">
                                <div class="titulo"><?=$produto->nome?></div>
                                <div class="btn-item"><span>Ver</span></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
                        
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owlin = jQuery("#blocoListaServicos .owl-carousel");
		owlin.owlCarousel({
			autoplay:false,
			autoplayTimeout:5000,
			autoplayHoverPause: true,
			items : 5,
			responsive : {
				0 : { items : 1.4 },
				767: { items : 2 },
				991 : { items : 4 },
			},
			margin:14,
			// loop:true,
			dots:false,
			nav:true,
		});
	});			
</script>

<script type="text/javascript">
    $('.bloco-categorias-lista .bloco').click(function(){

        $('.bloco-categorias-lista .bloco').removeClass('ativo');
        $(this).addClass('ativo');
        var cat = $(this).attr('alt');

        $('#blocoListaServicos .item').css('display','none');
        $('#blocoListaServicos .item.cat-'+cat).css('display','block');
    });

</script>
