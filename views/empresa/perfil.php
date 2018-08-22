<?
$tipo = 'empresa';
require('../../php/database/conexao.php');
require('../../php/util/sessao.php');

$segmentos = DBselect('segmento', 'order by nome ASC');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Meu Perfil</title>
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
	$pagina = "Perfil";
	include('menu.php'); 
	?>

	<div id="centro" class="scroll">
		<section class="painel box-window metade">
			<h2>Meu Perfil</h2>
			<form class="campos" id="perfil">
				<div class="input normal">
					<input type="" name="email_login" placeholder="Email de Acesso" autofocus required disabled>
					<label>Email de Acesso</label>
				</div>
				<div class="input normal metade">
					<input type="password" name="senha" placeholder="Nova Senha" disabled>
					<label>Nova Senha</label>
				</div>
				<div class="input normal metade">
					<input type="password" id="repetir_senha" placeholder="Repita a Nova Senha" disabled>
					<label>Repita a Nova Senha</label>
				</div>
				<div class="input normal">
					<input type="text" name="nome" placeholder="Nome da Empresa" required disabled>
					<label>Nome da Empresa</label>
				</div>
				<div class="input normal metade">
					<select name="id_segmento" disabled>
						<option value="0" selected>Escolha o segmento</option>
						<?
						foreach ($segmentos as $key => $value) {
						?>
						<option value="<? echo $value['id_segmento']; ?>"><? echo $value['nome']; ?></option>
						<?
						}
						?>
					</select>
					<label>Segmento</label>
				</div>
				<div class="input normal metade">
					<input type="number" name="qtd_filiais" placeholder="Número de Filiais" min="0" required disabled>
					<label>Número de Filiais</label>
				</div>
				<div class="input normal metade">
					<input type="number" name="qtd_funcionarios" placeholder="Quantidade de Funcionários" min="0" required disabled>
					<label>Quantidade de Funcionários</label>
				</div>
				<div class="input normal metade">
					<input type="number" name="faturamento" placeholder="Faturamento Mensal" step="0.01" min="0" required disabled>
					<label>Faturamento Mensal</label>
				</div>
				<div class="input normal metade">
					<input type="text" name="telefone_1" placeholder="Telefone (00) 0000-0000" required data-mask="(00) 0000-0000" disabled>
					<label>Telefone</label>
				</div>
				<div class="input normal metade">
					<input type="text" name="telefone_2" placeholder="Celular (00) 00000-0000" data-mask="(00) 00000-0000" disabled>
					<label>Celular</label>
				</div>

				<button class="botao" id="editar" type="button">Editar</button>
				<button class="botao vermelho" id="cancelar" style="display: none" type="button">Cancelar</button>
				<button class="botao direita" id="salvar" style="display: none">Atualizar</button>
			</form>
		</section>
	</div>
</body>
	<script type="text/javascript">
		// var empresa = <? //echo json_encode($empresa->toArray()); ?>;
	</script>
	<script type="text/javascript" src="assets/js/geral/jquery.mask.js"></script>
	<script type="text/javascript" src="assets/js/empresa/perfil.js"></script>
</html>