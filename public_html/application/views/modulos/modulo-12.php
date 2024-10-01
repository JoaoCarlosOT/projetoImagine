<?php
$controller->load->model('modulos/modulos_model_modulo', 'model_modulo');
$modulo_12 = $controller->model_modulo->buscar_modulo(12);

if (!$modulo_12) return;
?>

<div class="modulo12">
    <div class="container">
        <div class="tituloPadrao textCenter titulo"><?= $modulo_12['nome'] ?></div>
        <div class="blocos">
            <div class="bloco lista textoPadrao"><?= $modulo_12['descricao'] ?></div>
        </div>
    </div>
</div>