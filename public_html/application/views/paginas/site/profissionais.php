<?php
$profissionais = $this->dados["dados"]["resultado"];

$busca[0] = '';
$busca[1] = '';

if(isset($_POST['nome']) && $_POST['nome']){
    $busca[0] = $_POST['nome'];
}

if(isset($_POST['especialidade']) && $_POST['especialidade']){
    $busca[1] = $_POST['especialidade'];
}

?>
<div class="blocos-corpo-clinico">
    <div class="container">
        <h1 class="textCenter tituloPadrao" title="Procure pelo profissional">Procure pelo profissional</h1>

        <form class="bloco-busca-clinica" name="formProfissionais" method="post">
            <div class="input_e_btn">

                <div class="campo_bol_inf">
                    <input type="text" name="nome" class="inputbox bol_inf_txt1" placeholder="Nome do profissional" value="<?php echo $busca[0];?>">
                    <em class="icon-search" onclick="enviar_busca();"></em>
                </div>

                <div class="campo_bol_inf">
                    <input type="text" name="especialidade" class="inputbox bol_inf_txt2" placeholder="Buscar por especialidade" value="<?php echo $busca[1];?>">
                    <em class="icon-search" onclick="enviar_busca();"></em>
                </div>
                <!-- <span  class="btn" onclick="enviar_busca();"><em class="icon-search"></em></span> -->
            </div>
        </form>
        <script>
            function enviar_busca(){
                var f = document.formProfissionais;

                carregando();

                setTimeout(function(){f.submit();}, 500);

            }
        </script>

        <div class="lista-corpo-clinico">
            <?php 
            $dir = '/arquivos/imagens/profissional/';
            foreach($profissionais as $ka => $profissional):?>
            <div class="bloco">
                <div class="img">
                    <?php if($profissional->imagem):?>
                        <img src="<?php echo $controller->imagineImagem->otimizar($dir.$profissional->imagem, 250, 250, false, false, 80);?>" title="<?php echo $profissional->nome;?>" alt="<?php echo $profissional->nome;?>">
                    <?php endif;?>
                </div>
                <div class="texto">
                    <div class="titulo"><?php echo $profissional->nome;?></div>
                    <div class="txt1"><?php echo $profissional->txt1;?></div>
                    <div class="txt2"><?php echo $profissional->txt2;?></div>

                    <div class="txtcurriculo" onclick="abrirPopup_id('popupCurriculo<?php echo $profissional->id; ?>');">+currículo</div>
                </div>
            </div>

            <div class="popupFundo1" id="popupCurriculo<?php echo $profissional->id; ?>Fundo" onclick="fecharPopup_id('popupCurriculo<?php echo $profissional->id; ?>');"></div>
                <div class="popupSistema1" id="popupCurriculo<?php echo $profissional->id; ?>">
                    <div class="popupInformacoes">
                        <div class="tituloCurriculo"><?php echo $profissional->nome;?></div>
                        <div class="descricaoCurriculo">
                            <?php echo $profissional->descricao;?>
                        </div>
                    </div>
            </div>

            <?php endforeach;?>
        </div>
    </div>
</div>
<style>
    .popupSistema1 {
        width: 620px;
        /* top: 35%; */
        
    }
    .popupInformacoes{
        border: 10px solid #7296ac;
    }

    .tituloCurriculo {
        color: #7095ab;
        font-size: 23px;
        font-weight: bold;
        font-family: 'AL Nevrada Personal Use Only Regular';
        text-align: center;
    }
    .descricaoCurriculo {
        font-size: 16px;
    }
    .descricaoCurriculo p {
        margin: 5px 0;
    }
</style>
<style>
.blocos-corpo-clinico {
    background: url(/arquivos/imagens/bg-essence-img.jpg) no-repeat center center/100% 100%;
    padding: 50px 0;
    /* margin-top: 5px; */
}

.blocos-corpo-clinico .tituloPadrao {
    color: #fff;
    margin-bottom: 35px;
}
.lista-corpo-clinico {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.lista-corpo-clinico .bloco {
    width: 250px;
    margin: 5px 5px;
    margin-bottom: 20px;
}

.lista-corpo-clinico .bloco .img img {
    /* height: 250px;
    width: 250px;
    background: #fff; */
    width: 100%;
}

.lista-corpo-clinico .bloco .texto {
    background: #7095ab;
    color: #fff;
    padding: 5px 15px;
    position: relative;
    height: 125px;
    overflow: hidden;
}

.lista-corpo-clinico .bloco .texto .titulo {
    font-size: 18px;
    font-family: 'AL Nevrada Personal Use Only Regular';
}

.lista-corpo-clinico .bloco .texto  .txt1 {
    font-size: 15px;
}

.lista-corpo-clinico .bloco .texto  .txt2 {
    font-size: 15px;
}

.lista-corpo-clinico .bloco .texto .txtcurriculo {
    color: #fff;
    font-size: 13px;
    text-decoration: underline;
    position: absolute;
    bottom: 9px;
    right: 10px;
    cursor: pointer;
}

.bloco-busca-clinica {
    margin-bottom: 35px;
}

.blocos-corpo-clinico .bloco-busca-clinica .input_e_btn {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.blocos-corpo-clinico .bloco-busca-clinica .inputbox.bol_inf_txt1,
.blocos-corpo-clinico .bloco-busca-clinica .inputbox.bol_inf_txt2 {
    /* max-width: 325px; */
    color: #7095ab;
}

.blocos-corpo-clinico .bloco-busca-clinica .btn {
    padding: 10px 53px;
    font-size: 19px;
}

.blocos-corpo-clinico .bloco-busca-clinica .inputbox::placeholder{
	color: #7095ab !important;
}

.blocos-corpo-clinico .bloco-busca-clinica .inputbox.bol_inf_txt1 {
    /* margin-right: 20px; */
}

.blocos-corpo-clinico .campo_bol_inf em {
    position: absolute;
    top: 0;
    right: 0;
    color: #7095ab;
    font-size: 16px;
    z-index: 2;
    padding: 13px 10px;
}

.blocos-corpo-clinico .campo_bol_inf {
    margin: 0 10px;
    position: relative;
    width: 300px;
}


@media(max-width:767px){
    .blocos-corpo-clinico .campo_bol_inf {
        width: 100%;
        margin: 5px 0;
    }

    .lista-corpo-clinico .bloco {
        width: 100%;
    }

    .popupSistema1 {
        width: 85%;
    }
}
</style>