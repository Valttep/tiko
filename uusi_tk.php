<!DOCTYPE html>

<?php
   //tehtavakokoanisuuden lisäys sivu.
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
      die("Tietokantayhteyden luominen epäonnistui.");

    session_start();
    $kuvaus = '';
    $tyyppi = '';
    $vastaus = '';
    //kysymystenmäärä
    $kMaara = intval($_SESSION['tMaara']);
    $tehtavaLaskuri = 1;
    if(intval($_SESSION['tla']) > null){
      $tehtavaLaskuri = intval($_SESSION['tla']);
      $kyselyTuloste = $_SESSION['kyselytuloste'];
    }
    //tarkistetaan että käyttäjällä on oikeus.
    $op_nro = intval($_SESSION['op_nro']);
    $kayttajaNimi = pg_fetch_row(pg_query("SELECT nimi FROM kayttaja WHERE opnro = '$op_nro'"));
    $oikeus = pg_fetch_row(pg_query("SELECT oikeudet FROM kayttaja WHERE opnro = '$op_nro'"));
    if($oikeus[0] != 't'){
      session_destroy();
      header('location: index.php');
   } else {
      echo 'Käyttäjä: ' . $kayttajaNimi[0] . ' olet oikeutettu käyttämään aluetta. <br/>';
   }
   //tarkistus loppuu ^^^^^^

   //tulostetaan kysymysmäärä
   echo 'kysymyksiä annettavana: ' . $kMaara . '<br>';
   //Tulostetaan linkit sql kaavioon ja tietokannatilaan.
   echo '<tr> <td><a href="SQL-kaavio.PNG"> Avaa sql-kaavio </a></td> <td> <a href="tietokannantila.pdf">Tietokannan tila</a> </tr>';
   //lisätään kysymys

   if(isset($_POST['kLisaa']) && $kMaara > 0 && $_POST['tKuvaus'] != '' && $_POST['kTyyppi'] != '' && $_POST['kVastaus'] != ''){
      $kMaara = $kMaara - 1;
      $_SESSION['tMaara'] = $kMaara;
      //datat kyselyyn
      $temp = 100;
      $temp2 = intval($_SESSION['tkNumeroSes']);
      $tehtavaNumero = $temp*$temp2+$tehtavaLaskuri;
      $_SESSION['tla'] = $tehtavaLaskuri + 1;
      $kuvaus = $_POST['tKuvaus'];
      $tyyppi = $_POST['kTyyppi'];
      $vastaus = $_POST['kVastaus'];
      $kokonaisuudenNumero = intval($_SESSION['tkNumeroSes']);
      //kysely
      $tehtavaLisaysKysely = "INSERT INTO tehtava(tehtava_id, kuvaus, kyselytyyppi, vastaus, tk_id) VALUES('$tehtavaNumero', '$kuvaus', '$tyyppi', '$vastaus', '$kokonaisuudenNumero');";

      $tehtavanLisays = pg_query($tehtavaLisaysKysely);
      if(!$tehtavanLisays){
         echo '<h1> Virhe kyselyssä </h1>';
         $kyselyTuloste = '';
      } else {
         $_SESSION['kyselytuloste'] = 'Lisätty <br>Kuvaus: ' . $_POST['tKuvaus'] . '<br>Kyselyn tyyppi: ' . $_POST['kTyyppi'] . '<br>Kyselyn vastaus: ' . $_POST['kVastaus'];
         header('location: uusi_tk.php');

      }
   } else if($kMaara <= 0){
      $viesti = 'Kysymykset annettu!';
      $poistu = '<form method="post" action="uusi_tk.php"> <input type="submit" name="lopeta" value="Poistu" /> </form>';
   }
   if(isset($_POST['lopeta'])){

      header('location: adminvalikko.php');
   }

pg_close($yhteys);

?>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Uuden tehtäväkokonaisuuden luonti</title>
    </head>
    <body>
      <?php if (isset($viesti)) echo '<p style="color:red">'.$viesti.'</p>'; ?>
      <form method="post" action="uusi_tk.php">
         <p> Anna tehtävän tiedot: </p>
         <p> Tehtävän kuvaus </p>
         <input type="text" name="tKuvaus" placeholder="Esim. Hae kaikkien oppilaiden nimet" /> <br />
         <p> Tehtävän tyyppi </p>
         <input type="text" name="kTyyppi" placeholder="Esim. sql-kysely"/><br />
         <p> Tehtävän oikea muotoinen kysely </p>
         <input type="text" name="kVastaus" placeholder="Esim. SELECT nimi FROM opiskelija;" /><br />
         <input type="submit" name="kLisaa" value="Lisää kysymys"/>
      </form>
      <?php if (isset($kyselyTuloste)) echo '<p>Varmista että tehtävä on oikein ja paina alla olevaa painiketta, jos haluat lisätä tehtävän. <br />' .$kyselyTuloste.'</p> <form method="post" action="uusi_tk.php"> <input type="submit" name="varmasti" value="Haluatko varmasti lisätä?" /> </form>'; ?>
      <?php if(isset($poistu)) echo '<p>' . $poistu . '</p>'; ?>
    </body>
</html>
