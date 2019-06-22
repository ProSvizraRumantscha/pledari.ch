<?php

include "../config.php";

$searchTerm = @$_GET['searchTerm'];
$searchLanguage = @$_GET['searchLanguage'];
$displayLanguage = @$_GET['displayLanguage'];

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');

$SQLString = getQueryStatement($searchLanguage, $displayLanguage);

$connection = mysqli_connect('localhost', $mysql_user, $mysql_pass, $mysql_db);

$stmt = $connection->prepare($SQLString);
$placeholderList = '%, '.$searchTerm;
$placeholderListMultiple = '%, '.$searchTerm.',%';
$placeholderFreeText = '%'.$searchTerm.'%';
$stmt->bind_param('ssss', $searchTerm, $placeholderList, $placeholderListMultiple, $placeholderFreeText);
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
    'OR ' .$searchLanguage. '_search LIKE lower(?) ' .
    'OR Conjugation LIKE lower(?) ';
}
