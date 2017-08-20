<!DOCTYPE html>

<?php
    // TÃ¤mÃ¤ funktiokutsu jokaiseen
    // istuntoon sisÃ¤ltyvÃ¤n php-sivun alkuun
    $y_tiedot = "host= port= dbname= user= password=";
    if (!$yhteys = pg_connect($y_tiedot))
      die("Tietokantayhteyden luominen epäonnistui.");

    session_start();

    echo '<table>';
    $op_nro = intval($_SESSION['op_nro']);
    $kayttajaNimi = pg_fetch_row(pg_query("SELECT nimi FROM kayttaja WHERE opnro = '$op_nro'"));
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
   //Jos käyttäjä lopettaa siirrytään etusivulle ja lopetetaan sessio
   if (isset($_POST['lopeta'])) {
      session_destroy();
      header('Location: index.php');
 }

 $tkNumero = pg_fetch_row(pg_query("SELECT COUNT(tk_id) FROM tehtavakok;"));
 pg_close($yhteys);
?>

<html>
    <head>
        <meta charset="utf-8" />
        <link href="/style.css" rel="stylesheet" />
        <title>Valikko</title>
    </head>
    <body>
      <p> valitse tehtäväkokonaisuus. </p>
        <form method="post" action="valikko.php">
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
