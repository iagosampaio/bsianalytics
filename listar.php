<?php
session_start();
ob_start();
include_once 'conexao.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<p style='color: #ff0000; text-align: center;'>Necessário realizar login !</p>";
    header("Location: login.php");
}

if (isset($_GET['matricula']) && !empty($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $consulta = $conexao->prepare("SELECT * FROM aluno WHERE matricula = :matricula ORDER BY matricula ASC");
    $consulta->bindValue(':matricula', $matricula);
    $consulta->execute();
} else {
    $consulta = $conexao->query("SELECT * FROM aluno ORDER BY matricula ASC");
}

$totalLinhas = $consulta->rowCount();

$perPage = 6; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; 

$totalRecords = $consulta->rowCount(); 
$totalPages = ceil($totalRecords / $perPage); 

$offset = ($page - 1) * $perPage; 

if (isset($_GET['matricula']) && !empty($_GET['matricula'])) {
    $consulta = $conexao->prepare("SELECT * FROM aluno WHERE matricula = :matricula ORDER BY matricula ASC LIMIT $perPage OFFSET $offset");
    $consulta->bindValue(':matricula', $matricula);
    $consulta->execute();
} else {
    $consulta = $conexao->query("SELECT * FROM aluno ORDER BY matricula ASC LIMIT $perPage OFFSET $offset");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar</title>
    <link rel="stylesheet" type="text/css" href="estilos/global.css">
    <link rel="stylesheet" type="text/css" href="estilos/listar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script type="text/javascript">
        function searchAluno() {
    var matriculaValue = document.getElementById('matricula').value;
    window.location.href = 'listar.php?matricula=' + encodeURIComponent(matriculaValue);
}

var search = document.getElementById('matricula');

search.addEventListener("keydown", function(event) {
    if (event.key === "Enter") 
    {
        searchAluno();
    }
});
    </script>
</head>

<body>
<header class="cabecalho">
    <img class="cabecalho-imagem" src="imagens/logo_dados_bsi.png" alt="Logo - Dados BSI">
    <nav class="cabecalho-menu">
      <a class="cabecalho-menu-item" href="index.php">Início</a>
      <a class="cabecalho-menu-item" href="cadastrar.php">Cadastrar</a>
      <a class="cabecalho-menu-item_ativo" href="listar.php">Listar</a>
      <a class="cabecalho-menu-item" href="graficos.php">Gráficos</a>
      <a class="cabecalho-menu-sessao">Bem vindo <?php echo $_SESSION['nome']; ?>!</a>
      <a class="cabecalho-menu-sair" href="sair.php">Sair</a>
    </nav>
  </header>

  <main class="conteudo">
  <section class="conteudo-primario">
    <br>
    <div class="box-search">
        <input type="text" id="matricula" class="form-control w-25" placeholder="Pesquisar">
        <button onclick="searchAluno();" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </div>
    <div class="m-4">
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Sexo</th>
                    <th scope="col">Naturalidade</th>
                    <th scope="col">Nascimento</th>
                    <th scope="col">Ingresso</th>
                    <th scope="col">Matrícula</th>
                    <th scope="col">Período Inicial</th>
                    <th scope="col">Período Final</th>
                    <th scope="col">Conclusão</th>
                    <th scope="col">TCC</th>
                    <th scope="col">REP</th>
                    <th scope="col">AP com PF</th>
                    <th scope="col">I.R.A</th>
                    <th scope="col">...</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

                        echo "<tr>";
                        echo "<td>".$linha['matricula']."</td>";
                        echo "<td>".$linha['sexo']."</td>";
                        echo "<td>".$linha['naturalidade']."</td>";
                        echo "<td>".date('d/m/Y', strtotime($linha['data_nascimento']))."</td>";
                        echo "<td>".$linha['forma_ingresso']."</td>";
                        echo "<td>".date('d/m/Y', strtotime($linha['data_matricula']))."</td>";
                        echo "<td>".$linha['periodo_inicial']."</td>";
                        echo "<td>".$linha['periodo_final']."</td>";
                        echo "<td>".(strtotime($linha['data_conclusao']) > 0 ? date('d/m/Y', strtotime($linha['data_conclusao'])) : '') ."</td>";
                        echo "<td>".$linha['nota_tcc']."</td>";
                        echo "<td>".$linha['numero_reprovacoes']."</td>";
                        echo "<td>".$linha['numero_pf']."</td>";
                        echo "<td>".$linha['ira']."</td>";
                        echo "<td>
                        <a class='btn btn-sm btn-primary' href='editar.php?matricula=$linha[matricula]' title='Editar'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                                <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                            </svg>
                            </a> 
                            <a class='btn btn-sm btn-danger' href='deletar.php?matricula=$linha[matricula]' title='Deletar'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                                </svg>
                            </a>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
            </tbody>
            <tfoot>
        <tr>
            <th scope="col">Total: <?php echo $totalLinhas; ?></th>
        </tr>
    </tfoot>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php if (isset($_GET['matricula'])) echo '&matricula=' . $_GET['matricula']; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>
</section>
</main>
</body>
</html>