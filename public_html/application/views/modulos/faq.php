<?php
$controller->load->model('modulos/modulos_model_faq', 'model_faq');
$faqs = $controller->model_faq->buscar_faqs();
if(!$faqs) return;

?>

<div class="faqs">
    <img src="/arquivos/imagens/celular.png" alt="" class="img_faq">
    <div class="container">
        <h3 class="tituloPadrao">Dúvidas <br><span class="title_faq">frequentes</span></h3>
        <div class="blocos">
            <?php foreach($faqs as $faq):?>
                <div class="bloco">
                    <div class="pergunta tituloPadrao4"><?=$faq->pergunta?><em class="icon-plus"></em></div>
                    <div class="resposta textoPadrao"><?=$faq->resposta?></div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>


<script>
    $(".faqs .blocos .bloco").on('click', function(){
        // $('.faqs .blocos .bloco .resposta').slideUp();
        // $(this).find('.resposta').slideDown();
        $(this).toggleClass('ativado');
        $(this).find('.resposta').slideToggle();
    })
</script>

<style>
    .faqs{
        padding-top: 60px;
        padding-bottom: 60px;
        background-color: #01032a;
        color: #fff;
        display: flex;
        justify-content: end;
        gap: 25px;
        align-items: center;
        flex-wrap: wrap;
        flex-direction: row;
        
    }
    .container {
	width: 50%;
	max-width: 1000px;
	margin-right: auto;
	margin-left: auto;
}
    .img_faq{
        width:400px;
        margin: auto;
    }

    .title{
        color: #fff;
        padding: 15px;
    }
    .response{
        color: #fff;
        margin: 10px;
        padding: 15px;
    }
    .tituloPadrao{
        color: #fff;
    }
    .pergunta{
        color: #fff;
    }

    /* .pergunta:nth-child(1) {
        color: blue;
    }

    .pergunta:nth-child(2) {
        color: white;
    }

    .pergunta:nth-child(3) {
        color: blue;
    }

    .pergunta:nth-child(4) {
        color: white;
    } */

</style>
