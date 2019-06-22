<?php

include "../config.php";

$pled = @$_GET['pled'];
$search = @$_GET['search'];
$display = @$_GET['display'];
$modus = @$_GET['modus'];
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');

$SQLString = getQueryStatement($search, $display);

$connection = mysqli_connect('localhost', $mysql_user, $mysql_pass, $mysql_db);

$stmt = $connection->prepare($SQLString);
$placeholderList = '%, '.$pled;
$placeholderFreeText = '%'.$pled.'%';
$stmt->bind_param('sss', $pled, $placeholderList, $placeholderFreeText);
$stmt->execute();

$res = $stmt->get_result();

$field = $res->fetch_assoc();

$stmt->close();
$connection->close();

print json_encode($field);

function getQueryStatement(string $searchLanguage, string $displayLanguage) {
    return 'SELECT ' .$displayLanguage. '_display as translation, ' .
    'Source_Table_Name as sourceDict, ' .
    'Source_Table_Row_ID as sourceId ' .
    'FROM Alllanguages ' .
    'WHERE ' .$searchLanguage. '_search LIKE lower(?) ' .
    'OR ' .$searchLanguage. '_search LIKE lower(?) ' .
    'OR Conjugation LIKE lower(?) ';
}
