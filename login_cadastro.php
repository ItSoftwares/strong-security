<?
require('php/database/conexao.php');

$segmentos = DBselect('segmento', 'order by nome ASC');

session_start();

if (array_key_exists('empresa', $_SESSION)) {
	header('location: pesquisas');
}

$email_login = '';
$nome_empresa = '';
if (array_key_exists('email_login', $_GET)) {
	$email_login = $_GET['email_login'];
	$nome_empresa = $_GET['nome_empresa'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>ACESSO DO USUÁRIO</title>
	<base href="http://gap.strongsecurity.com.br/">
    <!-- <link rel="icon" type="image/png" href="img/logo.png" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content=""/>
    <link rel="stylesheet" type="text/css" href="assets/css/geral/login.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/login.css" media="(max-width: 999px)">
    <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
    <link href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<div id="centro">
		<a href="/" id="voltar" class="transition"><i class="fa fa-angle-left"></i>Página Inicial</a>

		<div id="logo">
			<img src="assets/img/logo-preta.png">
		</div>

		<div id="formularios">
			<form id="login" class="transition-filhos">
				<h3>LOGIN</h3>
				<div class="input transition">
					<input type="" name="email_login" placeholder="Email de Acesso" autofocus>
					<!-- <label>Email</label> -->
				</div>
				<div class="input transition">
					<input type="password" name="senha" placeholder="Senha">
					<!-- <label>Senha</label> -->
				</div>

				<span id="ir_cadastro" class="transition">Criar Conta</span>

				<button class="botao">Entrar</button>
			</form>

			<form id="cadastro" class="transition-filhos">
				<h3>NOVA CONTA</h3>
				<div class="input">
					<input type="" name="email_login" placeholder="Email de Acesso" autofocus required value="<? echo $email_login ?>">
				</div>
				<div class="input metade">
					<input type="password" name="senha" placeholder="Senha" required>
				</div>
				<div class="input metade">
					<input type="password" id="repetir_senha" placeholder="Repita a Senha" required>
				</div>
				<div class="input">
					<input type="text" name="nome" placeholder="Nome da Empresa" required value="<? echo $nome_empresa ?>">
				</div>
				<div class="input metade">
					<select name="id_segmento">
						<option value="0" selected>Escolha o segmento</option>
						<?
						foreach ($segmentos as $key => $value) {
						?>
						<option value="<? echo $value['id_segmento']; ?>"><? echo $value['nome']; ?></option>
						<?
						}
						?>
					</select>
				</div>
				<div class="input metade">
					<input type="number" name="qtd_filiais" placeholder="Número de Filiais" min="0" required>
				</div>
				<div class="input metade">
					<input type="number" name="qtd_funcionarios" placeholder="Quantidade de Funcionários" min="0" required>
				</div>
				<div class="input metade">
					<input type="number" name="faturamento" placeholder="Faturamento Mensal" step="0.01" min="0" required>
				</div>
				<div class="input metade">
					<input type="text" name="telefone_1" placeholder="Telefone (00) 0000-0000" required data-mask="(00) 0000-0000">
				</div>
				<div class="input metade">
					<input type="text" name="telefone_2" placeholder="Celular (00) 00000-0000" data-mask="(00) 00000-0000">
				</div>
				
				<span id="ir_login" class="transition">Já tenho uma conta</span>

				<button class="botao">Cadastrar</button>
			</form>
		</div>
	</div>
</body>
	<script type="text/javascript">

	</script>
	<script type="text/javascript" src="assets/js/geral/geral.js"></script>
	<script type="text/javascript" src="assets/js/geral/jquery.mask.js"></script>
	<script type="text/javascript" src="assets/js/login_cadastro.js"></script>
</html>
