<?php
session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['id'])) and (!isset($_SESSION['nome']))) {
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
  <title>Cadastrar</title>
  <link rel="stylesheet" type="text/css" href="estilos/global.css">
  <link rel="stylesheet" type="text/css" href="estilos/cadastrar.css">
  <!-- Script jQuery de verificação da matrícula digitada -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript">
$(document).ready(function() {
    function verificarMatricula(matricula) {
      $.ajax({
        url: "verificar_matricula.php",
        type: "POST",
        data: { matricula: matricula },
        success: function(response) {
          if (response === "disponivel") {
            $("#msgMatricula").html("<span style='color: lime;'>Disponível.</span>");
            $("#submit").prop("disabled", false);
          } else if (response === "indisponivel") {
            $("#msgMatricula").html("<span style='color: red;'>Já cadastrado.</span>");
            $("#submit").prop("disabled", true);
          } else {
            $("#msgMatricula").html("");
            $("#submit").prop("disabled", true);
          }
        }
      });
    }

    $("#matricula").on("input", function() {
      var matricula = $(this).val();
      if (matricula !== "") {
        verificarMatricula(matricula);
      } else {
        $("#msgMatricula").html("");
        $("#submit").prop("disabled", true);
      }
    });
  });
  </script>
  
</head>

<body>
  <header class="cabecalho">
    <img class="cabecalho-imagem" src="imagens/logo_dados_bsi.png" alt="Logo - Dados BSI">
    <nav class="cabecalho-menu">
      <a class="cabecalho-menu-item" href="index.php">Início</a>
      <a class="cabecalho-menu-item_ativo" href="cadastrar.php">Cadastrar</a>
      <a class="cabecalho-menu-item" href="listar.php">Listar</a>
      <a class="cabecalho-menu-item" href="graficos.php">Gráficos</a>
      <a class="cabecalho-menu-sessao">Bem vindo
        <?php echo $_SESSION['nome']; ?>!
      </a>
      <a class="cabecalho-menu-sair" href="sair.php">Sair</a>
    </nav>
  </header>

  <main class="conteudo">
    <section class="conteudo-primario">

      <form action="gravar.php" method="post">
        <div class="box">
          <fieldset>
            <legend><b>Dados Pessoais</b></legend>
            <br>
            <label for="data_nascimento"><b>Data de Nascimento:</b></label>
            <input type="date" name="data_nascimento" id="data_nascimento" required>
            <br><br>
            <p>Sexo:</p>
            <input type="radio" id="masculino" name="sexo" value="m" required>
            <label for="masculino">M</label>
            <br>
            <input type="radio" id="feminino" name="sexo" value="f" required>
            <label for="feminino">F</label>
            <br><br>
            <p>Naturalidade:</p>
            <input type="radio" id="vca" name="naturalidade" value="vca" required>
            <label for="vca">Vitória da Conquista - BA</label>
            <br>
            <input type="radio" id="200km" name="naturalidade" value="200km" required>
            <label for="200km">Cidades circunvizinhas (200km)</label>
            <br>
            <input type="radio" id="outros" name="naturalidade" value="outros" required>
            <label for="outros">Outros</label>
            <br><br>
          </fieldset>

        </div>

        <div class="box">

          <fieldset>
            <legend><b>Dados Acadêmicos</b></legend>
            <br>
            <div class="inputBox">
              <input type="number" name="matricula" id="matricula" class="inputUser" min="0" required>
              <span id="msgMatricula"></span>
              <label for="matricula" class="labelInput">Matrícula:</label>
            </div>
            <br>
            <p>Forma de Ingresso:</p>
            <input type="radio" id="ampla" name="forma_ingresso" value="ampla" required>
            <label for="ampla">AMPLA CONCORRÊNCIA</label>
            <br>
            <input type="radio" id="cota" name="forma_ingresso" value="cota" required>
            <label for="cota">COTA</label>
            <br>
            <input type="radio" id="outros" name="forma_ingresso" value="outros" required>
            <label for="outros">OUTROS</label>
            <br><br>
            <label for="data_matricula"><b>Data de Matrícula:</b></label>
            <input type="date" name="data_matricula" id="data_matricula" required>
            <br><br><br>
            <div class="inputBox">
              <input type="text" name="periodo_inicial" id="periodo_inicial" class="inputUser" required pattern="^\d{4}\.[12]$" title="Formato: XXXX.X (apenas números).">
              <label for="periodo_inicial" class="labelInput">Período Letivo Inicial:</label>
            </div>
            <br><br>
            <div class="inputBox">
              <input type="text" name="periodo_final" id="periodo_final" class="inputUser" required pattern="^\d{4}\.[12]$" title="Formato: XXXX.X (apenas números).">
              <label for="periodo_final" class="labelInput">Período Letivo Final:</label>
            </div>
            <br><br>
            <label for="data_conclusao"><b>Data de Conclusão:</b></label>
            <input type="date" name="data_conclusao" id="data_conclusao">
            <br>
          </fieldset>

        </div>

        <div class="box">

          <fieldset>
            <legend><b>Componentes Curriculares</b></legend>
            <br>
            <div class="inputBox">
              <input type="number" name="nota_tcc" id="nota_tcc" class="inputUser" min="7.0" max="10.0" step="0.1" required>
              <label for="nota_tcc" class="labelInput">Nota do TCC:</label>
            </div>
            <br><br>
            <div class="inputBox">
              <input type="number" name="numero_reprovacoes" id="numero_reprovacoes" class="inputUser" min="0" required>
              <label for="numero_reprovacoes" class="labelInput">Número de Reprovações:</label>
            </div>
            <br><br>
            <div class="inputBox">
              <input type="number" name="numero_pf" id="numero_pf" class="inputUser" min="0" required>
              <label for="numero_pf" class="labelInput">Número de Aprovações com Provas Finais:</label>
            </div>
            <br><br>
            <div class="inputBox">
              <input type="number" name="ira" id="ira" class="inputUser" min="0" max="10.0" step="0.01" required>
              <label for="ira" class="labelInput">Índice de Rendimento Acadêmico (I.R.A):</label>
            </div>
            <br>
          </fieldset>
        </div>
        <br>
        <fieldset class="estado">
        <p>Estado dos Dados:</p>
        <br>
            <input type="radio" id="completo_historico" name="estado" value="completo_historico" required>
            <label for="completo_historico">Completo (somente Histórico Final)</label>
            <br>
            <input type="radio" id="completo_outras" name="estado" value="completo_outras" required>
            <label for="completo_outras">Completo (necessitou de outras fontes)</label>
            <br>
            <input type="radio" id="incompleto" name="estado" value="incompleto" required>
            <label for="incompleto">Incompleto (dados faltantes)</label>
        </fieldset>
        <br><br>
        <input type="reset" name="reset" id="reset" value="Limpar Dados">
        <br><br>
        <input type="submit" name="submit" id="submit" value="Enviar Dados" disabled>
      </form>
      </div>
    </section>
  </main>
</body>

</html>