<?php

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



function getStudyTopicsOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, course FROM vr20_studytopics order by course asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $courseNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Õppeaineid ei ole";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function getStudyActivitiesOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8" );

    $stmt = $conn->prepare("SELECT id, activity FROM vr20_studyactivities order by activity asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $activityNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $activityNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Tegevusi ei ole!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function saveStudy($studyTopicId, $studyActivity, $elapsedTime)
{
    $userid = $_SESSION["userid"];
    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //Valmistan ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_studylog (userid, course, activity, time) VALUES (?, ?, ?, ?)");
    echo $conn->error;

    //Seon päringuga tegelikud andmed

    // i - integer
    // s - string
    // d - decimal
    $stmt->bind_param("iisd", $userid, $studyTopicId, $studyActivity, $elapsedTime);

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

function getStudyTableHTML()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT vr20_studylog.id, vr20_studylog.userid, vr20_studytopics.course, vr20_studyactivities.activity,vr20_users.firstname,
    vr20_users.lastname, time, day 
                                FROM vr20_studylog  
                                JOIN vr20_studytopics  on vr20_studylog.course=vr20_studytopics.id
                                JOIN vr20_studyactivities  on vr20_studylog.activity=vr20_studyactivities.id
                                JOIN vr20_users ON vr20_users.id = vr20_studylog.userid
                                order by id asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $useridFromDB, $courseNameFromDB, $activityNameFromDB, $firstFromDB, $lastFromDB, $elapsedTimeFromDB, $dateFromDB );
    $stmt->execute();

    $rowCount = 1;
    while ($stmt->fetch()) {

        $response .= '<tr>
        <th scope="row">' . $rowCount . '</th>
        <td>' . $courseNameFromDB . '</td>
        <td>' . $activityNameFromDB . '</td>
        <td>' . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $elapsedTimeFromDB . '</td>
        <td>' . $dateFromDB . '</td>
        <td>' . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"  . $firstFromDB." ". $lastFromDB. '</td>
        </tr>';

        $rowCount += 1;
    }

    if ($response == null) {
        $response = "Tegevusi ei ole lisatud!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}