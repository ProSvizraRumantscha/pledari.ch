<?
include "../config.php";
?>
<html>
<head>
    <title>Remartgas / commentos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="pledari.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700" rel="stylesheet">
</head>

<body>
<div class="textfield-alike">
    <?
    $nr = $_GET['nr'];
    $SQLString = "SELECT * FROM `monpledari` WHERE `nr` LIKE '$nr'";

    $colligiaziun = mysqli_connect("localhost", $mysql_user, $mysql_pass, $mysql_db);
    $Ergebnis = mysqli_query($colligiaziun, $SQLString);

    $field = $Ergebnis->fetch_array();

    echo nl2br(@$field['J4']);
    ?>
</div>
</body>
</html>
