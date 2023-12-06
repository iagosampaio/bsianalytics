<?php
session_start();
ob_start();
include_once 'conexao.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<p style='color: #ff0000; text-align: center;'>Necessário realizar login !</p>";
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BSI Analytics</title>
  <link rel="stylesheet" type="text/css" href="estilos/global.css">
  <link rel="stylesheet" type="text/css" href="estilos/index.css">
</head>

<body>
  <header class="cabecalho">
    <img class="cabecalho-imagem" src="imagens/logo_dados_bsi.png" alt="Logo - BSI Analytics">
    <nav class="cabecalho-menu">
      <a class="cabecalho-menu-item_ativo" href="index.php">Início</a>
      <a class="cabecalho-menu-item" href="cadastrar.php">Cadastrar</a>
      <a class="cabecalho-menu-item" href="listar.php">Listar</a>
      <a class="cabecalho-menu-item" href="graficos.php">Gráficos</a>
      <a class="cabecalho-menu-sessao">Bem vindo <?php echo $_SESSION['nome']; ?>!</a>
      <a class="cabecalho-menu-sair" href="sair.php">Sair</a>
    </nav>
  </header>

<main class="conteudo">

 <section class="conteudo-primario">
      <h3 class="conteudo-primario-titulo">BSI Analytics:</h3>
      <p class="conteudo-primario-paragrafo">1. Cadastrar: <strong>Permite registrar os concluintes no Banco de Dados</strong></p>
      <p class="conteudo-primario-paragrafo">2. Listar: <strong>Permite visualizar os registros, além de editar ou remover</strong></p>
      <p class="conteudo-primario-paragrafo">3. Gráficos: <strong>Permite filtrar gráficos, para realizar as análises</strong></p>
      <button class="botao-repositorio">Repositório</button>
      <div></div>
   </section>
  </main>
  <footer class="rodape">
    <img class="rodape-imagem" src="imagens/logo_ifba_vca.png">
  </footer>
</body>

</html>