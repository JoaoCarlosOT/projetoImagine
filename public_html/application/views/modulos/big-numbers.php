
<?php 

$controller->load->model('modulos/modulos_model_institucional', 'model_institucional');
$bignumber = $controller->model_institucional->buscar_bignumber(1);
if(!$bignumber) return;

if($bignumber["bigNumber"]){
    $bignumber["bigNumber"] = json_decode($bignumber['bigNumber'], true);	
}else{
    $bignumber["bigNumber"] = array();
}

?>
<div class="blocos-bigNumbers1">
    <div class="box_1"> 
        <h3 class="title_1"><?=$bignumber["nome"]?></h3>

        <?php if($bignumber["bigNumber"]): ?>
            <div class="caixas_1" id="bigNumber-lista">
                <?php foreach($bignumber["bigNumber"] as $key => $bigNumber):?>
                    <div class="caixa_1">
                        <?php if($bigNumber['classFontello']):?>
                            <em class="<?=$bigNumber['classFontello']?>" id="icon"></em>
                        <?php endif;?>
                            
                        <div class="txt2"><?=$bigNumber['prefixo']?><span class="numero" inicio="0" fim="<?=preg_replace('/[^0-9]/', '', $bigNumber['numero'])?>"><?=$bigNumber['numero']?></span><?=$bigNumber['sufixo']?></div>

                        <?php if($bigNumber['texto']):?>
                            <div class="text"><?=$bigNumber['texto']?></div>
                        <?php else:?>
                            <div class="text">&nbsp;</div>    
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
    .blocos-bigNumbers1{
        background-color:  #ffffff;
        color: black;
        padding: 60px 0;
    }
    #icon{
        color: #d5393f;
        font-size: 40px;
        margin: 15px;
        margin-left: 27px;
    }
    .box_1{
        width: 50%;
        max-width: 1000px;
        margin-right: auto;
        margin-left: auto;
        font-size: 60px;
    }
    .title_1{
        line-height: 50px;
        font-weight: 600;
        color: black;
        font-size: 60%;
    }
    .caixas_1{
        display: flex;
        flex-wrap: wrap;
        gap: 17px;
        justify-content: center;
        margin-top: 40px;
    }
    .caixa_1{
        margin-top: 60px;
        width: 220px;
        text-align: left;
        color: black;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: flex-start;
        /* height: 200px; */
        border-radius: 30px;
    }
    .caixa_1:nth-child(1){
        background-color: #fff;
        border: 2px solid black;
    }
    .caixa_1:nth-child(2){
        background-color: #f1f5ff;
        border: 2px solid #f1f5ff;
    }
    .caixa_1:nth-child(3){
        background-color: #fff;
        border: 2px solid black;
    }
    .caixa_1:nth-child(4){
        background-color: #f1f5ff;
        border: 2px solid #f1f5ff;
    }
    .text{
        color: black;
        font-weight: 500;
        margin: 15px;
        width: 100px;
        font-size: 18px;
        line-height: normal;
        margin-left: 27px;
    }

    @media screen and(max-width:1880px){
    .title_1{
       margin: auto;
    }
    }
</style>