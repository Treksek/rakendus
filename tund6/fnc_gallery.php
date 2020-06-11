<?php
$GLOBALS["originalPhotoDir"] = "../../uploadOriginalPhoto/";
$GLOBALS["normalPhotoDir"] = "../../uploadNormalPhoto/";
$GLOBALS["thumbPhotoDir"] = "../../uploadThumbPhoto/";

function readAllMyPictureThumbs(){
	$privacy = 3;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE userid=? AND privacy=? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("ii", $_SESSION["userid"], $privacy);
	$stmt->bind_result($filenameFromDb, $altFromDb);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= '<a href="' .$GLOBALS["normalPhotoDir"] .$filenameFromDb .'" target="_blank"><img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" alt="'.$altFromDb .'"></a>' ."\n \t \t";
	}
	if($html != ""){
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}
	
	$stmt->close();
	$conn->close();
	return $finalHTML;
}
	
	function readAllSemiPublicPictureThumbs(){
		$privacy = 2;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<a href="' .$GLOBALS["normalPhotoDir"] .$filenameFromDb .'" target="_blank"><img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" alt="'.$altFromDb .'"></a>' ."\n \t \t";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $finalHTML;
	 }
	 function readAllPictureThumbs(){
		$privacy = 1;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenameFromDb, $altFromDb);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<a href="' .$GLOBALS["normalPhotoDir"] .$filenameFromDb .'" target="_blank"><img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" alt="'.$altFromDb .'"></a>' ."\n \t \t";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		
		$stmt->close();
		$conn->close();
		return $finalHTML;
	}
	 
	 function countPics($privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function countPrivatePics(){
		$notice = null;
		$privacy = 3;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("ii", $privacy, $_SESSION["userid"]);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	function countAllPics(){
		$notice = null;
		$privacy = 1;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND userid = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("ii", $privacy, $_SESSION["userid"]);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	
		function readAllMyPictureThumbsPage($privacy, $page, $limit){
			
			$privacy = 3;
			$skip = ($page-1)*$limit;
			$finalHTML = "";
			$html = "";
			$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$conn->set_charset('utf8');
			$stmt = $conn->prepare("
			SELECT
			vr20_photos.id,
			vr20_photos.filename,
			vr20_users.firstname,
			vr20_users.lastname,
			vr20_photos.alttext,
			AVG(vr20_photoratings.rating) as AvgValue
			FROM vr20_photos
			JOIN vr20_users ON vr20_photos.userid = vr20_users.id
			LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
			WHERE vr20_photos.privacy <= ? AND vr20_photos.deleted IS NULL AND vr20_photos.userid=?
			GROUP BY vr20_photos.id DESC LIMIT ?, ?");
			echo $conn->error;
			$user = $_SESSION["userid"];
			$stmt->bind_param("iiii", $privacy, $user, $skip, $limit);
			$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
			$stmt->execute();
			while($stmt->fetch()){
				$html .= '<div class="galleryelement">'."\n";
				$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
				
				$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
				if ($ratingFromDB!=0){
				$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
				} else {
					$html .="<br>Hindeid pole\n";
				}
				$html .= "</div>\n";
			}
			if($html != ""){
				$finalHTML = $html;
			} else {
				$finalHTML = "<p>Kahjuks pilte pole!</p>";
			}
		
			$stmt->close();
			$conn->close();
			return $finalHTML;
		}
	
	function readAllSemiPublicPictureThumbsPage($privacy, $page, $limit){
		$privacy = 2;
		$skip = ($page-1)*$limit;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("
		SELECT
		vr20_photos.id,
		vr20_photos.filename,
		vr20_users.firstname,
		vr20_users.lastname,
		vr20_photos.alttext,
		AVG(vr20_photoratings.rating) as AvgValue
		FROM vr20_photos
		JOIN vr20_users ON vr20_photos.userid = vr20_users.id
		LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
		WHERE vr20_photos.privacy <= ? AND vr20_photos.deleted IS NULL
		GROUP BY vr20_photos.id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">'."\n";
			$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
			$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
			if ($ratingFromDB!=0){
				$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
			} else {
				$html .="<br>Hindeid pole\n";
			}
			$html .= "</div>\n";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}

		$stmt->close();
		$conn->close();
		return $finalHTML;
	}

	function readAllPictureThumbsPage($privacy, $page, $limit){
		$privacy = 1;
		$skip = ($page-1)*$limit;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("
		SELECT
		vr20_photos.id,
		vr20_photos.filename,
		vr20_users.firstname,
		vr20_users.lastname,
		vr20_photos.alttext,
		AVG(vr20_photoratings.rating) as AvgValue
		FROM vr20_photos
		JOIN vr20_users ON vr20_photos.userid = vr20_users.id
		LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
		WHERE vr20_photos.privacy <= ? AND deleted IS NULL
		GROUP BY vr20_photos.id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("iii", $privacy, $skip, $limit);
		$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">'."\n";
			$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
			$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
			if ($ratingFromDB!=0){
				$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
			} else {
				$html .="<br>Hindeid pole\n";
			}
			$html .= "</div>\n";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $finalHTML; 
	}
	

	function deleteMyPictureThumbs($privacy, $page, $limit){
		$privacy = 3;
		$skip = ($page-1)*$limit;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("
		SELECT
		vr20_photos.id,
		vr20_photos.filename,
		vr20_users.firstname,
		vr20_users.lastname,
		vr20_photos.alttext,
		AVG(vr20_photoratings.rating) as AvgValue
		FROM vr20_photos
		JOIN vr20_users ON vr20_photos.userid = vr20_users.id
		LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
		WHERE vr20_photos.privacy <= ? AND vr20_photos.deleted IS NULL AND vr20_photos.userid=?
		GROUP BY vr20_photos.id DESC LIMIT ?, ?");
		echo $conn->error;
		$user = $_SESSION["userid"];
		$stmt->bind_param("iiii", $privacy, $user, $skip, $limit);
		$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= '<div class="galleryelement">'."\n";
			$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
			
			$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
			if ($ratingFromDB!=0){
			$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
			} else {
				$html .='<br>Hindeid pole'."\n";
			}
	
			$html .= '<br>  Märgi <input type="checkbox" id="checkId" name="check[]" value="' .$idFromDB. '">'."\n \t \t";
			$html .= '<br><a href="deletephoto.php?photoid=' .$idFromDB. '">Kustuta foto </a>'."\n \t \t";
			
			$html .= '</div>'."\n";
		}
		
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
	
		$stmt->close();
		$conn->close();
		return $finalHTML;
	}
	function deleteId($id){
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("
		UPDATE vr20_photos
		SET deleted=now()
		WHERE id=? AND userid=?");
		$stmt->bind_param("ii", $id, $_SESSION["userid"]);
		$stmt->execute();
		$stmt->close();
		header("Location: " . $_SERVER['HTTP_REFERER']);
	}