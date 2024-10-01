<?php 
$id_banner = 23;
$controller->load->model('modulos/modulos_model_banner', 'model_banner');
$info = $controller->model_banner->getSlidesUnicaImg($id_banner,28);
// $info = $controller->model_banner->getSlides($id_banner);

if(!$info) return null;

$slide = $info['slide'];
$banner = $info['banner'];
if(empty($slide['fullhd'])) return;

$cls = 'imaginebanner'.$id_banner;
$tamanhos = array(
	'fullhd' => 1920,
	'extralarge' => 1440,
	'large' => 1200,
	'medium' => 922,
	'small' => 768,
	'extrasmall' => 576,
);
$tamanhos2 = array(
	'extrasmall' => 576,
	'small' => 576,
	'medium' => 768,
	'large' => 922,
	'extralarge' => 1200,
	'fullhd' => 1440,
);

$css_tamanhos = array();
$s = 1;
?>
<div class="<?php echo $cls; ?>">
	<div class="slides">
        <span title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>"><?php
        foreach($tamanhos as $k => $tamanho):
            if(!isset($css_tamanhos[$k])){
                $css_tamanhos[$k] = null;
            }

            if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
                $css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
                    background-image: url("'.$slide[$k].'");
                    align-items: center;
                    display: flex;
                    min-height:'.$banner->{$k}.'px;
                }';
            }else{
                $css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
                    display:none;
                }';
            }
        endforeach; ?>

            <div class="container blocos-informacoes-slide modelo-texto-<?php echo $slide["tipo_texto"]?>">
                <div class="bloco infor-texto">
                    <h3 class="tituloPadrao tituloP"><?php echo $slide["titulo"];?></h3>
                    <div class="texto headline2">
                        <?php echo $slide["descricao"];?>
                    </div>
                    <?php if($slide["texto_btn"]):?>
                    <div class="botao">
                        <a href="<?php echo $slide["link"];?>" class="btn"><?php echo $slide["texto_btn"];?></a>
                    </div>
                    <?php endif;?>
                </div>

                <div class="bloco form">  

                    <form action="" name="form_soli_bg" method="post">
                        <div class="form-txt">
                            <div class="tituloPadrao2 textCenter titulo">Quero saber mais</div>
                        </div>

                        <div class="corpo-form textoPadrao">	
                            <input type="hidden" name="url" value="<?=$this->uri->uri_string();?>">

                            <div class="linha input-container">
                                <input type="text" placeholder="" name="nm" class="campo-padrao"/>
                                <label class="textoPadrao txtCampo">Insira seu Nome</label>
                            </div>
                            <div class="linha input-container">
								<input class="campo-padrao" type="text" name="t1" id="phone_soli_bg" placeholder=""/>
								<label class="textoPadrao txtCampo">Telefone</label>
                                <input type="hidden" name="dialCode">
							</div>
                            <div class="linha input-container">
                                <input type="text" placeholder="" name="em" class="campo-padrao"/>
                                <label class="textoPadrao txtCampo">Insira seu Melhor E-mail</label>
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

                                <span class="btn botao-f" onclick="form_solicitacao_bg();">Falar agora</span>
                            </div>
                        </div>
                    </form> 

                </div>
            </div>

        </span> 
	</div>
</div>
<script type="text/javascript">
    // $(document).ready(function() {
        const input_s_bg = $("#phone_soli_bg");
        const iti_s_bg = window.intlTelInput(input_s_bg[0], {
            countrySearch: false,
            initialCountry: "br",
            nationalMode: true,
            preferredCountries: ['br', 'us'],
            utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
        });  

    function form_solicitacao_bg(){
        var f = document.form_soli_bg;

        if(vazio(f.nm.value)){
            return exibirAviso("Por favor, informe seu nome");
        }

        const countryData = iti_s_bg.getSelectedCountryData();        
        f.dialCode.value = countryData.dialCode; 
        
        if(!vazio(f.t1.value))
        {
            /*if(!validarTelefone(f.t1.value)){
                return exibirAviso("Por favor, informe seu telefone corretamente");
            }*/
            const isValidBG = iti_s_bg.isValidNumber();
            if(!isValidBG){
                return exibirAviso("Por favor, informe seu telefone corretamente");
            }
        } else{
            return exibirAviso("Por favor, informe seu telefone");
        }
        
        if(!validarEmail(f.em.value)){
            return exibirAviso("Por favor, informe seu email corretamente");
        } 
        
        if(!f.termos.checked){
            return exibirAviso("Por favor, aceite os termos de uso");
        }

        if(vazio(f.recap.value)){
            return exibirAviso("Por favor, informe o valor de <?= $n1.' '.$sinal.' '.$n2;?>");
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
<style type="text/css">
.<?php echo $cls; ?> .slides{
    position: relative;
}
.<?php echo $cls; ?> .slides .slide{
	width: 100%;
	background-size: cover;
	background-position: center center;
	display: block;
	background-color: rgba(0, 0, 0,0.6);
    background-blend-mode: color;
    /* padding: 60px 0; */
    position: relative;
    overflow: hidden;
}
.<?php echo $cls; ?> .slides.diagonal-left span{
    position: relative;
    padding: calc(60px + var(--tamanho-reta)) 0 60px 0;
    margin: 0;
    clip-path: polygon(0 var(--tamanho-reta), 100% 0, 100% 100%, 0% 100%);
}
.<?php echo $cls; ?> .slides.diagonal-rigth span{
    position: relative;
    padding: calc(60px + var(--tamanho-reta)) 0 60px 0;
    margin: 0;
    clip-path: polygon(0 0, 100% var(--tamanho-reta), 100% 100%, 0% 100%);
} 

<?php
    foreach($tamanhos2 as $k =>$tamanho){
        if($k != 'extrasmall') echo '@media (min-width: '.$tamanho.'px){';	
        echo $css_tamanhos[$k];
        if($k != 'extrasmall') echo '}';	
    }
?> 
.<?php echo $cls; ?> .blocos-informacoes-slide {
    display: flex;
    /* padding: 20px 0; */
    align-items: center;
    gap: 60px;
    height: 100%;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
	font-size: 18px;
    line-height: 20px; 
    width: 100%;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.infor-texto{
    padding: 20px 0;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form{
    max-width: 500px;
    height: 100%;
    padding: 20px 80px;
    background: #fff;
    color: #000;
    position: relative;
}

.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form form{
    width: 100%;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form form .corpo-form{
    margin-top: 30px;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form form .form-txt .titulo{
    color: var(--cor-principal);
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form .corpo-form .linha{
    margin-bottom: 10px;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form .corpo-form .linha-botao .recap{
    margin-bottom: 10px;
}
/* modelo 0 */
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
	color: #fff;
    margin: 0 auto; 
	/* text-align: center; */
	display: flex;
    justify-content: center;
    height: 100%;
	flex-direction: column;
    align-items: center;
} 
.<?php echo $cls; ?> .blocos-informacoes-slide .tituloP{
	color: #fff; 
    margin-bottom: 15px;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .botao{
    margin-top: 30px;
}

/* modelo 1 */ 

/* modelo 2 */ 
@media(min-width: 769px){
    .<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form:before{
        content: '';
        position: absolute;
        top: -100%;
        height: 100%;
        width: 100%;
        background: #fff;
    }
    .<?php echo $cls; ?> .blocos-informacoes-slide .bloco.form:after{
        content: '';
        position: absolute;
        bottom: -100%;
        height: 100%;
        width: 100%;
        background: #fff;
    }
}
@media(max-width:768px){ 
	.<?php echo $cls; ?> .blocos-informacoes-slide {
        flex-direction: column;
		gap: 20px;
		padding: 50px 0;
    }
	.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
		max-width: 100%;
	}
	
	/* modelo 1 */ 
}
</style>


