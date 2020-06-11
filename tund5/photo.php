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

    require("fnc_photos.php");

    //loen etteantud kataloogist pildifailid
	$picsDir = "../../uploadNormalPhoto/";
	$photoTypesAllowed = ["image/jpeg", "image/png"];
	$photoList = [];
	$allFiles = array_slice(scandir($picsDir), 2);
	//var_dump($allFiles);
	foreach($allFiles as $file) {
		$fileInfo = getimagesize($picsDir .$file);
		if(in_array($fileInfo["mime"], $photoTypesAllowed) == true) {
			array_push($photoList, $file);
			
			}
		
	}
	$photoCount = count($photoList);
	$photoNum = mt_rand(0, $photoCount - 1);
	


	//kolme pildi loterii
	

	$kolmPiltiList = [];
	$juhuslikIMGHTML = "";
	
	if($photoCount > 0){
		do {
			$juhuslikIMG = $photoList[mt_rand(0, $photoCount - 1)];
			if(!in_array($juhuslikIMG, $kolmPiltiList)){
				array_push($kolmPiltiList, $juhuslikIMG);
				$juhuslikIMGHTML .= '<img src="' . $picsDir . $juhuslikIMG . '" alt="juhuslik pilt "></img>' . "\n";
			} 
		} 
		while (count($kolmPiltiList)<=9);
		} 
	else {
		$juhuslikIMGHTML = "<p>Kuvamiseks pole ühtegi pilti</p>";
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

            <h1 >Fotogalerii</h1>
            <p>See leht on valminud õppetöö raames!</p>
            <p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
<h2>Hetkel näed siin lehel kümmet juhuslikult valitud pilti.</h2>

    </body>

<?php

echo $juhuslikIMGHTML;


?>

</html>
    