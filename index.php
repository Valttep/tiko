<!DOCTYPE html>

<?php

   $y_tiedot = "host= port= dbname= user= password=";
   if (!$yhteys = pg_connect($y_tiedot))
      die("Tietokantayhteyden luominen epäonnistui.");
    // Session funktiokutsu
    session_start();

    //kun jatka painiketta painetaa
    if (isset($_POST['lomake1'])) {
      //luetaan käyttäjän syötteet (ks = kayttajan syöte)
      $ks_op_id = intval($_POST['o_id']);
      $ks_op_nro = intval($_POST['op_nro']);
      //haetaam ks_
      $vertailuOpiskelijaNro = pg_fetch_row(pg_query("SELECT opnro FROM kayttaja WHERE kayttaja_id = '$ks_op_id'"));
      if($vertailuOpiskelijaNro[0] == $ks_op_nro){
         $_SESSION['o_id'] = $_POST['o_id'];
         $_SESSION['op_nro'] = $_POST['op_nro'];
         $oikeus = pg_fetch_row(pg_query("SELECT oikeudet FROM kayttaja WHERE kayttaja_id = '$ks_op_id'"));
         if($oikeus[0] == 't'){
            header('Location: adminvalikko.php');
         } else {
            header('Location: valikko.php');
         }

      } else {
         $viesti = 'Kayttaja id tai opiskelijanumero on väärin.';
      }

    }
pg_close($yhteys);
?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>tunnistaudu</title>
    </head>
    <body>
        <form method="post" action="index.php">
            <table>
               <tr><td> <h1> SQL-testi </h1> </td></tr>
               <tr><td> <h2> Ole hyvä ja tunnistaudu <br/>
               <?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>
               </h2> </td> </tr>

                <tr><td>käyttäjä id: </td><td><input type="text" name="o_id" value=""/></td></tr>
                <tr><td>Opiskelijanumero: </td><td><input type="text" name="op_nro" value=""/></td></tr>
                <tr><td></td><td><input type="submit" name="lomake1" value="Jatka"/></td></tr>
            </table>
        </form>
    </body>
</html>
