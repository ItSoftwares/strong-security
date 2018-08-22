<?
$tipo = 'adm';
require('../../php/util/sessao.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>PESQUISAS</title>
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
	include('menus.php'); 

	$questionarios = DBselect('questionario q', '', '*, (select COUNT(id_questionario) from relatorio where id_questionario = q.id_questionario) respondido');
	$grupo_perguntas = DBselect('grupo_perguntas');
	$perguntas = DBselect('pergunta');
	?>

	<div id="centro" class="scroll">
		<?
		if (array_key_exists('questionarios', $_POST)) $questionarios = $_POST['questionarios'];
		foreach ($questionarios as $value) { 
			$q = new Pesquisa($value);
		?>
		<section class="card" data-id="<? echo $q->id_questionario; ?>">
			<a href="">
				<h3><? echo $q->titulo; ?></h3>

				<p><? echo $q->descricao; ?></p>

				<hr class="linha">

				<span>Respondido <strong><? echo $q->respondido; ?></strong> <? echo $q->respondido==1?'Vez':'Vezes'; ?></span>
			</a>

			<footer>
				<a class="botao compartilhar icon compartilhar" id="facebook" title="Compartilhar no Facebook" href="<? echo $q->id_questionario; ?>"><i class="fab fa-facebook-f"></i></a>

				<div>
					<!-- <button class="botao compartilhar icon amarelo editar" title="Editar Pesquisa"><i class="fa fa-edit"></i></button> -->
					<a href="adm/preview?questionario=<? echo $q->id_questionario; ?>" class="botao compartilhar icon ver" title="Ver Questionario" target="_BLANK"><i class="fa fa-eye"></i></a>
					<button class="botao compartilhar icon vermelho excluir" title="Excluir Questionario"><i class="fa fa-trash"></i></button>
				</div>
			</footer>
		</section>
		<?
		}
		?>

		<section class="card" id="nova">
			<button class="botao preto">Nova<i class="fa fa-plus"></i></button>
		</section>
	</div>

	<div id="reload"></div>

	<section class="fundo scroll branco" id="pesquisa">
		<i class="fa fa-times fechar"></i>

		<!-- <form> -->
		<div class="">
			<h3>Nova Pesquisa</h3>
			<form class="campos" id="questionario-form">
				<div class="input normal">
					<input type="text" name="titulo" placeholder="Título da Pesquisa">
					<label>Título da Pesquisa</label>
				</div>
				<div class="input normal metade">
					<textarea name="descricao" placeholder="Descreva aqui os objetivos desta pesquisa"></textarea>
					<label>Descrição</label>
				</div>
				<div class="input normal metade">
					<textarea name="conclusao_1" placeholder="Para as empresas que obterem nota abaixo de 4.0"></textarea>
					<label>Conclusão 1</label>
				</div>
				<div class="input normal metade">
					<textarea name="conclusao_2" placeholder="Para as empresas que obterem nota entre 4.0 e 6.9"></textarea>
					<label>Conclusão 2</label>
				</div>
				<div class="input normal metade">
					<textarea name="conclusao_3" placeholder="Para as empresas que obterem nota entre 7.0 e 10"></textarea>
					<label>Conclusão 3</label>
				</div>
			</form>
		</div>

		<div class="grupo">
			<form class="campos">
				<button type="button" class="botao icon nova-pergunta" title="Nova Pergunta"><i class="fa fa-plus"></i></button>
				<button type="button" class="botao icon apagar-grupo vermelho" title="Remover Grupo"><i class="fa fa-trash"></i></button>
				<div class="input linha">
					<input type="text" name="titulo_grupo" placeholder="Título do grupo de perguntas">
					<i class="fa fa-tag"></i>
				</div>
			</form>
		</div>

		<div class="pergunta">
			<span class="numero">#1</span>
			<button type="button" class="botao icon apagar-pergunta vermelho" title="Remover Pergunta"><i class="fa fa-trash"></i></button>
			<form class="campos">
				<div class="input normal">
					<input type="text" name="titulo_pergunta" placeholder="Enunciado da Pergunta">
					<label>Enunciado</label>
				</div>
				<div class="input normal">
					<textarea name="descritivo_pergunta" placeholder="Detalhe esta pergunta"></textarea>
					<label>Descritivo</label>
				</div>
			</form>
		</div>

		<div class="nova">
			<div class="campos">
				<button class="botao">Grupo<i class="fa fa-plus"></i></button>
			</div>
		</div>

		<!-- <button class="botao" id="novo-grupo">Grupo<i class="fa fa-plus"></i></button> -->
		<button class="botao redondo icon fixed" id="salvar-pesquisa" onclick="salvarQuestionario()"><i class="fa fa-save"></i></button>
	</section>
</body>
	<script type="text/javascript">
		var questionarios = <? echo json_encode($questionarios) ?>;
		var grupo_perguntas = <? echo json_encode($grupo_perguntas) ?>;
		var perguntas = <? echo json_encode($perguntas) ?>;
	</script>
	<script type="text/javascript" src="assets/js/geral/geral.js?<? echo time(); ?>"></script>
	<script type="text/javascript" src="assets/js/adm/pesquisas.js?<? echo time(); ?>"></script>
</html>