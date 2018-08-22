<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>EMRPESAS</title>
	<base href="http://gap.strongsecurity.com.br/">
    <!-- <link rel="icon" type="image/png" href="img/logo.png" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/adm/empresas.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/adm/empresas.css" media="(max-width: 999px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
</head>
<body>
	<? 
	$pagina = "Empresas";
	include('menus.php'); 

	$segmentos = DBselect('segmento', 'order by nome ASC');
	$nome = null;
	$filtro = 0;
	$segmento = 0;
	$pagina = 1;

	if (!empty($_GET)) {
		if (array_key_exists('nome', $_GET)) $nome = $_GET['nome'];
		if (array_key_exists('filtro', $_GET)) $filtro = $_GET['filtro'];
		if (array_key_exists('segmento', $_GET)) $segmento = $_GET['segmento'];
		if (array_key_exists('pagina', $_GET)) $pagina = $_GET['pagina'];
	} 
	// else echo 'teste';
	?>

	<div id="centro" class="scroll">
		<section class="painel box-window">
			<div id="pesquisar">
				<div class="input linha metade">
					<input type="text" placeholder="Pesquisar Empresa" value="<? echo $nome; ?>" autofocus>
					<i class="fa fa-search"></i>
				</div>
				<div class="input linha quarto">
					<select id="filtro">
						<option value="0" <? echo $filtro==0?'selected':''; ?>>Todas</option>
						<option value="1" <? echo $filtro==1?'selected':''; ?>>Com Voucher</option>
						<option value="2" <? echo $filtro==2?'selected':''; ?>>Sem Voucher</option>
						<!-- <option value="1" <? echo $filtro==1?'selected':''; ?>>Bloqueadas</option> -->
						<!-- <option value="2" <? echo $filtro==2?'selected':''; ?>>Desbloqueadas</option> -->
					</select>
					<i class="fa fa-filter"></i>
				</div>
				<div class="input linha quarto">
					<select id="segmento">
						<option value="0">Todos os Segmentos</option>
						<?
						foreach ($segmentos as $key => $value) {
						?>
						<option value="<? echo $value['id_segmento']; ?>" <? echo $segmento==$value['id_segmento']?'selected':''; ?>><? echo $value['nome']; ?></option>
						<?
						}
						?>
					</select>
					<i class="fa fa-filter"></i>
				</div>
			</div>
		</section>
		
		<section class="painel box-window" id="tabela">
			<? $teste = true; include('conteudo/tabela.php'); ?>
		</section>
	</div>

	<div id="reload"></div>

	<section class="fundo scroll branco" id="detalhes">
		<i class="fa fa-times fechar"></i>

		<div>
			<h3>Detalhes</h3>
			<form class="campos">
				<!--  -->
				<!-- IMAGEM DO PERFIL -->
				<!--  -->
				<div class="input normal">
					<input type="text" name="nome" placeholder="Nome da empresa" readonly>
					<label>Nome</label>
				</div>
				<div class="input normal metade">
					<select id="segmento" readonly>
						<?
						foreach ($segmentos as $key => $value) {
						?>
						<option value="<? echo $value['id_segmento']; ?>" <? echo $segmento==$value['id_segmento']?'selected':''; ?>><? echo $value['nome']; ?></option>
						<?
						}
						?>
					</select>
					<label>Segmento</label>
				</div>
				<div class="input normal quarto">
					<input type="text" name="qtd_funcionarios" placeholder="Número de Funcionários" readonly>
					<label>Nº de Funcionários</label>
				</div>
				<div class="input normal quarto">
					<input type="text" name="qtd_filiais" placeholder="Quantidade de Filiais" readonly>
					<label>Qtd de Filiais</label>
				</div>
				<div class="input normal metade">
					<input type="number" name="faturamento" placeholder="Faturamento Mensal" step="0.01" min="0" readonly>
					<label>Faturamento Mensal</label>
				</div>
				<hr class="linha">
				<div class="input normal metade">
					<input type="text" name="email_login" placeholder="Email para Login" readonly>
					<label>Email para Login</label>
				</div>
				<div class="input normal metade">
					<input type="text" name="email_contato" placeholder="Email para Contato" readonly>
					<label>Email para Contato</label>
				</div>
				<div class="input normal quarto">
					<input type="text" name="telefone_1" placeholder="(00) 0000-0000" data-mask="(00) 0000-0000" readonly>
					<label>Telefone</label>
				</div>
				<div class="input normal quarto">
					<input type="text" name="telefone_2" placeholder="(00) 00000-0000" data-mask="(00) 00000-0000" readonly>
					<label>Celular</label>
				</div>
				<div class="input normal metade">
					<input type="text" name="voucher" placeholder="Voucher de acesso" readonly>
					<label>Voucher</label>
				</div>
			</form>
		</div>

		<div>
			<h3>Relatórios</h3>
			<div class="campos">
				<table class="lista" id="relatorios">
					<tr>
						<th>Relatório</th>
						<th>Data</th>
						<th></th>
					</tr>

					<!-- <tr>
						<td>Pesquisa desenvolvimento RH de empresas</td>
						<td>25/10/2018</td>
						<td>
							<button class="botao icon"><i class="fa fa-envelope" title="Reenviar Relatório"></i></button>
						</td>
					</tr> -->
				</table>
			</div>
		</div>
	</section>
</body>
	<script type="text/javascript">
		var empresas = <? echo json_encode($empresas); ?>;
		var relatorios = <? echo json_encode($relatorios); ?>;
		var qtd = <? echo $qtd; ?>;
		var nome = '<? echo $nome; ?>';
		var filtro = <? echo $filtro; ?>;
		var segmento = <? echo $segmento; ?>;
		var pagina = <? echo $pagina; ?>;
	</script>
	<script type="text/javascript" src="assets/js/geral/geral.js?<? echo time(); ?>"></script>
	<script type="text/javascript" src="assets/js/geral/jquery.mask.js?<? echo time(); ?>"></script>
	<script type="text/javascript" src="assets/js/adm/empresas.js?<? echo time(); ?>"></script>
</html>