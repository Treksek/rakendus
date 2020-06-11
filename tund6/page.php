<?php
	require("../../../../configuration.php");
	
	require("fnc_news.php");
	require("fnc_users.php");
	

	require("classes/Session.class.php");

	SessionManager::sessionStart("vr20", 0, "~/daire.luus/", "tigu.hk.tlu.ee");

	require ("classes/Test.class.php");
	$test = new Test();
	//echo $test->number;
	$test->reveal();
	unset($test);


	$myName = "Daire Lüüs";
	$fullTimeNow = 	date("d.m.Y H:i:s");
	//<p>Lehe avamise hetkel oli: <strong> 31.01.2020 11:32:07</strong></p>
	$timeHTML = "\n <p>Lehe avamise hetkel oli: <strong>" .$fullTimeNow ."</strong></p> \n";
	$hourNow = date("H");
	$partOfDay = "hägune aeg";
	
	
	if($hourNow < 10) {
		$partOfDay = "hommik";
		
	}
	if($hourNow >= 10 and $hourNow < 18) {
		$partOfDay = "aeg aktiivselt tegutseda";
		
	}
	
	$partOfDayHTML = "<p>Käes on " .$partOfDay ."!</p> \n";

	//tausta ja teksti värv
	if ($hourNow <10) {
		$taustColor = "morning";
	}
	elseif ($hourNow >= 10 and $hourNow < 18) {
		$taustColor = "day";
	} 
	else {
		$taustColor = "evening";
	}
	 



	
	//info semestri kulgemise kohta
	
	$semesterStart = new DateTime("2020-1-27");
	$semesterEnd = new DateTime("2020-6-22");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	//echo $semesterDuration; 
	//var_dump($semesterDuration);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart->diff($today);

	

	//<p>Semester on hoos: <meter value="" min="0" max=""></meter></p>
	if($today < $semesterStart) {
		$semesterProgressHTML = "<p>Semester ei ole pihta hakanud. Oota veel pisut!</p>";
	} 
	elseif ($today > $semesterEnd) {
		$semesterProgressHTML = "<p>Semester on läbi saanud. Pead ootama kuni hakkab järgmine!</p>";
	} 
	else {
	$semesterProgressHTML = '<p>Semester on hoos: <meter min="0" max="';
	$semesterProgressHTML .= $semesterDuration->format("%r%a");
	$semesterProgressHTML .= '" value="';
	$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
	$semesterProgressHTML .= '"></meter>.</p>' ."\n";
	}
	
	//loen etteantud kataloogist pildifailid
	$picsDir = "../../pics/";
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
	$randomImageHTML = '<img src="' .$picsDir .$photoList[$photoNum] .'" alt="juhuslik pilt Haapsalust">' ."\n";


	//kolme pildi loterii
	$pealkiri = "<h3>Nüüd tulevad kolm juhuslikku pilti Haapsalust</h3>";

	$kolmPiltiList = [];
	$juhuslikIMGHTML = "";
	
	if($photoCount > 0){
		do {
			$juhuslikIMG = $photoList[mt_rand(0, $photoCount - 1)];
			if(!in_array($juhuslikIMG, $kolmPiltiList)){
				array_push($kolmPiltiList, $juhuslikIMG);
				$juhuslikIMGHTML .= '<img src="' . $picsDir . $juhuslikIMG . '" alt="juhuslik pilt Haapsalust"></img>' . "\n";
			} 
		} 
		while (count($kolmPiltiList)<=2);
		} 
	else {
		$juhuslikIMGHTML = "<p>Kuvamiseks pole ühtegi pilti</p>";
	}

	$newsHTML = readNews(1);
	
	$notice = null;
	$email = null;
	$emailError = null;
	$passwordError = null;

	

	if(isset($_POST["login"])){
		if (isset($_POST["email"]) and !empty($_POST["email"])){
		  $email = test_input($_POST["email"]);
		} else {
		  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
		}
	  
		if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
		  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
		}
	  
		if(empty($emailError) and empty($passwordError)){
		   $notice = signIn($email, $_POST["password"]);
		} else {
			$notice = "Ei saa sisse logida!";
		}
	}




	
	
	
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
	<style>
	.morning {
		background-color: yellow;
		color: red;
	}
	.day {
		background-color: pink;
		color: purple;
	}
	.evening {
		background-color: green;
		color: darkblue;
	}
	

	</style>
</head>
<body class= <?php echo $taustColor; ?>>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<h2>Logi sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>E-mail (kasutajatunnus):</label><br>
		<input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
		<label>Salasõna:</label><br>
		<input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
		<input name="login" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span>
	</form>
	

	<p>Loo endale <a href="newuser.php">kasutajakonto</a></p>
	

	<?php
		echo $timeHTML;
		echo $partOfDayHTML;
		
		echo $semesterProgressHTML;
		echo $randomImageHTML;
		echo $pealkiri;

		echo $juhuslikIMGHTML;
		
		
		
	?>

<br>
	<hr>
	<h2>Uudis</h2>
	<div>
		<?php echo $newsHTML; ?>
		
	</div>
	
</body>
</html>