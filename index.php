<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dataName = "sales.csv";
$rows= file($dataName, FILE_IGNORE_NEW_LINES);
//var_dump($lines);

$memberNum = 0;
$memberNum = count($rows) - 1;
$saleSum = 0;
$avr = 0;
//var_dump($memberNum);

$record = array();

foreach ($rows as $row) {
  $record = explode(",", $row);
  $saleSum += $record[1];
}

$avr = $saleSum / $memberNum;

if (!empty($_POST['download'])) {
  $fileName = "report.csv";
  $csv = "";
  $csv = "社員数,売上合計,売上平均\n$memberNum,$saleSum,$avr";
  $fp = fopen($fileName, 'ab');
  flock($fp, LOCK_EX);
  fwrite($fp, $csv);
  fclose($fp);
  //echo("社員数,売上合計,売上平均\n$memberNum,$saleSum,$avr");
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename=' . $fileName);
  echo mb_convert_encoding($csv,"SJIS","utf-8");
  exit;
}

// $filename = "report.csv";
// file_put_contents($filename, "社員数,売上合計,売上平均\n$memberNum,$saleSum,$avr");


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CSVファイルの読み込み・出力</title>
  <link rel="stylesheet" href="">
</head>
<body>
<table>
  <tr>
  <th>社員数：</th>
  <td><?php echo $memberNum."<br>"; ?></td>
  </tr>

  <tr>
  <th>売上合計：</th>
  <td><?php echo $saleSum."<br>"; ?></td>
  </tr>

  <tr>
  <th>売上平均：</th>
  <td><?php echo $avr."<br>"; ?></td>
  </tr>
</table>
<form method="post">
<input type="submit" name="download" value="CSVでダウンロードする">
</form>
</body>
</html>