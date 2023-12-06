<?php
include_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matricula'])) {
  $matricula = $_POST['matricula'];

  // Verificar se a matrícula já existe no banco de dados
  $sql_verificar_matricula = "SELECT COUNT(*) AS count FROM aluno WHERE matricula = :matricula";
  $stmt_verificar_matricula = $conexao->prepare($sql_verificar_matricula);
  $stmt_verificar_matricula->bindValue(':matricula', $matricula);
  $stmt_verificar_matricula->execute();
  $result_verificar_matricula = $stmt_verificar_matricula->fetch(PDO::FETCH_ASSOC);

  if ($result_verificar_matricula['count'] > 0) {
    echo "indisponivel";
  } else {
    echo "disponivel";
  }
}
?>
