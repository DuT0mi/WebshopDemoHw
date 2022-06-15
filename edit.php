<?php
    // Session kezdése
        session_start();
    // Config file hívás
        require_once('config.php');
    // Ha nincs belépve valahogy vagy nem ADMIN  
        if(!LoggedIn() || ($_SESSION["Vid"] != $AdminID) )
        {
            header("location: login.php");
            exit;
        }
    // Szerkesztés - Technikai
        // Frissít
            if(isset($_POST["update"]))
            {
                $CoFFEE_ID = $mysql->real_escape_string($_POST["id"]);
                $coffeeName = $mysql->real_escape_string($_POST["CoffeName"]);
                $CoffeManu = $mysql->real_escape_string($_POST["CoffeManu"]);
                $CoffeType = $mysql->real_escape_string($_POST["CoffeType"]);
                $CoffePlaceOfOrigin = $mysql->real_escape_string($_POST["CoffePlaceOfOrigin"]);
                $Coffeedb = $mysql->real_escape_string($_POST["db"]);
                // Kép feldolgozása + update
                    // Ha a cím üres --> nincs mit intézni
                        if(!$coffeeName)
                        {
                            die("Címet kötelező megadni!");
                        }
                        else
                        {
                        // Ahol a kép mentve lesz
                            $target = "img/Added/";
                            $target = $target . basename( $_FILES['NewCoffeePic']['name']);
                        // Szervernek
                            // Volt kép
                                if(move_uploaded_file($_FILES['NewCoffeePic']['tmp_name'], $target))
                                {   
                                    $mysql->query("UPDATE coffee SET Cname = '$coffeeName' ,Ctype = '$CoffeType',Cmanufact = '$CoffeManu',Cimage = '$target',Cplaceoforigin = '$CoffePlaceOfOrigin',Cdb='$Coffeedb' WHERE Cid = $CoFFEE_ID");
                                    // Visszairányítás admin.php-ra
                                        header("location: admin.php");
                                        return;
                                }
                            // Nem volt kép                       
                                elseif(!move_uploaded_file($_FILES['NewCoffeePic']['tmp_name'], $target))
                                {                       
                                    $mysql->query("UPDATE coffee SET Cname = '$coffeeName' ,Ctype = '$CoffeType',Cmanufact = '$CoffeManu',Cplaceoforigin = '$CoffePlaceOfOrigin',Cdb='$Coffeedb' WHERE Cid = $CoFFEE_ID");
                                    // Visszairányítás admin.php-ra
                                        header("location: admin.php");
                                        return;
                                }
                    }
            }
        // Töröl
            elseif(isset($_POST["delete"]))
            {
                $ForDeleteID = $mysql->real_escape_string($_POST["id"]);
                    // Vásárlások közül törölni
                        $mysql->query("DELETE FROM purchase WHERE coffeeID = '$ForDeleteID'");
                    // Kávék közül is
                        $mysql->query("DELETE FROM coffee WHERE Cid = '$ForDeleteID'");
                    // Képek közül is

                    // Visszairányítás admin.php-ra (szerkesztés)
                        header("location: admin.php");
                        return;
            }
?>

<!DOCTYPE html>
<html lang="hu">
    <!--Fej-->
        <head>
            <title>Admin felület - termék szerkesztése</title>
            <meta charset="utf-8">
            <link rel="stylesheet" type="text/css" href="head.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 

        </head>
    <!--Test-->
        <body>
            <?php include 'navbar.html';?>
                    <!--Szerkesztés-->
                        <?php
                                // Megjött
                                    if(isset($_GET['id']))
                                    {
                                        $coffee_id = $mysql->real_escape_string($_GET['id']);
                                        $getter = $mysql->query("SELECT * FROM coffee WHERE Cid = $coffee_id");
                                        $row = $getter->fetch_assoc();
                                    }
                                // Nem jött meg
                                    else
                                    {
                                        //...
                                    }
                        ?>
                        <h4>Kávé adatainak modósítása</h4>
                        <form method="POST" enctype="multipart/form-data" action="">
                            <input type="hidden" name="id" id="id" value="<?=$coffee_id?>" />
                                    <div class="form-group">
                                        <label><strong>Kávé neve</strong></label>
                                        <input required class="form-control" name="CoffeName" type="text" value="<?= $row["Cname"]?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Kávé gyártó</strong></label>
                                        <input  class="form-control" name="CoffeManu" type="text" value="<?= $row["Cmanufact"]?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Kávé Típusa</strong></label>
                                        <input  class="form-control" name="CoffeType" type="text" value="<?= $row["Ctype"]?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Kávé származási helye</strong></label>
                                        <input  class="form-control" name="CoffePlaceOfOrigin" type="text" value="<?= $row["Cplaceoforigin"]?>"/>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Darabszám (1-100)</strong></label>
                                        <input type="number" name="db" min="1" max="100" value="<?= $row["Cdb"]?>">
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Kávéról kép (jpg)</strong></label>
                                        <input type="file" name="NewCoffeePic" class="form-control" value="<?= $row["Cimage"]?>">
                                    </div>
                                    <p></p>
                                    <div class="form-group">
                                        <input type = "submit" name = "update" class="btn btn-primary" value="Kávé modósítása">
                                        <input type = "submit" name = "delete" class="btn btn-secondary ml-2" value="Kávé Törlése">
                                        <a href="admin.php" class="btn btn-danger ml-3">Vissza</a>
                                    </div>
                        </form>
        </body>
</html>

