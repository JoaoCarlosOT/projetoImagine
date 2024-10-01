<?php
    $dados =$this->dados["dados"]["resultado"];
?>
<div class="bloco-op-conta">
    <div class="container">
        <div class="blocoIconesInicio">
            <div class="tituloPadrao textCenter">Bem-vindo(a) ao seu ambiente exclusivo Essence</div>

            <div class="blocos">

                <div class="blocoslistas">
                    <a class="bloco-lista" href="/minha-conta/conta.html">Informações cadastrais</a>
                    <a class="bloco-lista" href="/minha-conta/agendamentos.html" >Agendamentos</a>
                    <a class="bloco-lista ativo" href="/minha-conta/informativos.html">Informativos Essence</a>
                    <a class="bloco-lista" href="/sair.html" >Sair</a>
                </div>


                <div class="blocoslistasFormularios">

                    <div class="bloco-formulario">
                        <a href="/minha-conta.html" class="btn-voltar-mobile">Voltar</a>
                        <div class='bloco-titulo-form'>Informativos Essence</div>
                        <div class="blocoInformativos">
                                <?php if($dados):?>
                                    <?php foreach($dados as $dado):?>
                                        <a class="blocoInformativo" href="/minha-conta/informativo/<?php echo $dado->id;?>.html" title="<?php echo $dado->nome;?>">
                                            <div class="txt1-info"><?php echo date('d/m',strtotime($dado->cadastro));?></div>
                                            <div class="txt2-info"><?php echo $dado->nome;?></div>
                                        </a>
                                    <?php endforeach;?>
                                <?php endif;?>
                        </div>
                       
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
