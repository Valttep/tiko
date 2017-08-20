<!DOCTYPE html>

<?php


    //Session aloitus funktiokutsu
    session_start();
    echo '<html> <head> <meta charset="utf-8" /> </head> <body> <table cellspacing="40px">';
    //otetaan yhteystietokantaan
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
      $sId = intval($_SESSION['sId']);

      $haku = pg_query("SELECT tehtava_id, yritykset, oikea_vastaus FROM yritykset WHERE sessio_id ='$sId'");
      while($tulokset = pg_fetch_row($haku)){
         echo "<tr> <td>Tehtävä: $tulokset[0],</td> <td> Kuinka monta yritystä: $tulokset[1], </td> <td>Menikö oikein: $tulokset[2]</td> </tr>";

      }
   if(isset($_POST['valikkoon'])){
      $_SESSION['sId'] = null;
      $_SESSION['tl'] = 1;
      $_SESSION['yJ'] = 3;
      $_SESSION['viesti'] = '';
      header('Location: valikko.php');
   }
   echo '</table>';
   echo '<form method="post" action="yhteenveto.php"> <input type="submit" name="valikkoon" value="Päävalikkoon" /> </form>';
   echo '</body></html>';
?>
