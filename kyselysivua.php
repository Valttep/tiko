<!DOCTYPE html>
<?php

    // istuntoon sisÃ¤ltyvÃ¤n php-sivun alkuun
    // To do

    $_SESSION['oikeinko'] = false;
    //Session aloitus funktiokutsu
    session_start();
    //otetaan yhteystietokantaan
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
       die("Tietokantayhteyden luominen epäonnistui.");

       //yritykset
       $yrityksiaJaljella = 3;
       if($_SESSION['yJ'] <= 0){
          $yrityksiaJaljella = 0;
          $viesti = 'Olet jo yrittänyt 3 kertaa! Siirry seuraavaan tehtävään tai lopeta.';
       }

       if($_SESSION['viesti'] != null){
          $viesti = $_SESSION['viesti'];
       }
       //kayttajan antama kokonaisuus;
       $tehtKokNum = intval($_SESSION['tehtavaKok']);
       //kysymysten määrä
       $kysymysMaaraKysely = "SELECT maara FROM tehtavakok WHERE tk_id = '$tehtKokNum';";
       $kysymysMaara = pg_fetch_row(pg_query($kysymysMaaraKysely));
       //laskuri
       $tehtavaLaskuri = 1;
       echo 'Tehtavakokonaisuus numero: ' . $tehtKokNum . '<br>' . 'Kysymysten määrä: ' . $kysymysMaara[0] . '<br>';
       if(intval($_SESSION['tl']) > null){
          $tehtavaLaskuri = intval($_SESSION['tl']);
       }
       //luodaan sessio_id kun sessio alkaa.
       if($_SESSION['yJ'] == 3 && $_SESSION['sId'] == null){
          $sId = pg_fetch_row(pg_query("SELECT COUNT(sessio_id) FROM yritykset;"));
          $sessioId = intval($sId[0]) + intval($_SESSION['op_nro']);
          $_SESSION['sId'] = $sessioId;
       }
       if(intval($_SESSION['yJ']) > null){
          $yrityksiaJaljella = $_SESSION['yJ'];
       }
       //luodaan muuttuja tehtavanumeroita varten;
       echo 'Vastaus kertoja jäljellä: ' . $_SESSION['yJ'] . '<br>';
          $tehtavaNum = 100;
          $tehtavaNum = $tehtavaNum*$tehtKokNum+$tehtavaLaskuri;

          if($tehtavaLaskuri <= intval($kysymysMaara[0])){
          echo 'Tehtavanumero: ' . $tehtavaNum . '<br>';
          }

          $kysymysKysely = "SELECT kuvaus FROM tehtava WHERE tk_id = '$tehtKokNum' AND tehtava_id = '$tehtavaNum'";
          //tehdään kysely
          $yj = true;
          $kysymys = pg_fetch_row(pg_query($kysymysKysely));

             if($_POST['vastaa'] && $_POST['syote'] != ''){
               $kayttajaKysely = $_POST['syote'];
                //Syoteen tarkastus tähän.

                //haetaan vastaus
               $vastausHaku = "SELECT vastaus FROM tehtava WHERE tehtava_id = '$tehtavaNum'";

                //testittuloste
                //echo 'Kayttajan syote ' . $kayttajaKysely . ' <br>';

                //Haetaan oikeaa vastausta vastaava kysely
               $vastaus = pg_fetch_row(pg_query($vastausHaku));

                //testituloste
                //echo 'Oikea syote ' . $vastaus[0] . ' <br>';

                //suoritetaan haku oikealla kyselyllä
               $vertailuHaku = pg_fetch_row(pg_query($vastaus[0]));
                //suoritetaan käyttäjän kysely
               if($yrityksiaJaljella > 0){
                   if(strpos($kayttajaKysely, 'drop') === false && strpos($kayttajaKysely, 'DROP') == false){
                      //luodaan muuttujat syotteen testausta varten
                      $syntaksiVirhe = false;
                      $sSisaan = 0;
                      $sUlos = 0;
                      //tarkistetaan syotteen sulut for-luupissa
                      for($i = 0; $i < strlen($kayttajaKysely); $i++){
                         if(char_at($kayttajaKysely, $i) == '('){
                            $sSisaan++;
                         } else if(char_at($kayttajaKysely, $i) == ')'){
                            $sUlos++;
                         }
                      }
                      //tarkistetaan päättyykö syote puolipisteeseen
                      if(char_at($kayttajaKysely, (strlen($kayttajaKysely)-1)) != ';' || $sSisaan != $sUlos){
                         $syntaksiVirhe = true;
                         //testituloste
                        // echo '<br>sisal<br>';
                      }

                      //jos kyselyssä ei ole syntaksivirhettä suoritetaan haku ja vertailu
                     if($syntaksiVirhe == false){
                         $kayttajaVastaus = pg_fetch_row(pg_query($kayttajaKysely));

                         //testituloste
                         //echo 'vastaukset ' . $vertailuHaku[0] . ' ja ' . $kayttajaVastaus[0];

                         //Tarkastetaan onko vastaus oikein.
                        if($kayttajaVastaus == $vertailuHaku){
                           $viesti = 'Vastaus oikein! <br> Ole hyvä ja siirry seuraavaan tehtävään';
                           $_SESSION['oikeinko'] = true;
                           $malliVastaus = 'mallivastaus: ' . $vastaus[0];
                        } else {
                           $viesti = 'Vastaus väärin!';
                           $_SESSION['yJ'] = $yrityksiaJaljella -1;
                           $_SESSION['viesti'] = $viesti;

                           header('location: kyselysivua.php');
                        }
                     } else{
                         $viesti = 'Syntaksivirhe!';
                         $syntaksiVirhe = false;
                         $_SESSION['yJ'] = $yrityksiaJaljella -1;
                         $_SESSION['viesti'] = $viesti;
                         header('location: kyselysivua.php');
                     }
                  } else {
                     $viesti = 'Vastaus väärin!';
                     $_SESSION['yJ'] = $yrityksiaJaljella -1;
                     $_SESSION['viesti'] = $viesti;
                     header('location: kyselysivua.php');
                  }
               } else {
                  $viesti = 'Olet jo yrittänyt 3 kertaa! Siirry seuraavaan tehtävään tai lopeta.';
                  $malliVastaus = 'mallivastaus: ' . $vastaus[0];
                  $yj = false;
               }
            }


         if($tehtavaLaskuri <= intval($kysymysMaara[0])){
            if(isset($_POST['seuraava'])){
               //kerätään kaikki tiedot mitä tarvitaan yritys-tauluun.
               $tOikein = $_SESSION['oikeinko'];
               $myBool = intval($tOikein);
               $op_nro = intval($_SESSION['op_nro']);
               $yritykset = 3 - intval($_SESSION['yJ']);
               $sessionId = intval($_SESSION['sId']);
               $kId = pg_fetch_row(pg_query("SELECT kayttaja_id FROM kayttaja WHERE opnro = '$op_nro';"));
               $kayttajaIdKyselyyn = intval($kId[0]);

               $YritysTauluunTallennus = "INSERT INTO yritykset(sessio_id, kayttaja_id, yritykset, tehtava_id, oikea_vastaus) VALUES('$sessionId', '$kayttajaIdKyselyyn', '$yritykset', '$tehtavaNum', '$myBool');";
               $kyselyTulos = pg_query($YritysTauluunTallennus);
               if(!$kyselyTulos){
                  echo '<h1> session tietojen tallennus epäonnistui </h1>';
               } else{
                  $tehtavaLaskuri = $tehtavaLaskuri + 1;
                  $_SESSION['tl'] = $tehtavaLaskuri;
                  $_SESSION['yJ'] = 3;
                  $_SESSION['viesti'] = '';
                  $_SESSION['oikeinko'] = false;
                  header('Location: kyselysivua.php');
               }
            }
         } else {
            $viesti = 'Tehtäväkokonaisuus suoritettu <form method="post" action="kyselysivua.php"> <input type="submit" name="yhteenveto" value="Yhteenveto" /> </form>';
         }


    //charAt funktio
    function char_at($str, $pos){
      return $str{$pos};
   }
   echo '<table>';

    //haetaan käyttäjänimi op_nro perusteella.
    $op_nro = intval($_SESSION['op_nro']);
    $kayttajaNimi = pg_fetch_row(pg_query("SELECT nimi FROM kayttaja WHERE opnro = '$op_nro'"));
    //^^
    //tulostetaan nimi ja kysymys
    echo '<tr><td>Käyttäjä: </td><td> ' . $kayttajaNimi[0] . '</td></tr>';
    echo '<tr><td>Kysely: </td><td>' . $kysymys[0]  .'</td></tr>';
    echo '<tr> <td><a href="SQL-kaavio.PNG"> Avaa sql-kaavio </a></td> <td> <a href="tietokannantila.pdf">Tietokannan tila</a> </tr>';
    echo '</table><br />';
    // Lopetetaan kysely ja nollataan sessiomuuttujat
    if (isset($_POST['lopeta'])) {
      $_SESSION['sId'] = null;
      $_SESSION['tl'] = 1;
      $_SESSION['yJ'] = 3;
      $_SESSION['viesti'] = '';
      $_SESSION['oikeinko'] = false;
      header('Location: valikko.php');
    }
    if (isset($_POST['yhteenveto'])) {
      $_SESSION['tl'] = 1;
      $_SESSION['yJ'] = 3;
      $_SESSION['viesti'] = '';
      $_SESSION['oikeinko'] = false;
      header('Location: yhteenveto.php');
   }
pg_close($yhteys);
?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>Kysely</title>
    </head>
    <body>
      <?php if (isset($malliVastaus)) echo '<p>'.$malliVastaus.'</p>'; ?>
      <?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>
        <form method="post" action="kyselysivua.php">
            <textarea name="syote" rows="10" cols="50"></textarea><br />
            <input type="submit" name="vastaa" value="Vastaa"/>
            <input type="submit" name="lopeta" value="Lopeta"/>
            <input type="submit" name="seuraava" value="Seuraava"/>
        </form>
    </body>
</html>
