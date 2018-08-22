<?
$tipo = 'empresa';
require('../../php/util/sessao.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Meus Resultados</title>
	<base href="http://gap.strongsecurity.com.br/">
    <!-- <link rel="icon" type="image/png" href="img/logo.png" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/adm/perfil.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/adm/perfil.css" media="(max-width: 999px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
</head>
<body>
	<? 
	$pagina = "Meus Resultados";
	include('menu.php'); 

	$resultados = DBselect('relatorio r INNER JOIN questionario q ON r.id_questionario = q.id_questionario', 'where id_empresa = '.$empresa->id_empresa, 'r.*, q.titulo');
	?>

	<div id="centro" class="scroll">
		<section class="painel box-window metade">
			<h2>Os Relatório estão listados abaixo</h2>
			
			<table class="">
				<tr>
					<th>Relátorio</th>
					<th>Data</th>
					<th class="centro">Ações</th>
				</tr>
				<?
				foreach ($resultados as $key => $q) {
				?>
				<tr>
					<td><? echo $q['titulo']; ?></td>
					<td><? echo date('d/m/Y, H:i', strtotime($q['data_criacao'])); ?></td>
					<td class="centro"><a href="relatorio?id=<? echo $q['id_relatorio'] ?>" class="botao icon" title="Ver relatório" target='_BLANK'><i class="fa fa-envelope"></i></a></td>
				</tr>
				<?
				}
				?>
			</table>
		</section>
	</div>
</body>
	<script type="text/javascript">
		var resultados = <? echo json_encode($resultados) ?>;
	</script>
	<script type="text/javascript" src="assets/js/empresa/resultados.js"></script>
</html>