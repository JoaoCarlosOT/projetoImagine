<?php
$logo = '/arquivos/imagens/logo.png';
$this->load->library('Imgno_imagem', '', 'imagineImagem');
$servicos = $this->model->getProdutos(null, null, array('destaque'=>true,'ativarVenda'=>false));
$dir_p = '/arquivos/imagens/produto/';
?>

<div class="bloco-servicos4" id="galeriaServicos4">
    <div class="container">
        <div class="barra_titulo">
            <div class="titulo">
                <h2 class="tituloPadrao textCenter">
                    Rotas otimizadas, entregas <span>ágeis e economia de até 30%.</span><br>
                    Impulsione sua logística <span>com nossas soluções.</span>
                </h2>
            </div> 
        </div>
    </div>

    <div class="galeriaServicos4">
        <div class="btnsCaroseulContent">
            <div class="owl-carousel">
                <?php foreach($servicos as $servico):
                        if($servico->foto){
                            $img_p = $controller->imagineImagem->otimizar($dir_p.$servico->foto, 820, 680, false, true, 80);
                        }else{
                            // logo
                            $img_p = $controller->imagineImagem->otimizar($logo, 820, 680, false, false, 80);
                        }
                    ?>
                    <a href="/servicos/<?=$servico->alias?>.html" class="item">
                        <picture>
                            <img title="<?=$servico->nome?>" alt="<?=$servico->nome?>" src="<?=$img_p; ?>">
                        </picture> 

                        <div class="caixa-sobra">
                            <div class="base-fundo">
                                <h3 class="tituloPadrao2 titulo"><?=$servico->nome?></h3>
                                <div class="hidden">
                                    <p class="textoPadrao descricao"><?=$servico->descricao?></p>
                                    <span class="btn botao">Quero saber mais</span>
                                </div>
                            </div>                            
                        </div>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
    </div>

</div> 

<script type="text/javascript">
    var owlCliente = jQuery("#galeriaServicos4 .owl-carousel");
    owlCliente.owlCarousel({
        autoplay:true,
        autoplayTimeout:5000,
        smartSpeed: 900,
        autoplayHoverPause: true,
        items : 1,
        responsive : {
            0 : { items : 1.5},
            768 : { items : 3},
        },
        dots:true,
        nav:false,
        margin:0,
        loop:true,
        center:true,
    });
    jQuery("#galeriaServicos4 .navigation .customNextBtn").click(function(){
        owlCliente.trigger('next.owl.carousel');
    });
    jQuery("#galeriaServicos4 .navigation .customPrevBtn").click(function(){
        owlCliente.trigger('prev.owl.carousel');
    });

    owlCliente.on('translate.owl.carousel',function() {
        var h_ser3 = $('.bloco-servicos4 .owl-item.center').height();
        $('.bloco-servicos4 .owl-stage').css('min-height',h_ser3+'px');
    })
</script>