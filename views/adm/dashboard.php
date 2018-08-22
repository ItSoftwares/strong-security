<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>DASHBOARD</title>
	<base href="http://gap.strongsecurity.com.br/">
    <!-- <link rel="icon" type="image/png" href="img/logo.png" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/adm/dashboard.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/adm/dashboard.css" media="(max-width: 999px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
</head>
<body>
	<? 
	$pagina = "Dashboard";
	include('menus.php'); 

	$empresas = DBselect('empresa', 'order by data_cadastro DESC', 'id_empresa, data_cadastro');
	$relatorios = DBselect('relatorio', 'order by data_criacao DESC', 'id_relatorio, data_criacao');
	$voucher = DBselect('solicitacao_voucher s INNER JOIN empresa e ON e.id_voucher = s.id_solicitacao_voucher', '', 's.*, e.nome');

	$empresas_qtd = count($empresas);
	$relatorios_qtd = count($relatorios);
	$voucher_qtd = count($voucher);

	$empresas = timestampToDate($empresas, 'data_cadastro');
	$relatorios = timestampToDate($relatorios, 'data_criacao');
	$voucher = timestampToDate($voucher, 'data_criacao');

	function timestampToDate($array, $index) {
		foreach ($array as $key => $value) {
			$time = strtotime($value[$index]);
			$array[$key]['mes'] = date('m', $time);
			$array[$key]['ano'] = date('Y', $time);
		}

		return $array;
	}
	?>

	<div id="centro" class="scroll">
		<?
		//for ($i=0; $i < 3; $i++) { 
		?>
		<section class="resumo">
			<div class="icon">
				<i class="fa fa-briefcase"></i>
			</div>

			<div class="infos">
				<h4>Empresas</h4>
				<span class="qtd"><? echo number_format($empresas_qtd, 0, ',', '.'); ?></span>
			</div>
		</section>

		<section class="resumo">
			<div class="icon">
				<i class="fas fa-file-contract"></i>
			</div>

			<div class="infos">
				<h4>Relatórios</h4>
				<span class="qtd"><? echo number_format($relatorios_qtd, 0, ',', '.'); ?></span>
			</div>
		</section>

		<section class="resumo">
			<div class="icon">
				<i class="fas fa-file-contract"></i>
			</div>

			<div class="infos">
				<h4>Vouchers</h4>
				<span class="qtd"><? echo number_format($voucher_qtd, 0, ',', '.'); ?></span>
			</div>
		</section>

		<section class="resumo" id="pesquisar-empresa">
			<div class="input linha">
				<input type="text" placeholder="Pesquisar Empresa">
				<i class="fa fa-search"></i>
			</div>
		</section>
	<? //} ?>
		<section class="painel metade" id="empresas" class="grafico">
			<canvas></canvas>
		</section>

		<!-- <section class="painel quarto" id="pesquisas" class="grafico">
			<canvas></canvas>
		</section> -->

		<section class="painel metade">
			<table class="lista">
				<tr>
					<th class="">Vouchers Pendentes</th>
					<th class="centro">Data</th>
					<!-- <th></th> -->
				</tr>
				<?
				foreach ($voucher as $key => $value) {
				?>
				<tr>
					<td><? echo $value['nome']; ?></td>
					<td class="centro"><?  echo date('d/m/Y, h:i', strtotime($value['data_criacao'])); ?></td>
					<!-- <td class="direita"> -->
						<!-- <a class="botao">Liberar</a> -->
					<!-- </td> -->
				</tr>
				<?
				}
				?>
			</table>
		</section>
	</div>
</body>
	<script type="text/javascript">
		var empresas = <? echo json_encode($empresas); ?>;
		var relatorios = <? echo json_encode($relatorios); ?>;
		var voucher = <? echo json_encode($voucher); ?>;
	</script>
	<script type="text/javascript" src="assets/js/adm/dashboard.js"></script>
</html>