<?php
$logo = '/arquivos/imagens/logo.png';
$this->load->library('Imgno_imagem', '', 'imagineImagem');

$controller->load->model('modulos/modulos_model_config', 'model_config');
$config = $controller->model_config->buscar_config('telefone1');

$produtos = $this->model->getProdutos(null, null, array('destaque'=>true,'ativarVenda'=>true));
if(!$produtos) return;
$dir_p = '/arquivos/imagens/produto/';
?>
<div class="bloco-lista-servicos-3">
    <div class="container">
        <div class="bloco-lista-3" id="blocoListaServicos3">
            <div class="btnsCaroseulContent">
                <div class="owl-carousel">

                <?php foreach($produtos as $ka => $produto):
                    if($produto->foto){
                        $img_p = $controller->imagineImagem->otimizar($dir_p.$produto->foto, 460, 460, false, true, 80);
                    }else{
                        // logo
                        $img_p = $controller->imagineImagem->otimizar($logo, 460, 460, false, false, 80);
                    }

                    $cat_id = 0;
                    ?>
                    <div class="item cat-<?= $cat_id; ?> produto-<?= $produto->id?> ">
                        <a href="/servicos/<?=$produto->alias?>.html" title="<?=$produto->nome?>">
                            <div class="img">
                                <?php if($img_p):?>
                                    <img src="<?=$img_p?>" title="<?=$produto->nome?>" alt="<?=$produto->nome?>">
                                <?php endif;?>
                            </div>
                            <div class="textoPadrao texto">
                                <div class="tituloPadrao4 titulo"><?=$produto->nome?></div>
            					<?php if($produto->preco_de):?>
                                    <div class="preco_de">De: R$ <?=$this->model->moeda($produto->preco_de); ?></div>
                                    <div class="valor">Por: R$ <?=$this->model->moeda($produto->preco); ?></div>
                                <?php else:?>
                                    <div class="valor">R$ <?=$this->model->moeda($produto->preco); ?></div>                                
                                <?php endif;?>

                                <!-- <div class="headline subtitulo"><?=$produto->descricao?></div> -->

                            </div> 
                        </a> 


                        <div class="botoes">
                            <div class="whatsapp">
                                <a class="btn" target="_blank" href="https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=<?=urlencode("Olá! Gostaria de comprar este produto.\n\n".$produto->nome.": ".base_url().'servicos/'.$produto->alias).'.html'?>"><em class="icon-whatsapp"></em> Comprar pelo WhatsApp</a>
                            </div>
                            <div class="b_add">
								<?php if($produto->ativarBtnQtd):?>
                                    <input type="number" class="quantidade" name="quantidade" min="1" value="1" onchange="this.value < 1?this.value='1':''">
                                <?php else:?>	
									<input type="hidden" name="quantidade" value="1">
                                <?php endif;?> 

                                <div class="btn btn-produto" onclick="addCarrinho3(this, <?=$produto->id?>, <?=$produto->assinatura?>)"><?=$produto->assinatura?"Assinar":"Adicionar"?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                        
                </div>
            </div> 
        </div>

    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owlSer3 = jQuery("#blocoListaServicos3 .owl-carousel");
		owlSer3.owlCarousel({
			autoplay:false,
			autoplayTimeout:5000,
			autoplayHoverPause: true,
			items : 5,
			responsive : {
				0 : { items : 1.4 },
				769: { items : 2 },
				993 : { items : 3 },
			},
			margin:40,
			loop:true,
			dots:false,
			nav:true,
		});

	});			
    
    // Encontrar a altura máxima dos elementos .titulo
    $(document).ready(function() {
        var maxHeight = 0;

        $('#blocoListaServicos3 .item .titulo').each(function() {
            var currentHeight = $(this).outerHeight();
            maxHeight = Math.max(maxHeight, currentHeight);
        });

        $('#blocoListaServicos3 .item .titulo').css('height', maxHeight + 'px');
    });
    
	
	function addCarrinho3(contexto, id_produto, assinatura){
        if(!id_produto) return;
        var quantidade = $(contexto).closest('.b_add').find('input[name="quantidade"]').val();
        if(!quantidade) return;
		carregando();

        if(assinatura == 0){
            jQuery.ajax({
                url: '/site/Site_controller_checkout/adicionar_carrinho',
                type: 'POST',
                data:{
                    id_produto: id_produto,
                    quantidade: quantidade, 
                },
                error: function() {},
                success: function(res) {
                    
                    if(res){
                        window.location.href = "/checkout-email.html";
                    }

                }
            });
        }else{
            setTimeout(function(){
				window.location.href = "/checkout-email.html?id_produto_assinatura="+id_produto+"&qtd="+quantidade;			
			}, 500);
        }
		
	}
</script>