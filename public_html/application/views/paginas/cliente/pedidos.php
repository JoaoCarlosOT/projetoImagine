<?php
    $dados =$this->dados["dados"]["resultado"];

    $logo = '/arquivos/imagens/logo.png';
    $this->load->library('Imgno_imagem', '', 'imagineImagem');

    $dir_p = '/arquivos/imagens/produto/';
?>
<div class="bloco-op-conta">
    <div class="container">
        <div class="blocos-situacao">
            <div class="item pago">
                <span class="cont">0</span>
                <span class="tit">Pagemento confirmado</span>
            </div>
            <div class="item aguardando">
                <span class="cont">0</span>
                <span class="tit">Aguardando pagamento</span>
            </div>
            <div class="item outros">
                <span class="cont">0</span>
                <span class="tit">Outros</span>
            </div>
        </div>
        <?php if($dados):?>
        <div class="blocos-listagem">
            <?php foreach($dados as $dado):?>

                <?php if($dado->produtos):?>
                    <?php foreach($dado->produtos as $produto):
                            if($produto->imagem){
                                $img_p = $controller->imagineImagem->otimizar($dir_p.$produto->imagem, 315, 260, false, true, 80);
                            }else{
                                // logo
                                $img_p = $controller->imagineImagem->otimizar($logo, 315, 260, false, false, 80);
                            }

                            // if($dado->status_asaas == 'RECEIVED' || $dado->status_asaas == 'RECEIVED_IN_CASH' || $dado->status_asaas == 'CONFIRMED'){
                            if($dado->data_pagamento && $dado->data_pagamento != '0000-00-00'){
                                $status = 'Pagemento confirmado';
                                $class = "pago";
                            // }else if($dado->status_asaas == 'PENDING'){
                            }else if($dado->status_asaas == 'PENDING' && (!$dado->data_pagamento || $dado->data_pagamento == '0000-00-00')){
                                $status = 'Aguardando pagamento';
                                $class = "aguardando";
                            }else{
                                $status = 'Outros';
                                $class = "outros";
                            } 
                        ?>
                        <a class="item" href="/servicos/<?=$produto->alias?>.html">
                            <div class="img">
                                <img src="<?=$img_p?>" alt="">
                            </div>
                            <div class="infor">
                                <div class="status <?=$class?>"><?=$status?></div>
                                <div class="nome"><?=$produto->nome?></div>
                                <div class="valor"><?=$this->model->moeda($produto->valor, true); ?></div>
                                <div class="qtd"><?=$produto->quantidade?> unidade</div>
                                <div class="forma_pag">
                                    <?php
                                        if($dado->forma_pagamento == 0){
                                            echo 'Grátis';
                                        }else if($dado->forma_pagamento == 1){
                                            echo 'Pix';
                                        }else if($dado->forma_pagamento == 2){
                                            echo 'Boleto';
                                        }else if($dado->forma_pagamento == 3){
                                            echo 'Cartão de Crédito';
                                        }
                                    ?>
                                </div>
                                <div class="data">Realizada em <?=date('d/m/Y H:i', strtotime($dado->cadastrado))?></div>
                                
                            </div>
                        </a>
                    <?php endforeach;?>
                <?php endif;?>

            <?php endforeach;?>
        </div>
        <?php else:?>
        <div class="blocos-listagem" style="justify-content: center;text-align: center;">
            <p>Nenhum compra realizada</p>
        </div>
        <?php endif;?>
    </div>
</div>