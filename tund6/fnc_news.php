<?php




function saveNews($userid, $newsTitle, $newsContent, $newsPicture)
{

    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //Valmistan ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_news (userid, title, content, picture) VALUES (?, ?, ?, ?)");
    echo $conn->error;

    //Seon päringuga tegelikud andmed

    $userid = $_SESSION["userid"];
    // i - integer
    // s - string
    // d - decimal
    $stmt->bind_param("isss", $userid, $newsTitle, $newsContent, $newsPicture);

    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }

    //Sulgen päringu ja andmebaasi ühenduse.
    $stmt->close();
    $conn->close();
    return $response;
}

function readNews($limit)

{
    $newsPhotoDir = "../../uploadNewsPhoto/";
    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $conn->prepare("SELECT
       
       vr20_news.title,
       vr20_news.content, 
       vr20_news.created,
       vr20_news.picture,
       vr20_users.firstname,
       vr20_users.lastname
       FROM vr20_news 
       LEFT JOIN vr20_users ON vr20_users.id = vr20_news.userid 
       WHERE vr20_news.deleted is null 
       order by vr20_news.id desc LIMIT ?");
    echo $conn->error;

    $stmt->bind_param("i", $limit);
    $stmt->bind_result($titleFromDB, $contentFromDB,  $dateFromDB, $pictureFromDB, $firstFromDB, $lastFromDB);
    $stmt->execute();
    
    while ($stmt->fetch()) {
        
        $response .= '<div>';
        $response .= '<h3>' . $titleFromDB . '</h3>';
        $response .= '<p>' . $dateFromDB . '</p>';
        $response .= '<p>' ."Sisestas kasutaja: " . $firstFromDB . " " . $lastFromDB .'</p>';
        if ($pictureFromDB!=null){
            $response .='<p><img src="'.$newsPhotoDir.$pictureFromDB.'"></p>';
        }
        
        $response .= '<p>' . $contentFromDB . '</p>';

        $response .= '<hr';
        
        $response .= '</div>';
    }

    if ($response == null) {
        $response = "<p>Kahjuks uudised puuduvad!</p>";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function deleteNews($id)
{
    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("UPDATE vr20_news SET deleted = NOW() WHERE Id =?");

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }

    $stmt->close();
    $conn->close();
    return $response;
}


