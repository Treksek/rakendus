<?php

require("../../../../configuration.php");
require("fnc_news.php");



$newsHTML = readNews(1);

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
    
</head>

<body>

    <div class="container" style="max-width: 100%; margin-top:10px;">

        <section class="text-center">
            <h1 class="jumbotron-heading">Uudised</h1>
            <p>See leht on valminud õppetöö raames!</p>
            <br>
        </section>

        <div>
            <section class="text-center">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="limit"></label>
                    <?php
                    $aDropd = array("1", "3", "5", "10");
                    echo '<div class="form-group col-md-4">
                    <label for="limit">Kuvatavate uudiste arv:</label>  
                    <select id="limit" class="form-control" onchange="this.form.submit();" name="limitSet">';
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
            </section>
        </div>
    </div>

    

</body>

</html>