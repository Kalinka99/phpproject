
<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wystawa psów rasowych</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>

    <header>
        <img src="1.jpg" alt="Wystawa Psów Rasowych"/>
    </header>
    
    
    <h1 class="title">Ogólnopolskie Wystawy Psów Rasowych</h1>
  
    <?php
        ini_set('display_errors', 0);
        error_reporting(E_ERROR | E_WARNING | E_PARSE); 
        include 'autoryzacja.php';
        $conn=mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
        or die("Brak połączenia z bazą...");

        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        
        $id_pieseczka = $_GET['pies_id'];
        
        $dane_aktualne=mysqli_query($conn,"SELECT imie_psa, imie, nazwisko, rasa, plec, kolor, wzrost, wiek, waga 
        FROM pies, wlasciciel, cechy_fizyczne 
        WHERE pies.pies_id = ".$id_pieseczka." 
        AND pies.pies_id=wlasciciel.pies_id
        AND pies.pies_id=cechy_fizyczne.pies_id;");
        
        $row=mysqli_fetch_array($dane_aktualne);
    ?>


    <!-- FORMULARZ MODYFIKOWANIA -->

    <section>    
        <div id="modify">
            <h2> Modyfikuj: </h2>
                <form action="modyfikuj.php?pies_id=<?php echo $id_pieseczka; ?>" method="POST">
                        Imię: <br>
                        <input name="imie_psa" value="<?php echo $row['imie_psa']; ?>"> <br>
                    <h3> Podaj cechy: </h3>
                        Rasa:<br>
                        <input name="rasa" value="<?php echo $row['rasa']; ?>"> <br>
                        Płeć: <br>
                        <input name="plec" value="<?php echo $row['plec']; ?>"> <br>
                        Kolor:<br>
                        <input name="kolor" value="<?php echo $row['kolor']; ?>"> <br>
                        Wzrost (w cm): <br>
                        <input name="wzrost" value="<?php echo $row['wzrost']; ?>"> <br>
                        Wiek (w latach): <br>
                        <input name="wiek" value="<?php echo $row['wiek']; ?>"> <br>
                        Waga (w kg): <br>
                        <input name="waga" value="<?php echo $row['waga']; ?>"> <br>
                    <h3> Dane właściciela: </h3>
                        Imię: <br>
                        <input name="imie" value="<?php echo $row['imie']; ?>"> <br>
                        Nazwisko: <br>
                        <input name="nazwisko" value="<?php echo $row['nazwisko']; ?>"> <br><br>
                    <input type="reset" value="Wczytaj ponownie">
                    <input type="submit" value="Wyślij">
                </form>
        </div>


    <?php
        //MODYFIKACJA

        if(($_POST['imie_psa']!="")&&($_POST['rasa']!="")&&($_POST['plec']!="")&&($_POST['kolor']!="")
        &&($_POST['wzrost']!="")&&($_POST['wiek']!="")&&($_POST['waga']!="")&&($_POST['imie']!="")&&($_POST['nazwisko']!=""))
        {
            $wexist = "SELECT * FROM wlasciciel WHERE imie='".$_POST['imie']."' AND nazwisko='".$_POST['nazwisko']."' AND pies_id <>".$id_pieseczka.";";
        if(mysqli_num_rows(mysqli_query($conn,$wexist))==0 && $_POST['imie_psa'] != "" && $_POST['nazwisko'] != "" && $_POST['imie'] != "")
        {
            $mysqli->query("UPDATE pies SET imie_psa='".$_POST['imie_psa']."' WHERE pies_id=".$id_pieseczka.";");
            $mysqli->query("UPDATE cechy_fizyczne SET rasa='".$_POST['rasa']."', plec='".$_POST['plec']."', kolor='".$_POST['kolor']."', wzrost='".$_POST['wzrost']."', wiek='".$_POST['wiek']."', waga='".$_POST['waga']."' WHERE pies_id=".$id_pieseczka.";");
            $mysqli->query("UPDATE wlasciciel SET imie='".$_POST['imie']."', nazwisko='".$_POST['nazwisko']."' WHERE pies_id=".$id_pieseczka.";");
            $mysqli->commit();
            
            header('Location: index.php?alert=modyfikacja');
        }
            else echo "Taki właściciel jest już w bazie.<br>";
        }
    ?>