<?php
session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['id'])) and (!isset($_SESSION['nome']))) {
  $_SESSION['msg'] = "<p style='color: #ff0000; text-align: center;'>Necessário realizar login !</p>";
  header("Location: login.php");
}

$matricula = $_GET["matricula"];

try {
   
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
        $stmt = $conexao->prepare('DELETE FROM aluno WHERE matricula = :matricula');
        $stmt->bindParam(':matricula', $matricula);
        $stmt->execute();
        echo ("<script>alert('Matrícula $matricula excluída com sucesso.');window.location.assign('listar.php');</script>");
    } else {
        echo "<script>
                if (confirm('Tem certeza que deseja excluir a matrícula $matricula ?')) {
                    window.location.href = 'deletar.php?matricula=$matricula&confirm=true';
                } else {
                    window.location.href = 'listar.php';
                }
              </script>";
    }
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    echo ("<script>alert('Erro ao tentar excluir a matricula $matricula.');window.location.assign('listar.php');</script>");
}
?>
