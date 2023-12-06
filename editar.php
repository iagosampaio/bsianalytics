<?php
session_start();
ob_start();
include_once 'conexao.php';

if ((!isset($_SESSION['id'])) and (!isset($_SESSION['nome']))) {
  $_SESSION['msg'] = "<p style='color: #ff0000; text-align: center;'>Necessário realizar login !</p>";
  header("Location: login.php");
}

$matricula = $_GET["matricula"];

$consulta = $conexao->query("SELECT * FROM aluno WHERE matricula = $matricula;");
while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

echo "
<!DOCTYPE html>
<html lang='pt-br'>

<head>
  <meta charset='UTF-8'>
  <link rel='shortcut icon' href='imagens/favicon.ico' type='image/x-ico'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Editar</title>
  <link rel='stylesheet' type='text/css' href='estilos/global.css'>
  <link rel='stylesheet' type='text/css' href='estilos/editar.css'>
</head>

<body>
  <header class='cabecalho'>
    <img class='cabecalho-imagem' src='imagens/logo_dados_bsi.png' alt='Logo - Dados BSI'>
    <nav class='cabecalho-menu'>
      <a class='cabecalho-menu-item' href='index.php'>Início</a>
      <a class='cabecalho-menu-item' href='cadastrar.php'>Cadastrar</a>
      <a class='cabecalho-menu-item' href='listar.php'>Listar</a>
      <a class='cabecalho-menu-item' href='graficos.php'>Gráficos</a>
      <a class='cabecalho-menu-sessao'>Bem vindo ".$_SESSION['nome']."!</a>
      <a class='cabecalho-menu-sair' href='sair.php'>Sair</a>
    </nav>
  </header>

  <main class='conteudo'>
    <section class='conteudo-primario'>

      <form action='atualizar.php' method='post'>
        <input type='hidden' name='matricula' value='" . $linha['matricula'] . "'>
        <div class='box'>
          <fieldset>
            <legend><b>Dados Pessoais</b></legend>
            <br>
            <label for='data_nascimento'><b>Data de Nascimento:</b></label>
            <input type='date' name='data_nascimento' id='data_nascimento' value='" . $linha['data_nascimento'] . "'
              required>
            <br><br>
            <p>Sexo:</p>
            <input type='radio' id='masculino' name='sexo' value='m' " . ($linha['sexo'] == 'm' ? 'CHECKED' : '') . "
              required>
            <label for='masculino'>M</label>
            <br>
            <input type='radio' id='feminino' name='sexo' value='f' " . ($linha['sexo'] == 'f' ? 'CHECKED' : '') . "
              required>
            <label for='feminino'>F</label>
            <br><br>
            <p>Naturalidade:</p>
            <input type='radio' id='vca' name='naturalidade'
              value='vca' " . ($linha['naturalidade'] == 'vca' ? 'CHECKED' : '') . " required>
            <label for='vca'>Vitória da Conquista - BA</label>
            <br>
            <input type='radio' id='200km' name='naturalidade'
              value='200km' " . ($linha['naturalidade'] == '200km' ? 'CHECKED' : '') . " required>
            <label for='200km'>Cidades circunvizinhas (200km)</label>
            <br>
            <input type='radio' id='outros' name='naturalidade'
              value='outros' " . ($linha['naturalidade'] == 'outros' ? 'CHECKED' : '') . " required>
            <label for='outros'>Outros</label>
            <br><br>
          </fieldset>

        </div>

        <div class='box'>

          <fieldset>
            <legend><b>Dados Acadêmicos</b></legend>
            <br>
            <div class='inputBox'>
              <input type='text' name='matricula' id='matricula' class='inputUser' value=" . $linha['matricula'] . "
              readonly required>
              <label for='matricula' class='labelInput'>Matrícula:</label>
            </div>
            <br>
            <p>Forma de Ingresso:</p>
            <input type='radio' id='ampla' name='forma_ingresso'
              value='ampla' " . ($linha['forma_ingresso'] == 'ampla' ? 'CHECKED' : '') . " required>
            <label for='ampla'>AMPLA CONCORRÊNCIA</label>
            <br>
            <input type='radio' id='cota' name='forma_ingresso'
              value='cota' " . ($linha['forma_ingresso'] == 'cota' ? 'CHECKED' : '') . " required>
            <label for='social'>COTA</label>
            <br>          
            <input type='radio' id='outros' name='forma_ingresso'
              value='outros' " . ($linha['forma_ingresso'] == 'outros' ? 'CHECKED' : '') . " required>
            <label for='outros'>OUTROS</label>
            <br><br>
            <label for='data_matricula'><b>Data de Matrícula:</b></label>
            <input type='date' name='data_matricula' id='data_matricula' value='" . $linha['data_matricula'] . "' required>
            <br><br><br>
            <div class='inputBox'>
              <input type='text' name='periodo_inicial' id='periodo_inicial' class='inputUser' value='" . $linha['periodo_inicial'] . "' required  >
              <label for='periodo_inicial' class='labelInput'>Período Letivo Inicial:</label>
            </div>
            <br><br>
            <div class='inputBox'>
              <input type='text' name='periodo_final' id='periodo_final' class='inputUser' value='" . $linha['periodo_final'] . "' required  >
              <label for='periodo_final' class='labelInput'>Período Letivo Final:</label>
            </div>
            <br><br>
            <label for='data_conclusao'><b>Data de Conclusão:</b></label>
            <input type='date' name='data_conclusao' id='data_conclusao' value='" . $linha['data_conclusao'] . "' >
            <br>
          </fieldset>

        </div>

        <div class='box'>

          <fieldset>
            <legend><b>Componentes Curriculares</b></legend>
            <br>
            <div class='inputBox'>
              <input type='number' name='nota_tcc' id='nota_tcc' class='inputUser' min='7.0' max='10.0' step='0.1' required
                value='" . $linha['nota_tcc'] . "' >
              <label for='nota_tcc' class='labelInput'>Nota do TCC:</label>
            </div>
            <br><br>
            <div class='inputBox'>
              <input type='number' name='numero_reprovacoes' id='numero_reprovacoes' class='inputUser' min='0'
                value='" . $linha['numero_reprovacoes'] . "' required>
              <label for='numero_reprovacoes' class='labelInput'>Número de Reprovações:</label>
            </div>
            <br><br>
            <div class='inputBox'>
              <input type='number' name='numero_pf' id='numero_pf' class='inputUser' min='0' value='" . $linha['numero_pf'] . "' required>
              <label for='numero_pf' class='labelInput'>Número de Aprovações com Provas Finais:</label>
            </div>
            <br><br>
            <div class='inputBox'>
              <input type='number' name='ira' id='ira' class='inputUser' min='1.00' max='10.0' step='0.01'
                value='" . $linha['ira'] . "' required>
              <label for='ira' class='labelInput'>Índice de Rendimento Acadêmico (I.R.A):</label>
            </div>
            <br>
          </fieldset>
        </div>
        <br>
        <fieldset class='estado'>
        <p>Estado dos Dados:</p>
        <br>
            <input type='radio' id='completo_historico' name='estado' value='completo_historico' " . ($linha['estado'] == 'completo_historico' ? 'CHECKED' : '') . " required>
            <label for='completo_historico'>Completo (somente Histórico Final)</label>
            <br>
            <input type='radio' id='completo_outras' name='estado' value='completo_outras' " . ($linha['estado'] == 'completo_outras' ? 'CHECKED' : '') . " required>
            <label for='completo_outras'>Completo (necessitou de outras fontes)</label>
            <br>
            <input type='radio' id='incompleto' name='estado' value='incompleto' " . ($linha['estado'] == 'incompleto' ? 'CHECKED' : '') . " required>
            <label for='incompleto'>Incompleto (dados faltantes)</label>
        </fieldset>
        <br><br>
        <input type='submit' name='submit' id='submit' value='Atualizar Dados' onclick=\"return confirm('Tem certeza de que deseja atualizar os dados da matrícula $matricula ?');\">
      </form>
      </div>
    </section>
  </main>
</body>

</html>
";
}
?>