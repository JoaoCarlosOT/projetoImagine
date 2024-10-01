<?php
$controller->load->model('modulos/modulos_model_faq', 'model_faq');
$faqs = $controller->model_faq->buscar_faqs();
?>

<div class="topo-info">
	<div class="container">
		<div class="textos"><h1 class="tituloPadrao">FAQ</h1></div>
	</div>
</div>

<div class="duvidas">
    <div class="container">
        <div class="tituloPadrao">FAQ</div>
        <div id="duvidas">
			<?php foreach($faqs as $faq):?>
				<div class="bloco">
					<div class="tituloPadrao4 titulo"><?=$faq->pergunta?> <em class="icon-down-open"></em></div>
					<div class="textoPadrao desc"><?=$faq->resposta?></div>
				</div> 
			<?php endforeach;?>
        </div>
    </div>
</div>

<script type="text/javascript">
		// $('.duvidas .bloco').children('.desc').slideUp();
		$('.duvidas .bloco .titulo').click(function(){
			$(this).parent().toggleClass('ativo');
			$(this).parent().children('.desc').slideToggle();	
		});
	// $('.duvidas .bloco').first().addClass('ativo');
	// $('.duvidas .bloco').first().children('.desc').slideDown();
</script>