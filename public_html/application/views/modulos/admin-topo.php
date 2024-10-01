<?php
	$controller->load->model('admin/Admin_model_admin','model_admin');
	$dados_admin = $controller->model_admin->buscar_conta();
?>
<div class="topo-principal">
	<div class="container flex">
		<a class="logomarca" href="#">
			<div class="txt-topo-admin">Administração</div>
			<img src="/arquivos/imagens/logo.png" alt="Essence logo" title="Essence logo">
		</a>
		<ul class="menu" id="menu1">

			<?php if($controller->model->verificar_permissao_admin('clientes')): ?>
			<li class="item">
				<a class="link" href="/admin/clientes.html">Clientes</a>
			</li>
			<?php endif; ?> 

			<?php if(
				$controller->model->verificar_permissao_admin('institucional_produtos') 
				|| $controller->model->verificar_permissao_admin('institucional_produtos_categorias')
				|| $controller->model->verificar_permissao_admin('institucional_solicitacoes')
				|| $controller->model->verificar_permissao_admin('institucional_profissionais')
				|| $controller->model->verificar_permissao_admin('institucional_depoimentos') 
				|| $controller->model->verificar_permissao_admin('institucional_bignumbers') 
			): ?>
			<li class="item">
				<a class="link" href="#">Institucional</a>
				<ul class="submenu">
					<?php if($controller->model->verificar_permissao_admin('institucional_produtos')): ?>
					<li class="item">
						<a class="link" href="/admin/institucional/produtos.html">Produtos/Serviços</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('institucional_produtos_categorias')): ?>
					<li class="item">
						<a class="link" href="/admin/institucional/categorias.html">Categorias</a>
					</li>
					<?php endif; ?>
					
					<?php if($controller->model->verificar_permissao_admin('institucional_solicitacoes')): ?>
					<li class="item">
						<a class="link" href="/admin/institucional/solicitacoes.html">Solicitações</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('institucional_profissionais')): ?>
					<li class="item">
						<a class="link" href="/admin/institucional/profissionais.html">Profissionais</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('institucional_depoimentos')): ?>
					<li class="item">
						<a class="link" href="/admin/institucional/depoimentos.html">Depoimentos</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('institucional_bignumbers')): ?>
					<li class="item">
						<a class="link" href="/admin/institucional/bignumbers.html">Big numbers</a>
					</li>
					<?php endif; ?>

				</ul>
			</li>
			<?php endif; ?>

			<?php if($controller->model->verificar_permissao_admin('banners')): ?>
			<li class="item">
				<a class="link" href="/admin/banners.html">Banners</a>
			</li>
			<?php endif; ?>

			<?php if(
				$controller->model->verificar_permissao_admin('copywriters') 
				|| $controller->model->verificar_permissao_admin('copywriter_configuracao')
			): ?>
			<li class="item">
				<a class="link" href="#">Copywriters</a>
				<ul class="submenu">
					<?php if($controller->model->verificar_permissao_admin('copywriters')): ?>
					<li class="item">
						<a class="link" href="/admin/copywriters.html">Blocos</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('copywriter_configuracao')): ?>
					<li class="item">
						<a class="link" href="/admin/copywriter/configuracao.html">Configuração</a>
					</li>
					<?php endif; ?> 
				</ul>
			</li>
			<?php endif; ?>

			<?php if($controller->model->verificar_permissao_admin('seo')): ?>
			<li class="item">
				<a class="link" href="/admin/seo/links.html">SEO</a>
				<ul class="submenu">
					<li class="item">
						<a class="link" href="/admin/seo/links.html">Links</a>
					</li>
					<li class="item">
						<a class="link" href="/admin/seo/mapa.html">Mapa</a>
					</li>
					<li class="item">
						<a class="link" href="/admin/seo/configuracao.html">Configurações</a>
					</li>
				</ul>
			</li>
			<?php endif; ?>

			<?php if(
				$controller->model->verificar_permissao_admin('landingpage_estado_cidade')
				|| $controller->model->verificar_permissao_admin('landingpage_config_lp') 
				|| $controller->model->verificar_permissao_admin('landingpage_solicitacoes') 
				|| $controller->model->verificar_permissao_admin('landingpage_copywriters') 
			): ?>
			<li class="item">
				<a class="link" href="#">Landing Page</a>
				<ul class="submenu">
					
					<?php if($controller->model->verificar_permissao_admin('landingpage_estado_cidade')): ?>
					<li class="item">
						<a class="link" href="/admin/landing-page/estados-cidades.html">Estado/Cidade</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('landingpage_config_lp')): ?>
					<li class="item">
						<a class="link" href="/admin/landing-page/configuracoes-lp.html">Produtos/Servicos</a>
					</li>
					<?php endif; ?> 

					<?php if($controller->model->verificar_permissao_admin('landingpage_copywriters')): ?>
					<li class="item">
						<a class="link" href="/admin/landing-page/copywriters.html">Copywriters</a>
					</li>
					<?php endif; ?> 

					<?php if($controller->model->verificar_permissao_admin('landingpage_solicitacoes')): ?>
					<li class="item">
						<a class="link" href="/admin/landing-page/solicitacoes.html">Solicitações</a>
					</li>
					<?php endif; ?> 

				</ul>
			</li>
			<?php endif; ?>

			<?php if($controller->model->verificar_permissao_admin('financeiro')
				|| $controller->model->verificar_permissao_admin('financeiro_configuracoes')
				|| $controller->model->verificar_permissao_admin('financeiro_cupons')
				|| $controller->model->verificar_permissao_admin('financeiro_assinaturas')
			): ?>
			<li class="item">
				<a class="link" href="#">Loja Virtual</a>
				<ul class="submenu">
					<?php if($controller->model->verificar_permissao_admin('financeiro')): ?>
					<li class="item">
						<a class="link" href="/admin/financeiro.html">Financeiro</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('financeiro_assinaturas')): ?>
					<li class="item">
						<a class="link" href="/admin/financeiro/assinaturas.html">Assinaturas</a>
					</li> 
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('financeiro')): ?>
					<li class="item">
						<a class="link" href="/admin/financeiro/carrinhos-abandonados.html">Carrinhos abandonados</a>
					</li> 
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('financeiro_cupons')): ?>
					<li class="item">
						<a class="link" href="/admin/financeiro/cupons.html">Cupons de Desconto</a>
					</li> 
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('financeiro_configuracoes')): ?>
					<li class="item">
						<a class="link" href="/admin/financeiro/configuracao.html">Configurações</a>
					</li> 
					<?php endif; ?>

				</ul>
			</li>
			<?php endif; ?>

			<?php if(
				$controller->model->verificar_permissao_admin('blog')
				|| $controller->model->verificar_permissao_admin('blog_categorias') 
				|| $controller->model->verificar_permissao_admin('blog_configuracao') 
			): ?>
			<li class="item">
				<a class="link" href="#">Blog</a>
				<ul class="submenu">
					
					<?php if($controller->model->verificar_permissao_admin('blog')): ?>
					<li class="item">
						<a class="link" href="/admin/blog-artigos.html">Artigos</a>
					</li>
					<?php endif; ?> 

					<?php if($controller->model->verificar_permissao_admin('blog_categorias')): ?>
					<li class="item">
						<a class="link" href="/admin/blog/categorias.html">Categorias</a>
					</li>
					<?php endif; ?> 

					<?php if($controller->model->verificar_permissao_admin('blog_configuracao')): ?>
					<li class="item">
						<a class="link" href="/admin/blog/configuracao.html">Configuração</a>
					</li>
					<?php endif; ?> 

				</ul>
			</li>
			<?php endif; ?>  
			
			<?php if($controller->model->verificar_permissao_admin('galerias')): ?>
			<li class="item">
				<a class="link" href="/admin/galeria/galerias.html">Galerias</a>
			</li>
			<?php endif; ?>  

			<?php if(
				$controller->model->verificar_permissao_admin('contato_mensagens') 
				|| $controller->model->verificar_permissao_admin('contato_trabalhe')
				|| $controller->model->verificar_permissao_admin('contato_boletim')
				|| $controller->model->verificar_permissao_admin('contato_config')
				|| $controller->model->verificar_permissao_admin('contato_config_emails')
			): ?>
			<li class="item">
				<a class="link" href="#">Contato</a>
				<ul class="submenu">

					<?php if($controller->model->verificar_permissao_admin('contato_mensagens')): ?>
					<li class="item">
						<a class="link" href="/admin/contato/mensagens.html">Mensagens</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('contato_trabalhe')): ?>
					<li class="item">
						<a class="link" href="/admin/contato/trabalhe-mensagens.html">Trabalhe Conosco</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('contato_boletim')): ?>
					<li class="item">
						<a class="link" href="/admin/contato/boletim-mensagens.html">Boletim informativo</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('contato_config')): ?>
					<li class="item">
						<a class="link" href="/admin/contato/configuracoes.html">Configurações de contato</a>
					</li>
					<?php endif; ?>
					
					<?php if($controller->model->verificar_permissao_admin('contato_config_emails')): ?>
					<li class="item">
						<a class="link" href="/admin/contato/configuracoes-email.html">Configurações de emails</a>
					</li>
					<?php endif; ?>

				</ul>
			</li>
			<?php endif; ?>

			<li class="item">
				<a class="link" href="#"><em class="icon-cog"></em></a>
				<ul class="submenu">
					
					<?php if($controller->model->verificar_permissao_admin('usuarios')): ?>
					<li class="item">
						<a class="link" href="/admin/usuarios.html">Usuários</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('configuracao')): ?>
					<li class="item">
						<a class="link" href="/admin/configuracao.html">Configuração</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('lgpd')): ?>
					<li class="item">
						<a class="link" href="/admin/lgpd.html">LGPD</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('modulos')): ?>
					<li class="item">
						<a class="link" href="/admin/modulos-personalizado.html">Módulos</a>
					</li>
					<?php endif; ?> 

					<?php if($controller->model->verificar_permissao_admin('faqs')): ?>
					<li class="item">
						<a class="link" href="/admin/faqs.html">FAQs</a>
					</li>
					<?php endif; ?>

					<?php if($controller->model->verificar_permissao_admin('logs')): ?>
					<li class="item">
						<a class="link" href="/admin/logs.html">Logs</a>
					</li>
					<?php endif; ?>

					<li class="item">
						<a class="link" href="/admin/conta.html">Minha Conta</a>
					</li>
					<li class="item">
						<a class="link" href="/admin/sair.html">Sair</a>
					</li>
				</ul>
			</li>

		</ul>

		
		<span class="txt-topo-usuario">
		<?php 

			if($dados_admin->nome){
				$nome_usu_sep = explode(' ',$dados_admin->nome);
				echo $nome_usu_sep[0];
			}
		?>
		</span>
	</div>
</div>