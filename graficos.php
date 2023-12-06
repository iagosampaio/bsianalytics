<?php
session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['id'])) and (!isset($_SESSION['nome']))) {
  $_SESSION['msg'] = "<p style='color: #ff0000; text-align: center;'>Necessário realizar login !</p>";
  header("Location: login.php");
}

$contadores = [
  'sexo' => ['m' => 0, 'f' => 0],
  'naturalidade' => ['vca' => 0, '200km' => 0, 'outros' => 0],
  'forma_ingresso' => ['ampla' => 0, 'cota' => 0, 'outros' => 0],
  'idade_matricula' => ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0],
  'idade_conclusao' => ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0],
  'semestres' => ['8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0],
  'numero_pf' => ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0],
  'numero_reprovacoes' => ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0],
  'nota_tcc' => ['a' => 0, 'b' => 0, 'c' => 0],
  'ira' => ['a' => 0, 'b' => 0, 'c' => 0, 'd' => 0]
];

function calcularMedia($values) {
  $filteredValues = array_filter($values, function ($value) {
      return $value !== null && $value !== '';
  });

  return count($filteredValues) > 0 ? array_sum($filteredValues) / count($filteredValues) : null;
}

function calcularModa($values) {
  $filteredValues = array_filter($values, function ($value) {
      return $value !== null && $value !== '';
  });

  if (empty($filteredValues)) {
      return null;
  }

  $counts = array_count_values($filteredValues);
  $maxFrequency = max($counts);

  $modes = array_keys($counts, $maxFrequency);

  return $modes;
}

// Arrays para armazenar os valores de cada categoria

$matricula_values = [];
$conclusao_values = [];
$semestres_values = [];
$pf_values = [];
$rep_values = [];
$tcc_values = [];
$ira_values = [];

$consulta = $conexao->query("SELECT * FROM aluno");
while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    
    $contadores['sexo'][$linha['sexo']]++;
    $contadores['naturalidade'][$linha['naturalidade']]++;
    $contadores['forma_ingresso'][$linha['forma_ingresso']]++;
    $contadores['semestres'][$linha['semestres']]++;
    
    $idadeMatricula = $linha['idade_matricula'];
    if ($idadeMatricula >= 16 && $idadeMatricula <= 21) {
        $contadores['idade_matricula']['a']++;
    } elseif ($idadeMatricula >= 22 && $idadeMatricula <= 26) {
        $contadores['idade_matricula']['b']++;
    } elseif ($idadeMatricula >= 27 && $idadeMatricula <= 30) {
        $contadores['idade_matricula']['c']++;
    } else {
        $contadores['idade_matricula']['d']++;
    }

    $idadeConclusao = $linha['idade_conclusao'];
    if ($idadeConclusao !== null) {
        if ($idadeConclusao >= 20 && $idadeConclusao <= 22) {
            $contadores['idade_conclusao']['a']++;
        } elseif ($idadeConclusao >= 23 && $idadeConclusao <= 25) {
            $contadores['idade_conclusao']['b']++;
        } elseif ($idadeConclusao >= 26 && $idadeConclusao <= 30) {
            $contadores['idade_conclusao']['c']++;
        } else {
            $contadores['idade_conclusao']['d']++;
        }
    }
    
    $numeroPF = $linha['numero_pf'];
    if ($numeroPF == 0) {
        $contadores['numero_pf']['a']++;
    } elseif ($numeroPF >= 1 && $numeroPF <= 3) {
        $contadores['numero_pf']['b']++;
    } elseif ($numeroPF >= 4 && $numeroPF <= 6) {
        $contadores['numero_pf']['c']++;
    } else {
        $contadores['numero_pf']['d']++;
    }

    $numeroREP = $linha['numero_reprovacoes'];
    if ($numeroREP == 0) {
        $contadores['numero_reprovacoes']['a']++;
    } elseif ($numeroREP >= 1 && $numeroREP <= 3) {
        $contadores['numero_reprovacoes']['b']++;
    } elseif ($numeroREP >= 4 && $numeroREP <= 6) {
        $contadores['numero_reprovacoes']['c']++;
    } else {
        $contadores['numero_reprovacoes']['d']++;
    }

    $notaTCC = $linha['nota_tcc'];
    if ($notaTCC !== '') {
        if ($notaTCC >= 7.0 && $notaTCC <= 8.0) {
            $contadores['nota_tcc']['a']++;
        } elseif ($notaTCC >= 8.1 && $notaTCC <= 9.0) {
            $contadores['nota_tcc']['b']++;
        } else {
            $contadores['nota_tcc']['c']++;
        }
    }

    $IRA = $linha['ira'];
    if ($IRA < 5.00) {
        $contadores['ira']['a']++;
    } elseif ($IRA >= 5.00 && $IRA <= 7.00) {
        $contadores['ira']['b']++;
    } elseif ($IRA >= 7.01 && $IRA <= 9.00) {
        $contadores['ira']['c']++;
    } else {
        $contadores['ira']['d']++;
    }
    
    $matricula_values[] = $linha['idade_matricula'];
    $conclusao_values[] = $linha['idade_conclusao'];
    $semestres_values[] = $linha['semestres'];
    $pf_values[] = $linha['numero_pf'];
    $rep_values[] = $linha['numero_reprovacoes'];
    $tcc_values[] = $linha['nota_tcc'];
    $ira_values[] = $linha['ira'];
}

$sexo_values = ['Masculino' => $contadores['sexo']['m'], 'Feminino' => $contadores['sexo']['f']];
$naturalidade_values = ['Vitória da Conquista' => $contadores['naturalidade']['vca'], 'Circunvizinhas (raio de 200km)' => $contadores['naturalidade']['200km'], 'Outros' => $contadores['naturalidade']['outros']];
$ingresso_values = ['Ampla Concorrência' => $contadores['forma_ingresso']['ampla'], 'Cota' => $contadores['forma_ingresso']['cota'], 'Outros' => $contadores['forma_ingresso']['outros']];

// Calcular as médias
$media_matricula = calcularMedia($matricula_values);
$media_conclusao = calcularMedia($conclusao_values);
$media_semestres = calcularMedia($semestres_values);
$media_pf = calcularMedia($pf_values);
$media_rep = calcularMedia($rep_values);
$media_tcc = calcularMedia($tcc_values);
$media_ira = calcularMedia($ira_values);

// Calcular as modas
$moda_sexo = array_keys($sexo_values, max($sexo_values));
$moda_naturalidade = array_keys($naturalidade_values, max($naturalidade_values));
$moda_ingresso = array_keys($ingresso_values, max($ingresso_values));
$moda_matricula = calcularModa($matricula_values);
$moda_matricula = calcularModa($matricula_values);
$moda_conclusao = calcularModa($conclusao_values);
$moda_pf = calcularModa($pf_values);
$moda_rep = calcularModa($rep_values);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gráficos</title>
  <link rel="stylesheet" type="text/css" href="estilos/global.css">
  <link rel="stylesheet" type="text/css" href="estilos/graficos.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        // Gráfico 1: Idade na Matrícula
      var data = google.visualization.arrayToDataTable([
        ["Faixa Etária", "Concluintes", { role: "style" } ],
        ["Mais de 30 anos", <?php echo $contadores['idade_matricula']['d']; ?>, "red"],
        ["27 a 30 anos", <?php echo $contadores['idade_matricula']['c']; ?>, "orangered"],
        ["22 a 26 anos", <?php echo $contadores['idade_matricula']['b']; ?>, "yellow"],
        ["16 a 21 anos", <?php echo $contadores['idade_matricula']['a']; ?>, "lime"]
      ]);

      var viewIdadeMatricula = new google.visualization.DataView(data);
      viewIdadeMatricula.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsIdadeMatricula = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'80%',height:'85%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartIdadeMatricula = new google.visualization.BarChart(document.getElementById("grafico_matricula"));
      chartIdadeMatricula.draw(viewIdadeMatricula, optionsIdadeMatricula);
        
        // Gráfico 2: Sexo
        var dataSexo = google.visualization.arrayToDataTable([
        ['Sexo', 'Concluintes'],
        ['Masculino', <?php echo $contadores['sexo']['m']; ?>],
        ['Feminino', <?php echo $contadores['sexo']['f']; ?>]
      ]);
      var optionsSexo = {
        pieSliceTextStyle: {fontName: 'Arial', fontSize: 20, bold: true},
        is3D: true,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        width: 600,
        height: 400,
        pieSliceText: "value",
        tooltipText: 'both',
        legend: {position: "top", alignment: "center"},
        chartArea:{width:'100%',height:'85%'},
        slices: {
          0: { color: 'blue' },
          1: { color: 'magenta' }
        }
      };
      var chartSexo = new google.visualization.PieChart(document.getElementById('grafico_sexo'));
      chartSexo.draw(dataSexo, optionsSexo);
      
      // Gráfico 3: Naturalidade
      var data = google.visualization.arrayToDataTable([
        ["Naturalidade", "Concluintes", { role: "style" } ],
        ["Vitória da Conquista", <?php echo $contadores['naturalidade']['vca']; ?>, "chocolate"],
        ["Circunvizinhas (raio de 200km)", <?php echo $contadores['naturalidade']['200km']; ?>, "burlywood"],
        ["Outros", <?php echo $contadores['naturalidade']['outros']; ?>, "moccasin"]
      ]);

      var viewNaturalidade = new google.visualization.DataView(data);
      viewNaturalidade.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsNaturalidade = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'90%',height:'76%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartNaturalidade = new google.visualization.ColumnChart(document.getElementById("grafico_naturalidade"));
      chartNaturalidade.draw(viewNaturalidade, optionsNaturalidade);

      // Gráfico 4: Forma de Ingresso
      var data = google.visualization.arrayToDataTable([
        ["Forma de Ingresso", "Concluintes", { role: "style" } ],
        ["Ampla", <?php echo $contadores['forma_ingresso']['ampla']; ?>, "lime"],
        ["Cota", <?php echo $contadores['forma_ingresso']['cota']; ?>, "deeppink"],
        ["Outros", <?php echo $contadores['forma_ingresso']['outros']; ?>, "darkgray"]
      ]);

      var viewIngresso = new google.visualization.DataView(data);
      viewIngresso.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsIngresso = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'86%',height:'76%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartIngresso = new google.visualization.ColumnChart(document.getElementById("grafico_ingresso"));
      chartIngresso.draw(viewIngresso, optionsIngresso);

      // Gráfico 5: Reprovações
      var data = google.visualization.arrayToDataTable([
        ["Reprovações", "Concluintes", { role: "style" } ],
        ["Mais de 6 Reprovações", <?php echo $contadores['numero_reprovacoes']['d']; ?>, "red"],
        ["4 a 6 Reprovações", <?php echo $contadores['numero_reprovacoes']['c']; ?>, "orangered"],
        ["1 a 3 Reprovações", <?php echo $contadores['numero_reprovacoes']['b']; ?>, "yellow"],
        ["Nenhuma", <?php echo $contadores['numero_reprovacoes']['a']; ?>, "lime"]
      ]);

      var viewREP = new google.visualization.DataView(data);
      viewREP.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsREP = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'69%',height:'83%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartREP = new google.visualization.BarChart(document.getElementById("grafico_rep"));
      chartREP.draw(viewREP, optionsREP);

      // Gráfico 6: Provas Finais
      var data = google.visualization.arrayToDataTable([
        ["Provas Finais Realizadas", "Concluintes", { role: "style" } ],
        ["Mais de 6 Finais", <?php echo $contadores['numero_pf']['d']; ?>, "red"],
        ["4 a 6 Finais", <?php echo $contadores['numero_pf']['c']; ?>, "orangered"],
        ["1 a 3 Finais", <?php echo $contadores['numero_pf']['b']; ?>, "yellow"],
        ["Nenhuma", <?php echo $contadores['numero_pf']['a']; ?>, "lime"]
      ]);

      var viewPF = new google.visualization.DataView(data);
      viewPF.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsPF = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'72%',height:'82%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartPF = new google.visualization.BarChart(document.getElementById("grafico_pf"));
      chartPF.draw(viewPF, optionsPF);

      // Gráfico 7: Nota TCC
      var dataTCC = google.visualization.arrayToDataTable([
        ['Nota', 'Concluintes'],
        ['7.0 a 8.0', <?php echo $contadores['nota_tcc']['a']; ?>],
        ['8.1 a 9.0', <?php echo $contadores['nota_tcc']['b']; ?>],
        ['9.1 a 10.0', <?php echo $contadores['nota_tcc']['c']; ?>]
      ]);
      var optionsTCC = {
        pieSliceTextStyle: {fontName: 'Arial', fontSize: 20, bold: true},
        is3D: true,
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        pieSliceText: "value",
        tooltipText: 'both',
        legend: {position: "top", alignment: "center"},
        chartArea:{width:'100%',height:'85%'},
        slices: {
          0: { color: 'orchid' },
          1: { color: 'mediumorchid' },
          2: { color: 'darkorchid' }
        }
      };
      var chartTCC = new google.visualization.PieChart(document.getElementById('grafico_tcc'));
      chartTCC.draw(dataTCC, optionsTCC);

      // Gráfico 8: I.R.A
      var data = google.visualization.arrayToDataTable([
        ["I.R.A", "Concluintes", { role: "style" } ],
        ["9.01 a 10.00", <?php echo $contadores['ira']['d']; ?>, "lime"],
        ["7.01 a 9.00", <?php echo $contadores['ira']['c']; ?>, "yellow"],
        ["5.00 a 7.00", <?php echo $contadores['ira']['b']; ?>, "orangered"],
        ["Abaixo de 5.00", <?php echo $contadores['ira']['a']; ?>, "red"]
      ]);

      var viewIRA = new google.visualization.DataView(data);
      viewIRA.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsIRA = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'82%',height:'82%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartIRA = new google.visualization.BarChart(document.getElementById("grafico_ira"));
      chartIRA.draw(viewIRA, optionsIRA);

      // Gráfico 9: Idade na Conclusão
      var data = google.visualization.arrayToDataTable([
        ["Faixa Etária", "Concluintes", { role: "style" } ],
        ["Mais de 30 anos", <?php echo $contadores['idade_conclusao']['d']; ?>, "red"],
        ["26 a 30 anos", <?php echo $contadores['idade_conclusao']['c']; ?>, "orangered"],
        ["23 a 25 anos", <?php echo $contadores['idade_conclusao']['b']; ?>, "yellow"],
        ["20 a 22 anos", <?php echo $contadores['idade_conclusao']['a']; ?>, "lime"]
      ]);

      var viewIdadeConclusao = new google.visualization.DataView(data);
      viewIdadeConclusao.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var optionsIdadeConclusao = {
        annotations: {textStyle: {fontName: 'Arial', fontSize: 20, bold: true}},
        width: 600,
        height: 400,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        chartArea:{width:'80%',height:'82%'},
        bar: {groupWidth: "75%"},
        legend: 'none',
      };
      var chartIdadeConclusao = new google.visualization.BarChart(document.getElementById("grafico_conclusao"));
      chartIdadeConclusao.draw(viewIdadeConclusao, optionsIdadeConclusao);

      // Gráfico 10: Quantidade de Semestres
      var dataSemestres = google.visualization.arrayToDataTable([
        ['Quantidade de Semestres', 'Concluintes'],
        ['8', <?php echo $contadores['semestres']['8']; ?>],
        ['9', <?php echo $contadores['semestres']['9']; ?>],
        ['10', <?php echo $contadores['semestres']['10']; ?>],
        ['11', <?php echo $contadores['semestres']['11']; ?>],
        ['12', <?php echo $contadores['semestres']['12']; ?>],
        ['13', <?php echo $contadores['semestres']['13']; ?>],
        ['14', <?php echo $contadores['semestres']['14']; ?>],
        ['15', <?php echo $contadores['semestres']['15']; ?>],
        ['16', <?php echo $contadores['semestres']['16']; ?>],
        ['17', <?php echo $contadores['semestres']['17']; ?>],
        ['18', <?php echo $contadores['semestres']['18']; ?>],
        ['19', <?php echo $contadores['semestres']['19']; ?>],
        ['20', <?php echo $contadores['semestres']['20']; ?>],
        ['21', <?php echo $contadores['semestres']['21']; ?>]
      ]);
      var optionsSemestres = {
        pieSliceTextStyle: {fontName: 'Arial', fontSize: 20, bold: true},
        is3D: true,
        backgroundColor: {strokeWidth:10, stroke:'dodgerblue'},
        width: 600,
        height: 400,
        pieSliceText: "value",
        tooltipText: 'both',
        legend: {position: "left", alignment: "center"},
        chartArea:{width:'85%',height:'85%'},
        slices: {
          0: { color: 'blue' },
          1: { color: 'magenta' },
          2: { color: 'lime' },
          3: { color: 'yellow' },
          4: { color: 'orangered' },
          5: { color: 'red' },
          6: { color: 'maroon' },
          7: { color: 'darkred' },
          8: { color: 'black' },
          9: { color: 'olive' },
          10: { color: 'cyan' },
          11: { color: 'gray' },
          12: { color: 'purple' },
          13: { color: 'brown' }
        }
      };
      var chartSemestres = new google.visualization.PieChart(document.getElementById('grafico_semestres'));
      chartSemestres.draw(dataSemestres, optionsSemestres);

    }
    </script>
  </head>
  <body>

<header class="cabecalho">
   <img class="cabecalho-imagem" src="imagens/logo_dados_bsi.png" alt="Logo - Dados BSI">
   <nav class="cabecalho-menu">
     <a class="cabecalho-menu-item" href="index.php">Início</a>
     <a class="cabecalho-menu-item" href="cadastrar.php">Cadastrar</a>
     <a class="cabecalho-menu-item" href="listar.php">Listar</a>
     <a class="cabecalho-menu-item_ativo" href="graficos.php">Gráficos</a>
     <a class="cabecalho-menu-sessao">Bem vindo <?php echo $_SESSION['nome']; ?>!</a>
     <a class="cabecalho-menu-sair" href="sair.php">Sair</a>
   </nav>
</header>

 <main class="conteudo">
 <section class="conteudo-primario">
 <div class="container">
 <div class="box">
       <fieldset>
           <legend><b>Selecione um gráfico:</b></legend>
           <p><b>Início do Curso</b></p>
           <input type="radio" name="graficoSelecionado" value="matricula" checked>
           <label>Idade na Matrícula</label><br>
           <input type="radio" name="graficoSelecionado" value="sexo"> 
           <label>Sexo</label><br> 
           <input type="radio" name="graficoSelecionado" value="naturalidade">
           <label>Naturalidade </label><br>  
           <input type="radio" name="graficoSelecionado" value="ingresso">
           <label>Forma de Ingresso</label><br><br>
           <p><b>Meio do Curso</b></p>
           <input type="radio" name="graficoSelecionado" value="rep"> 
           <label>Nº de Reprovações</label><br>
           <input type="radio" name="graficoSelecionado" value="pf"> 
           <label>Nº de Aprovações com Provas Finais</label><br><br>
           <p><b>Fim do Curso</b></p>
           <input type="radio" name="graficoSelecionado" value="tcc"> 
           <label>Nota do TCC</label><br>
           <input type="radio" name="graficoSelecionado" value="ira"> 
           <label>Índice de Rendimento</label><br>
           <input type="radio" name="graficoSelecionado" value="conclusao"> 
           <label>Idade na Conclusão</label><br>
           <input type="radio" name="graficoSelecionado" value="semestres"> 
           <label>Quantidade de Semestres</label><br>
       </fieldset>
   </div>

       <!-- Área de visualização dos gráficos -->
       <div class="graphs">
           <div class="grafico-container" id="grafico_sexo"></div>
           <div class="grafico-info" id="grafico_info_sexo">  
           <p class="estatisticas">Moda: <?php echo implode(', ', $moda_sexo); ?></p> 
           </div>
          
           <div class="grafico-container" id="grafico_tcc"></div>
               <div class="grafico-info" id="grafico_info_tcc">
                   <p class="estatisticas">Média: <?php echo round($media_tcc, 2); ?></p>   
           </div>

           <div class="grafico-container" id="grafico_naturalidade"></div>
               <div class="grafico-info" id="grafico_info_naturalidade">
                   <p class="estatisticas">Moda: <?php echo implode(', ', $moda_naturalidade); ?></p>
           </div>

           <div class="grafico-container" id="grafico_ingresso"></div>
               <div class="grafico-info" id="grafico_info_ingresso">
                   <p class="estatisticas">Moda: <?php echo implode(', ', $moda_ingresso); ?></p> 
           </div>

           <div class="grafico-container" id="grafico_matricula"></div>
               <div class="grafico-info" id="grafico_info_matricula">
                   <p class="estatisticas">Média: <?php echo round($media_matricula, 2); ?> &nbsp; / &nbsp; Moda: <?php echo implode(', ', $moda_matricula); ?></p> 
           </div>

           <div class="grafico-container" id="grafico_conclusao"></div>
               <div class="grafico-info" id="grafico_info_conclusao">
                   <p class="estatisticas">Média: <?php echo round($media_conclusao, 2); ?> &nbsp; / &nbsp; Moda: <?php echo implode(', ', $moda_conclusao); ?></p> 
           </div>

           <div class="grafico-container" id="grafico_semestres"></div>
               <div class="grafico-info" id="grafico_info_semestres">
                   <p class="estatisticas">Média: <?php echo round($media_semestres, 2); ?></p> 
           </div>

           <div class="grafico-container" id="grafico_pf"></div>
               <div class="grafico-info" id="grafico_info_pf">
                   <p class="estatisticas">Média: <?php echo round($media_pf, 2); ?> &nbsp; / &nbsp; Moda: <?php echo implode(', ', $moda_pf); ?></p>   
           </div>

           <div class="grafico-container" id="grafico_rep"></div>
               <div class="grafico-info" id="grafico_info_rep">
                   <p class="estatisticas">Média: <?php echo round($media_rep, 2); ?> &nbsp; / &nbsp; Moda: <?php echo implode(', ', $moda_rep); ?></p> 
           </div>

           <div class="grafico-container" id="grafico_ira"></div>
               <div class="grafico-info" id="grafico_info_ira">
                   <p class="estatisticas">Média: <?php echo round($media_ira, 2); ?></p> 
           </div>
       </div>
       </div>
</div>
   </section>
</main>
<script type="text/javascript">
  // Script que mostra o gráfico selecionado pelo botão
const radioButtons = document.querySelectorAll('input[type="radio"][name="graficoSelecionado"]');
const graphContainers = document.querySelectorAll('.grafico-container');
const graficoInfoContainers = document.querySelectorAll('.grafico-info');

function mostrarGraficoPadrao() {
    const graficoSelecionado = document.querySelector('input[type="radio"][name="graficoSelecionado"]:checked');
    if (graficoSelecionado) {
        const selectedValue = graficoSelecionado.value;
        const selectedGraph = document.getElementById(`grafico_${selectedValue}`);
        const selectedGraphInfo = document.getElementById(`grafico_info_${selectedValue}`);
        if (selectedGraph && selectedGraphInfo) {
            selectedGraph.classList.remove('hide');
            selectedGraphInfo.classList.remove('hide');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    graphContainers.forEach(container => {
        container.classList.add('hide');
    });
    graficoInfoContainers.forEach(info => {
        info.classList.add('hide');
    });

    mostrarGraficoPadrao();

    radioButtons.forEach(button => {
        button.addEventListener('change', () => {
            
            graphContainers.forEach(container => {
                container.classList.add('hide');
            });
            graficoInfoContainers.forEach(info => {
                info.classList.add('hide');
            });

            mostrarGraficoPadrao();
        });
    });
});
</script>
</body>
</html>