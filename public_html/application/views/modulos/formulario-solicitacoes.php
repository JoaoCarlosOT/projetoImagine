<link rel="stylesheet" href="/arquivos/css/intl-tel-input/build/css/intlTelInput.min.css">
<script src="/arquivos/css/intl-tel-input/build/js/intlTelInput.min.js"></script>

<div class="form-solicitacoes">
    <div class="container">
        
        <div class="bloco-formulario">
            <form action="" name="form_soli" method="post">
                <div class="form-txt">
					<div class="tituloPadrao textCenter titulo">Fale Conosco</div>
                    <!-- <div class="headline textCenter subtitulo">Complete Nosso Formulário de Solicitações para uma Experiência Personalizada e Eficiente!</div> -->
				</div>
                <div class="div-form">
					<div class="corpo-form textoPadrao">	
    					<input type="hidden" name="url" value="<?=$this->uri->uri_string();?>">

						<div class="linha input-container">
							<input type="text" placeholder="" name="nm" class="campo-padrao"/>
							<label class="textoPadrao txtCampo">Insira seu Nome</label>
						</div>
						<div class="linha input-container">
							<input type="text" placeholder="" name="em" class="campo-padrao"/>
							<label class="textoPadrao txtCampo">Insira seu Melhor E-mail</label>
						</div>
						<div class="linha row-cell">
							<div class="linha-tel input-container">
								<input class="campo-padrao" type="text" name="t1" id="phone_soli" placeholder=""/>
								<label class="textoPadrao txtCampo">Telefone</label>
                                <input type="hidden" name="dialCode">
							</div>
							<div class="linha-tel input-container">
								<input class="campo-padrao" alt="phone" type="text" name="t2" id="phone_soli_2" placeholder=""/>
								<label class="textoPadrao txtCampo">Celular</label>
                                <input type="hidden" name="dialCode2">
							</div>
						</div> 
                        <div class="linha input-container">
							<textarea class="campo-padrao" style="height:120px;resize: vertical;" placeholder="" name="msg"></textarea>
							<label class="textoPadrao txtCampo">Descreva o que você deseja Contratar</label>
                        </div>
                        <div class="linha linha-termos textoPadrao">
                            <input type="checkbox" name="termos" value="1"> Aceito termos de uso
                        </div>
                        <?php
							$n1 = mt_rand(0,10);
							$n2 = mt_rand(0,10);

							if($n1 > $n2):
								$sinal = '-';
								$n3 = $n1 - $n2;
							else :
								$sinal = '+';
								$n3 = $n1 + $n2;
							endif;
						?>
						<div class="linha-botao">
                            <div class="recap">
                                <label class="textoPadrao txt-label"><span>Valor de: </span> <?= $n1.' '.$sinal.' '.$n2.' = ';?> </label>
                                <input style="width: 80px;"  type="text" class="campo-padrao" name="recap" maxlength="2" />
                                <input type="hidden" name="recap_n1" value="<?= $n1 ?>" />
                                <input type="hidden" name="recap_n2" value="<?= $n2 ?>" />
                                <input type="hidden" name="recap_sinal" value="<?= $sinal ?>" />
                            </div>

							<span class="btn" onclick="form_solicitacao();">Solicitar Orçamento</span>
						</div>
					</div>
				</div>
            </form>
        </div>
    </div>
</div> 

<script type="text/javascript">
    // $(document).ready(function() {
        const input_s = $("#phone_soli");
        const iti_s = window.intlTelInput(input_s[0], {
            countrySearch: false,
            initialCountry: "br",
            nationalMode: true,
            preferredCountries: ['br', 'us'],
            utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
        }); 

        const input2_s = $("#phone_soli_2");
        const iti2_s = window.intlTelInput(input2_s[0], {
            countrySearch: false,
            initialCountry: "br",
            nationalMode: true,
            preferredCountries: ['br', 'us'],
            utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
        }); 

    function form_solicitacao(){
        var f = document.form_soli;

        if(vazio(f.nm.value)){
            return exibirAviso("Por favor, informe seu nome");
        }
        if(!validarEmail(f.em.value)){
            return exibirAviso("Por favor, informe seu email corretamente");
        } 

        const countryData = iti_s.getSelectedCountryData();        
        f.dialCode.value = countryData.dialCode;
        const countryData2 = iti2_s.getSelectedCountryData();        
        f.dialCode2.value = countryData2.dialCode;
        
        if(!vazio(f.t1.value))
        {
            /*if(!validarTelefone(f.t1.value)){
                return exibirAviso("Por favor, informe seu telefone corretamente");
            }*/
            const isValid = iti_s.isValidNumber();
            if(!isValid){
                return exibirAviso("Por favor, informe seu telefone corretamente");
            }
        }
        else if(!vazio(f.t2.value))
        {
            /*if(!validarTelefone(f.t2.value)){
                return exibirAviso("Por favor, informe seu celular corretamente");
            }*/
            const isValid2 = iti2_s.isValidNumber();
            if(!isValid2){
                return exibirAviso("Por favor, informe seu celular corretamente");
            }
        }
        else
        {
            return exibirAviso("Por favor, informe seu telefone ou celular");
        } 
        
        if(vazio(jQuery(f.msg).val())){
            return exibirAviso("Por favor, escreva sua solicitação");
        }  
        
        if(vazio(f.recap.value)){
            return exibirAviso("Por favor, informe o valor de <?= $n1.' '.$sinal.' '.$n2;?>");
        }

        if(!f.termos.checked){
            return exibirAviso("Por favor, aceite os termos de uso");
        }

        if(f.recap.value != <?=$n3;?>){
            return exibirAviso("Por favor, informe corretamente o código de segurança");
        } 
        
        carregando();
        /*if(typeof imgn_cmnc_sender == 'function'){
            var args = [
                {nome:'nome',valor:f.nm.value},
                {nome:'email',valor:f.em.value},
                {nome:'telefone',valor:f.t1.value},
                {nome:'solicitação',valor:jQuery(f.msg).val()},
            ];
            imgn_cmnc_sender(args);
        }*/

		var dados = jQuery(f).serialize();

        jQuery.ajax({
            type: 'POST',
            url: "/ajax/salvar_solicitacao",
            data: dados,
            success: function(response) {
                
                carregado(); 

                f.reset();
                if(response == 'ok') return exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
                else exibirAviso('Não enviado');
            }
        });
    }
</script>