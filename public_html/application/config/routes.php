<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH .'/config/database.php');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Site_sys_paginas';
$route['404_override'] = 'erro';
// $route['translate_uri_dashes'] = FALSE;


$route["posts-instagram(?:\.html)?"] = "site/Site_controller_paginas/posts_instagram_imagens/$1";
$route["posts-facebook(?:\.html)?"] = "site/Site_controller_paginas/posts_facebook_imagens/$1";
// Paginas

// $route["erro(?:\.html)?"] = "site/Site_controller_paginas/erro";

// $route["inicio(?:\.html)?"] = "site/Site_controller_paginas/inicio";
$route["/"] = "site/Site_controller_paginas/inicio";
$route["quem-somos(?:\.html)?"] = "site/Site_controller_paginas/quem_somos";

$route["servicos-categoria/([A-Za-z0-9_\-]+)(?:\.html)?"] = "site/Site_controller_paginas/servicos/$1";
$route["servicos(?:\.html)?"] = "site/Site_controller_paginas/servicos";
$route["servicos/([A-Za-z0-9_\-]+)(?:\.html)?"] = "site/Site_controller_paginas/servico/$1";

$route["profissionais(?:\.html)?"] = "site/Site_controller_paginas/profissionais";
$route["blog(?:\.html)?"] = "site/Site_controller_paginas/blog";
$route["blog/([A-Za-z0-9_\-]+)(?:\.html)?"] = "site/Site_controller_paginas/blog_artigo/$1";
$route["contato(?:\.html)?"] = "site/Site_controller_paginas/contato";
$route["trabalhe-conosco(?:\.html)?"] = "site/Site_controller_paginas/trabalhe_conosco";
$route["faq(?:\.html)?"] = "site/Site_controller_paginas/faq";

$route["lgpd(?:\.html)?"] = "site/Site_controller_paginas/lgpd";

//checkout
$route["checkout-email(?:\.html)?"] = "site/Site_controller_checkout/email";
$route["checkout-recuperar(?:\.html)?"] = "site/Site_controller_checkout/recuperar_senha";
$route["checkout-senha/([A-Za-z0-9_\-]+)(?:\.html)?"] = "site/Site_controller_checkout/alterar_senha/$1";
$route["checkout-cadastrar(?:\.html)?"] = "site/Site_controller_checkout/cadastrar";
$route["checkout-atualizar(?:\.html)?"] = "site/Site_controller_checkout/atualizar";
$route["checkout(?:\.html)?"] = "site/Site_controller_checkout/checkout";
$route["checkout-finalizar(?:\.html)?"] = "site/Site_controller_checkout/checkout_finalizar";
$route["checkout-finalizar-assinatura(?:\.html)?"] = "site/Site_controller_checkout/checkout_finalizar_assinatura";
$route["checkout-finalizado(?:\.html)?"] = "site/Site_controller_checkout/checkout_finalizado";

// Clientes
$route["login(?:\.html)?"] = "cliente/Cliente_controller_cliente/login";
$route["sair(?:\.html)?"] = "cliente/Cliente_controller_cliente/sair";
$route["conta(?:\.html)?"] = "cliente/Cliente_controller_cliente/conta";
$route["minha-conta(?:\.html)?"] = "cliente/Cliente_controller_cliente/inicio";
$route["minha-conta/conta(?:\.html)?"] = "cliente/Cliente_controller_cliente/conta";
$route["minha-conta/agendamentos(?:\.html)?"] = "cliente/Cliente_controller_cliente/agendamentos";
$route["minha-conta/pedidos(?:\.html)?"] = "cliente/Cliente_controller_cliente/pedidos";
$route["minha-conta/assinaturas(?:\.html)?"] = "cliente/Cliente_controller_cliente/assinaturas";
$route["minha-conta/informativos(?:\.html)?"] = "cliente/Cliente_controller_cliente/informativos";
$route["minha-conta/informativo/([A-Za-z0-9_\-]+)(?:\.html)?"] = "cliente/Cliente_controller_cliente/informativo/$1";

// Admin 
$route["admin/usuarios(?:\.html)?"] = "admin/Admin_controller_admin/usuarios/$1";
$route["admin/usuarios/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_admin/usuarios/$1";
$route["admin/usuario/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_admin/usuario/$1";
$route["admin/usuario(?:\.html)?"] = "admin/Admin_controller_admin/usuario/$1";

$route["admin/configuracao(?:\.html)?"] = "admin/Admin_controller_configuracao/configuracao";

$route["admin/lgpd(?:\.html)?"] = "admin/Admin_controller_lgpd/lgpd";

$route["admin/financeiro(?:\.html)?"] = "admin/Admin_controller_financeiro/financeiro_dados";
$route["admin/financeiro/carrinhos-abandonados(?:\.html)?"] = "admin/Admin_controller_financeiro/carrinhos_abandonados";
$route["admin/financeiro/carrinho/([A-Za-z0-9_\-]+)(?:\.html)?"] = "admin/Admin_controller_financeiro/carrinho/$1";
$route["admin/financeiro/configuracao(?:\.html)?"] = "admin/Admin_controller_financeiro/configuracao";

$route["admin/financeiro/cupons(?:\.html)?"] = "admin/Admin_controller_financeiro/cupons/$1";
$route["admin/financeiro/cupons([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_financeiro/cupons/$1";
$route["admin/financeiro/cupom/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_financeiro/cupom/$1";
$route["admin/financeiro/cupom(?:\.html)?"] = "admin/Admin_controller_financeiro/cupom/$1";

$route["admin/financeiro/assinaturas(?:\.html)?"] = "admin/Admin_controller_financeiro/assinaturas/$1";
$route["admin/financeiro/assinaturas([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_financeiro/assinaturas/$1";
$route["admin/financeiro/assinatura/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_financeiro/assinatura/$1";
$route["admin/financeiro/assinatura-cancelar/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_financeiro/assinatura_cancelar/$1";
// $route["admin/financeiro/assinatura(?:\.html)?"] = "admin/Admin_controller_financeiro/assinatura/$1";

$route["admin/clientes(?:\.html)?"] = "admin/Admin_controller_cliente/clientes/$1";
$route["admin/clientes/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_cliente/clientes/$1";
$route["admin/cliente/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_cliente/cliente/$1";
$route["admin/cliente(?:\.html)?"] = "admin/Admin_controller_cliente/cliente/$1";

$route["admin/banners(?:\.html)?"] = "admin/Admin_controller_banner/banners/$1";
$route["admin/banners/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_banner/banners/$1";
$route["admin/banner/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_banner/banner/$1";
$route["admin/banner(?:\.html)?"] = "admin/Admin_controller_banner/banner/$1";

$route["admin/banner/([0-9]+)/slides(?:\.html)?"] = "admin/Admin_controller_banner/slides/$1/$2";
$route["admin/banner/([0-9]+)/slides/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_banner/slides/$1/$2";
$route["admin/banner/([0-9]+)/slide(?:\.html)?"] = "admin/Admin_controller_banner/slide/$1/$2";
$route["admin/banner/([0-9]+)/slide/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_banner/slide/$1/$2";

$route["admin/contato/mensagens(?:\.html)?"] = "admin/Admin_controller_contato/mensagens/$1";
$route["admin/contato/mensagens/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/mensagens/$1";
$route["admin/contato/mensagem/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/mensagem/$1";
$route["admin/contato/mensagem(?:\.html)?"] = "admin/Admin_controller_contato/mensagem/$1";

$route["admin/contato/trabalhe-mensagens(?:\.html)?"] = "admin/Admin_controller_contato/trabalhe_mensagens/$1";
$route["admin/contato/trabalhe-mensagens/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/trabalhe_mensagens/$1";
$route["admin/contato/trabalhe-mensagem/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/trabalhe_mensagem/$1";
$route["admin/contato/trabalhe-mensagem(?:\.html)?"] = "admin/Admin_controller_contato/trabalhe_mensagem/$1";

$route["admin/contato/vagas(?:\.html)?"] = "admin/Admin_controller_contato/vagas/$1";
$route["admin/contato/vagas/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/vagas/$1";
$route["admin/contato/vaga/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/vaga/$1";
$route["admin/contato/vaga(?:\.html)?"] = "admin/Admin_controller_contato/vaga/$1";

$route["admin/contato/boletim-mensagens(?:\.html)?"] = "admin/Admin_controller_contato/boletim_mensagens/$1";
$route["admin/contato/boletim-mensagens/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_contato/boletim_mensagens/$1";

$route["admin/contato/configuracoes(?:\.html)?"] = "admin/Admin_controller_contato/config";
$route["admin/contato/configuracoes-email(?:\.html)?"] = "admin/Admin_controller_contato/config_email";

$route["admin/institucional/produtos/excluir(?:\.html)?"] = "admin/Admin_controller_institucional/produto_excluir/$1";

$route["admin/institucional/produtos(?:\.html)?"] = "admin/Admin_controller_institucional/produtos/$1";
$route["admin/institucional/produtos([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/produtos/$1";
$route["admin/institucional/produto/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/produto/$1";
$route["admin/institucional/produto(?:\.html)?"] = "admin/Admin_controller_institucional/produto/$1";

$route["admin/institucional/profissionais(?:\.html)?"] = "admin/Admin_controller_institucional/profissionais/$1";
$route["admin/institucional/profissionais([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/profissionais/$1";
$route["admin/institucional/profissional/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/profissional/$1";
$route["admin/institucional/profissional(?:\.html)?"] = "admin/Admin_controller_institucional/profissional/$1";

$route["admin/institucional/categorias(?:\.html)?"] = "admin/Admin_controller_institucional/categorias/$1";
$route["admin/institucional/categorias([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/categorias/$1";
$route["admin/institucional/categoria/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/categoria/$1";
$route["admin/institucional/categoria(?:\.html)?"] = "admin/Admin_controller_institucional/categoria/$1";

$route["admin/institucional/solicitacoes(?:\.html)?"] = "admin/Admin_controller_institucional/solicitacoes/$1";
$route["admin/institucional/solicitacoes([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/solicitacoes/$1";
$route["admin/institucional/solicitacao/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/solicitacao/$1";

$route["admin/institucional/depoimentos(?:\.html)?"] = "admin/Admin_controller_institucional/depoimentos/$1";
$route["admin/institucional/depoimentos([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/depoimentos/$1";
$route["admin/institucional/depoimento/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/depoimento/$1";
$route["admin/institucional/depoimento(?:\.html)?"] = "admin/Admin_controller_institucional/depoimento/$1";

$route["admin/institucional/bignumbers(?:\.html)?"] = "admin/Admin_controller_institucional/bignumbers/$1";
$route["admin/institucional/bignumbers([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/bignumbers/$1";
$route["admin/institucional/bignumber/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_institucional/bignumber/$1";
$route["admin/institucional/bignumber(?:\.html)?"] = "admin/Admin_controller_institucional/bignumber/$1";

$route["admin/landing-page/estados-cidades(?:\.html)?"] = "admin/Admin_controller_landingpage/estados_cidades/$1";
$route["admin/landing-page/estados-cidades([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/estados_cidades/$1";
$route["admin/landing-page/estado-cidade/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/estado_cidade/$1";
$route["admin/landing-page/estado-cidade(?:\.html)?"] = "admin/Admin_controller_landingpage/estado_cidade/$1";

$route["admin/landing-page/configuracoes-lp(?:\.html)?"] = "admin/Admin_controller_landingpage/configuracoes_lp/$1";
$route["admin/landing-page/configuracoes-lp([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/configuracoes_lp/$1";
$route["admin/landing-page/configuracao-lp/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/configuracao_lp/$1";
$route["admin/landing-page/configuracao-lp(?:\.html)?"] = "admin/Admin_controller_landingpage/configuracao_lp/$1";

$route["admin/landing-page/solicitacoes(?:\.html)?"] = "admin/Admin_controller_landingpage/solicitacoes/$1";
$route["admin/landing-page/solicitacoes/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/solicitacoes/$1";
$route["admin/landing-page/solicitacao/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/solicitacao/$1";
$route["admin/landing-page/solicitacao(?:\.html)?"] = "admin/Admin_controller_landingpage/solicitacao/$1";

$route["admin/landing-page/repositorio-lp-geradas/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/repositorio_lp/$1";





$route["admin/landing-page/copywriters(?:\.html)?"] = "admin/Admin_controller_landingpage/copywriters/$1";
$route["admin/landing-page/copywriters([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/copywriters/$1";
$route["admin/landing-page/copywriter/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_landingpage/copywriter/$1";
$route["admin/landing-page/copywriter(?:\.html)?"] = "admin/Admin_controller_landingpage/copywriter/$1";

$route["admin/landing-page/copywriter-configuracao(?:\.html)?"] = "admin/Admin_controller_landingpage/copywriter_configuracao";





$route["admin/galeria/galerias(?:\.html)?"] = "admin/Admin_controller_galeria/galerias/$1";
$route["admin/galeria/galerias([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_galeria/galerias/$1";
$route["admin/galeria/galeria/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_galeria/galeria/$1";
$route["admin/galeria/galeria(?:\.html)?"] = "admin/Admin_controller_galeria/galeria/$1";

$route["admin/galeria/categorias(?:\.html)?"] = "admin/Admin_controller_galeria/categorias/$1";
$route["admin/galeria/categorias([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_galeria/categorias/$1";
$route["admin/galeria/categoria/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_galeria/categoria/$1";
$route["admin/galeria/categoria(?:\.html)?"] = "admin/Admin_controller_galeria/categoria/$1";

$route["admin/blog-artigos(?:\.html)?"] = "admin/Admin_controller_blog/blog_artigos/$1";
$route["admin/blog-artigos/([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_blog/blog_artigos/$1";
$route["admin/blog-artigo/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_blog/blog_artigo/$1";
$route["admin/blog-artigo(?:\.html)?"] = "admin/Admin_controller_blog/blog_artigo/$1";

$route["admin/blog/categorias(?:\.html)?"] = "admin/Admin_controller_blog/categorias/$1";
$route["admin/blog/categorias([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_blog/categorias/$1";
$route["admin/blog/categoria/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_blog/categoria/$1";
$route["admin/blog/categoria(?:\.html)?"] = "admin/Admin_controller_blog/categoria/$1";

$route["admin/blog/configuracao(?:\.html)?"] = "admin/Admin_controller_blog/configuracao";

$route["admin/modulos-personalizado(?:\.html)?"] = "admin/Admin_controller_modulopersonalizado/modulos/$1";
$route["admin/modulos-personalizado([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_modulopersonalizado/modulos/$1";
$route["admin/modulo-personalizado/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_modulopersonalizado/modulo/$1";
$route["admin/modulo-personalizado(?:\.html)?"] = "admin/Admin_controller_modulopersonalizado/modulo/$1";

$route["admin/copywriters(?:\.html)?"] = "admin/Admin_controller_copywriter/copywriters/$1";
$route["admin/copywriters([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_copywriter/copywriters/$1";
$route["admin/copywriter/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_copywriter/copywriter/$1";
$route["admin/copywriter(?:\.html)?"] = "admin/Admin_controller_copywriter/copywriter/$1";

$route["admin/copywriter/configuracao(?:\.html)?"] = "admin/Admin_controller_copywriter/configuracao";

$route["admin/faqs(?:\.html)?"] = "admin/Admin_controller_faq/faqs/$1";
$route["admin/faqs([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_faq/faqs/$1";
$route["admin/faq/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_faq/faq/$1";
$route["admin/faq(?:\.html)?"] = "admin/Admin_controller_faq/faq/$1";

$route["admin/seo/mapa(?:\.html)?"] = "admin/Admin_controller_seo/mapa/$1";
$route["admin/seo/gerar_sitemap(?:\.html)?"] = "admin/Admin_controller_seo/gerar_sitemap/$1";

$route["admin/seo/links(?:\.html)?"] = "admin/Admin_controller_seo/links/$1";
$route["admin/seo/links([a-z]+)-([0-9]+)(?:\.html)?"] = "admin/Admin_controller_seo/links/$1";
$route["admin/seo/link/([0-9]+)(?:\.html)?"] = "admin/Admin_controller_seo/link/$1";
$route["admin/seo/configuracao(?:\.html)?"] = "admin/Admin_controller_seo/config/$1";

$route["admin/logs(?:\.html)?"] = "admin/Admin_controller_log/logs/$1";

$route["admin(?:\.html)?"] = "admin/Admin_controller_admin/login";
$route["admin/login(?:\.html)?"] = "admin/Admin_controller_admin/login";
$route["admin/inicio(?:\.html)?"] = "admin/Admin_controller_admin/inicio";
$route["admin/sair(?:\.html)?"] = "admin/Admin_controller_admin/sair";
$route["admin/conta(?:\.html)?"] = "admin/Admin_controller_admin/conta";

// webhook
$route["webhook/assas(?:\.html)?"] = "site/Site_controller_checkout/webhook_asaas";

// cron 
$route["cron/pacientes(?:\.html)?"] = "cron/Cron_controller_cron/pacientes";

//ajax
$route["ajax/salvar_solicitacao_lp(?:\.html)?"] = "landingpage/Landingpage_controller_landingpage/salvar_solicitacao/$1";
$route["ajax/salvar_solicitacao(?:\.html)?"] = "site/Site_controller_paginas/salvar_solicitacao/$1";
$route["ajax/salvar_contato(?:\.html)?"] = "site/Site_controller_paginas/salvar_contato/$1";
$route["ajax/cadastrar_boletim(?:\.html)?"] = "site/Site_controller_paginas/cadastrar_boletim/$1";

// Usuario
// $route["admin-usuario/inicio(?:\.html)?"] = "usuario/Usuario_controller_usuario/inicio";
// $route["usuario(?:\.html)?"] = "usuario/Usuario_controller_usuario/login";
// $route["usuario/login(?:\.html)?"] = "usuario/Usuario_controller_usuario/login";
// $route["usuario/sair(?:\.html)?"] = "usuario/Usuario_controller_usuario/sair";
// $route["usuario/conta(?:\.html)?"] = "usuario/Usuario_controller_usuario/conta";

// Landing Pages
if(file_exists(APPPATH.'/config/routes-lp/lp-conexao.php')) include APPPATH.'/config/routes-lp/lp-conexao.php';