<?php
    $controller->load->model('site/Site_model_paginas', 'model_site');
    $carrinho_lista_topo = $controller->model_site->buscar_carrinho_lista(); 

    if(!file_exists(APPPATH .'controllers/admin/Admin_controller_financeiro.php')) return;
?> 

<div class="bloco" id="carrinhoLista">
    <div class="img">
        <em class="icon-basket"></em>
    </div>
    <?php if($carrinho_lista_topo):?>
        <span class="carrinho-numero"><?php echo count($carrinho_lista_topo); ?></span>
    <?php endif;?>

    <div class="blocoCarrinhoLista">
        <?php if($carrinho_lista_topo):?>
                <?php foreach($carrinho_lista_topo as $carrinho_topo):
                
                $opcao =null;

                if($carrinho_topo->opcao){
                    $opcao = json_decode($carrinho_topo->opcao);
                }
                    
            ?>
            <div class="bloco-produto c-<?php echo $carrinho_topo->id ;?>">
                <div class="texto">
                    <a href="/servicos/<?php echo $carrinho_topo->alias ;?>.html" class="txt">Produto: <?php echo $carrinho_topo->produto_nome ;?></a>
                </div>
                <div class="valor-produto">Qtd: <?php echo $carrinho_topo->quantidade;?></div>
                <div class="valor-produto">Valor: R$ <?php echo $this->model->moeda($carrinho_topo->produto_preco);?></div>

                <em class="icon-trash icone-td remover-carrinho" onclick="remover_carrinho_topo(<?php echo $carrinho_topo->id ;?>);"></em>
            </div>
            <?php endforeach; ?>
            
            <a href="/checkout-email.html" class="btn-carrinho-finalizar">Finalizar Compra</a>

        <?php else:?>
            <div class="txtVazio">Seu carrinho está vazio</div>
        <?php endif;?>
    </div>
</div>

<script>
    function remover_carrinho_topo(carrinho){
        carregando();
        jQuery.ajax({
				url: '/site/Site_controller_checkout/remover_carrinho',
				type: 'POST',
				data:{
					id:carrinho
				},
				error: function() {},
				success: function(res) {
                    
                    // if(res){
                    //     jQuery('.c-'+carrinho).remove();
                    // }
                    
					// carregado();

                    document.location.reload(true);
				}
		});
    }
</script>