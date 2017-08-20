<!DOCTYPE html>

<?php

    // TÃ¤mÃ¤ funktiokutsu jokaiseen
    // istuntoon sisÃ¤ltyvÃ¤n php-sivun alkuun
    session_start();

    // Luetaan syote ja alustetaan sessiomuuttujat
    if (isset($_POST['lomake1'])) {
        $_SESSION['nimi'] = $_POST['nimi'];
        $_SESSION['op_nro'] = $_POST['op_nro'];
        $_SESSION['paaaine'] = $_POST['paaaine'];

        // ja siirrytÃ¤Ã¤n sivulle lomake2.php
        header('Location: lomake2.php');
    }

?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>Esimerkki PHP-sessiosta (1)</title>
    </head>
    <body>
        <form method="post" action="lomake1.php">
            <table>
                <tr><td>Nimi: </td><td><input type="text" name="nimi" value=""/></td></tr>
                <tr><td>Opiskelijanumero: </td><td><input type="text" name="op_nro" value=""/></td></tr>
                <tr><td>P&auml;&auml;aine: </td><td><input type="text" name="paaaine" value="" /></td></tr>
                <tr><td></td><td><input type="submit" name="lomake1" value="Jatka"/></td></tr>
            </table>
        </form>
    </body>
</html>
