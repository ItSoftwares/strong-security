<?php
// header("Location: /manutencao.php");
function verificarSeSessaoExpirou($tipo) {
    $token = md5('sat'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

    if(!isset($_SESSION)){session_start();}

    $duracao = 1000;

    $agora = time();
    
    if (!array_key_exists($tipo, $_SESSION)) {
        session_unset();

        $_SESSION['erro_msg'] = "Faça Login ou cadastre-se!";
        if ($tipo=='adm') header('Location: /adm/login');
        else header('location: /acesso');
        exit;
    } else if ($_SESSION['expire'] + $duracao * 60 < $agora) {
        session_unset();
        $_SESSION['info_msg'] = "Sua sessão expirou faça Login novamente!";

        if ($tipo=='adm') header('Location: /adm/login');
        else header('location: /acesso');
        exit;
    }
}

verificarSeSessaoExpirou($tipo);
?>