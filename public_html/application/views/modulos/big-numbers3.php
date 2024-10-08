
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
<div class="blocos-bigNumbers3">
    <div class="box"> 
        <h3 class="title"><?=$bignumber["nome"]?></h3>

        <?php if($bignumber["bigNumber"]): ?>
            <div class="caixas" id="bigNumber-lista">
                <?php foreach($bignumber["bigNumber"] as $key => $bigNumber):?>
                    <div class="caixa">
                        <?php if($bigNumber['classFontello']):?><em id="icon-bigNumber" class="<?=$bigNumber['classFontello']?>"></em><?php endif;?>
                            
                            <div class="txt2">
                                <//?=$bigNumber['prefixo']?>
                                    <span class="numero">
                                        <?=$bigNumber['numero']?>
                                    </span> 
                                <?=$bigNumber['sufixo']?>
                            </div>

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
    .blocos-bigNumbers3{
        display: flex;
        align-items: center;
        justify-content:center;
        flex-direction:column;
        background-color: #fff;
        flex-wrap: wrap;
        width: 100%;
        padding-top:15px ;
        padding-bottom: 15px;
    }
    .box{
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: space-between;
        width: 50%;
    }
    #icon-bigNumber{
        font-size: 30px;
    }
    .title{
        color: black;
        font-size: 40px;
        font-weight: 1;
        width: 400px;
        line-height: 50px;
        margin: auto;
        font-weight: 400;
    }
    .caixas{
        display: flex;
        align-items: center;
        flex-wrap: wrap;  
        gap: 25px;
        margin: auto;  
    }
    .caixa{
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        width: 146px;
        height: 130px;
        border-radius: 20px;
        margin: auto;
        padding: 26px;
        text-align: center;
    }
    .caixa:nth-child(1){
        background-color: #01032a;
        color: #fff;
    }
    .caixa:nth-child(2){
        background-color: #fff;
        color: #26a6d7;
        border: 2px solid #26a6d7;
    }
    .caixa:nth-child(3){
        background-color:#26a6d7;
        color: #fff;
    }
    .txt2{
        font-size: 20px;
        font-weight: 700;
    }
</style>