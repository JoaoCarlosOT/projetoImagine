
<?php 

$controller->load->model('modulos/modulos_model_institucional', 'model_institucional');
$bignumber = $controller->model_institucional->buscar_bignumber(3);
if(!$bignumber) return;

if($bignumber["bigNumber"]){
    $bignumber["bigNumber"] = json_decode($bignumber['bigNumber'], true);	
}else{
    $bignumber["bigNumber"] = array();
}

?>
<div class="blocos-bigNumbers">
    <div class="container"> 
        <h3 class="tituloPadrao textLeft titulo"><?=$bignumber["nome"]?></h3>

        <?php if($bignumber["bigNumber"]): ?>
            <div class="blocos" id="bigNumber-lista">
                <?php foreach($bignumber["bigNumber"] as $key => $bigNumber):?>
                    <div class="bloco">
                        <?php if($bigNumber['classFontello']):?><em class="<?=$bigNumber['classFontello']?>"></em><?php endif;?>
                            
                            <div class="txt2"><//?=$bigNumber['prefixo']?><span class="numero" inicio="0" fim="<?=preg_replace('/[^0-9]/', '', $bigNumber['numero'])?>"><?=$bigNumber['numero']?></span><?=$bigNumber['sufixo']?></div>

                        <?php if($bigNumber['texto']):?>
                            <div class="textoPadrao textoAbaixo"><?=$bigNumber['texto']?></div>
                        <?php else:?>
                            <div class="textoPadrao textoAbaixo opacity">&nbsp;</div>    
                        <?php endif;?>
                    </div>
                <?php endforeach;?>
            </div>


            <script type="text/javascript"> 

                var ctdr_bn = false;
                jQuery(window).scroll(function(){
                    if(!ctdr_bn){
                        var p_bn = jQuery('#bigNumber-lista').offset().top;
                        var s_bn = jQuery(window).scrollTop();
                        var h_bn = jQuery(window).height();

                        if(s_bn >= (p_bn - h_bn + 15)){
                            ctdr_bn = true;
                            jQuery('#bigNumber-lista .bloco .numero').each(function(){
                                var start_bn = jQuery(this).attr('inicio');
                                var end_bn = jQuery(this).attr('fim');
                                jQuery(this).animate( {count:end_bn} , {
                                    duration: 2000,
                                    easing: 'swing',
                                    step: function (a,b) {
                                        jQuery(this).text(number_format(Math.ceil(a), 0, ',', '.'));
                                    }
                                });
                            });
                        }
                    }				
                });

            </script>
        <?php endif;?>
    </div>
</div>

<style>
    /* .blocos-bigNumbers3{
        display: flex;
        align-items: center;
        justify-content: space-around;
        background-color: #fff;
    } */
</style>