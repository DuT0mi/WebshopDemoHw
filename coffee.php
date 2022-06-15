<?php    
        // Session kezdése
            session_start();
        // Config file hívás
            require_once('config.php');
?>
<!DOCTYPE html>
<meta charset="UTF-8">
<title>Termékeink</title>
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
    <style>             
            * 
            {
                box-sizing: border-box;
            }

            form.example input[type=text] 
            {
                padding: 10px;
                font-size: 17px;
                border: 1px solid grey;
                float: left;
                width: 80%;
                background: #f1f1f1;
            }

            form.example button 
            {
                float: left;
                width: 20%;
                padding: 10px;
                background: #2196F3;
                color: white;
                font-size: 17px;
                border: 1px solid grey;
                border-left: none;
                cursor: pointer;
            }

            form.example button:hover 
            {
                background: #0b7dda;
            }

            form.example::after 
            {
                content: "";
                clear: both;
                display: table;
            }
        </style>
<!--Test-->
    <body>
        <p></p>
        <!--Kereső menü-->
            <!--Kereső menü kiegészítés-->
            <?php              
                $search = null;
             if (isset($_POST['search'])) 
                {
                    $search = $_POST['search'];
                }
            ?>
            <h6>Amennyiben mindet leszeretné kérni, csak üresen kattintson a keresésre!</h6>
        <form class="form-inline" method="post">
            <div class="card">
                <div class="card-body">
                    Keresés: 
                    <input style="width:300px;margin-left;" placeholder="Adja meg a termék nevét.." class="form-control" id = "NameField" type="search" name="search" value="<?php if (isset($_POST['search'])) echo $_POST['search']; ?>">
                    <button class="btn btn-success" style="margin-left:10px;" type="submit">Keresés</button>
                </div>
            </div>
        </form>
        
        <!--PHP-s-->
        
        <table class="table table-dark table-hover">
            <!--Táblázat-->
                    <!--Táblázat teste-->
                    <?php
                        // Ha rányomott a keresés gombra
                            if($_SERVER["REQUEST_METHOD"] == "POST" )
                            {                         
                                //Ha a search üres
                                    if($_POST['search'] == null)
                                    {
                                        $getter = $mysql->query("SELECT * FROM coffee");
                                            if(!$getter) {echo "Nincs készleten termék!";}
                                    }
                                    else
                                    {   
                                        $coffeName = $mysql->real_escape_string($_POST['search']);
                                        // Részneves keresés (meg nyilván akkor teljes is)
                                                $getter = $mysql->query("SELECT * FROM coffee  WHERE LOCATE('$coffeName',Cname) ");
                                                if(!$getter) {echo "Nincs készleten termék!";}
                                    }
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
                                            <?php	
                                                                if(LoggedIn())
                                                                    {   ?>
                                                                            <th scope="col">Rendelés</th>
                                                                        <?php
                                                                    }
                                                                else
                                                                    {   ?>
                                                                            <th scope="col">Rendelés*</th>
                                                                        <?php
                                                                    }
                                                            ?>
                                        </tr>
                                    </thead>
                                    
                            <?php
                                // Gyártnó név lekérése
                                    
                                // Lekérdezés
                                    while($data = $getter->fetch_assoc())
                                    {
                                        ?>
                                        <tbody>
                                            <td><?= $data['Cname']?></td>
                                            <td><?= $data['Cmanufact']?></td>
                                            <td><?=$data['Ctype']?></td>
                                            <td><?= $data['Cplaceoforigin']?></td>
                                            <td><img class="rounded float-end" src="<?=$data['Cimage']?>" width="115" height="150"></td>
                                            <td>
                                                <?php
                                                    // Darabszám ellenőrzés
                                                    if($data['Cdb']>0)
                                                    {
                                                        echo "Készleten: ". $data['Cdb'];
                                                    }
                                                    // Nincs
                                                    else
                                                    {
                                                        echo "Sajnos elfogyott";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if(LoggedIn() && $data['Cdb']>0)
                                                {
                                                    ?>
                                                        <a class="btn btn-primary" id="buy" href="purchase.php?id=<?= $data['Cid'] ?>">Megrendelem <i class="fa fa-angle-double-right"></i></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        <?php
                                    }
                                    
                            }
                            ?>
                        </tbody>
        </table>
    </body>
    <!--Csinosítgatás-->
    <?php 
            if(!LoggedIn())
            {   ?>
                <footer>
                *Rendelés csak regisztrált felhasználóinknak elérhető. 
                </footer>
                <?php
            }
        ?>
