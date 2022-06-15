<!--Session kezdése és  belépés sikerességének ellenőrzése, ha nem sikerült akkor login.php-r való irányítás-->
    <?php
    // Session kezdése
        session_start();
    // Config file hívása
        require_once('config.php');
    // Ha nincs belépve valahogy vagy nem ADMIN  
            if(!LoggedIn() || ($_SESSION["Vid"] != $AdminID) )
            {
                header("location: login.php");
                exit;
            }
    // Új termék hozzáadása
        // Változók definiálása
            $NewCoffeeName = $NewCoffeeManu = $NewCoffeeType = $NewCoffeePlaceOfO = $NewCoffeePic =$NewCoffeeDB= "";
        // Form feldolgozása
            // Form ellett küldve
            if(isset($_POST["create"]))
            {
                $NewCoffeeName = $mysql->real_escape_string($_POST["NewCoffeeName"]);
                $NewCoffeeManu = $mysql->real_escape_string($_POST["NewCoffeeManu"]);
                $NewCoffeeType = $mysql->real_escape_string($_POST["NewCoffeeType"]);
                $NewCoffeePlaceOfO = $mysql->real_escape_string($_POST["NewCoffeePlaceOfO"]);
                $NewCoffeeDB = $mysql->real_escape_string($_POST["db"]);
                // Kép feldolgozása
                    // Ahol a kép mentve lesz
                        $target = "img/Added/";
                        $target = $target . basename( $_FILES['NewCoffeePic']['name']);                
                    // Szervernek
                        if(move_uploaded_file($_FILES['NewCoffeePic']['tmp_name'], $target))
                        {   
                            $mysql->query("INSERT INTO coffee (Cname,Ctype,Cmanufact,Cimage,Cplaceoforigin,Cdb) VALUES ('$NewCoffeeName','$NewCoffeeType','$NewCoffeeManu','$target','$NewCoffeePlaceOfO',$NewCoffeeDB)");
                        }
                        else
                        {
                            echo"Hiba történt a feltöltése során! Lehetséges ok: hiányzó adat!";
                        }
                
            }
    
            
    ?>
<!DOCTYPE html>
<html lang="hu">
<meta charset="utf-8">
        <title>Admin felület</title>
        <!--Fej-->
        <head>
            <?php include 'navbar.html'; ?>
            <link rel="stylesheet" type="text/css" href="head.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!--W3SCHOOL css-->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">              
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>
        <!--Test-->
            <body>
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <strong>Admin felület</strong> 
                </div>
                <!--Detail 1. hozzáadás-->
                 <h6>
                     <details>
                            <summary>Új kávé hozzáadása</summary>
                            <!--Form-->
                                <div class="wrapper">
                                    <h4>Termék hozzáadás</h4>
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Kávé neve</label>
                                            <input type="text" name="NewCoffeeName" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Kávé gyártója</label>
                                            <input type="text" name="NewCoffeeManu" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Kávé típusa</label>
                                            <input type="text" name="NewCoffeeType" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Kávé származási helye</label>
                                            <input type="text" name="NewCoffeePlaceOfO" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Darabszám (1-100)</label>
                                            <input type="number" name="db" min="1" max="100">
                                        </div>
                                        <div class="form-group">
                                            <label>Kávéról kép (jpg)</label>
                                            <input type="file" name="NewCoffeePic" class="form-control">
                                        </div>
                                        <p></p>
                                        <div class="form-group">
                                                <input type="submit" name = "create" class="btn btn-primary" value="Létrehozás">
                                                <input type="reset" class="btn btn-secondary ml-2" value="Tisztítás">
                                        </div>
                                        </form>
                                </div>
                     </details>
                 </h6>
                <!--Detail 2. szerkesztés-->
                    <h6>
                        <details>
                            <summary>Meglévő kínálat szerkesztése</summary>
                            <!--Kereső-->
                                <?php
                                    $search = null;
                                        if (isset($_POST['search'])) 
                                        {
                                            $search = $_POST['search'];
                                        }
                                    $query = $mysql->query("SELECT * FROM coffee");
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <p></p>
                                <label for="stuff" style="margin-left: 10px;">Válassza ki, amelyet szeretné szerkeszteni:</label>   
                                <select  aria-label=".form-select-lg example" name="stuff" id="stuff">                              
                                    <?php
                                        while($row = $query->fetch_assoc())
                                        {
                                            ?>
                                                <option value="<?= $row['Cname']?>"><?= $row['Cname']?></option>
                                            <?php
                                        }
                                        ?>
                                            <br><br>
                                             <input type="submit" value="Kiválasztom" class="btn btn-primary">
                                        <?php
                                    ?>
                                                                                                           
                                </select>
                                </form>
                                <p></p>
                            <!--Táblázat-->
                                <table class="table table-dark table-hover">         
                                    <!--Táblázat teste-->
                                         <?php
                                            // Ha rányomott a keresés gombra (üresen) --> tehát mindent lekér
                                                if(isset($_POST["stuff"]) )
                                                {   
                                                             
                                                                // Csak, amelyiket szeretné                                                           
                                                                $getter = $mysql->query("SELECT * FROM coffee WHERE Cname = '{$_POST["stuff"]}' ");
                                                            
                                                            $data = $getter->fetch_assoc();
                                                            ?>
                                                            <!--Táblázat feje-->
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">Név</th>
                                                                        <th scope="col">Gyártó</th>
                                                                        <th scope="col">Kávé típusa</th>
                                                                        <th scope="col">Származási hely</th>
                                                                        <th scope="col">Kép</th>
                                                                        <th scope="col">Készleten lévő [csomag]</th>
                                                                        <th scope="col">Szerkesztés</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                
                                                        <?php                                                                                                                                                                                   
                                                                
                                                                    ?>
                                                                    <tbody>
                                                                        <td><?= $data['Cname']?></td>
                                                                        <td><?= $data['Cmanufact']?></td>
                                                                        <td><?=$data['Ctype']?></td>
                                                                        <td><?= $data['Cplaceoforigin']?></td>
                                                                        <td><img class="rounded float" src="<?=$data['Cimage']?>" width="55" height="70"></td>
                                                                        <td><?= $data['Cdb']?></td>
                                                                        <td><a class="btn btn-primary" id="buy" href="edit.php?id=<?= $data['Cid'] ?>">Szerkesztem <i class="fa fa-angle-double-right"></i></a></td>                                                                        
                                                                    <?php
                                                                
                                                                
                                                }
                                                        ?>
                                                    </tbody>
                                </table>         
                        </details>
                    </h6>
            </body>
</html>