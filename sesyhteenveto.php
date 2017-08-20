<!DOCTYPE html>

<?php


    //Session aloitus funktiokutsu
    session_start();
    echo '<html> <head> <meta charset="utf-8" /> </head> <body> <table cellspacing="40px">';
    //otetaan yhteystietokantaan
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");
      $sesid = intval($_SESSION['sesnum']);
      $laskuri = 0;
      $l2 = 0;
      $haku = pg_query("SELECT DISTINCT tehtava_id, yritykset, oikea_vastaus FROM yritykset WHERE sessio_id = '$sesid'");
      if(!$haku){
         echo 'Väärä sessio_id';
      } else {
         while($tulokset = pg_fetch_row($haku)){
            if(strcmp($tulokset[2], 'f')){
               $laskuri++;
            }
            $l2++;
            echo "<tr> <td>Tehtävä: $tulokset[0],</td> <td> Kuinka monta yritystä: $tulokset[1], </td> <td>Menikö oikein: $tulokset[2]</td> </tr>";

         }
         $prosOikein = $laskuri/$l2;
         $prosOikein = $prosOikein*100;

         echo $prosOikein . '% oikeiden vastausten määrä tehtäväkokonaisuudessa kaikkien vastanneiden kesken.';

      if(isset($_POST['valikkoon'])){
         $_SESSION['sId'] = null;
         $_SESSION['tl'] = 1;
         $_SESSION['yJ'] = 3;
         $_SESSION['viesti'] = '';
         header('Location: adminvalikko.php');
      }
      echo '</table>';
      echo '<form method="post" action="yhteenveto.php"> <input type="submit" name="valikkoon" value="Päävalikkoon" /> </form>';
      echo '</body></html>';
   }
?>
