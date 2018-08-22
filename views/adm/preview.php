<?
require('../../php/database/conexao.php');
if (array_key_exists('questionario', $_GET)) {
	$id = $_GET['questionario'];
	$questionario = DBselect('questionario', "where id_questionario = {$id}");
	$grupos = DBselect('grupo_perguntas', "where id_questionario = {$id} order by id_grupo_perguntas ASC");
	$perguntas = DBselect('pergunta p INNER JOIN grupo_perguntas g ON p.id_grupo = g.id_grupo_perguntas', "where g.id_questionario = {$id} order by p.id_pergunta ASC", 'p.*');

	if (count($questionario)==0) {
		echo 'questionario inválido!'; exit;
	}
	else $questionario = $questionario[0];
} else {
	echo 'questionario inválido!'; exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>PESQUISA #3</title>
	<base href="http://gap.strongsecurity.com.br/">
    <!-- <link rel="icon" type="image/png" href="img/logo.png" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/empresa/pesquisa.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/empresa/pesquisa.css" media="(max-width: 999px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
</head>
<body>
	<? 
	$pagina = $questionario['titulo'].' - PREVIEW';
	include('menus.php'); 
	?>

	<div id="centro" class="scroll">
		<?
		foreach ($grupos as $g) {
		?>
		<section class="painel grupo">
			<i class="fa fa-tag"></i>
			<h3><? echo $g['titulo']; ?></h3>
		</section>
		<?
			foreach ($perguntas as $p) {
				if ($p['id_grupo']!=$g['id_grupo_perguntas']) continue;
			?>
		<section class="painel pergunta">
			<header>
				<h4><? echo $p['enunciado']; ?></h4>
				<div class="descricao">
					<i class="fa fa-question transition"></i>
					<p><? echo $p['descricao']; ?></p>
				</div>
			</header>

			<div class="resposta">
				<?
				for ($i=1; $i <= 10; $i++) { 
				?>
				<div class="radio">
					<input type="radio" id="<? echo $i.'pergunta'.$p['id_pergunta']; ?>" name="<? echo 'pergunta'.$p['id_pergunta']; ?>" value="<? echo $i; ?>">
					<label for="<? echo $i.'pergunta'.$p['id_pergunta']; ?>"></label>
					<span><? echo $i; ?></span>
				</div>
				<?
				}
				?>
			</div>
		</section>

			<?
			}
		}
		?>

	</div>

	<!-- <button class="botao" id="gerar">Gerar meu relatório</button> -->
</body>
	<script type="text/javascript">
	</script>
	<!-- <script type="text/javascript" src="assets/js/empresa/pesquisa.js"></script> -->
</html>