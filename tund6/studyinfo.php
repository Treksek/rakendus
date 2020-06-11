<?php

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
require("fnc_study.php");

$studyTableHTML = getStudyTableHTML();

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
    
            <h1>Õppetegevus</h1>
            <p>See leht on valminud õppetöö raames!</p>
            <p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
            <br>
        </section>
        <table>
            <thead>
                <tr>
                    <th>NR</th>
                    <th>Õppeaine</th>
                    <th>Tegevus</th>
                    <th>Kulunud aeg</th>
                    <th>Kuupäev</th>
                    <th>Kasutaja ID</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $studyTableHTML; ?>
            </tbody>
        </table>
    </div>

    
</body>

</html>