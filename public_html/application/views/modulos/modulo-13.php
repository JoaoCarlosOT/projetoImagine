<?php
    $controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
    $modulo_13 = $controller->model_modulo->buscar_modulo(13);

    if(!$modulo_13) return;
?>

<div class="modulo_13">
    <div class="container">
        <!-- <div class="blocos"> -->
            <div class="bloco_text">
                <h1>Faz <span class="bold">muitos</span> envios por mês?</h1>
                <p>Conheça as vantagens de um <span>cliente correntista.</span></p>
                <a href="#" class="button">VAMOS CONVERSAR</a>
            </div>
            <div class="bloco_img">
                <img src="/arquivos/imagens/img_copwriter2_despachoRapido.png" alt="imagem" title="imagem" class="img_modulo3">
            </div>
            <div class="bloco_lista ">
                <!-- <span class="title"><//?= $modulo_12['nome'] ?></span> -->
                <?=$modulo_13['descricao']?>
            </div>
        <!-- </div> -->
    </div>
</div>
<!-- 
<style>
    .modulo_13{
        height: 500px;
        display: flex;
        background-color: #f1f5ff;
    }
    h1{
        font-size: 22px;
    }
    p{
        color: #26a5d7;
    }
    .bold{
        font-weight: 800;
    }
    .bloco_text{
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .container{
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-around;
    }
    .img_modulo3{
        width: 400px;
    }
    .title{
        color: #f1f5ff;
        background-color: #000;
        border-radius: 12px;
    }
    .button{
        color: #f1f5ff;
        background-color: #dd252d;
        border-radius: 10px;
    }
    .bloco_lista{
        color: #fff;
        background-color: #26a5d7;
        border-radius: 10px;
        width: 150px;
        height: 200px;
    }
</style> -->