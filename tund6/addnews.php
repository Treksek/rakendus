<?php
	

	require("../../../../configuration.php");
    //session_start();

   
	require("classes/Session.class.php");
	require("classes/Photo.class.php");
	require("fnc_news.php");

	SessionManager::sessionStart("vr20", 0, "/~daire.luus/", "tigu.hk.tlu.ee");
    

    if(!isset($_SESSION["userid"])){
        header("Location: page.php");
    
    }

    if (isset($_GET["logout"])){
        session_destroy();
        header("Location: page.php");
	}
	$originalPhotoDir = "../../uploadOriginalPhoto/";
	$normalPhotoDir = "../../uploadNormalPhoto/";
	$thumbPhotoDir = "../../uploadThumbPhoto/";
	$newsPhotoDir = "../../uploadNewsPhoto/";
	$error = null;
	$notice = null;
	$imageFileType = null;
	$fileUploadSizeLimit = 1048576;
	$fileNamePrefix = "news_";
	$fileName = null;
	$maxWidth = 600;
	$maxHeight = 400;
	$thumbSize = 100;
	$showClassError = null;
    
	$newsTitle = null;
	$newsContent = null;
	$newsError = null;
	$newsPicture = null;

	function test_input($data) {
 		 $data = trim($data);
 		 $data = stripslashes($data);
 		 $data = htmlspecialchars($data);
 		 return $data;
	}
	
	if(isset($_POST["newsBtn"])){
		if(isset($_POST["newsTitle"]) and !empty(test_input ($_POST["newsTitle"]))){
			$newsTitle = test_input ($_POST["newsTitle"]);

		} else {
			$newsError = "Uudise pealkiri on sisestamata! ";

		} 
		if(isset($_POST["newsEditor"]) and !empty(test_input ($_POST["newsEditor"]))){
			$newsContent = test_input ($_POST["newsEditor"]);

		} else {
			$newsError .= "Uudise sisu on sisestamata!";
		}
		

		if(!empty($_FILES["fileToUpload"]["tmp_name"])){
			
			$photoUp = new Photo($_FILES["fileToUpload"], $fileUploadSizeLimit, $fileNamePrefix);
			//Failitüübi ja suuruse kontroll kutsutakse välja, enne kui minnakse koodiga edasi
			if ($photoUp->imageFileTypeCheck()) {
			$photoUp->resizePhoto($maxWidth, $maxHeight);
			$photoUp->addWatermark("vr_watermark.png", 3, 10);
			$result = $photoUp->saveImgToFile($newsPhotoDir .$photoUp->fileName);
			if($result == 1) {
				$notice .= "Vähendatud pilt laeti üles! ";
			} else {
				$error .= "Vähendatud pildi salvestamisel tekkis viga!";
			}
			}
		}
		//saadame andmebaasi
		if(empty ($newsError)) {
			//echo "Salvestame!";
			$response = saveNews($_SESSION["userid"], $newsTitle, $newsContent, $newsPicture);
			if($response ==1){
				$newsError = "Uudis on salvestatud!";
			} else {
				$newsError = "Uudise salvestamisel tekkis tõrge!";
			}
		}
		unset($photoUp);

	}
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Veebirakendused ja nende loomine 2020</title>
	<style>
body {
  background-color: lightblue;
}
</style>
	
</head>
<body>
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud Õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>


	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Uudise pealkiri: </label>
		<input type="text" name="newsTitle" placeholder="Uudise pealkiri" value="<?php echo $newsTitle; ?>"><br>
		<label>Uudise sisu</label>
		<textarea name="newsEditor" placeholder="Uudis" rows="5" cols="40"><?php echo $newsContent; ?></textarea>
		<br><br><br>
		<label>Lisa pilt: </label><br>
      	<input type="file" name="fileToUpload"><br><br><br>
		<input type="submit" name="newsBtn" value="Salvesta uudis!">
		<span><?php echo $newsError; ?></span>

	</form>
	<br>
	<hr>
	
</body>
</html>