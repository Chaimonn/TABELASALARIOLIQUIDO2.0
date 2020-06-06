<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela INSS</title>

    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Calcular Salário Líquido</h1>
    <form class="calculo" action="" method="post">
      <input type="text" name="nome" id="" required placeholder="  Nome">
      <input type="text" name="salariobruto" id="" required placeholder="  Salário Bruto">
      <input type="number" name="dependentes" id="" required placeholder="  Nº de Dependentes">
      <button type="submit" name="Enviar">Enviar</button>
    </form>

    <?php
      error_reporting(0);
      ini_set(“display_errors”, 0);
      $c=0;
      $e=0;
    ?>

  <?php
    if(isset($_POST['Enviar']) && $_POST['nome'] != NULL){
      $nome = $_POST['nome'];
      $salarioBruto = $_POST['salariobruto'];
      $dependentes = $_POST['dependentes'];
      
      //INSS
      $salarioLiquido = 0;
      $descontoINSS = 0;
      $aliquotaINSS=0;
      if($salarioBruto <= 1045){
        $descontoINSS = $salarioBruto*(7.5/100);
        $aliquotaINSS = 7.5;
      }
      else if($salarioBruto <= 2089){
        $descontoINSS = $salarioBruto*(9/100);
        $aliquotaINSS = 9;
      }
      else if($salarioBruto <= 3143.12){
        $descontoINSS = $salarioBruto*(12/100);
        $aliquotaINSS = 12;
      }
      else{
        $descontoINSS = $salarioBruto*(14/100);
        $aliquotaINSS = 14;
      }
      
      //IR
      $aliquotaIRRF=0;
      $descontoIRPF=0;
      if($salarioBruto>=1903.99 && $salarioBruto<=2826.65){
        $descontoIRPF = $salarioBruto*(7.5/100);
        $aliquotaIRRF = 7.5;
       }
       else if($salarioBruto>2826.65 && $salarioBruto<=3751.05){
         $descontoIRPF = $salarioBruto*(15/100);
         $aliquotaIRRF = 15;
       }
       else if($salarioBruto>3751.05 && $salarioBruto<=4664.68){
         $descontoIRPF = $salarioBruto*(22.5/100);
         $aliquotaIRRF = 22.5;
       }
       else if($salarioBruto>4664.68){
         $descontoIRPF = $salarioBruto*(27.5/100);
         $aliquotaIRRF = 27.5;
       }
        
      $descontoDependente = $dependentes*189.59;
      $salarioLiquido = $salarioBruto-$descontoINSS-$descontoIRPF-$descontoDependente;

      $candido = "$nome|$salarioBruto|$aliquotaINSS|$descontoINSS|$aliquotaIRRF|$descontoIRPF|$descontoDependente|$salarioLiquido\n";

      $arquivo = fopen('salarioliq.txt','a+');
      fwrite($arquivo, $candido);
      fclose($arquivo);

    $arquivo = fopen("salarioliq.txt",'r');

    while(true) {
      $valores[$e] = fgets($arquivo);
      $valoresT = explode('|',$valores[$e]);

      $nomeT[$c]=$valoresT[0];
      $brutoT[$c]=$valoresT[1];
      $porINSS[$c]=$valoresT[2];
      $descINSS[$c]=$valoresT[3];
      $porIRRF[$c]=$valoresT[4];
      $desIRRF[$c]=$valoresT[5];
      $depend[$c]=$valoresT[6];
      $liqu[$c]=$valoresT[7];

      $brutoT[$c] = number_format($brutoT[$c], 2, '.', '');
      $porINSS[$c] = number_format($porINSS[$c], 2, '.', '');
      $descINSS[$c] = number_format($descINSS[$c], 2, '.', '');
      $porIRRF[$c] = number_format($porIRRF[$c], 2, '.', '');
      $desIRRF[$c] = number_format($desIRRF[$c], 2, '.', '');
      $depend[$c] = number_format($depend[$c], 2, '.', '');
      $liqu[$c] = number_format($liqu[$c], 2, '.', '');

      if ($valores[$e] == null) break;
      $e++;
      $c++;
 }
 fclose($arquivo);
}

  ?>

    <table class="tabelinha">
      <tr>
        <th class="tit">NOME</th>
        <th class="tit">SALÁRIO BRUTO</th>
        <th class="tit">% INSS</th>
        <th class="tit">DESCONTO INSS</th>
        <th class="tit">% IRRF</th>
        <th class="tit">DESCONTO IRRF</th>
        <th class="tit">$ DOS DEPENDENTES</th>
        <th class="tit">SALÁRIO LÍQUIDO</th>
      </tr>

      <?php
      if ($_POST['nome']!=NULL) {

        for ($i=0; $i < $c; $i++) {
          echo "<tr><th>$nomeT[$i]</td><td>R$$brutoT[$i]</td><td>$porINSS[$i]</td><td>R$$descINSS[$i]</td><td>$porIRRF[$i]</td><td>R$$desIRRF[$i]</td><td>R$$depend[$i]</td><td>R$$liqu[$i]</td><tr>";
        }

        $c = $c + 1;
      }
    ?>

    </table>

</body>
</html>
