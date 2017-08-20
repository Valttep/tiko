<!DOCTYPE html>

<?php
   //Valikko kayttajille joilla oikeus tehdä uusia tehtavakokoanisuuksia.
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
      die("Tietokantayhteyden luominen epäonnistui.");

    session_start();

    echo '<table>';
    $op_nro = intval($_SESSION['op_nro']);
    $kayttajaNimi = pg_fetch_row(pg_query("SELECT nimi FROM kayttaja WHERE opnro = '$op_nro'"));
    $oikeus = pg_fetch_row(pg_query("SELECT oikeudet FROM kayttaja WHERE opnro = '$op_nro'"));
    if($oikeus[0] != 't'){
      session_destroy();
      header('location: index.php');
   } else {
      echo 'Käyttäjä: ' . $kayttajaNimi[0] . ' olet oikeutettu käyttämään aluetta.';
   }
    // Tulostetaan vanhat sessiomuuttujat
    echo '<tr><td>Tunnistettu </td><td> ';
    echo '<tr><td>Nimi: </td><td> '              . $kayttajaNimi[0]    . '</td></tr>';
    echo '<tr><td>Opiskelijanumero: </td><td> '  . $_SESSION['op_nro']  . '</td></tr>';

    // Luetaan syote ja alustetaan uusi sessiomuuttuja
    echo '</table><br />';

    if (isset($_POST['valitse'])) {
      $_SESSION['tehtavaKok'] = $_POST['tehtavaKok'];
      $_SESSION['yJ'] = 3;
      header('Location: kyselysivua.php');
   }
   //Jos käyttäjä luo uuden tehtavakokonaisuuden, siirrytään tehtäväkokonaisuuden luonti sivulle.
   if(isset($_POST['uusiTehtavaKokonaisuus'])){
      header('Location: uusi_tkm.php');
   }
   //jos käyttäjä haluu tarkastella miten tehtäväkokonaisuuksista on suoriuduttu.
   if(isset($_POST['tarkastele'])){
      $_SESSION['tk_t'] = $_POST['tk_tarkast'];
      header('Location: ayhteenveto.php');
   }
   //Jos käyttäjä lopettaa siirrytään etusivulle ja lopetetaan sessio
   if (isset($_POST['lopeta'])) {
      session_destroy();
      header('Location: index.php');
 }
 if (isset($_POST['tarkasteleses'])) {
    $_SESSION['sesnum'] = $_POST['sessionumero'];
    header('Location: sesyhteenveto.php');
}
 $tkNumero = pg_fetch_row(pg_query("SELECT COUNT(tk_id) FROM tehtavakok;"));
 pg_close($yhteys);
?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>admin valikko</title>
    </head>
    <body>
      <form method="post" action="adminvalikko.php">
          <p> Tehtavakokonaisuuden luominen: </p>
          <input type="submit" name ="uusiTehtavaKokonaisuus" value="Luo uusi"/>
          <p> Tarkastele tietyn tehtäväkokonaisuuden suoritusta. </p>
          <select name="tk_tarkast">
                <?php
                   for($i=1; $i <= $tkNumero[0]; $i++){
                      echo "<option value=".$i.">".$i."</option>";
                   }
                 ?>

          </select>
          <input type="submit" name="tarkastele" value="Tarkastele"/>
      </form>
      <form method="post" action="adminvalikko.php">
          <p> Tarkastele tiettyä sessiota: (Esim. 111111 tai 222239)</p>
          <input type="text" name ="sessionumero" />
          <input type="submit" name="tarkasteleses" value="Tarkastele sessiota"/>
      </form>
      <p> Vastaa tehtäväkokonaisuus: </p>
      <p> valitse tehtäväkokonaisuus. </p>
        <form method="post" action="adminvalikko.php">
            <select name="tehtavaKok">
                  <?php
                     for($i=1; $i <= $tkNumero[0]; $i++){
                        echo "<option value=".$i.">".$i."</option>";
                     }
                   ?>

            </select>
            <input type="submit" name ="valitse" value="Valitse"/>
            <input type="submit" name ="lopeta" value="Lopeta"/>
        </form>
    </body>
</html>
