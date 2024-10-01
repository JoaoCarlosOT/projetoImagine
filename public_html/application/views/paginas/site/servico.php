<?php
$produto = $this->dados["dados"]["resultado"];
$relacionados = $this->dados["dados"]["relacionados"];

$imgPrincipal = null;
$imgSegundaria = null;

$dir_p = '/arquivos/imagens/produto/';
if(isset($produto->fotos[0]->imagem) && $produto->fotos[0]->imagem){
    $imgPrincipal= $this->imagineImagem->otimizar($dir_p.$produto->fotos[0]->imagem, 700, 700, false, true,80);
}else{
	$imgPrincipal = $this->imagineImagem->otimizar('/arquivos/imagens/logo.png', 500, 500, false, true,80);
}

$controller->load->model('modulos/modulos_model_config', 'model_config');
$config = $controller->model_config->buscar_config('telefone1,telefone2,email_atendimento,horario,campos');

$configuracao_checkout = $controller->model_config->buscar_config_checkout();

?> 

<div class="layout-produto blocos_servico_pc">
	<div class="container">
		<div class="bloco_descricao">
			<div class="bloco bc1" id="imagensProdutos">
				<div class="base">
					<div class="img imgPrincipal zoom">
						<img title="<?php echo$produto->nome;?>" alt="<?php echo$produto->nome;?>" src="<?php echo $imgPrincipal;?>">
					</div>
					
					<?php if(count($produto->fotos) > 1):?>
						<div class="btnsCaroseulContent imgSegundaria">
							<div class="owl-carousel">
								<?php $i = 0; foreach($produto->fotos as $foto_extra):?>
									<div class="item">
										<img id="imgCrs-<?=$foto_extra->id?>" onClick="trocarImgPrincipal(<?=$foto_extra->id?>, '<?=$this->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 700, 700, false, true,80)?>')" src="<?=$controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 120, 120, false, true, 80); ?>" title="" alt="" class="<?=$i == 0?'active':''?>">
									</div>
								<?php $i++; endforeach;?>
							</div>
						</div>

						<script type="text/javascript">
							jQuery(document).ready(function() {
								
								var owl2 = jQuery("#imagensProdutos .owl-carousel");
								owl2.owlCarousel({
									autoplay:false,
									// autoplayTimeout:5000,
									items : 1,
									responsive : {
										0 : { items : 3 },
										769 : { items : 3},
										993 : { items : 4 },
									},
									nav:true,
									dots: false,
									margin: 10,
									loop:false,
								});
								jQuery("#imagensProdutos .btnsCaroseulContent .next").click(function(){
									owl2.trigger('next.owl.carousel');
								});
								jQuery("#imagensProdutos .btnsCaroseulContent .prev").click(function(){
									owl2.trigger('prev.owl.carousel');
								});
							});		 

							function trocarImgPrincipal(id,principal){
								$('#imagensProdutos .imgSegundaria img').removeClass('active');
								$('#imgCrs-'+id).addClass('active');
								$('#imagensProdutos .imgPrincipal img').attr("src", principal);
							}
						</script>
					<?php endif;?>
				</div>
			</div>

			<div class="bloco bc2">
				<div class="bc_txt">
					<div class="tituloPadrao"><?php echo$produto->nome; ?></div>
					<div class="headline subtitulo"><?=$produto->descricao?></div>					
					<?php if($produto->ativarVenda):?>
						<?php if($produto->preco_de):?>
							<div class="preco_de">De: R$ <span><?php echo $this->model->moeda($produto->preco_de); ?></span></div>
							<div class="valor">Por R$ <span><?php echo $this->model->moeda($produto->preco); ?></span></div>
						<?php else:?>
							<div class="valor">R$ <span><?php echo $this->model->moeda($produto->preco); ?></span></div>
						<?php endif;?>

						<div>
							<a class="btn btn-prod-whats" target="_blank" href="https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=<?=urlencode("Olá! Gostaria de comprar este produto.\n\n".$produto->nome.": ".base_url().'servicos/'.$produto->alias).'.html'?>"><em class="icon-whatsapp"></em> Comprar pelo WhatsApp</a>
						</div>

						<form name="opcoesProduto" method="post">
							<input type="hidden" value="<?php echo $produto->id?>" name="id_produto" id="id_produto">
							<div class="opcoes-produto">
								<div class="b_add">
									<?php if($produto->ativarBtnQtd):?>
										<input class="quantidade" type="number" name="quantidade" min="1" value="1" onchange="this.value < this.min?this.value=this.min:''">
									<?php else:?>	
										<input type="hidden" name="quantidade" value="1">
									<?php endif;?>

									<span class="btn btn-produto" onclick="addCarrinho();"><?=$produto->assinatura?"Assinar agora":"Comprar agora"?></span>
								</div>
							</div>
						</form>

						<?php if($configuracao_checkout && $produto->preco > 0 && $produto->assinatura == 0):?>
							<div class="formas-pagamento-lista">
								<ul>
									<?php if($configuracao_checkout['parcela']):?>
										<li>
											<div class="heading border">
												<div>
													<img src="/arquivos/imagens/payu-cards.png" alt="logo pagamentos card" title="logo pagamentos card">
												</div>
												<span class="texts">Parcelas</span>
											</div>
											<div class="infor border">
												<?php for ($i=1; $i <= $configuracao_checkout['parcela'] ; $i++):?>
													<p><b><?=$i?>x</b> de R$ <?php echo $this->model->moeda($produto->preco/$i)?></p> 
												<?php endfor;?>
											</div>
										</li>
									<?php endif;?> 

									<li>
										<div class="heading border">
											<div>
												<img src="/arquivos/imagens/pagali-pix-logo.png" alt="logo pagamentos pix" title="logo pagamentos pix">
											</div>	
											<span class="texts">R$ <?=$this->model->moeda($produto->preco-($configuracao_checkout['descontoPix']*$produto->preco/100)) ?></span>
										</div> 
									</li>

									<li>
										<div class="heading border">
											<div>	
												<img src="/arquivos/imagens/boleto-logo.png" alt="logo pagamentos boleto" title="logo pagamentos boleto">
											</div>
											<span class="texts">R$ <?=$this->model->moeda($produto->preco) ?></span>
										</div> 
									</li>
								</ul>
							</div>
						<?php endif;?>
					<?php else: ?>
						<div>
							<a class="btn btn-prod-whats" target="_blank" href="https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=<?=urlencode("Olá! Gostaria de comprar este produto.\n\n".$produto->nome.": ".base_url().'servicos/'.$produto->alias).'.html'?>"><em class="icon-whatsapp"></em> Comprar pelo WhatsApp</a>		
						</div>
					<?php endif; ?> 
				</div>
			</div>

		</div>

		
		<?php if($produto->descricao1):?>
			<div class="descricao_grande">
				<div class="tituloPadrao2 textCenter titulo">DESCRIÇÃO DO SERVIÇO</div>
				<div class="textoPadrao texto">
					<?php echo $produto->descricao1; ?>
				</div>
			</div>
		<?php endif; ?> 
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#imagensProdutos .imgPrincipal').zoom();
	});

</script>

<?php if(!$produto->ativarVenda):?>
<div class="cta-servicos">
	<div class="container">
		<form class="formCta" id="formCta" name="formCta" method="post">
			<div class="blocos-form">
				
				<div class="blocos-form-base"></div>
				<div class="blocos-form-base-ciclo"></div>

				<div class="form-txt">
					<div class="tituloBarra3">Quero Impulsionar minha Empresa:</div>
				</div>
				
				<div class="corpo-form">

					<input type="hidden" name="url" value="<?=$this->uri->uri_string();?>"> 

					<div class="linha input-container linha-cta">
						<input type="text" placeholder="" name="nm" class="campo-padrao2">
						<label class="textoPadrao txtCampo">Nome</label>
					</div>

					<div class="linha input-container">
						<input type="text" placeholder="" name="t1" id="phone_cta" class="campo-padrao2">
						<label class="textoPadrao txtCampo">Telefone/WhatsApp</label>
						<input type="hidden" name="dialCode">
					</div>

					<div class="linha input-container linha-cta">
						<input type="text" placeholder="E-mail" name="em" class="campo-padrao2">
						<label class="textoPadrao txtCampo">E-mail</label>
					</div>

					<div class="linha" >
						<a target="_blank" class="btn" onclick="form_cta(event);">Mais informações</a>
					</div>
				</div>

			</div>
		</form>  
	</div>
</div>
<link rel="stylesheet" href="/arquivos/css/intl-tel-input/build/css/intlTelInput.min.css">
<script src="/arquivos/css/intl-tel-input/build/js/intlTelInput.min.js"></script>
<script>
// $(document).ready(function() {
	const input = $("#phone_cta");
	const iti_cta = window.intlTelInput(input[0], {
		countrySearch: false,
		initialCountry: "br",
		nationalMode: true,
		preferredCountries: ['br', 'us'],
		utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
	}); 
// });
	function form_cta(event){ 

        var f = document.formCta;

        if(vazio(f.nm.value)){
            return exibirAviso("Por favor, informe seu nome");
        }
        
        if(vazio(f.t1.value)){
            return exibirAviso("Por favor, informe seu telefone");
        }
       
        // if(!validarTelefone(f.t1.value)){
        //     return exibirAviso("Por favor, informe seu telefone corretamente");
        // } 

        const countryData = iti_cta.getSelectedCountryData();        
        f.dialCode.value = countryData.dialCode;
        const isValid = iti_cta.isValidNumber();
        if(!isValid){
            return exibirAviso("Por favor, informe seu telefone corretamente");
        }  

        if(!validarEmail(f.em.value)){
            return exibirAviso("Por favor, informe seu email corretamente");
        }
        
        carregando();
        if(typeof imgn_cmnc_sender == 'function'){
            var args = [
                {nome:'nome',valor:f.nm.value},
                {nome:'email',valor:f.em.value},
                {nome:'telefone',valor:f.t1.value},
            ];
            imgn_cmnc_sender(args);
        }

		var dados = jQuery(f).serialize();

        jQuery.ajax({
            type: 'POST',
            url: "/ajax/salvar_solicitacao",
            data: dados,
            success: function(response) {
                console.log(response)
                carregado(); 

                if(response == 'ok') {
					f.reset();
					exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
				}
                else {
					exibirAviso('Não enviado');
					return;
				}
            }
        });

		window.open("https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=<?=urlencode('Olá, vim do site e gostaria de mais informações')?>", '_blank');
    }
</script>
<?php endif; ?> 

<?php if($relacionados):?>
<div class="blocoRelacionado">
	<div class="container">
		<div class="tituloPadrao">Confira outras serviços</div>
		<div class="bloco-lista" id="blocoListaServicos">
            <?php foreach($relacionados as $ka => $relacionado):
                    $img_p = '/arquivos/imagens/logo.png';
                     if($relacionado->foto){
                        $img_p = $dir_p.$relacionado->foto;
                     }
                ?>
                <div class="item produto-<?= $relacionado->id?> ">
                    <a href="/servicos/<?=$relacionado->alias?>.html" title="<?=$relacionado->nome?>">
                        <div class="img" style="position: relative;overflow: hidden;">
                            <?php if($img_p):?>
                                <img src="<?=  $controller->imagineImagem->otimizar($img_p,265 ,  350, false, true, 80); ?>" title="<?=$relacionado->nome?>" alt="<?=$relacionado->nome?>">
                            <?php endif;?>
                        </div>
                        <div class="texto">
                            <div class="titulo"><?=$relacionado->nome?></div>
                            <div class="btn-item"><span>Quero marcar agora</span></div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
		</div>
	</div>
</div>
<style>
#blocoListaServicos {
    display: flex;
    /* justify-content: center; */
    flex-wrap: wrap;
}
.bloco-categorias-lista {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin: 10px 0 25px;
}

.bloco-categorias-lista .bloco {
    margin: 5px 10px;
    background: #ffc298;
    padding: 5px 19px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s all;
}

.bloco-categorias-lista .bloco:hover {
    background: #e49b69;
}

#blocoListaServicos .item {
	width: 260px;
    margin: 5px 5px;
    /* margin-bottom: 20px; */
    position: relative;
}

#blocoListaServicos .item .img {
    /* height: 350px; */
}

#blocoListaServicos .item .texto {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 10px 0;
    background: rgb(112,149,171);
    background: linear-gradient(180deg, rgb(112 149 171 / 22%) 12%, rgb(112 149 171 / 82%) 58%);
    box-shadow: 0px -20px 20px 0px rgb(112 149 171 / 0.2);
    min-height: 131px;
    padding-bottom: 61px;
}

#blocoListaServicos .item .titulo {
    color: #fff;
    text-align: center;
    font-size: 18px;
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


.blocoRelacionado {
    padding: 20px 0;
}
</style>
<?php endif;?>

<script type="text/javascript">		
	
	function addCarrinho(){
		var f = document.opcoesProduto;

		/*if(!f.id_agenda.value){
			return exibirAviso('Selecione a Agenda');
		}
		if(!f.data.value){
			return exibirAviso('Selecione a Data');
		}
		if(!f.id_agendamento.value){
			return exibirAviso('Selecione o Horário');
		}*/

		carregando();

		<?php if($produto->assinatura == 0):?>
			jQuery.ajax({
				url: '/site/Site_controller_checkout/adicionar_carrinho',
				type: 'POST',
				data:{
					id_produto: f.id_produto.value,
					quantidade: f.quantidade.value,
					opcao: {
						// id_agenda:f.id_agenda.value,
						// data:f.data.value,
						// id_agendamento:f.id_agendamento.value,
						// texto_agenda: agendas[ f.id_agenda.value ].descricao,
						// texto_horario: agendamentos[ f.id_agendamento.value ].horaInicioFormatado,
					},
				},
				error: function() {},
				success: function(res) {
					
					if(res){
						window.location.href = "/checkout-email.html";
					}

				}
			});
		<?php else:?>

			setTimeout(function(){
				window.location.href = "/checkout-email.html?id_produto_assinatura="+f.id_produto.value+"&qtd="+f.quantidade.value;			
			}, 500);

		<?php endif;?>

	}
</script>

