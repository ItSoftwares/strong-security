<?
$tipo = 'empresa';
require('../../php/util/sessao.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>PESQUISAs</title>
	<base href="http://gap.strongsecurity.com.br/">
    <!-- <link rel="icon" type="image/png" href="img/logo.png" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/adm/pesquisas.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/adm/pesquisas.css" media="(max-width: 999px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
</head>
<body>
	<? 
	$pagina = "Pesquisas";
	include('menu.php'); 

	$questionarios = DBselect('questionario q', '', '*, (select COUNT(id_questionario) from relatorio where id_questionario = q.id_questionario) respondido');
	?>

	<div id="centro" class="scroll">
		<?
		if (array_key_exists('questionarios', $_POST)) $questionarios = $_POST['questionarios'];
		foreach ($questionarios as $value) { 
			$q = new Pesquisa($value);
		?>
		<section class="card">
			<a>
				<h3><? echo $q->titulo; ?></h3>

				<p><? echo $q->descricao; ?></p>

				<hr class="linha">

				<span>Respondido <strong><? echo $q->respondido; ?></strong> <? echo $q->respondido==1?'Vez':'Vezes'; ?></span>
			</a>

			<footer>
				<div class="qtd-respostas"><? echo $i*3; ?></div>
				<a class="botao compartilhar icon compartilhar" id="facebook" title="Compartilhar no Facebook"><i class="fab fa-facebook-f"></i></a>

				<div>
					<a href="responder-questionario?questionario=<? echo $q->id_questionario; ?>" class="botao icon responder" title="Responder Pesquisa" target="_BLANK"><i class="fa fa-arrow-right"></i></a>
				</div>
			</footer>
		</section>
		<?
		}
		?>
	</div>
</body>
	<script type="text/javascript">
		
	</script>
	<script type="text/javascript" src="assets/js/empresa/pesquisa.js"></script>
</html>