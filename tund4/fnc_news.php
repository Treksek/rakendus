<?php


function saveNews($newsTitle, $newsContent)
{

    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //Valmistan ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_news (userid, title, content) VALUES (?, ?, ?)");
    echo $conn->error;

    //Seon päringuga tegelikud andmed

    $userid = $_SESSION["userid"];
    // i - integer
    // s - string
    // d - decimal
    $stmt->bind_param("iss", $userid, $newsTitle, $newsContent);

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
    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $conn->prepare("SELECT id, userid, title, content, created FROM vr20_news where deleted is null order by id desc LIMIT ?");
    echo $conn->error;

    $stmt->bind_param("i", $limit);
    $stmt->bind_result($idFromDB, $useridFromDB, $titleFromDB, $contentFromDB, $dateFromDB);
    $stmt->execute();
    
    while ($stmt->fetch()) {
        //<h2>uudise pealkiri<h1>
        //<p>uudis<p>
        $response .= '<div>';
        $response .= '<h3>' . $titleFromDB . '</h3>';
        $response .= '<p>' . $dateFromDB . '</p>';
        $response .= '<p>' ."Sisestas kasutaja ID:" . $useridFromDB . '</p>';
        
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


