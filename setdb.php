<?php
    // Session kezdése
        session_start();
    // Config gile hívása
        require_once('config.php');
    // Feldolgozás
        if(isset($_GET['id']))
        {
            // Kávé ID megszerzése
                // Vásárlás ID
                    $IDofPurch = $_GET['id'];
                // Adat lekérése
                    $getter = $mysql->query("SELECT * FROM purchase WHERE Pid = $IDofPurch AND vevoID = {$_SESSION["Vid"]} ");
                // Adat tárolás
                    $res = $getter->fetch_assoc();
                // Kávé ID
                $coffe_id = $res["coffeeID"];
            // Plusz darabszám, ha megnyomta
                $NewCoffeeDB = $mysql->real_escape_string($_GET["db"]);
            // Frissíteni a vásárlás táblát
                $newDB = intval($DEFAULT_BUY) + intval($NewCoffeeDB);
                $mysql->query("UPDATE purchase SET Pdb = $newDB WHERE Pid = '$IDofPurch' AND vevoID = {$_SESSION["Vid"]} AND coffeeID = '$coffe_id' ");
            // Frissíteni a készletet
                $mysql->query("UPDATE coffee SET Cdb = Cdb - $NewCoffeeDB WHERE Cid = $coffe_id");
            // Ha oké minden, vissza a purchases fülre
                header("location: purchases.php");
                return;

        }

?>