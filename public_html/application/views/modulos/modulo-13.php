<?php
    $controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
    $modulo_13 = $controller->model_modulo->buscar_modulo(13);

    if(!$modulo_13) return;
?>

<div class="modulo13">
    <div class="container">
        <div class="blocos">
            <div class="bloco imag">
                <img src="/arquivos/imagens/mulher-celular.png" alt="imagem" title="imagem">
            </div>
            <div class="bloco lista headline2">
                <?=$modulo_13['descricao']?>
                <a href="#" class="btn2 botao">Saber mais</a>
            </div>
        </div>
    </div>
</div>