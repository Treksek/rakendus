<?php


// Piltide andmbaasi salvestamie funktsioon
    function uploadPhoto($userid, $filename, $alttext, $privacy){
        //andmebaasi serveriga ühenduse loomine
        $conn = new mysqli( $GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"] );

        //andmebaasipäringu ettevalmistamine
        $stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, alttext, privacy) VALUES (?,?,?,?)"); //klassist conn
        echo $conn->error;//kui tekib mingi viga, siis annab veateate
        $userid = $_SESSION["userid"];
        $stmt->bind_param("issi", $userid, $filename,  $alttext, $privacy); //s=string, i=integer, d=decimal
        $notice = null;
        if ($stmt->execute()) {
            $notice = "Piltide salvestamine õnnestus.";
        } else {
            $notice = "Kahjuks tekkis tehniline viga: " .$stmt->error;
        }

        //andmebaasi ühenduse sulgemine
        $stmt->close();
        $conn->close();
        return $notice;


    }