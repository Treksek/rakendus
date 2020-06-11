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

	/* require("fnc_news.php");
	
	$newsHTML = readNewsPage(5); */
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Meie äge koduleht</h1>
    <p>Tere! <?php echo $_SESSION["userFirstName"] . " " .$_SESSION["userLastName"]; ?></p>
    <p>See leht on valminud õppetöö raames!</p>
    <p>Logi <a href="?logout=1">välja</<>!</a></p>

        <hr>
        <p>Uudiste <a href="news.php">lugemine</a></p>
        <hr>
        
        <p>Uudiste <a href="addnews.php">sisestamine</a></p>
        <hr>
        <p>Õppimise <a href="addstudy.php">sisestamine</a></p>
        <hr>
        <p>Õppimise <a href="studyinfo.php">vaatamine</a></p>
        <hr>
        <p>Piltide <a href="photoUpload.php">lisamine</a></p>
        <hr>
        <p>Piltide <a href="photo.php">vaatamine</a></p>
        <hr>
        <p>Privaatse galerii <a href="privategallery.php">vaatamine</a></p>
        <hr>
        <p>Kasutajate galerii <a href="semipublicgallery.php">vaatamine</a></p>
        <hr>
        <p>Avaliku galerii <a href="publicgallery.php">vaatamine</a></p>
        <hr>
	
</body>
</html>