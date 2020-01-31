<?php
	$myName = "Daire Lüüs";
	$fullTimeNow = date("d.m.Y H:i:s");
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
	
	//info semestri kulgemise kohta
	
	$semesterStart = new DateTime("2020-1-27");
	$semesterEnd = new DateTime("2020-6-22");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	//echo $semesterDuration; 
	//var_dump($semesterDuration);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart->diff($today);
	
	//<p>Semester on hoos: <meter value="" min="0" max=""></meter></p>
	$semesterProgressHTML = '<p>Semester on hoos: <meter min="0" max="';
	$semesterProgressHTML .= $semesterDuration->format("%r%a");
	$semesterProgressHTML .= '" value="';
	$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
	$semesterProgressHTML .= '"></meter>.</p>' ."\n";
	
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
	
	
	
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php
		echo $timeHTML;
		echo $partOfDayHTML;
		echo $semesterProgressHTML;
		echo $randomImageHTML;
		
		
	?>
	
</body>
</html>