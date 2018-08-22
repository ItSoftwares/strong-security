<?
session_start();

if (array_key_exists('adm', $_SESSION)) {
	header('location: dashboard');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>LOGIN | ADM</title>
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
			<form id="login">
				<div class="input transition">
					<input type="" name="email" placeholder="Email de Acesso" autofocus>
					<!-- <label>Email</label> -->
				</div>
				<div class="input transition">
					<input type="password" name="senha" placeholder="Senha">
					<!-- <label>Senha</label> -->
				</div>

				<button class="botao completo">Entrar</button>
			</form>
		</div>
	</div>
</body>
	<script type="text/javascript">

	</script>
	<script type="text/javascript" src="assets/js/geral/geral.js"></script>
	<script type="text/javascript" src="assets/js/adm/login.js"></script>
</html>
