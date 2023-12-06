<?php
session_start();
include_once 'conexao.php';

$nasc = new DateTime($_POST['data_nascimento']);
$matr = new DateTime($_POST['data_matricula']);
$conc = new DateTime($_POST['data_conclusao']);

$idade_matricula = $matr->diff($nasc)->y;
$idade_conclusao = $conc->diff($nasc)->y;

// Convertendo os períodos letivos para semestres
$periodo_inicial = $_POST['periodo_inicial'];
$periodo_final = $_POST['periodo_final'];

// Extração do ano e semestre dos períodos
list($ano_inicial, $semestre_inicial) = explode('.', $periodo_inicial);
list($ano_final, $semestre_final) = explode('.', $periodo_final);

// Convertendo para semestres e calculando a diferença
$total_semestres_inicial = ($ano_inicial - 1) * 2 + $semestre_inicial;
$total_semestres_final = ($ano_final - 1) * 2 + $semestre_final;

// Correção do cálculo para considerar o semestre 2 do ano anterior como o semestre 1 do ano seguinte
$semestres = ($total_semestres_final - $total_semestres_inicial) + 1;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'];

    // Verificar se a matrícula já existe no banco de dados
    $sql_verificar_matricula = "SELECT COUNT(*) AS count FROM aluno WHERE matricula = :matricula";
    $stmt_verificar_matricula = $conexao->prepare($sql_verificar_matricula);
    $stmt_verificar_matricula->bindValue(':matricula', $matricula);
    $stmt_verificar_matricula->execute();
    $result_verificar_matricula = $stmt_verificar_matricula->fetch(PDO::FETCH_ASSOC);

    // insert
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=alunos', 'root', null);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO aluno VALUES(:matricula, :sexo, :naturalidade, :data_nascimento, :forma_ingresso, :data_matricula, :periodo_inicial, :periodo_final, :data_conclusao, :nota_tcc, :numero_reprovacoes, :numero_pf, :ira, :idade_matricula, :idade_conclusao, :semestres, :estado)');
        $stmt->execute(array(
            ':matricula' => $matricula,
            ':sexo' => $_POST["sexo"],
            ':naturalidade' => $_POST["naturalidade"],
            ':data_nascimento' => $_POST["data_nascimento"],
            ':forma_ingresso' => $_POST["forma_ingresso"],
            ':data_matricula' => $_POST["data_matricula"],
            ':periodo_inicial' => $_POST["periodo_inicial"],
            ':periodo_final' => $_POST["periodo_final"],
            ':data_conclusao' => $_POST["data_conclusao"],
            ':nota_tcc' => $_POST["nota_tcc"],
            ':numero_reprovacoes' => $_POST["numero_reprovacoes"],
            ':numero_pf' => $_POST["numero_pf"], 
            ':ira' => $_POST["ira"],
            ':idade_matricula' => $idade_matricula,
            ':idade_conclusao' => $idade_conclusao,
            ':semestres' => $semestres,
            ':estado' => $_POST["estado"]
        ));
        echo("<script>alert('Dados registrados com sucesso.');window.location.assign('cadastrar.php')</script>");
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        echo("<script>alert('Dados não registrados.');window.location.assign('cadastrar.php')</script>");
    }
}
?>