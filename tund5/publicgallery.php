<?php
		require("../../../../configuration.php");
	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~daire.luus/", "tigu.hk.tlu.ee");//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		header("Location: page.php");
	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}

	require("../../../../configuration.php");
	require("fnc_gallery.php");
	
	
	$privacy_set = 1;
	$page = 1; 
	$limit = 5;
	$picCount = countPics($privacy_set); 
	
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	  } elseif(round($_GET["page"] - 1) * $limit >= $picCount){
		$page = intval(ceil($picCount / $limit));
	  }	else {
		$page = intval($_GET["page"]);
	  }
	  
	  $galleryHTML = readpublicgalleryImages($privacy_set, $page, $limit);

	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	
	<title>Avalik fotoalbum</title>
	
	
	<style >
	
	.block {width: 16% ;float: left;}
	</style>
</head>
<body>
	<h1>Avalikud pildid</h1>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	
	<hr>
	<?php 
		if($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
		} else {
			echo "<span>Eelmine leht</span> | ";
		}
		if($page *$limit <= $picCount){
			echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
		} else {
			echo "<span> Järgmine leht</span>";
		}
	?>
	<div>
    	<br>
		<?php
		echo $galleryHTML;
		?>
		
	</div>
	
</body>
</html>