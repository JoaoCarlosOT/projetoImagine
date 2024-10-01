<?php
    $controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
    $modulo_14 = $controller->model_modulo->buscar_modulo(14);

    if(!$modulo_14) return;
?>

<div class="modulo14">
    <div class="container">
        <div class="blocos">
            <div class="bloco desc headline2"><?=$modulo_14['descricao']?></div>
            <div class="bloco imag">
                <img src="/arquivos/imagens/celular.png" alt="imagem" title="imagem">
            </div>
            <div class="bloco lista headline2">
                <ul>
                    <li>Praticidade</li>
                    <li>Interação</li>
                    <li>Produtividade</li>
                    <li>Crescimento</li>
                    <li>Experiência</li>
                    <li>Personalização</li>
                </ul>
            </div>
        </div>
    </div>
</div>