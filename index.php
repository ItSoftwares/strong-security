<!DOCTYPE HTML>
<html>
    <head>
        <title>Strong Security</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="img/logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="" />
        <meta name="keywords" content=""/>
        <meta name="robots" content="index, follow">
        <link rel="stylesheet" type="text/css" href="assets/css/index.css" media="(min-width: 1000px)">
        <link rel="stylesheet" type="text/css" href="assets/css/geral/geral.css" media="(min-width: 1000px)">
        <link rel="stylesheet" type="text/css" href="assets/cssmobile/index.css" media="(max-width: 999px)">
        <link rel="stylesheet" type="text/css" href="assets/cssmobile/geral/geral.css" media="(max-width: 999px)">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>

    <body>
		<header id="menu-superior">
			<div class="box-window">
				<img src="assets/img/logo-colorida.png" id="logo">
				<!-- <h3 id="logo"></h3> -->

				<ul id="menu">
					<li><a href="acesso">LOGIN</a></li>
					<!-- <li><a href="#">CONTATO</a></li> -->
				</ul>
			</div>
		</header>

		<div id="inicio">
			<div id="fundo"></div>
			<h1>SISTEMA DE ANALISE DE GAP. FAÇA AGORA O SUA ANALISE</h1>

			<form class="transition" action="acesso">
				<!-- <div class="input"> -->
					<input type="text" name="nome_empresa" placeholder="Nome da Empresa" required>
				<!-- </div> -->
				<!-- <div class="input"> -->
					<input type="text" name="email_login" placeholder="Email de Acesso" required>
				<!-- </div> -->
				<button class="botao redondo">Faça seu cadastro</button>
			</form>

			<p>*INFORMAR O SEU MELHOR E-MAIL. NÃO FAZEMOS SPAM E RESPEITAMOS A SUA PRIVACIDADE!</p>

			<i class="fa fa-angle-down"></i>
		</div>

		<div id="lista">
			<ul class="box-window">
				<?
				for ($i=0; $i < 3; $i++) {
				?>
				<li>
					<i class="fab fa-bity"></i>
					<h4>Título do item</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</li>
				<?
				}
				?>
			</ul>
		</div>

		<div id="video">
			<div class="box-window">
				<div>
					<h3>Mais um título pra ser adicionado Sobre o vídeo tomara que todos assitam</h3>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat.</p>
				</div>

				<img src="assets/img/video.png">
			</div>
		</div>

		<div id="cards">
			<h3>Título sobre os cards</h3>
			<ul class="box-window">
				<?
				for ($i=0; $i < 3; $i++) {
				?>
				<li>
					<img src="assets/img/youtube.jpg">
					<h4>Título do item</h4>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</li>
				<?
				}
				?>
			</ul>
		</div>

		<div id="cadastro">
			<div class="box-window">
				<div>
					<h3>Mais um títulos para ser adicionado</h3>

					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugi.</p>
				</div>

				<form class="campos transition" action="acesso">
					<h4>Aproveite e faça seu cadastro agora!</h4>

					<input type="text" name="nome_empresa" placeholder="Nome da Empresa" required>
					<input type="text" name="email_login" placeholder="Email de Acesso" required>
					<button type="button" class="botao redondo">Faça seu cadastro</button>
				</form>
			</div>
		</div>

		<footer>
			<div class="box-window">
				<h3 id="contato">Mande-nos uma mensagem</h3>

				<span class="telefone">(88) 9999-9999</span>
				<span class="email">teste@teste.com</span>

				<ul id="redes-sociais" class="transition">
					<li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
					<li><a href="#"><i class="fab fa-instagram"></i></a></li>
					<li><a href="#"><i class="fab fa-twitter"></i></a></li>
				</ul>

				<span class="direitos">Todos os direitos reservados, Desenvolvido por <a href="#">ItSoftwares</a></span>
			</div>
		</footer>
    </body>

    <!-- <script src="assets/js/index.js?<? echo time(); ?>"></script> -->
    <script type="text/javascript">
	</script>
</html>
