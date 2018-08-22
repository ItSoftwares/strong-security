<?php
require('../php/database/conexao.php');

if (!array_key_exists('id', $_GET)) {
	echo 'Relatório inválido!'; exit;
}

$id = $_GET['id'];

$relatorio = DBselect('relatorio', "where id_relatorio = {$id}");
if (count($relatorio)==0) {
	echo 'Relatório inválido!'; exit;
}
else $relatorio = $relatorio[0];

$relatorio_master = $relatorio;

$questionario = DBselect('questionario', "where id_questionario = {$relatorio['id_questionario']}")[0];
$grupos = DBselect('grupo_perguntas', "where id_questionario = {$relatorio['id_questionario']} order by id_grupo_perguntas ASC");
$perguntas = DBselect('pergunta p INNER JOIN grupo_perguntas g ON p.id_grupo = g.id_grupo_perguntas', "where g.id_questionario = {$relatorio['id_questionario']} order by p.id_pergunta ASC", 'p.*');
$respostas = DBselect('resposta', 'where id_relatorio = '.$id);

// PEGAR DADOS DA EMPRESA
$empresa = DBselect('empresa e LEFT JOIN solicitacao_voucher s ON e.id_voucher = s.id_solicitacao_voucher', 'where e.id_empresa = '.$relatorio['id_empresa'], 'e.*, s.estado')[0];

// PEGAR MÉDIA DAS RESPOSTAS ANTERIORES AO MESMO RELATÓRIO
$ultimosRelatorios = DBselect('relatorio r', "where id_questionario = {$relatorio['id_questionario']} and id_empresa = {$relatorio['id_empresa']}", 'r.*, (SELECT AVG(valor) FROM resposta WHERE id_relatorio = r.id_relatorio) pontos');

// PEGAR RESPOSTAS ANTERIORES AO MESMO RELATÓRIO
$respostasRelAnteriores = DBselect('resposta r INNER JOIN relatorio rel ON r.id_relatorio = rel.id_relatorio', "where rel.id_questionario = {$relatorio['id_questionario']} and id_empresa = {$relatorio['id_empresa']} and rel.id_relatorio <> {$id} order by r.id_resposta ASC", 'r.*');

// RESULTADOS DAS ULTIMAS 5 EMPRESAS
$outrasEmpresas = DBselect('relatorio r INNER JOIN empresa e ON r.id_empresa = e.id_empresa', 'order by pontos DESC limit 5', 'e.nome, (SELECT AVG(valor) FROM resposta WHERE id_relatorio = r.id_relatorio) pontos');

// PEGAR MEDIA DAS RESPOSTAS DE EMPRESAS DO MESMO SEGMENTO
$mesmoSegmento = DBselect('resposta r INNER JOIN relatorio rel ON r.id_relatorio = rel.id_relatorio INNER JOIN empresa e ON rel.id_empresa = e.id_empresa', "where rel.id_questionario = {$relatorio['id_questionario']} and e.id_segmento = {$empresa['id_segmento']} group by r.id_pergunta", 'r.id_pergunta, AVG(r.valor) pontos');

// PEGAR MEDIA DAS RESPOSTAS DESTE QUESTIONARIO
$mediaGeral = DBselect('resposta r INNER JOIN relatorio rel ON r.id_relatorio = rel.id_relatorio', "where rel.id_questionario = {$relatorio['id_questionario']} group by r.id_pergunta", 'r.id_pergunta, AVG(r.valor) pontos');

// REORDENAR ARRAYS
$temp = [];
foreach ($respostas as $key => $value) {
	$temp[$value['id_pergunta']] = $value;
}
$respostas = $temp;

$temp = [];
foreach ($mesmoSegmento as $key => $value) {
	$temp[$value['id_pergunta']] = $value;
}
$mesmoSegmento = $temp;

$temp = [];
foreach ($mediaGeral as $key => $value) {
	$temp[$value['id_pergunta']] = $value;
}
$mediaGeral = $temp;

function getEstado($valor1, $valor2, $valor3) {
	$estado = '';
	if ($valor1 > $valor2 and $valor1>$valor3) 
		return 'Melhor <!--<i class="fa fa-sort-up">-->';
	else if ($valor1 > $valor2 or $valor1 > $valor3)
		return 'Intermediário <!--<i class="fa fa-minus">-->';
	else if ($valor1 == $valor2 and $valor1 == $valor3)
		return 'Média <i class="fa fa-minus">';
	else
		return 'Pior <!--<i class="fa fa-sort-down">-->';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>RELATÓRIO</title>
	<base href="http://gap.strongsecurity.com.br/">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/geral/relatorio.css">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/menus.css">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
</head>
<body>
	<div id="editor"></div>
	<div id="container">
		<header>
			<img src="assets/img/logo-preta.png" id="logo"></img>

			<div>
				<span>Relatório #<?php echo $relatorio['id_relatorio']; ?></span>
				<span><?php echo date('d/m/Y, H:i', strtotime($relatorio['data_criacao'])); ?></span>
				<h3>Strong Security GAP</h3>
			</div>
		</header>

		<!-- <article> -->
		<h1><?php echo $questionario['titulo']; ?></h1>
		<p><?php echo $questionario['descricao']; ?></p>

		<hr class="separador">

		<canvas id="grafico-radar"></canvas>

		<div id="tabelas">
			<table>
				<tr>
					<th colspan="2">Meus Últimos Relátorios</th>
				</tr>
				<tr>
					<th>Data</th>
					<th class="centro">Pontos</th>
					<!-- <th class="centro">Qualidade %</th> -->
				</tr>
				<?php
				foreach ($ultimosRelatorios as $relatorio) {
				?>
				<tr>
					<td><?php echo date('d/m/Y, H:i', strtotime($relatorio['data_criacao'])); ?></td>
					<td class="centro"><?php echo number_format($relatorio['pontos'], 1, ',', ' '); ?></td>
					<!-- <td class="centro"><?php echo '10%'; ?></td> -->
				</tr>
				<?php
				}
				?>
			</table>

			<table>
				<tr>
					<th colspan="2">Comparação entre empresas</th>
				</tr>
				<tr>
					<th>Nome</th>
					<th>Pontos</th>
					<!-- <th>Qualidade %</th> -->
				</tr>
				<?php
				foreach ($outrasEmpresas as $relatorio) {
				?>
				<tr>
					<td><?php echo $relatorio['nome']; ?></td>
					<td class="centro"><?php echo number_format($relatorio['pontos'], 1, ',', ' '); ?></td>
					<!-- <td class="centro"><?php echo '10%'; ?></td> -->
				</tr>
				<?php
				}
				?>
			</table>
		</div>

		<!-- <hr class="separador"> -->

		<div id="pontuacao">
			<!-- Somátorio de pontos MÉDIA -->
			<!-- <canvas id="grafico-pizza-pontos"></canvas> -->
			<!-- Pontos por questão -->
			<canvas id="grafico-linha-media-respostas"></canvas>
		</div>

		<hr class="separador">
		<? if ($empresa['id_voucher']==null || $empresa['estado']==0 || $empresa['estado']==1) { ?>
		<div id="sem-voucher">
			<!-- <i class="fa fa-ban"></i> -->

			<h3>Você não tem acesso ao relatório completo! Para conseguir acesso total solicite o VOUCHER na paginal inicial!</h3>
		</div>

		<!-- <ul class="grupo"> -->
		<?php
		}
		$numero = 0;
		$media = 0;
		$temVoucher = true;
		if ($empresa['id_voucher']==null || $empresa['estado']==0 || $empresa['estado']==1) $temVoucher = false;
		foreach ($grupos as $g) {
		?>
		<? if ($temVoucher) { ?><h3><span><?php echo $g['titulo']; ?></span></h3><? } ?>
		<?php
			foreach ($perguntas as $p) {
				if ($p['id_grupo']!=$g['id_grupo_perguntas']) continue;
				$numero++;
				$respostas[$p['id_pergunta']]['numero'] = $numero;
				$mesmoSegmento[$p['id_pergunta']]['numero'] = $numero;
				$mediaGeral[$p['id_pergunta']]['numero'] = $numero;

				$media += $respostas[$p['id_pergunta']]['valor'];

				$meu = $respostas[$p['id_pergunta']]['valor'];
				$segmento = $mesmoSegmento[$p['id_pergunta']]['pontos'];
				$mediaG = $mediaGeral[$p['id_pergunta']]['pontos'];
				if (!$temVoucher) continue;
		?>
		<li class="pergunta">
			<h4><span><?php echo $numero; ?></span><?php echo $p['enunciado']; ?></h4>
			<p class="descricao"><?php echo $p['descricao']; ?></p>

			<table class="resposta">
				<tr>
					<th>Sua Resposta</th>
					<th><?php echo number_format($meu, 1, ',', ' '); ?></th>
					<th class="centro"><?php echo getEstado($meu, $segmento, $mediaG); ?></th>
				</tr>
				<tr>
					<td>Média mesmo Segmento</td>
					<td><?php echo number_format($segmento, 1, ',', ' '); ?></td>
					<td class="centro"><?php echo getEstado($segmento, $meu, $mediaG); ?></td>
				</tr>
				<tr>
					<td>Média Geral</td>
					<td><?php echo number_format($mediaG, 1, ',', ' '); ?></td>
					<td class="centro"><?php echo getEstado($mediaG, $meu, $segmento); ?></td>
				</tr>
			</table>
		</li>
		<?php
			}
		?>

		<?php
		}
		$media /= $numero;
		?>
		<h2>Sua média final foi de <strong><?php echo number_format($media, 1, ',', ' '); ?></strong></h2>
		<!-- </ul> -->
		<!-- </article> -->

		<hr class="separador">

		<footer>
			<h3>Dica para que você possa melhorar</h3>
			<p class="conclusao">
				<?php 
				if ($media<4) echo $questionario['conclusao_1'];
				else if ($media<7) echo $questionario['conclusao_2'];
				else echo $questionario['conclusao_3'];
				?>
			</p>
		</footer>
	</div>

	<div id="loading">
		<img src="assets/img/loading.gif">

		<h3 class="mensagem">Gerando Gráficos...</h3>
	</div>
</body>
	<script type="text/javascript">
		var relatorio = <?php echo json_encode($relatorio_master); ?>;
		var empresa = <?php echo json_encode($empresa); ?>;
		var respostas = <?php echo json_encode($respostas); ?>;
		var respostasRelAnteriores = <?php echo json_encode($respostasRelAnteriores); ?>;
		var mesmoSegmento = <?php echo json_encode($mesmoSegmento); ?>;
		var mediaGeral = <?php echo json_encode($mediaGeral); ?>;
		var questoes = <?php echo $numero; ?>;
	</script>
	<script src="assets/js/lib/html2canvas.min.js?<?php echo time(); ?>"></script>
	<script type="text/javascript" src="assets/js/geral/geral.js?<?php echo time(); ?>"></script>
	<script type="text/javascript" src="assets/js/geral/relatorio.js?<?php echo time(); ?>"></script>
</html>