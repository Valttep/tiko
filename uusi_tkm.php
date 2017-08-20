<!DOCTYPE html>

<?php
   //tällä sivulla varmistetaan kysymysten määrä
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
      die("Tietokantayhteyden luominen epäonnistui.");

    session_start();

    //varmistetaan oikeudet
    $op_nro = intval($_SESSION['op_nro']);
    $kayttajaNimi = pg_fetch_row(pg_query("SELECT nimi FROM kayttaja WHERE opnro = '$op_nro'"));
    $oikeus = pg_fetch_row(pg_query("SELECT oikeudet FROM kayttaja WHERE opnro = '$op_nro'"));
    if($oikeus[0] != 't'){
      session_destroy();
      header('location: index.php');
   } else {
      echo 'Käyttäjä: ' . $kayttajaNimi[0] . ' olet oikeutettu käyttämään aluetta.';
   }

   if(isset($_POST['annaMaara'])){
      //tallennetaan määrä sessiomuuttujaksi
      $_SESSION['tMaara'] = $_POST['tMaara'];
      $tehtavaMaara = intval($_POST['tMaara']);
      $tehtkokKuvaus = $_POST['tkKuvaus'];
      $omistaja = pg_fetch_row(pg_query("SELECT kayttaja_id FROM kayttaja WHERE opnro = '$op_nro';"));
      echo 'om '. $omistaja[0];
      $tkNumero = pg_fetch_row(pg_query("SELECT COUNT(tk_id) FROM tehtavakok;"));
      echo 'tk '. $tkNumero[0];
      $tkNumeroKyselyyn = intval($tkNumero[0]) + 1;
      $_SESSION['tkNumeroSes'] = $tkNumeroKyselyyn;
      $uusiTK = "INSERT INTO tehtavakok(tk_id, kuvaus, maara, omistaja) VALUES('$tkNumeroKyselyyn', '$tehtkokKuvaus','$tehtavaMaara','$omistaja[0]')";
      $luoTK = pg_query($uusiTK);
      if(!$luoTK){
         echo "Virhe kyselyssä. " . $luoTK;
      } else {
         header('Location: uusi_tk.php');
      }
   }
pg_close($yhteys);
?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>Uuden tehtäväkokonaisuuden luonti</title>
    </head>
    <body>
      <form method="post" action="uusi_tkm.php">
         <p> Syötä kysymysten määrä </p>
         <select name="tMaara">
            <option value="1"> 1 </option>
            <option value="2"> 2 </option>
            <option value="3"> 3 </option>
            <option value="4"> 4 </option>
            <option value="5"> 5 </option>
            <option value="6"> 6 </option>
            <option value="7"> 7 </option>
            <option value="8"> 8 </option>
            <option value="9"> 9 </option>
            <option value="10"> 10 </option>
         </select>
         <p> Anna kuvaus tehtäväkokonaisuudelle </p>
         <input type="text" name="tkKuvaus" />
         <input type="submit" name="annaMaara" value="Syötä tiedot"/>
      </form>

    </body>
</html>
