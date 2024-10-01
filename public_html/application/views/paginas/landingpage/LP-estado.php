<?php 
    $estado = $this->dados["dados"]["resultado"];
    $alias_pre = $this->dados["dados"]["alias_pre"];
?>
<div class="container">
<?php if($estado):?>
    <div class="estado">
        <h1 class="tituloPadrao"><?=$estado->nome?></h1>
        <p class="subTituloPadrao">Veja algumas cidades:</p>
        <?php if($estado->cidades):?>
            <div class="cidades">
            <?php foreach($estado->cidades as $cidade):?>

                <div class="cidade">
                    <a href="/<?=$alias_pre.'-'.$cidade->alias?>.html" title="<?=$cidade->nome?>"><?=$cidade->nome?></a>
                </div>

            <?php endforeach;?>
            </div>
        <?php endif;?>

    </div>
<?php endif;?>
</div>