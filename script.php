<?php
/**
 * Autor: @abnerfilipe 
 * documentacao das funcoes php https://www.php.net/manual/pt_BR/book.mysqli.php
 * 
 */ 

// nome da tabela
$db_record = '';
// query where opcional
$where = 'WHERE 1 ORDER BY 1';
// nome do arquvio concatenado com o nome da tabela
$csv_filename = 'db_export_'.$db_record.'_'.date('Y-m-d').'.csv';
// dados de acesso ao banco de dados 
// 127.0.0.1 ou localhost 
$hostname = 'xxxxxx';
$user = "xxxxxxxxx";
$password = "xxxxxxxxx";
$database = "xxxxxxxxxx";
$port = 3306;

$conn = mysqli_connect($hostname, $user, $password, $database, $port);
if (mysqli_connect_errno()) {
    die("Nao foi possivel conectar ao MySQL: " . mysqli_connect_error());
}
$csv_export = '';
// query para pegar os dados do banco de dados
$query = mysqli_query($conn, "SELECT * FROM ".$db_record." ".$where);
$field = mysqli_field_count($conn);
// novas linhas com o nome da coluna
for($i = 0; $i < $field; $i++) {
    $csv_export.= mysqli_fetch_field_direct($query, $i)->name.';';
}
// nova linha 
$csv_export.= '
';

while($row = mysqli_fetch_array($query)) {
    // create line with field values
    for($i = 0; $i < $field; $i++) {
        $csv_export.= '"'.$row[mysqli_fetch_field_direct($query, $i)->name].'";';
    }
    $csv_export.= '
';
}

// Exporta os dados para um arquivo csv para download
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=".$csv_filename."");
echo "\xEF\xBB\xBF"; // UTF-8 BOM
file_put_contents($csv_filename, $csv_export);
// printa os dados na tela
// echo($csv_export);
echo("Script Finalizado");
