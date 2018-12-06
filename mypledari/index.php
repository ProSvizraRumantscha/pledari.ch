<?
include "../config.php";
include "../SqlQueryGenerator.php";

$nr = @$_POST['nr'];
$pled = @$_POST['pled'];
$direcziun = @$_POST['direcziun'];
$modus = @$_POST['modus'];
$medem = @$_POST['medem'];

if($nr != ""){
    if($medem == "<<"){
        $nr--;
    } else{
        $nr++;
    }
} else {
    $nr = 0;
}

if($pled == ""){
    $pled = date("Ymd");
    $modus = "entschatta";
    $direcziun = 0;
    $pled_ei_datum = 1;
}

$colligiaziun = mysqli_connect("localhost", $mysql_user, $mysql_pass, $mysql_db);
$SQLString = SqlQueryGenerator::generateEnQuery($pled, $direcziun, $modus);
	
$Ergebnis = mysqli_query($colligiaziun, $SQLString);
$max_tot = @mysqli_num_rows($Ergebnis);

$SQLString = $SQLString." LIMIT $nr, 1";
$Ergebnis = mysqli_query($colligiaziun, $SQLString);

?>
<!doctype html>
<html>
<head>
    <title>myPledari</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link href="pledari.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700" rel="stylesheet">
</head>

<body onLoad="this.document.tschertga.pled.focus();">
<script type="text/javascript">
function go()
{
	box = this.document.tschertga.direcziun;
	direcziun = box.options[box.selectedIndex].value;
	if (direcziun === 8) {
	this.document.tschertga.modus[2].checked = true;
	}
	if (direcziun === 9) {
	this.document.tschertga.modus[2].checked = true;
	}
}
</script>

<div class="cuntegn">
    <div class="head">
        <div class="header">
            <a class="title accent-a" href=".">myPledari</a>
            <div class="slogan">
                <div>Pledari rumantsch-englais</div>
                <div>English-Romansh Dictionary</div>
            </div>
        </div>

        <form name="tschertga" method="post" action="index.php" class="search">

            <label>
                Direcziun per la tschertga / Search direction<br>
                <select name="direcziun" onChange="go()">
                    <option value="0" <? if($direcziun == 0){ echo "selected"; } ?>>English -> Romansh (RG)</option>
                    <option value="1" <? if($direcziun == 1){ echo "selected"; } ?>>Rumantsch (RG) -> English</option>
                    <option value="2" <? if($direcziun == 2){ echo "selected"; } ?>>Sursilvan -> English</option>
                    <option value="3" <? if($direcziun == 3){ echo "selected"; } ?>>Sutsilvan -> English</option>
                    <option value="4" <? if($direcziun == 4){ echo "selected"; } ?>>Surmiran -> English</option>
                    <option value="5" <? if($direcziun == 5){ echo "selected"; } ?>>Puter -> English</option>
                    <option value="6" <? if($direcziun == 6){ echo "selected"; } ?>>Vallader -> English</option>
                    <option value="7" <? if($direcziun == 7){ echo "selected"; } ?>>Rumantsch (total) -> English</option>
                    <option value="8" <? if($direcziun == 8){ echo "selected"; } ?>>Intercurir remartgas</option>
                    <option value="9" <? if($direcziun == 9){ echo "selected"; } ?>>Search comments</option>
                </select>
            </label>

            <fieldset>
                <legend>Modus per la tschertga / Search mode</legend>
                <label>
                    <input type="radio" name="modus" value="entschatta" class="buccolur" <? if($modus == "entschatta"){ echo "checked"; } ?> checked>
                    normal / begins with<br>
                </label>
                <label>
                    <input type="radio" name="modus" value="exact" class="buccolur" <? if($modus == "exact"){ echo "checked"; } ?>>
                    exact / precisely<br>
                </label>
                <label>
                    <input type="radio" name="modus" value="intern" class="buccolur" <? if($modus == "intern"){ echo "checked"; } ?>>
                    a l'intern / contains<br>
                </label>
                <label>
                    <input type="radio" name="modus" value="finiziun" class="buccolur" <? if($modus == "finiziun"){ echo "checked"; } ?>>
                    finiziun / ends with
                </label>
            </fieldset>

            <label>
                Term da tschertgar / Search term<br>
                <input type="text" name="pled" value="<? if(@$pled_ei_datum != 1){echo $pled; } ?>">
            </label>

            <input type="submit" name="tschertgar" value="Tschertgar / search">
        </form>
    </div>

<?
    if($max_tot != 0) {
        $field = $Ergebnis->fetch_array();
?>

    <form name="form1" method="post" action="index.php" class="nav-results">
        <input type="hidden" name="pled" value="<?=$pled; ?>">
        <input type="hidden" name="nr" value="<?=$nr; ?>">
        <input type="hidden" name="modus" value="<?=$modus; ?>">
        <input type="hidden" name="direcziun" value="<?=$direcziun; ?>">

        <div class="nav-back">
            <? if($nr!=0){ ?>
            <input type="submit" name="medem" value="<<" class="nav-button">
            <? } ?>
        </div>
        <div class="nav-index">
            <div><? echo $nr+1; ?> / <? echo $max_tot; ?></div>
        </div>
        <div class="nav-forward">
            <? if($nr+1<$max_tot){ ?>
            <input type="submit" name="medem" value=">>" class="nav-button">
            <? } ?>
        </div>
    </form>

    <div class="results">
        <div class="results-column" id="results-left">
            <div class="result">
                <div class="result-label">Englais:<br>English:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['B1'];?></div>
                    <div class="result-comment"><?=@$field['B0'];?></div>
                </div>
            </div>

            <div class="result">
                <div class="result-label">Rumantsch<br>Grischun:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['A1'];?></div>
                    <div class="result-comment"><?=@$field['A3'];?></div>
                </div>
            </div>

            <div class="result">
                <div class="result-label">Sursilvan:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['D1'];?></div>
                    <div class="result-comment"></div>
                </div>
            </div>

            <div class="result">
                <div class="result-label">Sutsilvan:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['E1'];?></div>
                    <div class="result-comment"></div>
                </div>
            </div>

            <div class="result">
                <div class="result-label">Surmiran:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['F1'];?></div>
                    <div class="result-comment"></div>
                </div>
            </div>

            <div class="result">
                <div class="result-label">Puter:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['G1'];?></div>
                    <div class="result-comment"></div>
                </div>
            </div>

            <div class="result">
                <div class="result-label">Vallader:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['H1'];?></div>
                    <div class="result-comment"></div>
                </div>
            </div>
        </div>

        <div class="results-column" id="results-right">
            <div class="result" id="result-comments">
                <div class="result-label">Comments:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['J1'];?></div>
                    <div class="result-comment">
                        <? if(@$field['J4']!=""){ ?>
                            <a href="declaraziuns.php?nr=<?=@$field['nr']; ?>" rel="modal:open">Details</a>
                        <? } ?>
                    </div>
                </div>
            </div>

            <div class="result" id="result-remartgas">
                <div class="result-label">Remartgas:</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['J2'];?></div>
                    <div class="result-comment">
                        <? if(@$field['J5']!=""){ ?>
                            <a href="declaraziuns.php?nr=<?=@$field['nr']; ?>" rel="modal:open">Details</a>
                        <? } ?>
                    </div>
                </div>
            </div>

            <div class="result" id="result-examples">
                <div class="result-label">Exempel(s):</div>
                <div class="result-content">
                    <div class="result-text"><?=@$field['J3'];?></div>
                    <div class="result-comment"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="sources list">
        <?
        if(@$field['10'] != ""){ echo "Term used in TeachMe Romansh<br>"; }
        if(@$field['11'] != ""){ echo "Term used in the Menzli Romansh course<br>"; }
        if(@$field['12'] != ""){ echo "Term used in En lingia directa<br>"; }
        if(@$field['14'] != ""){ echo "Term duvrà en il stgalim aut grischun<br>"; }
        if(@$field['16'] != ""){ echo "Term duvrà en englais per Rumantsch<br>"; }
        ?>
    </div>

<?php
    }
?>
</div>

</body>
</html>
