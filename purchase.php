<?php
// Session kezdése
    session_start();
// Config fájl hívás
    require_once('config.php');
// Feldolgozás
        if(isset($_GET['id']) )
        {               
            $buyer_id = $_SESSION["Vid"];
            $coffee_id = $_GET["id"];
                // purchase táblába beszúrni a vásárlást + darabszám növelése (megvásárolt termék)
                $mysql->query("INSERT INTO purchase (coffeeID,vevoID,Pdb) VALUES ($coffee_id,$buyer_id,$DEFAULT_BUY)");                
                // Darabszám csökkentése (update)
                    // Ha nincs az lekezelésre kerül a coffee.php-ban
                $mysql -> query("UPDATE coffee SET Cdb = Cdb -1 WHERE Cid = $coffee_id ");
                header("location: purchases.php");
                exit;
        }
?>