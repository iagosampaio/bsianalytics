<?php
session_start();
ob_start();
include_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="estilos/login.css">
</head>
    
<body>
    <?php
    //criptografar a senha
    //echo password_hash('senha_aqui', PASSWORD_DEFAULT);
    ?>
<div>
    <img class="logo" src="imagens/logo_dados_bsi.png" alt="Logo - Dados BSI">
    <h1>Login</h1>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['submit'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id, nome, usuario, senha_usuario 
                        FROM usuarios 
                        WHERE usuario =:usuario  
                        LIMIT 1";
        $result_usuario = $conexao->prepare($query_usuario);
        $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
        $result_usuario->execute();

        if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_usuario);
            if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])){
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['nome'] = $row_usuario['nome'];
                header("Location: index.php");
            }else{
                $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Usuário ou senha inválida!</p>";
            }
        }else{
            $_SESSION['msg'] = "<p style='color: #ff0000'>Erro: Usuário ou senha inválida!</p>";
        }

        
    }

    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="usuario" placeholder="Usuário" value="<?php if(isset($dados['usuario'])){ echo $dados['usuario']; } ?>">
        <br><br>
        <input type="password" name="senha_usuario" placeholder="Senha" value="<?php if(isset($dados['senha_usuario'])){ echo $dados['senha_usuario']; } ?>">
        <br><br>
        <input class="inputSubmit" type="submit" name="submit" value="Acessar">
    </form>
</div>
</body>

</html>