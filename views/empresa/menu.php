<link rel="stylesheet" type="text/css" href="assets/css/geral/menus.css?<? echo time(); ?>" media="(min-width: 1000px)">
<link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/menus.css?<? echo time(); ?>" media="(max-width: 999px)">

<?
require_once('../../php/classes/empresa.class.php');
require_once('../../php/classes/pesquisa.class.php');
require_once('../../php/database/conexao.php');
if (session_status() == PHP_SESSION_NONE) session_start();
$empresa = unserialize($_SESSION['empresa']);
if (array_key_exists('voucher', $_SESSION)) $voucher = $_SESSION['voucher'];
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
		<!-- <li><a href="#"><i class="fa fa-cog"></i></a></li> -->
		<!-- <li><a href="#"><i class="fa fa-sync-alt"></i></a></li> -->
		<li><a href="sair"><i class="fa fa-sign-out-alt"></i></a></li>
	</ul>
</header>

<aside id="menu-lateral" class="transition">
	<ul>
		<!-- <li <? echo $pagina=='Inicio'?'class="selecionado"':'' ?>><a href="inicio"><i class="fa fa-home"></i><span>Inicio</span></a></li> -->
		<li <? echo $pagina=='Pesquisas'?'class="selecionado"':'' ?>><a href="pesquisas"><i class="fas fa-file-contract"></i><span>Pesquisas</span></a></li>
		<li <? echo $pagina=='Meus Resultados'?'class="selecionado"':'' ?>><a href="meus_resultados"><i class="fa fa-chart-bar"></i><span>Resultados</span></a></li>
		<li <? echo $pagina=='Perfil'?'class="selecionado"':'' ?>><a href="perfil"><i class="fa fa-cog"></i><span>Perfil</span></a></li>
		<? 
		$estado = 'Solicitar Voucher';
		if (!isset($voucher)) { 
		?>
		<li title="Solicitar voucher para ter relatórios completos." id="solicitar-voucher" data-id='1'><a href=""><i class="fa fa-tag"></i><span>Solicitar Voucher</span></a></li>
		<? 
		} else { 
			$estado = 'Voucher Pendente';
			if ($voucher['estado']==1) $estado = 'Voucher Recusado';
			else if ($voucher['estado']==2) $estado = 'Voucher'.$voucher['voucher'];
		?>
		<li title="Solicitar voucher para ter relatórios completos." id="solicitar-voucher" data-id='2'><a href=""><i class="fa fa-tag"></i><span><? echo $estado; ?></span></a></li>
		<? } ?>
	</ul>

	<!-- <div id="foto-perfil">
		<img src="assets/img/profile-default.png">
	</div> -->
</aside>
<script type="text/javascript">
	var empresa = <? echo json_encode($empresa->toArray()); ?>;
</script>
<script src="assets/js/geral/geral.js?<? echo time() ?>"></script>
<script src="assets/js/geral/dashboard.js?<? echo time() ?>"></script>