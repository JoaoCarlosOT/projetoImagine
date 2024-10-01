<?php
    $dado =$this->dados["dados"]["resultado"];
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
                        <div class='bloco-titulo-form'>Informativos Essence</div>
                        <div class="blocotextoInformativos">
                            <div class="titulo"><?php echo $dado->nome;?></div>
                            <div class="desc"><?php echo $dado->descricao;?></div>
                        </div>
                        <a href="/minha-conta/informativos.html" class="btn-voltar">Voltar</a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
