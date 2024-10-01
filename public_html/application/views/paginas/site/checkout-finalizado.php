<?php
    $controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
    $modulo_11 = $controller->model_modulo->buscar_modulo(11);    
?>
<style>
@keyframes rotateAndScale {
    0% {
        transform: rotate(0deg) scale(0);
        opacity: 0;
    }
    100% {
        transform: rotate(360deg) scale(1);
        opacity: 1;
    }
}
.verificado-blocos{
    margin: 60px 0;
}
.verificado-blocos .img{
    text-align: center;
}
.verificado-blocos .imagem-verificado{
    animation: rotateAndScale 2s ease;
}
.verificado-blocos .textoAgradecimento{
    text-align: center;
}
</style>

<div class="container">
    <div class="verificado-blocos">
        <div class="img">
            <img src="/arquivos/imagens/verificado.png" alt="verificado" title="verificado" class="imagem-verificado">
        </div>
        <div class="textoAgradecimento tituloPadrao">
            <?=$modulo_11['descricao']?>
        </div>
    </div>
</div>