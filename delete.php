<?php
    // Session kezdése
        session_start();
    // Config fájlok hívása
        require_once ('config.php');
    // purchase id?=.., lekérése
        if(isset($_GET['did']))
        {   
            // Vásárlás ID
                $IDofPurch = $_GET['did'];
            // Adat lekérés
                $getter = $mysql->query("SELECT * FROM purchase WHERE Pid = $IDofPurch AND vevoID = {$_SESSION["Vid"]} ");

            // Adat letárolás
                $res = $getter->fetch_assoc();
            // Kávé ID (törlendő)
                $coffee_id = $res["coffeeID"];
            // Vásárlások száma
                $count = $res["Pdb"];
            // Törlendő darabszám
                $NewCoffeeDB = $mysql->real_escape_string($_GET["ddb"]);
            // Ha minden törli
                if($count == $NewCoffeeDB)
                {   
                    // plus
                        $plus = intval($NewCoffeeDB);
                    // Vásárlás törlése
                        $mysql->query("DELETE FROM purchase WHERE Pid = $IDofPurch");
                    // Darabszám frissítése
                        $mysql->query("UPDATE coffee SET Cdb = Cdb + $plus WHERE Cid = $coffee_id");
                    // Maradunk a vásárlásaim alatt, hátha több volt a vásárlás és többet szeretne törölni
                        header("location: purchases.php");
                        exit;
                }
                // Ha kevesebbet
                elseif($count > $NewCoffeeDB)
                {   
                    // Result
                        $res = intval($count) - intval($NewCoffeeDB);
                    // Frissíteni a vásárlást
                        $mysql->query("UPDATE purchase SET Pdb = $res");
                    // Frissíteni a darabszámot
                        $mysql->query("UPDATE coffee SET Cdb = Cdb+ $NewCoffeeDB WHERE Cid = $coffee_id");
                    // Maradunk a vásárlásaim alatt, hátha több volt a vásárlás és többet szeretne törölni
                        header("location: purchases.php");
                        exit;
                }
                // Többet írt be és nem a legördülőből választ nincs semmi, mert bevan állítva a max = ""...
            
        }    
?>