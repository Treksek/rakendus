<?php

require("../../../../configuration.php");
    //session_start();

    require("classes/session.class.php");

	SessionManager::sessionStart("vr20", 0, "/~daire.luus/", "tigu.hk.tlu.ee");
    

    if(!isset($_SESSION["userid"])){
        header("Location: page.php");
    
    }

    if (isset($_GET["logout"])){
        session_destroy();
        header("Location: page.php");
    }
require("fnc_study.php");

$studyTopicsOptions = getStudyTopicsOptions();
$studyActivitiesOptions = getStudyActivitiesOptions();

$studyTopicId = null;
$studyActivity = null;
$elapsedTime = null;
$studyError = null;

if (isset($_POST['studyBtn'])) {

    if (isset($_POST["studyTopicId"]) and !empty(test_input($_POST["studyTopicId"]))) {
        $studyTopicId = test_input($_POST["studyTopicId"]);
    } else {
        $studyError .= "Õppeaine on valimata! ";
    }

    if (isset($_POST["studyActivity"]) and !empty(test_input($_POST["studyActivity"]))) {
        $studyActivity = test_input($_POST["studyActivity"]);
    } else {
        $studyError .= "Tegevus on valimata! ";
    }

    if (isset($_POST["elapsedTime"]) and !empty(test_input($_POST["elapsedTime"])) and $_POST["elapsedTime"] != 0) {
        $elapsedTime = test_input($_POST["elapsedTime"]);
    } else {
        $studyError .= "Aeg on määramata! ";
    }

    //Saadame andmebaasi
    if (empty($studyError)) {

        $response = saveStudy($studyTopicId, $studyActivity, $elapsedTime);

        if ($response == 1) {
            $studyError = "Salvestatud!";
        } else {
            $studyError = "Salvestamisel tekkis tõrge!";
        }
    }
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

    
                <h1>Õppetegevus</h1>
                <p>See leht on valminud õppetöö raames!</p>
                <p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
                
           

            <div>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div>
                        <div>
                        <label>Vali siit õpitud õppeaine:</label>
                            <select name="studyTopicId">
                                <option value="" selected disabled>Õppeaine</option>
                                <?php echo $studyTopicsOptions; ?>
                            </select>
                        </div>
                        <div>
                        <label>Vali siit tehtud tegevus:</label>
                            <select name="studyActivity">
                                <option value="" selected disabled>Tegevus</option>
                                <?php echo $studyActivitiesOptions; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div>
                            <br>
                            <label>Kulunud aeg 15 minuti kaupa (15min = 0,25):</label>
                            <input type="number" min=".25" max="24" step=".25" name="elapsedTime">
                        </div>
                    </div><br>
                    <input type="submit" name="studyBtn" value="Salvesta tegevus!"><br><br>
                    <span><?php echo $studyError; ?></span>
                </form>
            </div>
        </section>
    </div>

    
</body>

</html>