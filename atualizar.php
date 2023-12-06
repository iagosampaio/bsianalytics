<?php
session_start();
ob_start();
include_once 'conexao.php';

if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    $_SESSION['msg'] = "<p style='color: #ff0000; text-align: center;'>Necessário realizar login !</p>";
    header("Location: login.php");
}

$nasc = new DateTime($_POST['data_nascimento']);
$matr = new DateTime($_POST['data_matricula']);
$conc = new DateTime($_POST['data_conclusao']);

$idade_matricula = $matr->diff($nasc)->y;
$idade_conclusao = $conc->diff($nasc)->y;

$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

list($ano_inicial, $semestre_inicial) = explode('.', $periodo_inicial);
list($ano_final, $semestre_final) = explode('.', $periodo_final);

$total_semestres_inicial = ($ano_inicial - 1) * 2 + $semestre_inicial;
$total_semestres_final = ($ano_final - 1) * 2 + $semestre_final;

$semestres = ($total_semestres_final - $total_semestres_inicial) + 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit']) && $_POST['submit'] == "Atualizar Dados") {
    try {
     
        $stmt = $conexao->prepare('UPDATE aluno SET sexo = :sexo, naturalidade = :naturalidade, data_nascimento = :data_nascimento, forma_ingresso = :forma_ingresso, data_matricula = :data_matricula, periodo_inicial = :periodo_inicial, periodo_final = :periodo_final, data_conclusao = :data_conclusao, nota_tcc = :nota_tcc, numero_reprovacoes = :numero_reprovacoes, numero_pf = :numero_pf, ira = :ira, idade_matricula = :idade_matricula, idade_conclusao = :idade_conclusao, semestres = :semestres, estado = :estado WHERE matricula = :matricula');
       
        // Bind parameters
        $stmt->bindParam(':matricula', $_POST['matricula']);
        $stmt->bindParam(':sexo', $_POST['sexo']);
        $stmt->bindParam(':naturalidade', $_POST['naturalidade']);
        $stmt->bindParam(':data_nascimento', $_POST['data_nascimento']);
        $stmt->bindParam(':forma_ingresso', $_POST['forma_ingresso']);
        $stmt->bindParam(':data_matricula', $_POST['data_matricula']);
        $stmt->bindParam(':periodo_inicial', $_POST['periodo_inicial']);
        $stmt->bindParam(':periodo_final', $_POST['periodo_final']);
        $stmt->bindParam(':data_conclusao', $_POST['data_conclusao']);
        $stmt->bindParam(':nota_tcc', $_POST['nota_tcc']);   
        $stmt->bindParam(':numero_reprovacoes', $_POST['numero_reprovacoes']);
        $stmt->bindParam(':numero_pf', $_POST['numero_pf']);  
        $stmt->bindParam(':ira', $_POST['ira']);
        $stmt->bindParam(':idade_matricula', $idade_matricula);
        $stmt->bindParam(':idade_conclusao', $idade_conclusao);
        $stmt->bindParam(':semestres', $semestres);
        $stmt->bindParam(':estado', $_POST['estado']);

        // Executa a atualização
        $stmt->execute();
        
        echo "<script>alert('Matrícula {$_POST['matricula']} atualizada com sucesso.'); window.location.assign('listar.php');</script>";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        echo "<script>alert('Erro ao atualizar a matrícula {$_POST['matricula']}.'); window.location.assign('listar.php');</script>";
    }
  } else {
    echo "<script>window.location.assign('listar.php');</script>";
}
}
?>
