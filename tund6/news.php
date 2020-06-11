<?php

//require("../../../../configuration.php");
//require("fnc_news.php");

require("../../../../configuration.php");
    //session_start();

    require("classes/Session.class.php");

	SessionManager::sessionStart("vr20", 0, "/~daire.luus/", "tigu.hk.tlu.ee");
    

    if(!isset($_SESSION["userid"])){
        header("Location: page.php");
    
    }

    if (isset($_GET["logout"])){
        session_destroy();
        header("Location: page.php");
    }

    require("fnc_news.php");

$newsHTML = readNews(1);
    $originalPhotoDir = "../../uploadOriginalPhoto/";
	$normalPhotoDir = "../../uploadNormalPhoto/";
	$thumbPhotoDir = "../../uploadThumbPhoto/";
	$newsPhotoDir = "../../uploadNewsPhoto/";

if (isset($_POST["newsDelBtn"])) {
    deleteNews($_POST["newsDelBtn"]);
    $newsHTML = readNews($_POST["limitSet"]);
}

if (isset($_POST["limitSet"])) {

    $newsHTML = readNews($_POST["limitSet"]);
}

?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine 2020</title>
    <style>
body {
  background-color: lightblue;
}
</style>
    
</head>

<body>

            <h1 >Uudised</h1>
            <p>See leht on valminud õppetöö raames!</p>
            <p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
           
            
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="limit"></label>
                    <?php
                    $aDropd = array("1", "3", "5", "10");
                    echo '<div>
                    <label for="limit">Kuvatavate uudiste arv:</label>  
                    <select id="limit" onchange="this.form.submit();" name="limitSet">';
                    foreach ($aDropd as $sOption) {
                        $sSel = ($sOption == $_POST['limitSet']) ? "Selected='selected'" : "";
                        echo "<option   $sSel>$sOption</option>";
                    }
                    echo '</select></div>';
                    echo '<div>';
                    echo $newsHTML;
                    echo '</div>';
                    ?>
                </form>
           
        </div>
    </div>

    

</body>

</html>