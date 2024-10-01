<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $dados = $this->dados["dados"]["resultado"];
?>
<div class="<?php echo $controller->_classes_pagina(); ?>">
    <div class="topo-componente">
        <div class="container">
            <div class="blocotextoTopo">
                <div class="tituloTopo">Configurações de contato</div>
                <div class="botoesTopo">
                    <a onclick="form_enviar();" class="botao_padra_1 btn_verde">Salvar</a>
                </div>
            </div>
        </div>
    </div>

	<div class="meio-componente">
        <div class="container">
            <form name="form" action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="blocoFormulario" style="margin-top: 0px;">
                    <div class="tituloBloco" onclick="sanfona('#bloco1');">Notificações de Email</div>
                    <div class="informacao" id="bloco1">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email de notificações 1</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email1" value="<?php echo $dados["email1"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email de notificações 2</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email2" value="<?php echo $dados["email2"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email de notificações do trabalhe conosco:</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email_trabalhe_conosco" value="<?php echo $dados["email_trabalhe_conosco"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email para Resposta</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email_resposta" value="<?php echo $dados["email_resposta"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Observação</div>
                                    <div class="input-div">

                                        <div style="margin-left: 10px;">
                                            <div>Os endereços de email que foram configurados acima receberão notificações do site indicando interações de usuário.</div>
                                            <div>Caso o email de "Trabalhe Conosco" estiver vazio, as mensagens do formulário de "Trabalhe Conosco" irão para o primeiro e segundo email.</div>
                                            <div>Caso o email para "Resposta" estiver preenchido, as respostas das mensagens enviadas vão ser encaminhadas para ele.</div>
                                            <div><b>O primeiro email de notificações é obrigatório.</b></div>
                                        </div>
                        
                                    </div>
                                </div>
                        </div>
                        

                    </div>
                </div>


                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco2');">Configurações de estilo do envio</div>
                    <div class="informacao" id="bloco2" style="display: none;">
                        <div class="linhaCompleta formulario-pd">
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Imagem</div>
                                <div class="input-div">
                                    <input class="campo-padrao" type="file" name="imagem[]"/>
                                </div>
                            </div>
                            <div class="linha" style="width: 48%;">
                                <div class="txtCampo">Logomarca</div>
                                <div class="anexos-salvos">
                                    <?php 
                                    if(isset($dados["logomarca"]) && $dados["logomarca"]):
                                        $dir = base_url('arquivos').'/imagens/contato/';
                                        ?>
                                        <div class="img-anexo">
                                            <img src="<?php echo $dir.$dados["logomarca"];?>" style="max-height: 80px;">
                                        </div>
                                    <?php 
                                    endif;
                                    ?>

                                    <input type="hidden" name="logomarca" value="<?php echo $dados["logomarca"];?>">
                                </div>
                            </div>    
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Cor da Borda</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="color" name="email_cor_borda" value="<?php echo $dados["email_cor_borda"];?>"  style="padding:0px;">
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Cor do Fundo</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="color" name="email_cor_fundo" value="<?php echo $dados["email_cor_fundo"];?>"  style="padding:0px;">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco3');">Configurações de envio</div>
                    <div class="informacao" id="bloco3" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email/Conta</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email_envio" value="<?php echo $dados["email_envio"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Senha</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="password" name="senha_envio" value="<?php echo $dados["senha_envio"];?>" >
                                    </div>
                                </div>
                        </div>
                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Nome de envio</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome_envio" value="<?php echo $dados["nome_envio"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email de exibição</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email_exibicao" value="<?php echo $dados["email_exibicao"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">SMTP</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="smtp" value="<?php echo $dados["smtp"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Porta</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="porta" value="<?php echo $dados["porta"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Segurança</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="seguranca" value="<?php echo $dados["seguranca"];?>" >
                                    </div>
                                </div>
                        </div>

                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco4');">Informações</div>
                    <div class="informacao" id="bloco4" style="display: none;">  

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Slogan</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="slogan" value="<?php echo $dados["slogan"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Email de atendimento</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="email_atendimento" value="<?php echo $dados["email_atendimento"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">WhatsApp</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="telefone1" value="<?php echo $dados["telefone1"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Telefone Fixo</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="telefone2" value="<?php echo $dados["telefone2"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Nome da Empresa</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="nome_empresa" value="<?php echo $dados["nome_empresa"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Razão Social</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="razao_social" value="<?php echo $dados["razao_social"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">CNPJ</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="cnpj" value="<?php echo $dados["cnpj"];?>" alt="cnpj">
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Endereço</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="endereco" value="<?php echo $dados["endereco"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Traçar Rota</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="tracar" value="<?php echo $dados["tracar"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Horario</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="horario" value="<?php echo $dados["horario"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 100%;">
                                    <div class="txtCampo">Iframe Google Maps</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="iframe_google_maps" value="<?php echo htmlspecialchars($dados["iframe_google_maps"], ENT_QUOTES);?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Facebook</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_facebook" value="<?php echo $dados["redes_facebook"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Instagram</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_instagram" value="<?php echo $dados["redes_instagram"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Twitter</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_twitter" value="<?php echo $dados["redes_twitter"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">YouTube</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_youtube" value="<?php echo $dados["redes_youtube"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Pinterest</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_pinterest" value="<?php echo $dados["redes_pinterest"];?>" >
                                    </div>
                                </div>
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Linkedin</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_linkedin" value="<?php echo $dados["redes_linkedin"];?>" >
                                    </div>
                                </div>
                        </div>

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">TikTok</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="redes_tiktok" value="<?php echo $dados["redes_tiktok"];?>" >
                                    </div>
                                </div> 
                                <div class="linha" style="width: 31.5%;">
                                    <div class="txtCampo">Avaliação Google meu negócio</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="google_meu_negocio" value="<?php echo $dados["google_meu_negocio"];?>" >
                                    </div>
                                </div> 
                        </div>

                        <div class="linhaCompleta formulario-pd">
                            <div class="linha">
                                <label>
                                    <input name="popup_cookies" class="checkbox" type="checkbox" value="1" <?php echo ($dados["popup_cookies"] == 1?'checked=""':'');?> >
                                    <span class="texto">Exibir popup de utilização de cookies</span>
                                </label>
                            </div>  
                        </div>  

                        <div class="linhaCompleta formulario-pd">

                                

                                <div id="tabela_campo" style="display: flex;flex-wrap: wrap;width: 60%;">
                                    <?php 
                                        $id_campo = 0;
                                        if($dados['campos']):
                                        foreach(json_decode($dados['campos']) as $k => $campo): $id_campo = $k; ?>
                                        <div class="linha" style="width: 100%;">
                                            <div class="txtCampo">Campo adicional</div>
                                            <div class="input-div">
                                                <input class="campo-padrao" type="text" name="campos[<?=$k?>]" value="<?=$campo?>" >
                                                <em class="icon-trash icone-td btn_remover"></em>
                                            </div>
                                        </div>
                                    <?php 
                                        endforeach; 
                                        endif;
                                    ?>
                                </div>
                                <div class="mais-div-conteudo text-right" style="width:100%;">
                                    <span class="txt" id="btn_adicionar">+ Campo adicional<span>
                                </div>
                        </div>

                        <script type="text/javascript">
                            var id = <?=$id_campo?>;
                            $('#btn_adicionar').click(function(){
                                id++;

                                var html = $(`
                                            <div class="linha" style="width: 100%;">
                                                <div class="txtCampo">Campo adicional</div>
                                                <div class="input-div">
                                                    <input class="campo-padrao" type="text" name="campos[`+id+`]" value="" >

                                                    <em class="icon-trash icone-td btn_remover"></em>
                                                </div>
                                            </div>`);
                                html.find('.btn_remover').click(remover_campo);
                                $('#tabela_campo').append(html);
                            });

                            function remover_campo(){
                                $(this).parent().parent().remove();
                            }

                            $('#tabela_campo .btn_remover').click(remover_campo);
                        </script>


                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco5');">Configurações Recaptcha</div>
                    <div class="informacao" id="bloco5" style="display: none;">

                        <div class="linhaCompleta formulario-pd">
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Recaptcha Key</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="recaptcha_key" value="<?php echo $dados["recaptcha_key"];?>">
                                    </div>
                                </div>
                                <div class="linha" style="width: 48%;">
                                    <div class="txtCampo">Recaptcha Secret</div>
                                    <div class="input-div">
                                        <input class="campo-padrao" type="text" name="recaptcha_secret" value="<?php echo $dados["recaptcha_secret"];?>">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="blocoFormulario">
                    <div class="tituloBloco" onclick="sanfona('#bloco6');">Formações Padrões</div>
                    <div class="informacao" id="bloco6" style="display: none;">  
                        <div class="linhaCompleta formulario-pd formacoes">

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Título 1</div>
                                <div class="exemplos">
                                    <div class="tituloPadrao">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Título 2</div>
                                <div class="exemplos">
                                    <div class="tituloPadrao2">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Título 3</div>
                                <div class="exemplos">
                                    <div class="tituloPadrao3">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Título 4</div>
                                <div class="exemplos">
                                    <div class="tituloPadrao4">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Headline</div>
                                <div class="exemplos">
                                    <div class="headline">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Headline 2</div>
                                <div class="exemplos">
                                    <div class="headline2">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Texto</div>
                                <div class="exemplos">
                                    <div class="textoPadrao">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Botão</div>
                                <div class="exemplos">
                                    <div class="btn">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Botão 2</div>
                                <div class="exemplos">
                                    <div class="btn2">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Botão 3</div>
                                <div class="exemplos">
                                    <div class="btn3">Formação Padrão</div>
                                </div>
                            </div>

                            <div class="linha" style="width: 100%;">
                                <div class="txtCampo">Lista</div>
                                <div class="exemplos">
                                    <ul>
                                        <li>Formação Padrão</li>
                                        <li>Formação Padrão</li>
                                        <li>Formação Padrão</li>
                                        <li>Formação Padrão</li>
                                        <li>Formação Padrão</li>
                                    </ul>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                

                <script>
                    function form_enviar(){
                        var f = document.form;

                        carregando();
                        setTimeout(function(){f.submit();}, 300);
                    }
                </script>

            </form>
        </div>
    </div>
</div>

<script>
    function sanfona(elemento){
        $('.informacao').slideUp();
        $(elemento).slideToggle();
    }

    $('.formacoes .exemplos').map(function(i,e){

        var linha = $(e).find('>*');

        var fonte = linha.css('font-family');

        if(linha.is('ul') || linha.is('ol')){
            $(e).find('li').attr('data-font', ' ('+fonte+')')
        }else{
            linha.attr('data-font', ' ('+fonte+')')
        }
         
    })
</script>
<style>
.formacoes .exemplos >*:after, 
.formacoes .exemplos >ul li:after,
.formacoes .exemplos >ol li:after{
    content: attr(data-font);
}
.icone-td {
    font-size: 18px;
    color: #113075;
    cursor: pointer;
    margin: 11px 5px;
}
</style>