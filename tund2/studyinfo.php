<?php

require("../../../../configuration.php");
require("fnc_study.php");

$studyTableHTML = getStudyTableHTML();

?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine 2020</title>
    
</head>

<body>
    <div class="container" style="max-width: 100%; margin-top:10px;">
        <section class="text-center">
            <h1 class="jumbotron-heading">Õppetegevus</h1>
            <p class="lead text-muted">See leht on valminud õppetöö raames!</p>
            <br>
        </section>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">NR</th>
                    <th scope="col">Õppeaine</th>
                    <th scope="col">Tegevus</th>
                    <th scope="col">Kulunud aeg</th>
                    <th scope="col">Kuupäev</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $studyTableHTML; ?>
            </tbody>
        </table>
    </div>

    
</body>

</html>