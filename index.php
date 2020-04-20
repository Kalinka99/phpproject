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
    
    <div class="hello">
        <p>Witaj! Czy uważasz, że Twój pupil jest urodzonym czempionem o idealnym wyglądzie i nienagannych manierach?
        Nie zwlekaj i zapisz go do naszego prestiżowego konkursu! Organizujemy Ogólnopolskie Wystawy Psów rasowych od 1999 roku. 
        Nasi coroczni zwycięzcy pojawiają się u nas na stronie głównej i w telewizji. Na pieski czekają medale i nagrody rzeczowe 
        (w postaci zabawek, karmy) a dla ich właścicieli nagrody pieniężne!</p>
    </div>

    <h1>Wyniki Ogólnopolskiej Wystawy Psów Rasowych</h1>
    <h1>Edycja 2019</h1>

    <?php

    ini_set('display_errors', 0);
    error_reporting(E_ERROR | E_WARNING | E_PARSE); 

    include 'autoryzacja.php';
    $conn=mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Brak połączenia z bazą...");

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if($_GET['alert'] == "modyfikacja"){
        echo "<script type='text/javascript'>alert('Rekord zmodyfikowany');</script>";
    }
        $wyniki=mysqli_query($conn,"SELECT pies.pies_id, wlasciciel.wlasciciel_id,
        imie_psa, imie, nazwisko, zajete_miejsce, ocena_jury
        FROM pies, wlasciciel, wynik 
        WHERE pies.pies_id=wlasciciel.pies_id 
        AND pies.pies_id=wynik.pies_id 
        AND wynik.zajete_miejsce IS NOT NULL 
        ORDER BY zajete_miejsce ASC LIMIT 10;");

        // WYNIKI KONKURSU 2019

        echo '<center><table style="margin-bottom:30px;">';
        echo"
            <tr>
            <th>"."Zajęte miejsce"."</th><th>"."Imię psa"."</th><th>"."Ocena jury"."</th><th>"."Imię Właściciela"."</th><th>"."Nazwisko"."</th>
            </tr>";
        while($row=mysqli_fetch_array($wyniki))
        {
            echo
            "<tr><td>".$row['zajete_miejsce']."</td><td>".$row['imie_psa']."</td><td>".$row['ocena_jury'].
            "</td><td>".$row['imie']."</td><td>".$row['nazwisko'].'</td></tr>';
        }

        echo '</table></center>';
    ?>

    <img class="bodzio" src="Bodzio.jpg" alt="maltańczyk Bodzio">
    <h2 style="text-align: center;">Bezkonkurencyjnym zwycięzcą zeszłorocznej edycji okazał się maltańczyk o imieniu Bodzio.</h2>

    <!-- FORMULARZ DODAWANIA PSA DO KONKURSU-->

    <section>    
        <div id="divleft">
            <h2> Dodaj psa: </h2>
                <form action="index.php" method="POST">
                        Imię: <br>
                        <input name="pies_imie"> <br>
                    <h3> Podaj cechy: </h3>
                        Rasa:<br>
                        <input name="rasa"> <br>
                        Płeć: <br>
                        <input name="plec"> <br>
                        Kolor:<br>
                        <input name="kolor"> <br>
                        Wzrost (w cm): <br>
                        <input name="wzrost"> <br>
                        Wiek (w latach): <br>
                        <input name="wiek"> <br>
                        Waga (w kg): <br>
                        <input name="waga"> <br>
                    <h3> Dane właściciela: </h3>
                        Imię: <br>
                        <input name="imie"> <br>
                        Nazwisko: <br>
                        <input name="nazwisko"> <br><br>
                    <input type="reset" value="Wyczyść">
                    <input type="hidden" name="sub" value="1">
                    <!-- obecność wartości sub świadczy o tym, że formularz został przesłany-->
                    <input type="submit" value="Wyślij">
                </form>
        </div>

        <!-- FORMULARZ WYSZUKIWANIA PSA W BAZIE-->
        
        
        <div id="divright">
            <h2> Wyszukaj: </h2>
                <form action="index.php" method="POST">
                        Imię psa: <br>
                        <input name="search_pies_imie"> <br>
                    <h3>Właściciel </h3>
                        Imię: <br>
                        <input name="search_imie"> <br>
                        Nazwisko: <br>
                        <input name="search_nazwisko"> <br><br>
                    <input type="reset" value="Wyczyść">
                    <input type="submit" value="Wyszukaj">
                </form>
        </div>
    </section>

    <?php
        
    // USUWANIE :( 
    
    if($_GET['pies_id'] != "" && $_GET['X'] == "y"){
        
        $mysqli->query("DELETE FROM wlasciciel WHERE pies_id='".$_GET['pies_id']."';" );
        $mysqli->query("DELETE FROM cechy_fizyczne WHERE pies_id='".$_GET['pies_id']."';" );
        $mysqli->query("DELETE FROM wynik WHERE pies_id='".$_GET['pies_id']."';" );
        $mysqli->query("DELETE FROM pies WHERE pies_id='".$_GET['pies_id']."';" );
        
        $mysqli->commit();
    }
    
    
    //DODAWANIE

    if(($_POST['pies_imie']!="")&&($_POST['rasa']!="")&&($_POST['plec']!="")&&($_POST['kolor']!="")
    &&($_POST['wzrost']!="")&&($_POST['wiek']!="")&&($_POST['waga']!="")&&($_POST['imie']!="")&&($_POST['nazwisko']!=""))
        {
            $wexist = "SELECT * FROM wlasciciel WHERE imie='".$_POST['imie']."' AND nazwisko='".$_POST['nazwisko']."';";
            if(mysqli_num_rows(mysqli_query($conn,$wexist))==0)
                {
                    $mysqli->query("INSERT INTO pies(imie_psa) VALUES ('".$_POST['pies_imie']."');");
                    $mysqli->query("INSERT INTO cechy_fizyczne(rasa, plec, kolor, wzrost, wiek, waga, pies_id)
                    VALUES ('".$_POST['rasa']."','".$_POST['plec']."','
                    ".$_POST['kolor']."','".$_POST['wzrost']."','
                    ".$_POST['wiek']."','".$_POST['waga']."', LAST_INSERT_ID());");
                    $mysqli->query("INSERT INTO wlasciciel(imie,nazwisko, pies_id)
                    VALUES ('".$_POST['imie']."','".$_POST['nazwisko']."',LAST_INSERT_ID());");
                    $mysqli->query("INSERT INTO wynik(pies_id) VALUES (LAST_INSERT_ID());");
                    $mysqli->commit();
                }
                else
                {
                    if($_POST['sub'] == '1')
                        {
                            echo "<script type='text/javascript'>alert('Osoba o podanym imieniu i nazwisku zgłosiła już psa do konkursu. Zgodnie z regulaminem, jeden właściciel może zgłosić tylko jednego psa.');</script>";
                        }
                }

            }
            else
            {
                if($_POST['sub'] == '1')
                {
                    echo "<script type='text/javascript'>alert('Aby dodać rekord uzupełnij wszystkie pola');</script>";
                }
            }
    ?>


<!--WYSZUKIWANIE I WSZYSTKIE REKORDY-->

<div id="records" >

<?php

    //WYSZUKIWANIE

    if($_POST['search_pies_imie']!=""||($_POST['search_imie']!=""&&$_POST['search_nazwisko']!=""))
        {        
        if($_POST['search_pies_imie']!="")
            {
            $search=mysqli_query($conn,"SELECT pies.pies_id, imie_psa, rasa, plec, kolor, wzrost, wiek, waga 
            FROM pies, cechy_fizyczne WHERE imie_psa='".$_POST['search_pies_imie']."' AND pies.pies_id=cechy_fizyczne.pies_id;");

            echo '<center><table><h2>Wyniki wyszukiwania:</h2><br>';
                echo"
                <tr>
                    <th>"."Imię psa"."</th><th>"."Rasa"."</th><th>"."Płeć"."</th><th>"."Kolor"."</th><th>"."Wzrost"."</th><th>"."Wiek"."</th><th>"."Waga"."</th><th>"."Usuń"."</th>
                </tr>";
                while($row=mysqli_fetch_array($search))
                {
                    echo "<tr><td>".$row['imie_psa']."</td><td>".$row['rasa']."</td><td>".$row['plec']."</td><td>".$row['kolor']."</td><td>".$row['wzrost']."</td><td>".$row['wiek']."</td><td>".$row['waga']."</td><td>"
                    .'<a href="main.php">X</a></td></tr>';
                }
            echo '</table></center>';
        }

        if($_POST['search_imie']!=""&&$_POST['search_nazwisko']!="")
        {
            $search2=mysqli_query($conn,"SELECT pies.pies_id, imie_psa, imie, nazwisko, wlasciciel.wlasciciel_id FROM wlasciciel, pies WHERE 
            imie='".$_POST['search_imie']."' AND nazwisko='".$_POST['search_nazwisko']."' AND pies.pies_id=wlasciciel.pies_id;");
            
            echo '<center><table><h2>Wyniki wyszukiwania:</h2><br>';
                echo"
                <tr>
                    <th>"."Imię"."</th><th>"."Nazwisko"."</th><th>"."Imię psa"."</th><th>"."Usuń"."</th>
                </tr>";
                while($row=mysqli_fetch_array($search2))
                {
                        echo "<tr><td>".$row['imie']."</td><td>".$row['nazwisko']."</td><td>".$row['imie_psa']."</td><td>"
                        .'<a href="main.php">X</a>'.'</td>'.'</tr>';
                }
            echo '</table></center>';
        }        
                
    }

    //WSZYSTKIE REKORDY    
 
    $show=mysqli_query($conn,"SELECT pies.pies_id, wlasciciel.wlasciciel_id,
    imie_psa, imie, nazwisko, zajete_miejsce, ocena_jury, rasa, plec, kolor, wzrost, wiek, waga 
    FROM pies, wlasciciel, wynik, cechy_fizyczne 
    WHERE pies.pies_id=wlasciciel.pies_id 
    AND pies.pies_id=wynik.pies_id
    AND pies.pies_id=cechy_fizyczne.pies_id;");

    echo '<center><table><h2>Wszystkie psy zgłoszone do konkursu:<h2><br>';
        echo"
            <tr>
            <th>"."Imię psa"."</th><th>"."Rasa"."</th><th>"."Imię właściciela"."</th><th>"."Nazwisko"."</th><th>"."Usuń"."</th><th>"."Modyfikuj"."</th>
            </tr>";
            while($row=mysqli_fetch_array($show))
            {
                echo
                "<tr><td>".$row['imie_psa']."</td><td>".$row['rasa']."</td><td>".$row['imie']."</td><td>".$row['nazwisko']."</td><td>"
                .'<a href="index.php?pies_id='.$row['pies_id'].'&usun=y">X</a>'.'</td><td>'
                .'<a href="modyfikuj.php?pies_id='.$row['pies_id'].'">modyfikuj</a>'.'</td>'.'</tr>';
            }
    echo '</table></center>';
?>
</div>
</body>
</html>