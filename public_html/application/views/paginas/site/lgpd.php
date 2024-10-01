<?php
	$controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config();

	$dados = $this->dados["dados"]["resultado"];

    $strings_replace = array(
        'URL-do-Seu-Site' => base_url(),
        'Nome-da-Sua-Empresa' => $config['nome_empresa'],
        'Email-de-Contato' => $config['email_atendimento'],
        'Link-Politica-de-Privacidade' => base_url().'lgpd.html#politica-privacidade',
        'Link-Termos-de-Uso' => base_url().'lgpd.html#termos-uso',
        'Link-Politica-de-Cookies' => base_url().'lgpd.html#politica-cookies',
        'Link-Consentimento' => base_url().'lgpd.html#politica-privacidade',
        'Data-de-Atualizacao' => date("d/m/Y", strtotime($dados->data_atualizado)),
    );
?>

<div class="bloco-termos-uso" id="bloco-termos-uso">
    <div class="side-bar-termos">
        <div class="bloco-side-bar" id="side-bar-termos">
			<ul>
				<li><a href="#politica-privacidade">Política de privacidade</a></li>
				<li><a href="#termos-uso">Termos de uso</a></li>
				<li><a href="#politica-cookies">Política cookies</a></li>
				<li><a href="#consentimento">Consentimento para o processamento de dados e comunicação</a></li>
			</ul>
		</div>
    </div>

    <div class="texto-termos">
        <div class="bloco-termos">
			<div id="politica-privacidade">
                <h3 class="tituloPadrao">Política de privacidade</h3>
                <?=$controller->ImgnoUtil->replace_tags($dados->des_politica_privacidade, $strings_replace)?>
			</div>

			<div id="termos-uso">
                <h3 class="tituloPadrao">Termos de uso</h3>
                <?=$controller->ImgnoUtil->replace_tags($dados->des_termos_uso, $strings_replace)?>
			</div>

			<div id="politica-cookies">
                <h3 class="tituloPadrao">Política cookies</h3>
                <?=$controller->ImgnoUtil->replace_tags($dados->des_politica_cookies, $strings_replace)?>
			</div>

            <div id="consentimento">
                <h3 class="tituloPadrao">Consentimento para o processamento de dados e comunicação</h3>
                <?=$controller->ImgnoUtil->replace_tags($dados->des_consentimento, $strings_replace)?>
			</div>
		</div>
    </div>
</div>

<script type="text/javascript">
	let old = jQuery(window).scrollTop();
	let order = 'down';
	jQuery(window).scroll(function(){
		if(jQuery(window).width() <= 991) return;
		let top_content = jQuery('#bloco-termos-uso').offset().top;
		let h_content = jQuery('#bloco-termos-uso').height();
		let bot_content = top_content + h_content;
		
		let top_block = jQuery('#side-bar-termos').offset().top;
		let h_block = jQuery('#side-bar-termos').height();
		let bot_block = top_block + h_block;
		
		let menu_h = 30;
		bot_content -= 30;
		
		let top_window = jQuery(window).scrollTop() + menu_h;
		let h_window = jQuery(window).height();
		let bot_window = top_window + h_window;
		
		if(order == 'down'){
			if(old > top_window){
				order = 'up';
			}
		}else{
			if(old < top_window){
				order = 'down';
			}
		}
		old = top_window;
		let top = jQuery('#side-bar-termos').css('top');
		if(order == 'down'){
			if(bot_window >= (bot_block+15)){
				if(bot_content <= bot_block) return;
				x = bot_window - (h_block+25) - top_content;
				if(h_block < h_window){
					x = x - (h_window - h_block) + 15;
					if(x < 0) x = menu_h;
				}
				jQuery('#side-bar-termos').css('top',x);
			}
		}else{
			if(top_window <= (top_block+15)){
				if(top_content >= top_block) return;
				x = top_window - top_content;
				if(x < 0) x = menu_h;
				jQuery('#side-bar-termos').css('top',x);
			}	
		}
	});	
</script>