<?php
    $controller->load->model('cliente/Cliente_model_cliente', 'model_cliente');
    $conta = $controller->model_cliente->buscar_conta(); 
    if(!$conta) return;
?> 
<div class="topo-principal-conta">
	<div class="container"> 
        <div class="topo-menu">
            <nav id="menus-lista">
                <ul class="menu">
                    <li><a href="/minha-conta/conta.html">Meus dados</a></li>
                    <li><a href="/minha-conta/pedidos.html">Meus pedidos</a></li>
                    <li><a href="/minha-conta/assinaturas.html">Minhas assinaturas</a></li>
                    <li><a href="/sair.html">Sair</a></li>
                </ul>
            </nav>

            <div class="usuario">
                Olá, <span><?=$conta->nome?> <em class="icon-user-circle-o"></em></span>
            </div>
        </div>
	</div>
</div>


<script>
    $(document).ready(function() {
        var currentPage = window.location.pathname;
        console.log(currentPage)
        $('.topo-principal-conta #menus-lista li a[href="' + currentPage + '"]').parent().addClass('active'); 
    });
</script>