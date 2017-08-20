<!DOCTYPE html>

<?php

    // TÃ¤mÃ¤ funktiokutsu jokaiseen
    // istuntoon sisÃ¤ltyvÃ¤n php-sivun alkuun
    session_start();

    echo '<table>';

    // Tulostetaan vanhat sessiomuuttujat
    echo '<tr><td>Nimi: </td><td> '              . $_SESSION['nimi']    . '</td></tr>';
    echo '<tr><td>Opiskelijanumero: </td><td> '  . $_SESSION['op_nro']  . '</td></tr>';
    echo '<tr><td>P&auml;&auml;aine: </td><td> ' . $_SESSION['paaaine'] . '</td></tr>';

    // Luetaan syote ja alustetaan uusi sessiomuuttuja
    if (isset($_POST['lomake2']) && $_POST['syote'] != '') {
        if (!isset($_SESSION['syote'])) {
            $_SESSION['syote'] = $_POST['syote'];
        } else {
            $_SESSION['syote'] = $_SESSION['syote'] . ' ' . $_POST['syote'];
        }
        // ja tulostetaan se
        echo '<tr><td>Sy&ouml;te: </td><td>' . $_SESSION['syote'] . '</td></tr>';
    }

    echo '</table><br />';

    // Lopetetaan sessio
    if (isset($_POST['lopeta'])) {
        session_destroy();
        // ja siirrytÃ¤Ã¤n sivulle lomake1.php
        header('Location: lomake1.php');
    }

?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>Esimerkki PHP-sessiosta (2)</title>
    </head>
    <body>
        <form method="post" action="lomake2.php">
            <textarea name="syote" rows="10" cols="50"></textarea><br />
            <input type="submit" name ="lomake2" value="LisÃ¤Ã¤"/>
            <input type="submit" name ="lopeta" value="Lopeta"/>
        </form>
    </body>
</html>
