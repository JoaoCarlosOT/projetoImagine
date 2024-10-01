<?php
    $dados = $this->dados["dados"]["resultado"];

    $logo = '/arquivos/imagens/logo.png';
    $this->load->library('Imgno_imagem', '', 'imagineImagem');

    $dir_p = '/arquivos/imagens/produto/';

?>
<div class="bloco-op-conta">
    <div class="container">
        <div class="blocos-situacao colunas-2">
            <div class="item pago">
                <span class="cont">0</span>
                <span class="tit">Ativado</span>
            </div> 
            <div class="item desativado">
                <span class="cont">0</span>
                <span class="tit">Desativado</span>
            </div>
        </div>
        <?php if($dados):?>
        <div class="blocos-listagem">
            <?php foreach($dados as $dado):
                    if($dado->imagem){
                        $img_p = $controller->imagineImagem->otimizar($dir_p.$dado->imagem, 315, 260, false, true, 80);
                    }else{
                        // logo
                        $img_p = $controller->imagineImagem->otimizar($logo, 315, 260, false, false, 80);
                    }

                    if($dado->status_asaas == 'ACTIVE'){
                        $status = 'Ativado';
                        $class = "pago";
                    }else{
                        $status = 'Desativado';
                        $class = "desativado";
                    } 
                ?> 
                    <a class="item" href="/servicos/<?=$dado->alias?>.html">
                        <div class="img">
                            <img src="<?=$img_p?>" alt="">
                        </div>
                        <div class="infor">
                            <div class="status <?=$class?>"><?=$controller->asaas->getStatusMappingAssinatura($dado->status_asaas)?></div>
                            <div class="nome"><?=$dado->nome?></div>
                            <div class="valor"><?=$this->model->moeda($dado->valor_total, true); ?></div>
                            <div class="qtd"><?=$dado->quantidade?> unidade</div>
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
                            <div class="data">Realizada em <?=date('d/m/Y H:i', strtotime($dado->cadastro))?></div>
                            
                        </div>
                    </a>

            <?php endforeach;?>
        </div>
        <?php else:?>
        <div class="blocos-listagem" style="justify-content: center;text-align: center;">
            <p>Nenhum assinatura realizada</p>
        </div>
        <?php endif;?>
    </div>
</div>