<?php
$controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
$modulo_16 = $controller->model_modulo->buscar_modulo(16);

$produto_assinatura = $this->dados["dados"]["produto_assinatura"];

$configuracao_checkout = $this->dados["dados"]["buscar_configuracao"];
if($configuracao_checkout['cad_cli_cpf_cnpf'] == 2){
    $nomenclatura_cpf_cnpj = "CPF/CNPJ";
    $funcao_validacao_cpf_cnpj = "validarCPFCNPJ";
    $alt_cpf_cnpj = "cpf_cnpj";
}else if($configuracao_checkout['cad_cli_cpf_cnpf'] == 1){
    $nomenclatura_cpf_cnpj = "CNPJ";
    $funcao_validacao_cpf_cnpj = "validarCNPJ";
    $alt_cpf_cnpj = "cnpj";
}else{
    $nomenclatura_cpf_cnpj = "CPF";
    $funcao_validacao_cpf_cnpj = "validarCPF";
    $alt_cpf_cnpj = "cpf";
}
$valor_total = 0;
?>

<style>
    .popupSistema1 {
        width: 620px;
        /* top: 35%; */
    }
    .popupInformacoes{
        border: 10px solid #7296ac;
    }
    .tituloAlerta {
        color: #7095ab;
        font-size: 23px;
        font-weight: bold;
        font-family: 'AL Nevrada Personal Use Only Regular';
        text-align: center;
    }
    .descricaoAlerta {
        font-size: 16px;
    }
    .descricaoAlerta p {
        margin: 5px 0;
    }
</style>

<style>
input[type="date"]:not(.has-value):before{
		color: #6b6b6b;
		content: attr(placeholder);
		background: #fff;
		position:absolute;
		/* width:100%; */
}   
.subtituloPadrao {
    font-size: 15px;
}
.dados-conta {
    position: relative;
    background: #fff;
    border: 1px solid #7095ab;
    padding: 20px 15px;
    color: #7095ab;
    font-size: 20px;
    font-weight: bold;
}
.bgPagCheckout{
    padding: 40px 0;
    padding-bottom: 100px;
}
.bloco-checkout {
    padding: 30px 0;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
}

.bloco-checkout .bloco-info{
    flex-grow: 1; 
    width: calc(50% - 40px);
}

.bloco-checkout .forma-pagamento{
    width: 100%; 
}

.bloco-checkout .bloco {
    width: 100%;
    background: #20201f; 
}

.bloco-checkout .bloco .titulo {
    color: #fff;
    padding: 10px 15px;
    font-size: 17px;
}

.bloco-checkout .bloco .titulo span {
    text-decoration: underline;
}
.bloco-checkout .bloco .conteudo {
    position: relative;
}
.bloco-checkout .produtos-total {
    background: #7095ab;
    color: #fff;
    font-size: 18px;
    padding: 6px 10px;
    text-align: right;
    font-weight: bold;
}
.bloco-checkout .produtos-total .valorTotalSemDesconto{
    text-decoration: line-through;
}
.bloco-checkout .produtos-total .descontoTexto{
    font-size: 12px;
}
.bloco-checkout .cupom_desconto_bloco{
    display: flex;
    padding: 10px;
}
.bloco-checkout .cupom_desconto_bloco .btn{
    margin: 0;
    border-radius: 0;
}

.bloco-checkout .bloco-produto  em.remover-carrinho {
    position: absolute;
    top: 20px;
    right: 10px;
    z-index: 2;
    cursor: pointer;
}
.bloco-checkout .btn-editar-dados{
    cursor: pointer;
    position: absolute;
    top: 20px;
    right: 10px;
    z-index: 2;
}
.bloco-checkout .bloco-produto {
    position: relative;
    background: #fff;
    border: 1px solid #7095ab;
    padding: 20px 15px;
    color: #7095ab;
    font-size: 20px;
    font-weight: bold;
}

.bloco-checkout .bloco-produto .valor-produto {
    font-weight: bold;
    color: #20201f;
    text-align: right;
    font-size: 17px;
}

.bloco-checkout .bloco-produto .frequencia_assina{
    font-weight: bold;
    color: #20201f;
    font-size: 14px;
}

.blocoform {
    padding: 10px 10px;
}
.blocoform .linha label{
    color: #fff;
}
.checkboxLabel span {
    font-size: 14px;
}
.checkboxLabel {
    display: inline-flex;
    color: #fff;
    cursor: pointer;
}

.checkpadrao {
    border: none !important;
    width: 20px;
    margin-top: 3px;
}

.btn-cadastrar {
    margin: 0 auto;
    border-radius: 0;
    padding: 10px 50px;
    max-width: 200px;
}

.formas-pagamentos {
    background: #fff;
    padding: 10px 25px;
    border: 1px solid #7095ab;
}

.formas-pagamentos .txt {
    color: #7095ab;
    font-size: 16px;
    margin-bottom: 10px;
}

.opcoesPagamento{
    display: flex;
    align-items: flex-start;
}
.opcoesPagamento .opcao {
    display: flex;
    cursor: pointer;
    width: 33.333%;
    justify-content: center;
    flex-wrap: wrap;
}

.opcoesPagamento .opcao .img {
    /* border-radius: 8px; */
    /* border: 1px solid #7095ab; */
    text-align: center;
}

.opcoesPagamento .opcao .img img {
    width: 32px;
    margin-top: 2px;
}

.opcoesPagamento .opcao .txt-opcao {
    padding: 7px 11px;
    font-size: 16px;
    font-weight: bold;
}
.opcoesPagamento .opcao .txt-opcao-total{
    width: 100%;
    font-size: 16px;
    text-align: center;
}
.opcoesPagamento .opcao .txt-opcao-desco{
    width: 100%;
    font-size: 14px;
    text-align: center;
}
.opcoesPagamento .opcao #blocoParcela{
    width: 100%;
}
.opcoesPagamento .opcao .radio-div {
    width: 24px;
    padding: 12px 0;
}
.dadosPix{
    max-width: 100%;
    margin: 50px auto 0px auto;
    padding: 40px;
    background-color: #f8f8f8;
    box-shadow: 0px 2px 20px -3px #e2e2e2;
    display: flex;
    gap: 40px;
}
.bloco-pix{
    width: 100%;
}
.pagamento-bloco-pix > div {
    width: 50%;
    padding: 8px;
}
.mdl-grid {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    justify-content: center;
}
.bloco-qr-pix {
    display: flex;
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
}
.bloco-qr-pix .js-pix-qr-code{
    max-width: 300px;
    max-height: 300px;
    width: 100%;
}
.bloco-qr-pix .pix-qrcode-txt-leitura {
    font-size: 13px;
    padding-top: 35px;
    padding-bottom: 35px;
    padding-left: 20px;
    line-height: 1.4;
    width: 100%;
    max-width: 160px;
}
.bloco-text-pix-copy {
    width: 265px;
}
.pix-qrcode-label {
    font-size: 14px;
    font-weight: bold;
}
.pix-code {
    font-size: 10px;
    word-break: break-all;
}
.div-btn {
    margin-left: 15px;
}
.div-btn .btn-copiar {
    background-color: #FF4E7A;
    color: #FFFFFF;
    border-radius: 2px;
    margin: 0;
    margin-top: 25px;
    position: relative;
    height: 36px;
    min-width: 64px;
    padding: 0 16px;
    display: inline-block;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
    line-height: 1;
    letter-spacing: 0;
    overflow: hidden;
    will-change: box-shadow;
    transition: box-shadow 0.2s cubic-bezier(0.4, 0, 1, 1), background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    line-height: 36px;
    vertical-align: middle;
    border: none;
    text-align: center;
    width: 110px;
}
.div-btn .copy-pix-snackbar {
    color: #00d32d;
    font-weight: bold;
    text-align: center;
    margin-top: 8px;
}
.div-btn .copy-barra-snackbar {
    color: #00d32d;
    font-weight: bold;
    text-align: center;
    margin-top: 8px;
}
.dadosBoleto{
    max-width: 100%;
    margin: 50px auto 0px auto;
    padding: 40px;
    background-color: #f8f8f8;
    box-shadow: 0px 2px 20px -3px #e2e2e2;
    display: flex;
    gap: 40px;
}
.blocos-boleto{
    width: 100%;
}
.bloco-boleto{
    width: 100%;
    text-align: center;
}
.barra-qrcode-label {
    font-size: 14px;
    font-weight: bold;
}
.dadosCartaoCredito{
    max-width: 100%;
    margin: 50px auto 0px auto;
    padding: 40px;
    background-color: #f8f8f8;
    box-shadow: 0px 2px 20px -3px #e2e2e2;
    display: flex;
    gap: 40px;
}
.dadosCartaoCredito .InforCCTitular{
    /* margin-top: 40px; */
}
.dadosCartaoCredito .tituloPaga{
    color: #000;
    padding: 0 0 20px 0;
    font-size: 25px;
    font-weight: 700;
    line-height: 25px;
}
.dadosCartaoCredito .campos{
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.dadosCartaoCredito .campos .linha{
    width: 100%;
}
.dadosCartaoCredito .campos .linha-completa{
    display: flex;
    gap: 10px;
    width: 100%;
}
.dadosCartaoCredito .campos .linha .campo-padrao{
    border: 2px solid #ccc;
    border-radius: 5px;
}
.checkout-sep {
    /* background: #7095ab;
    width: 1px; */
}

.checkout-sep:before {
    content: '\f105';
    font-family: "fontello";
    -moz-osx-font-smoothing: grayscale;
    margin-top: 70px;
    display: block;
    font-size: 28px;
    color: #7095ab;
}

.btn-finalizado {
    display: inline-block;
    background: #41b144;
    color: #fff;
    padding: 10px 20px;
    font-size: 18px;
    width: 100%;
    text-align: center;
    margin-top: 20px;
    cursor: pointer;
}

.boletim-footer {
    display: none !important;
}
.blocoCentralPag { 
    background: #fff;
    padding: 30px 0px;
}

.mais-consultas {
    margin: 4px 20px;
}

.mais-consultas a {
    color: #7095ab;
    font-weight: bold;
    text-decoration: underline;
}

@media(max-width:768px){
    .bloco-qr-pix .pix-qrcode-txt-leitura{
        max-width: 100%;
        padding-left: 0;
    }
    .bgPagCheckout .tituloPadrao {
        text-align: center;
    }

    .bgPagCheckout .subtituloPadrao {
        text-align: center;
    }

    .bloco-checkout .bloco {
        margin: 0;
        width: 100%;
        margin-bottom: 30px;
    } 
    .mais-consultas{
        margin-bottom: 30px;
    }
    .checkout-sep {
        display: none;
    }
    .bloco-checkout {
        display: block;
        padding: 0 10px;
    }
    .dadosCartaoCredito{
        flex-direction: column;
        gap: 20px;
        padding: 20px 10px;
    }
    .bloco-checkout .bloco-info{
        width: 100%;
    }
}
</style>


<div class="bgPagCheckout">
<div class="container">
<div class="blocoCentralPag">
    <div class="tituloPadrao">Falta pouco para você concluir</div>
    <div class="subtituloPadrao">Preencha as informações abaixo para prosseguir</div>
<div class="bloco-checkout">

    <div class="bloco-info">
        <div class="bloco">
            <div class="titulo">
                <span>Resumo de pagamento</span>
            </div>
            <div class="conteudo">

                <div class="produtos-lista">
                    <?php if($produto_assinatura):?>

                        <div class="bloco-produto c-<?php echo $produto_assinatura->id ;?>">
                            <div class="texto">
                                <div class="txt">Produto/Serviço: <br><?php echo $produto_assinatura->produto_nome ;?></div>
                                <?php if($produto_assinatura->produto_descricao):?> <div class="txt">Descrição: <br><?php echo $produto_assinatura->produto_descricao ;?></div><?php endif;?>
                            </div>
                            <div class="frequencia_assina">*Assinatura <?=$controller->asaas->getCicloAssinatura($produto_assinatura->frequencia_assina);?></div>
                            <div class="valor-produto">Qtd: <?php echo $produto_assinatura->quantidade?></div>
                            <div class="valor-produto">Valor: R$ <?php echo $this->model->moeda($produto_assinatura->produto_preco);?></div>
                        </div>


                    <?php $valor_total += $produto_assinatura->produto_preco*$produto_assinatura->quantidade;
                    elseif($this->dados["dados"]["carrinho"]):?>
                        <?php foreach($this->dados["dados"]["carrinho"] as $carrinho): 
                            $opcao =null;

                            if($carrinho->opcao){
                                $opcao = json_decode($carrinho->opcao);
                            }
                            
                        ?>
                        <div class="bloco-produto c-<?php echo $carrinho->id ;?>">
                            <div class="texto">
                                <div class="txt">Produto/Serviço: <br><?php echo $carrinho->produto_nome ;?></div>
                                <?php if($carrinho->produto_descricao):?> <div class="txt">Descrição: <br><?php echo $carrinho->produto_descricao ;?></div><?php endif;?>
                            </div>
                            <div class="valor-produto">Qtd: <?php echo $carrinho->quantidade?></div>
                            <div class="valor-produto">Valor: R$ <?php echo $this->model->moeda($carrinho->produto_preco);?></div>

                            <em class="icon-trash icone-td remover-carrinho" onclick="remover_carrinho(<?php echo $carrinho->id ;?>);"></em>
                        </div>
                        <?php 
                            $valor_total += $carrinho->produto_preco*$carrinho->quantidade;
                            endforeach; ?>
                    <?php endif; if($valor_total < 0) $valor_total = 0;?>

                    <div class="produtos-total">
                        <div class="base">
                            <span>Total: </span>
                            <span id="valorTotal">R$ <?php echo $this->model->moeda($valor_total);?></span>
                        </div>
                    </div>  

                    <div class="cupom_desconto_bloco">
                        <input type="text" id="valorCupom" placeholder="Cupom de desconto" value="<?=!empty($_GET['codigo_cupom'])?$_GET['codigo_cupom']:''?>">
                        <span class="btn" onclick="cupomDesconto();">Adicionar</span>
                    </div>


                </div>
            </div>
        </div>
        <!-- <div class="mais-consultas">
            <a href="/servicos.html" title="Adicionar mais produtos">Adicionar mais produtos</a>
        </div> -->

        <script>
            $( document ).ready(function() {
                cupomDesconto(true)
            });
            function cupomDesconto($ready = false){
                var valorTotal = parseFloat("<?=$valor_total?>");
                var descontoPix = parseFloat("<?=$configuracao_checkout['descontoPix']?>");
                var valorCupom = $("#valorCupom").val();

                if(!valorCupom) return;

                if(!$ready) carregando();

                jQuery.ajax({
                    url: "/site/Site_controller_checkout/AjaxGetCupom",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        cupom: valorCupom
                    },
                    error: function() {
                        exibirAviso('erro');
                    },
                    success: function(res) {  
                        if(!res) {
                            carregado();
                            return exibirAviso('Cupom inválido');
                        }

                        var texto = '';

                        if(res.desconto_tipo == 1){
                            // porcentagem
                            resultado = valorTotal - (parseFloat(res.valor)*valorTotal/100);

                            texto = '(Cupom de Desconto de '+res.valor+'%)';

                        }else{
                            // fixo 
                            resultado = valorTotal -  parseFloat(res.valor);
                            
                            texto = '(Cupom de Desconto de '+parseFloat(res.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })+')';
                        }

                        if(resultado < 0) resultado = 0;

                        if(resultado == 0){ 
                            $(".opcoesPagamento .opcao").hide()
                            $(".blocoOpcaoDado").hide();
                            $(".opcoesPagamento #blocoGratis").show()
                        }else{
                            $(".opcoesPagamento .opcao").show()
                            $(".opcoesPagamento #blocoGratis").hide()
                        }

                        valorTotalReal = valorTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        resultadoReal = resultado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

                        var html = ''; 
                        html += '<div class="base">'; 
                            html += '<span class="valorTotalSemDesconto">'; 
                                html += 'De: <span>'+valorTotalReal+'</span>'; 
                            html += '</span>'; 
                        html += '</div>'; 
                        html += '<div class="base">'; 
                            html += '<span class="desconto">'; 
                                html += 'Por: <span>'+resultadoReal+'</span>'; 
                            html += '</span>'; 
                        html += '</div>';
                        html += '<div class="base" style="margin-top: -10px;">'; 
                            html += '<span class="descontoTexto">'; 
                                html += texto; 
                            html += '</span>'; 
                        html += '</div>'; 

                        $(".produtos-total").html(html)
                        $("input[name='id_cupom']").val(res.id) 
                        $("input[name='codigo_cupom']").val(res.codigo_cupom)  


                        
                        var parcelaCC = "";
                        // Total em cada forma de parcela
                        for (var i = 1; i <= <?=$configuracao_checkout['parcela']?>; i++) { 
                            $("select[name='parcela'] option[value='"+i+"']").text(i+'x de '+ (resultado/i).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }))
                            parcelaCC += '<p>'+i+'x de '+ (resultado/i).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })+'</p>';
                        } 

                        $('#opcaoCC').html(parcelaCC)
                        $('#opcaoPix span').text((resultado - (descontoPix*resultado/100)).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }))
                        $('#opcaoBoleto span').text(resultadoReal)

                        $('input[name="forma_pagamento"]').prop("checked", false);

                        $("#pix-code-copy").val('')
                        $("#dadosPix").hide();  
                        	
                        carregado();
                        if(!$ready) exibirAviso('Cupom aplicado com sucesso!', "ok");

                    }
                });
            }
        </script>
    </div>

    <div class="checkout-sep"></div>

        <?php if($this->dados["dados"]["logado"] == true):?>
            <div id="bloco-dados" class="bloco-info">
                <div class="bloco">
                    <?php
                        $cliente = $this->dados["dados"]["cliente"];    
                    ?>
                    <div class="titulo">
                        <span>Dados da conta:</span>
                    </div>
                    <div class="conteudo dados-conta">
                        <div>Nome: <?php echo $cliente->nome;?></div>
                        <div>Email: <?php echo $cliente->email;?></div>
                        <div><?=$nomenclatura_cpf_cnpj?>: <?php echo $cliente->cpf_cnpj;?></div>
                        <div>Telefone: <?php echo ($cliente->telefone?$cliente->telefone:'Não informado');?></div>
                        <!-- <div>Celular: <?php echo ($cliente->celular?$cliente->celular:'Não informado');?></div> -->
                

                        <em onclick="blocoAtualizar(1);" class="icon-edit btn-editar-dados"></em>
                    </div>
                </div>
            </div>
            <script>
                function blocoAtualizar(valor){

                    if(valor = 1){
                        jQuery('#bloco-atualizar').css('display','block');
                        jQuery('#bloco-dados').css('display','none');
                    }else{
                        jQuery('#bloco-atualizar').css('display','none');
                        jQuery('#bloco-dados').css('display','block');
                    }
                   
                }
            </script>

            <div id="bloco-atualizar" style="display: none;">
            <div class="bloco">
                <div class="titulo">
                    <span>Atualizar dados:</span>
                </div>
                <div class="conteudo">

                    <form class="blocoform" name="formAtualizar" action="/checkout-atualizar.html" method="post">
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="" name="cpf_cnpj" alt="<?=$alt_cpf_cnpj?>" value="<?php echo $cliente->cpf_cnpj;?>">
                            <label for="">Insira seu <?=$nomenclatura_cpf_cnpj?></label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="Nome" name="nome" value="<?php echo $cliente->nome;?>">
                            <label for="">Insira seu Nome</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="Telefone/Celular" name="telefone" alt="phone" value="<?php echo $cliente->telefone;?>">
                            <label for="">Insira seu Telefone/Celular</label>
                        </div>
                    </div> 
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="Email" name="email" autocomplete="off" value="<?php echo $cliente->email;?>">
                            <label for="">Insira seu Email</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="password" placeholder="Senha" name="senha" autocomplete="off" value="">
                            <label for="">Insira sua Senha</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="password" placeholder="Confirme a Senha" name="senha_confirme" autocomplete="off" value="">
                            <label for="">Confirme a Senha</label>
                        </div>
                    </div>

                    <div class="linhaCompleta">
                            <div class="linha" style="width: 100%;">
                                <label class="checkboxLabel" >
                                    <input type="checkbox" class="checkpadrao" name="concordo" checked="" value="1"> <span> Li e aceito os <span style="cursor: pointer;text-decoration: underline;" onclick="abrirPopup_id('popupAlerta');">termos de uso</span> </span>
                                </label>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <label class="checkboxLabel" >
                                    <input type="checkbox" class="checkpadrao" <?php echo($cliente->notificacao == 1?'checked=""':''); ?> name="notificacao" value="1"> <span>Receber novidades via E-mail e SMS</span>
                                </label>
                            </div>
                    </div>
                    <!-- <div class="linhaCompleta">
                        <div class="linha" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="Celular" name="celular" alt="phone">
                        </div>
                    </div> -->

                    <div class="textCenter" style="margin-top: 10px;">
                        <span class="btn btn-cadastrar" onclick="salvar_dados();">Salvar</span>
                    </div>
                </form>

                </div>
            </div>
        </div>

        <div class="popupFundo1" id="popupAlertaFundo" onclick="fecharPopup_id('popupAlerta');"></div>
                <div class="popupSistema1" id="popupAlerta">
                    <div class="popupInformacoes">
                        <div class="tituloAlerta">Termos de uso</div>
                        <div class="descricaoAlerta">
                            <?php echo $modulo_16['descricao']; ?>
                        </div>
                    </div>
            </div>

        <script>
            function salvar_dados(){
                var f = document.formAtualizar;

                if(!<?=$funcao_validacao_cpf_cnpj?>(f.cpf_cnpj.value)){
                    return exibirAviso('Informe o <?=$nomenclatura_cpf_cnpj?> corretamente');
                }

                if(vazio(f.nome.value)){
                    return exibirAviso('Informe o seu Nome');
                }

                if(!validarTelefone(f.telefone.value)){
                    return exibirAviso('Informe o Telefone/Celular corretamente');
                }

                if(!validarEmail(f.email.value)){
                    return exibirAviso('Informe o Email corretamente');
                }

                // if(vazio(f.telefone.value) && vazio(f.celular.value)){
                //     return exibirAviso('Informe o Telefone ou Celular');
                // }

                // if(!vazio(f.telefone.value)){
                    
                // }

                // if(!vazio(f.celular.value)){
                //     if(!validarTelefone(f.celular.value)){
                //         return exibirAviso('Informe o Celular corretamente');
                //     }
                // }

                if(f.concordo.checked == false){
                    return exibirAviso('Aceite os termos de uso');
                }

                carregando();

                setTimeout(function(){f.submit();}, 500);
                
            }
        </script>

        <?php else:?>
        <div class="bloco-info">
            <div class="bloco">
                <div class="titulo">
                    <span>Identifique-se:</span>
                </div>
                <div class="conteudo">

                    <form class="blocoform" name="formCadastrar" action="/checkout-cadastrar.html" method="post">
                    <input type="hidden" name="codigo_cupom" value="">
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="" name="cpf_cnpj" alt="<?=$alt_cpf_cnpj?>" value="<?php echo $this->dados["dados"]["login_cpf_cnpj"];?>">
                            <label for="">Insira seu <?=$nomenclatura_cpf_cnpj?></label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="" name="nome">
                            <label for="">Insira seu Nome</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="" name="telefone" alt="phone">
                            <label for="">Insira seu Telefone/Celular</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="" name="email" autocomplete="off">
                            <label for="">Insira seu Email</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="password" placeholder="" name="senha" autocomplete="off">
                            <label for="">Insira sua Senha</label>
                        </div>
                    </div>
                    <div class="linhaCompleta">
                        <div class="linha input-container" style="width: 100%;">
                            <input class="campo-padrao" type="password" placeholder="" name="senha_confirme" autocomplete="off">
                            <label for="">Confirme a Senha</label>
                        </div>
                    </div>

                    <!-- <div class="linhaCompleta">
                        <div class="linha" style="width: 100%;">
                            <input class="campo-padrao" type="text" placeholder="Celular" name="celular" alt="phone">
                        </div>
                    </div> -->

                    <div class="linhaCompleta">
                        <div class="linha" style="width: 100%;">
                            <label class="checkboxLabel" >
                                <input type="checkbox" class="checkpadrao" name="concordo" value="1"> <span> Li e aceito os <span style="cursor: pointer;text-decoration: underline;" onclick="abrirPopup_id('popupAlerta');">termos de uso</span> </span>
                            </label>
                        </div>

                        <div class="linha" style="width: 100%;">
                            <label class="checkboxLabel" >
                                <input type="checkbox" class="checkpadrao" name="notificacao" value="1"> <span>Receber novidades via E-mail e SMS</span>
                            </label>
                        </div>
                    </div>

                    <div class="textCenter" style="margin-top: 10px;">
                        <span class="btn btn-cadastrar" onclick="cadastrar();">Cadastrar</span>
                    </div>
                </form>

                </div>
            </div>
        </div>

            <div class="popupFundo1" id="popupAlertaFundo" onclick="fecharPopup_id('popupAlerta');"></div>
                <div class="popupSistema1" id="popupAlerta">
                    <div class="popupInformacoes">
                        <div class="tituloAlerta">Termos de uso</div>
                        <div class="descricaoAlerta">
                            <?php echo $modulo_16['descricao']; ?>
                        </div>
                    </div>
            </div>

        <script>
            function cadastrar(){
                var f = document.formCadastrar;

                if(!<?=$funcao_validacao_cpf_cnpj?>(f.cpf_cnpj.value)){
                    return exibirAviso('Informe o <?=$nomenclatura_cpf_cnpj?> corretamente');
                }

                if(vazio(f.nome.value)){
                    return exibirAviso('Informe o seu Nome');
                }

                if(!validarTelefone(f.telefone.value)){
                    return exibirAviso('Informe o Telefone/Celular corretamente');
                }

                if(!validarEmail(f.email.value)){
                    return exibirAviso('Informe o Email corretamente');
                } 

                if(vazio(f.senha.value)){
                    return exibirAviso('Informe a Senha');
                }

                if(vazio(f.senha_confirme.value)){
                    return exibirAviso('Informe sua confirmação de senha');
                }

                if(f.senha.value != f.senha_confirme.value){
                    return exibirAviso('Senhas diferentes!');
                }

                // if(vazio(f.telefone.value) && vazio(f.celular.value)){
                //     return exibirAviso('Informe o Telefone ou Celular');
                // }

                // if(!vazio(f.telefone.value)){
                    
                // }

                // if(!vazio(f.celular.value)){
                //     if(!validarTelefone(f.celular.value)){
                //         return exibirAviso('Informe o Celular corretamente');
                //     }
                // }

                if(f.concordo.checked == false){
                    return exibirAviso('Aceite os termos de uso');
                }

                carregando();

                setTimeout(function(){f.submit();}, 500);
                
            }
        </script>
    <?php endif;?>

    <?php if($this->dados["dados"]["logado"] == true):?>
    <div class="checkout-sep"></div>
        <?php if(!$produto_assinatura):?>

            <div class="bloco-info forma-pagamento" id="forma-pagamento">
                <div class="bloco">
                    <div class="titulo">
                        <span>Forma de pagamento</span>
                    </div>
                    <div class="conteudo">

                        <form class="formas-pagamentos" name="formPagamento" method="post" action="/checkout-finalizar.html" >
                            <div class="txt">Selecione a forma de pagamento</div>
                            <div class="opcoesPagamento">
                                <input type="hidden" name="id_cupom" value="0">
                    
                                <label class="opcao" id="blocoGratis" style="display: <?=$valor_total == 0?"":"none"?>;">
                                    <div class="radio-div"><input type="radio" name="forma_pagamento" value="0"></div>
                                    <div class="img"><img src="/arquivos/imagens/credit-card.png" title="Gratis" alt="Gratis"></div>
                                    <div class="txt-opcao">Grátis</div>
                                    <div class="txt-opcao-total" id="opcaoGratis"><span>R$ <?=$this->model->moeda(0) ?></span></div>
                                </label>

                                <?php if(in_array(1, $configuracao_checkout['formas_pagamento'])):?>
                                    <label class="opcao" style="display: <?=$valor_total == 0?"none":""?>;">
                                        <div class="radio-div"><input type="radio" name="forma_pagamento" value="1"></div>
                                        <div class="img"><img src="/arquivos/imagens/credit-card.png" title="PIX" alt="PIX"></div>
                                        <div class="txt-opcao">PIX</div>
                                        <div class="txt-opcao-total" id="opcaoPix"><span>R$ <?=$this->model->moeda($valor_total-($configuracao_checkout['descontoPix']*$valor_total/100)) ?></span></div>
                                        <?php if($configuracao_checkout['descontoPix']):?>
                                            <div class="txt-opcao-desco">No pix desconto de <?=$configuracao_checkout['descontoPix']?>%</div>
                                        <?php endif;?>
                                        <input type="hidden" name="descontoPix" value="<?=$configuracao_checkout['descontoPix']?$configuracao_checkout['descontoPix']:0?>">

                                    </label>
                                <?php endif;?>

                                <?php if(in_array(2, $configuracao_checkout['formas_pagamento'])):?>
                                    <label class="opcao" style="display: <?=$valor_total == 0?"none":""?>;">
                                        <div class="radio-div"><input type="radio" name="forma_pagamento" value="2"></div>
                                        <div class="img"><img src="/arquivos/imagens/credit-card.png" title="Boleto" alt="Boleto"></div>
                                        <div class="txt-opcao">Boleto</div>
                                        <div class="txt-opcao-total" id="opcaoBoleto"><span>R$ <?=$this->model->moeda($valor_total) ?></span></div>
                                    </label>
                                <?php endif;?>

                                <?php if(in_array(3, $configuracao_checkout['formas_pagamento'])):?>
                                    <label class="opcao" style="display: <?=$valor_total == 0?"none":""?>;">
                                        <div class="radio-div"><input type="radio" name="forma_pagamento" value="3"></div>
                                        <div class="img"><img src="/arquivos/imagens/credit-card.png" title="Cartão" alt="Cartão"></div>
                                        <div class="txt-opcao">Cartão de Crédito</div>
                                        <div class="txt-opcao-total" id="opcaoCC">
                                            <?php if($configuracao_checkout['parcela']):?>
                                                <?php for ($i=1; $i <= $configuracao_checkout['parcela'] ; $i++):?>
                                                    <p><?=$i?>x de R$ <?php echo $this->model->moeda($valor_total/$i)?></p> 
                                                <?php endfor;?>
                                            <?php endif;?> 
                                        </div>


                                    </label>
                                <?php endif;?>
                            </div>

                            <?php if(in_array(1, $configuracao_checkout['formas_pagamento'])):?>
                                <div class="blocoOpcaoDado dadosPix" id="dadosPix" style="display: none;">
                                    <div class="bloco-pix">
                                        <div class="mdl-grid">
                                            <div class="bloco-qr-pix">
                                                <img class="js-pix-qr-code" src="" alt="QR Code Pix">
                                                <div class="pix-qrcode-txt-leitura">Acesse seu APP de pagamentos e faça a leitura do QR Code ao lado para efetuar o pagamento de forma rápida e segura.</div>
                                            </div>
                                            <div class="bloco-text-pix-copy">
                                                <span class="pix-qrcode-label">
                                                    Código Pix Copia e Cola
                                                </span>
                                                <div class="pix-code">
                                                    <span class="pix-code-info" style="word-break: break-all; "></span>
                                                    <input type="hidden" id="pix-code-copy" value="">
                                                </div>
                                            </div>
                                            <div class="div-btn">
                                                <button type="button" class="btn-copiar" id="btn-pix-code-copy">Copiar</button>
                                                
                                                <div class="copy-pix-snackbar" id="copy-pix-snackbar"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.getElementById('btn-pix-code-copy').addEventListener('click', copyCodePix);
                                    function copyCodePix() {
                                        let text = document.querySelector("#pix-code-copy").value;
                                        // text.select();                  
                                        // document.execCommand('copy');
                                        navigator.clipboard.writeText(text);

                                        document.querySelector("#copy-pix-snackbar").innerHTML = 'Código copiado!';
                                    }
                                </script>
                            <?php endif;?> 

                            <?php if(in_array(2, $configuracao_checkout['formas_pagamento'])):?>
                                <div class="blocoOpcaoDado dadosBoleto" id="dadosBoleto" style="display: ;">
                                    <div class="blocos-boleto">
                                        <div class="bloco-boleto">
                                            <div class="barra-qrcode-label">Código de Barra Copia e Cola</div>
                                            <div class="barra-code-info" style="word-break: break-all; "></div>
                                            <input type="hidden" id="barra-code-copy" value="">

                                            <div class="div-btn">
                                                <button type="button" class="btn-copiar" id="btn-barra-code-copy">Copiar</button>
                                                <div class="copy-barra-snackbar" id="copy-barra-snackbar"></div>
                                            </div>
                                            <div class="btn-ver">
                                                <a href="#" class="btn" target="_blank" style="margin: 40px auto 0 auto;">Visualizar boleto</a>
                                            </div>
                                        </div>
                                        <div class="bloco"></div>
                                    </div>
                                </div>
                                <script>
                                    document.getElementById('btn-barra-code-copy').addEventListener('click', copyCodeBarra);
                                    function copyCodeBarra() {
                                        let text = document.querySelector("#barra-code-copy").value;
                                        navigator.clipboard.writeText(text);
                                        document.querySelector("#copy-barra-snackbar").innerHTML = 'Código copiado!';
                                    }
                                </script>
                            <?php endif;?> 

                            <?php if(in_array(3, $configuracao_checkout['formas_pagamento'])):?>
                            <div class="blocoOpcaoDado dadosCartaoCredito" id="dadosCartaoCredito" style="display: none;">
                                
                                <div class="InforCC">
                                    <div class="tituloPaga">Informações do cartão de crédito</div>
                                    <div class="campos">
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Nome impresso no cartão" name="holderName">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Número do cartão" name="number" alt="number_credit_card">
                                        </div>
                                        <div class="linha-completa">
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Mês de expiração (ex: 06)" name="expiryMonth" alt="month">
                                            </div>
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Ano de expiração com 4 dígitos (ex: 2019):" name="expiryYear" alt="year">
                                            </div>
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="CCV" name="ccv" alt="ccv">
                                            </div>
                                        </div>
                                        <?php if($configuracao_checkout['parcela']):?>
                                        <div class="linha">
                                            <select name="parcela" class="campo-padrao">
                                                <option value="">Parcela</option>
                                                <?php for ($i=1; $i <= $configuracao_checkout['parcela'] ; $i++):?>
                                                    <option value="<?=$i?>"><?=$i?>x de R$ <?php echo $this->model->moeda($valor_total/$i)?></option> 
                                                <?php endfor;?>
                                            </select>
                                        </div>
                                        <?php endif;?> 
                                    </div>
                                </div>

                                <div class="InforCCTitular">
                                    <div class="tituloPaga">Informações do titular do cartão de crédito</div>
                                    <div class="campos">
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Nome" name="name">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Email" name="email">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="CPF ou CNPJ" name="cpfCnpj" alt="cpf_cnpj">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="CEP" name="postalCode" alt="cep">
                                        </div>
                                        <div class="linha-completa">
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Complemento do endereço" name="addressComplement">
                                            </div>
                                            <div class="linha" style="max-width: 150px;">
                                                <input class="campo-padrao" type="text" placeholder="Número do endereço" name="addressNumber">
                                            </div>
                                        </div>
                                        <div class="linha-completa">
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Telefone" name="phone" alt="phone">
                                            </div>
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Celular" name="mobilePhone" alt="phone">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <?php endif;?>

                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        ativar_blocoOpcaoDado();
                    })

                    $('input[name="forma_pagamento"]').on('change', function(){
                        ativar_blocoOpcaoDado();
                    })
                    function ativar_blocoOpcaoDado(){
                        $(".blocoOpcaoDado").hide();
                        $(".btn-finalizado").show();

                        var checkboxCartao = $('input[name="forma_pagamento"]:checked').val();
                        if(checkboxCartao == 3){
                            $("#dadosCartaoCredito").show(); 
                        }else if(checkboxCartao == 2){
                            if($("#barra-code-copy").val()) {
                                $("#dadosBoleto").show(); 
                                $(".btn-finalizado").hide();
                            }
                        }else if(checkboxCartao == 1){
                            if($("#pix-code-copy").val()) {
                                $("#dadosPix").show();
                                $(".btn-finalizado").hide();
                            }
                        }
                        
                    }
                    
                </script>

                <div> 
                    
                    <span class="btn-finalizado" onclick="finalizar();">Finalizar compra</span>


                </div>

            </div>

            <script>
                function finalizar(){
                    var f = document.formPagamento;

                    if(!<?=$funcao_validacao_cpf_cnpj?>("<?=$cliente->cpf_cnpj?>")){
                        return exibirAviso('<?=$nomenclatura_cpf_cnpj?> inválido, Favor atualize o <?=$nomenclatura_cpf_cnpj?> para fazer o pagamento');
                    } 

                    if(vazio(f.forma_pagamento.value)){
                        return exibirAviso('Informe a forma de pagamento');
                    }
                    

                    if(f.forma_pagamento.value == 3){
                        // informacoes cartao de credito
                
                        if(vazio(f.holderName.value)){
                            return exibirAviso('Informe o nome impresso no cartão de crédito');
                        }

                        if(!validarNumeroCCredito(f.number.value)){
                            return exibirAviso('Informe o número do cartão de crédito corretamente!');
                        }

                        if(!validarMes(f.expiryMonth.value)){
                            return exibirAviso('Informe o mês do cartão de crédito corretamente!');
                        }

                        if(!validarAno(f.expiryYear.value)){
                            return exibirAviso('Informe o ano do cartão de crédito corretamente!');
                        }

                        if(!validarCVV(f.ccv.value)){
                            return exibirAviso('Informe o ccv do cartão de crédito corretamente!');
                        }

                        if(vazio(f.parcela.value)){
                            return exibirAviso('Informe a quantidade de parcela!');
                        }

                        // informacoes titular
                        if(vazio(f.name.value)){
                            return exibirAviso('Informe o nome do titular do cartão de crédito!');
                        }

                        if(!validarEmail(f.email.value)){
                            return exibirAviso('Informe o email do titular do cartão de crédito!');
                        }

                        if(!validarCPFCNPJ(f.cpfCnpj.value)){
                            return exibirAviso('Informe o CPF ou CNPJ do titular do cartão de crédito!');
                        }

                        if(!validarCEP(f.postalCode.value)){
                            return exibirAviso('Informe o CEP do titular do cartão de crédito!');
                        }

                        if(vazio(f.addressNumber.value)){
                            return exibirAviso('Informe o número do endereço do titular do cartão de crédito!');
                        }

                        if(!validarTelefoneOld(f.phone.value)){
                            return exibirAviso('Informe o telefone do titular do cartão de crédito!');
                        }

                        if(!vazio(f.mobilePhone.value) && !validarTelefoneOld(f.mobilePhone.value)){
                            return exibirAviso('Informe o celular do titular do cartão de crédito!');
                        }
                    }  

                    if(f.forma_pagamento.value == 1){
                        if($("#pix-code-copy").val()) {
                            $("#dadosPix").show(); 
                            return;
                        }

                        carregando();
                        
                        jQuery.ajax({
                            url: f.action+'?ajax=1',
                            type: f.method,
                            data: jQuery(f).serialize(),
                            error: function() {},
                            success: function(res) { 
                                
                                if(res.success){     
                                    $(".pix-code-info").text(res.payload);
                                    $("#pix-code-copy").val(res.payload);
                                    $(".js-pix-qr-code").attr('src', 'data:image/png;base64,'+res.encodedImage);
                                    ativarVerificaçãoPix(res.id);
                                    ativar_blocoOpcaoDado();

                                    $('html, body').animate({
                                        scrollTop: $("#forma-pagamento").offset().top
                                    }, 1000); 
                                }else if(res.errors[0].description){
                                    exibirAviso(res.errors[0].description)
                                }else{
                                    exibirAviso('Falha no pagamento, informe ao administrador do site!')
                                }
                                
                                carregado();
                            }
                        });

                        return;
                    }else if(f.forma_pagamento.value == 2){
                        if($("#barra-code-copy").val()) {
                            $("#dadosBoleto").show(); 
                            return;
                        }

                        carregando();
                        
                        jQuery.ajax({
                            url: f.action+'?ajax=1',
                            type: f.method,
                            data: jQuery(f).serialize(),
                            error: function() {},
                            success: function(res) { 
                                
                                if(res.id){     
                                    $(".barra-code-info").text(res.barCode);
                                    $("#barra-code-copy").val(res.barCode);
                                    $("#dadosBoleto .bloco-boleto .btn-ver a").attr('href',res.bankSlipUrl);
                                    ativar_blocoOpcaoDado();

                                    $('html, body').animate({
                                        scrollTop: $("#forma-pagamento").offset().top
                                    }, 1000); 
                                }else if(res.errors[0].description){
                                    exibirAviso(res.errors[0].description)
                                }else{
                                    exibirAviso('Falha no pagamento, informe ao administrador do site!')
                                }
                                
                                carregado();
                            }
                        });

                        return;
                    }

                    carregando();

                    setTimeout(function(){f.submit();}, 500);
                    
                }

                function ativarVerificaçãoPix(id){ 
                    const eventSource = new EventSource('/site/Site_controller_checkout/getStatusCobrancaPix/'+id);
                    
                    eventSource.onmessage = (event) => {
                        const jsonData = JSON.parse(event.data);
                        if(jsonData.status == "RECEIVED") window.location.href = '/checkout-finalizado.html';
                    };
                }
            </script>

        <?php else:?>
            <div class="bloco-info forma-pagamento" id="forma-pagamento">
                <div class="bloco">
                    <div class="titulo">
                        <span>Cartão de crédito</span>
                    </div>
                    <div class="conteudo">

                        <form class="formas-pagamentos" name="formPagamento" method="post" action="/checkout-finalizar-assinatura.html" >
                            <input type="hidden" name="id_cupom" value="0">
                            
                            <?php if(in_array(3, $configuracao_checkout['formas_pagamento'])):?>
                            <div class="blocoOpcaoDado dadosCartaoCredito" id="dadosCartaoCredito">
                                
                                <div class="InforCC">
                                    <div class="tituloPaga">Informações do cartão de crédito</div>
                                    <div class="campos">
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Nome impresso no cartão" name="holderName">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Número do cartão" name="number" alt="number_credit_card">
                                        </div>
                                        <div class="linha-completa">
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Mês de expiração (ex: 06)" name="expiryMonth" alt="month">
                                            </div>
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Ano de expiração com 4 dígitos (ex: 2019):" name="expiryYear" alt="year">
                                            </div>
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="CCV" name="ccv" alt="ccv">
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                <div class="InforCCTitular">
                                    <div class="tituloPaga">Informações do titular do cartão de crédito</div>
                                    <div class="campos">
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Nome" name="name">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="Email" name="email">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="CPF ou CNPJ" name="cpfCnpj" alt="cpf_cnpj">
                                        </div>
                                        <div class="linha">
                                            <input class="campo-padrao" type="text" placeholder="CEP" name="postalCode" alt="cep">
                                        </div>
                                        <div class="linha-completa">
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Complemento do endereço" name="addressComplement">
                                            </div>
                                            <div class="linha" style="max-width: 150px;">
                                                <input class="campo-padrao" type="text" placeholder="Número do endereço" name="addressNumber">
                                            </div>
                                        </div>
                                        <div class="linha-completa">
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Telefone" name="phone" alt="phone">
                                            </div>
                                            <div class="linha">
                                                <input class="campo-padrao" type="text" placeholder="Celular" name="mobilePhone" alt="phone">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <?php endif;?>

                            </div>
                        </form>
                    </div>
                </div>

                <div> 
                    
                    <span class="btn-finalizado" onclick="finalizar();">Finalizar compra</span>


                </div>

            </div>

            <script>
                function finalizar(){
                    var f = document.formPagamento;

                    if(!<?=$funcao_validacao_cpf_cnpj?>("<?=$cliente->cpf_cnpj?>")){
                        return exibirAviso('<?=$nomenclatura_cpf_cnpj?> inválido, Favor atualize o <?=$nomenclatura_cpf_cnpj?> para fazer o pagamento');
                    }  

                    // informacoes cartao de credito
            
                    if(vazio(f.holderName.value)){
                        return exibirAviso('Informe o nome impresso no cartão de crédito');
                    }

                    if(!validarNumeroCCredito(f.number.value)){
                        return exibirAviso('Informe o número do cartão de crédito corretamente!');
                    }

                    if(!validarMes(f.expiryMonth.value)){
                        return exibirAviso('Informe o mês do cartão de crédito corretamente!');
                    }

                    if(!validarAno(f.expiryYear.value)){
                        return exibirAviso('Informe o ano do cartão de crédito corretamente!');
                    }

                    if(!validarCVV(f.ccv.value)){
                        return exibirAviso('Informe o ccv do cartão de crédito corretamente!');
                    }

                    // informacoes titular
                    if(vazio(f.name.value)){
                        return exibirAviso('Informe o nome do titular do cartão de crédito!');
                    }

                    if(!validarEmail(f.email.value)){
                        return exibirAviso('Informe o email do titular do cartão de crédito!');
                    }

                    if(!validarCPFCNPJ(f.cpfCnpj.value)){
                        return exibirAviso('Informe o CPF ou CNPJ do titular do cartão de crédito!');
                    }

                    if(!validarCEP(f.postalCode.value)){
                        return exibirAviso('Informe o CEP do titular do cartão de crédito!');
                    }

                    if(vazio(f.addressNumber.value)){
                        return exibirAviso('Informe o número do endereço do titular do cartão de crédito!');
                    }

                    if(!validarTelefoneOld(f.phone.value)){
                        return exibirAviso('Informe o telefone do titular do cartão de crédito!');
                    }

                    if(!vazio(f.mobilePhone.value) && !validarTelefoneOld(f.mobilePhone.value)){
                        return exibirAviso('Informe o celular do titular do cartão de crédito!');
                    } 

                    carregando();

                    setTimeout(function(){f.submit();}, 500);
                    
                }
            </script>
        <?php endif;?>
    <?php endif;?>

</div>

</div>
</div>

</div>



<script>
    

    function verificar_idade(data_nasicmento){
        
        var d = new Date,
        ano_atual = d.getFullYear(),
        mes_atual = d.getMonth() + 1,
        dia_atual = d.getDate(),
        split = data_nasicmento.split('-'),

        ano_aniversario = + split[0],
        mes_aniversario = + split[1],
        dia_aniversario = + split[2],

        quantos_anos = ano_atual - ano_aniversario;

        if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
            quantos_anos--;
        }

        return (quantos_anos > 0? quantos_anos : 0 );
    }
</script>


<script>
    function remover_carrinho(carrinho){
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