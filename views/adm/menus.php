<link rel="stylesheet" type="text/css" href="assets/css/geral/menus.css?<? echo time(); ?>" media="(min-width: 1000px)">
<link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/menus.css?<? echo time(); ?>" media="(max-width: 999px)">

<?
require_once('../../php/classes/adm.class.php');
require_once('../../php/classes/pesquisa.class.php');
require_once('../../php/database/conexao.php');
if (session_status() == PHP_SESSION_NONE) session_start();
$adm = unserialize($_SESSION['adm']);
?>

<header id="topo">
	<div id="toggle-menu">
		<i class="fa fa-bars"></i>
	</div>

	<div id="logo">
		<img src="assets/img/logo-colorida.png">
		<h1><? echo $pagina; ?></h1>
	</div>

	<ul id="menu-superior">
		<div id="pesquisar">
			<input type="text" name="pesquisar-empresa" placeholder="Digite o nome da empresa">
			<li><i class="fa fa-search"></i></li>
		</div>

		<li id="configuracoes"><a href=""><i class="fa fa-cog"></i></a></li>
		<!-- <li><a href="#"><i class="fa fa-sync-alt"></i></a></li> -->
		<li><a href="sair"><i class="fa fa-sign-out-alt"></i></a></li>
	</ul>
</header>

<aside id="menu-lateral" class="transition">
	<ul>
		<li <? echo $pagina=='Dashboard'?'class="selecionado"':'' ?>><a href="adm/dashboard"><i class="fas fa-chart-bar"></i><span>Dashboard</span></a></li>
		<li <? echo $pagina=='Empresas'?'class="selecionado"':'' ?>><a href="adm/empresas"><i class="fa fa-briefcase"></i><span>Empresas</span></a></li>
		<li <? echo $pagina=='Pesquisas'?'class="selecionado"':'' ?>><a href="adm/pesquisas"><i class="fas fa-file-contract"></i><span>Pesquisas</span></a></li>
		<!-- <li <? echo $pagina=='Vouchers'?'class="selecionado"':'' ?>><a href="adm/vouchers"><i class="fa fa-tags"></i><span>Vouchers</span></a></li> -->
	</ul>

	<!-- <div id="foto-perfil">
		<img src="assets/img/profile-default.png">
	</div> -->
</aside>

<section class="fundo" id="perfil">
	<i class="fa fa-times fechar"></i>

	<div class="menor">
		<h3>Editar Perfil</h3>

		<form class="campos">
			<div class="input linha">
				<input type="text" name="email" placeholder="Email">
				<i class="fa fa-envelope"></i>
			</div>
			<div class="input linha metade">
				<input type="password" name="senha" placeholder="Nova Senha">
				<i class="fa fa-key"></i>
			</div>
			<div class="input linha metade">
				<input type="password" id="repetir_senha" placeholder="Repita a nova senha">
				<i class="fa fa-key"></i>
			</div>

			<button class="botao direita">Atualizar</button>
		</form>
	</div>
</section>

<script src="assets/js/geral/dashboard.js"></script>