<?php
    $controller->load->model('modulos/modulos_model_config', 'model_config');
    $config = $controller->model_config->buscar_config();

    if($config['campos']){
        $config['campos'] = json_decode($config['campos'],true);
    }

    $controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
    $modulo_2 = $controller->model_modulo->buscar_modulo(2);
    $modulo_3 = $controller->model_modulo->buscar_modulo(3);

    if(file_exists(APPPATH .'models/landingpage/Landingpage_model_landingpage.php')){
        $controller->load->model('landingpage/Landingpage_model_landingpage', 'model_landingpage');
        $configuracoesLP = $controller->model_landingpage->buscarConfigAll();
        $estadosCapitaisLP = $controller->model_landingpage->buscarEstadoCapitalAll();
        $estadosLP = $controller->model_landingpage->buscarEstadoAll();
    }
    
?>

<footer class="footere">
    <section id="rodape-bloco" class="textoPadrao">
        <div class="container">
            <div class="footer-itens">
                <div class="footers-1">
                    <div class="titulo-rodape"><?=$config['nome_empresa']?></div>
                    <div class="bloco">
                        <div class="txt1" style="max-width: 200px;"><?=$config['slogan']?></div>
                        <input type="text" placeholder="Digite aqui seu e-mail" class="input_footer1">
                    </div>
                    <div>
                        <h1>Central de atendimento</h1>

                    </div>
                </div>
                <div class="footers-2">
                    <div class="titulo-rodape"><?=$modulo_3['nome']?></div>
                    <div class="bloco aft-rodape">
                        <?=$modulo_3['descricao']?>
                    </div>
                </div>
                <div class="footers-3">
                    <div class="titulo-rodape"><?=$modulo_2['nome']?></div>
                    <div class="bloco aft-rodape">
                        <?=$modulo_2['descricao']?>
                    </div>
                </div>
                <div class="footers-4">
                    <div class="titulo-rodape">Social</div>
                    <ul>
                        <li>Facebook</li>
                        <li>Instagram</li>
                        <li>Youtube</li>
                    </ul>
                </div>
            </div>

            <div class="footers-extra">
                <div class="imagens">
                    <div class="bloco">
                        <?php if($config['telefone1']): ?>
                            <div class="txt whatsapp">
                                <a target="_blank" title="WhatsApp" href="https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=Olá, Vim do site e gostaria de mais informações">
                                    <em class="icon-whatsapp"></em><?=$config['telefone1']?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($config['telefone2']): ?>
                            <div class="txt whatsapp">
                                <a title="telefone" href="tel:0<?=preg_replace('/[^0-9]/', '', $config['telefone2']);?>">
                                    <em class="icon-phone"></em><?=$config['telefone2']?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($config['email_atendimento']): ?>
                        <div class="txt email">
                            <a title="Email" href="mailto:<?=$config['email_atendimento']?>"><?=$config['email_atendimento']?></a>
                        </div>
                        <?php endif; ?> 
                    </div>
                    
                </div>  

                
                <img src="/arquivos/imagens/logo_despacho_rapido.png" alt="logo" title="logo">
                      
            </div>
            <hr>

            
            <!-- <?php if(!empty($configuracoesLP)):?>
                <?php foreach($configuracoesLP as $configuracao): 
                    $alias_pre = $controller->model_landingpage->getAlias($configuracao->{'Palavra-Chave-01'});
                    ?>
                    <?php if(!empty($estadosCapitaisLP)): $i = 0;?>
                        <div class="rodape-bloco-baixo-cidades">
                            <div class="titulo-rodape"><?=$configuracao->{'Palavra-Chave-01'}?> - Capitais</div>
                            <div class="bloco aft-rodape">
                                <?php foreach($estadosCapitaisLP as $cidade): 
                                    if($i++ != 0) echo " - "; ?>
                                    <a href="<?=base_url().$alias_pre.'-'.$cidade->alias;?>.html" title="<?=$cidade->nome?>"><?=$cidade->nome?></a>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endif;?>

                    <?php if(!empty($estadosLP)): $i = 0;?>
                        <div class="rodape-bloco-baixo-cidades">
                            <div class="titulo-rodape"><?=$configuracao->{'Palavra-Chave-01'}?> - Estados</div>
                            <div class="bloco aft-rodape">
                                <?php foreach($estadosLP as $estado): 
                                    if($i++ != 0) echo " - "; ?>
                                    <a href="<?=base_url().$alias_pre.'-'.$estado->alias;?>.html" title="<?=$estado->nome?>"><?=$estado->nome?></a>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endif;?>
                <?php endforeach;?>    
            <?php endif;?> -->


           

        </div>
    </section>

    <section id="copyright" class="fundo">
        <div class="box">
                <div class="bloco-endereco">
                    <a href="https://www.imaginecomunicacao.com.br/" target="_blank" title="Agência Publicidade Digital" class="txt1">Despacho Rápido. Todos os direitos reservados 2024</a>
                    <?php if($config['razao_social'] && $config['cnpj']){
                        echo $config['razao_social'].' - CNPJ: '.$config['cnpj'];
                    }else{
                        echo $config['razao_social'].$config['cnpj'];
                        echo $config['endereco'];
                    }
                    ?> 
                </div>
                <p class="text-fundo">Política de Privacidade</p>
		</div>	
        
	</section>
</footer>

<script type="text/javascript">
    function dropdown_canvas(div){
        $(div).parent().toggleClass('down-menu');
        if('none' == $(div).parent().children('ul').css('display')){
            $(div).parent().children('ul').slideDown();
        }else{
            $(div).parent().children('ul').slideUp();
        }
    }
</script>  

<style>
    .bloco-endereco{
        display: flex;
        flex-direction: column;
        margin: auto;
    }
    .footere{
        padding:20px ;
        color: #fff;
	    background-color: #01032a;
    }

    .fundo{
        display: flex;
        flex-wrap: wrap;
        width: 80%;
    }

    .container{
        display: flex;
        flex-direction: column;
    }
    .bloco{
        display: flex;
        flex-direction: column;
    }
    .footers-extra{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    hr{
        width: 100%;
        margin: auto;
        margin: 20px;
    }
    .input_footer1{
        border-radius: 15px;
        border: none;
        margin-top: 10px;
    }

    .footers-1{
        gap: 12px;
    }
    .box{
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: auto;
    }
    .text-fundo{
        width: 200px;
    }
   
</style>

