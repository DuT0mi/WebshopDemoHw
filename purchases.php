<!--Session kezdése + config fájl hívás-->
    <?php
        session_start();
        require_once('config.php');

    ?>

<!DOCTYPE hmtl>
<html lang="hu">
    <meta charset="utf-8">
    <title>Vásárlásaim</title>
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
            <div id="tartalom">
            <table class="table table-dark table-hover">
                <!--Táblázat-->
                    <!--Táblázat feje-->
                        <thead>
                                <th scope="col">Név</th>
                                <th scope="col">Gyártó</th>
                                <th scope="col">Kép</th>
                                <th scope="col">Megvásárolt [csomag]</th>
                                <th scope="col">További termék hozzáadása [csomag]</th>
                                <th scope="col">Törlés</th>
                        </thead>
                    <!--Táblázat teste-->
                        <?php
                            //Adatok lekérése
                            $buyer_id = $_SESSION["Vid"];
                            $getter = $mysql->query("SELECT * FROM purchase WHERE vevoID = $buyer_id");
                            //Amig van adat
                            while($row = $getter->fetch_assoc())
                            {
                                    $coffe_id = $row['coffeeID'];
                                    $data = $mysql->query("SELECT * FROM coffee WHERE Cid = $coffe_id");
                                    $array = $data->fetch_assoc();   
                                             
                                    ?>
                        <tbody>
                            <tr>
                                <td><?=$array['Cname']?></td>
                                <td><?=$array['Cmanufact']?></td>                                
                                <td><img class="rounded float-end" src="<?=$array['Cimage']?>" width="115" height="150"></td>
                                <td><?=$row['Pdb']?></td>
                                <td>
                                    <form action="setdb.php">
                                        <input type="hidden" name="id" id="id" value="<?=$row['Pid']?>" />
                                        <input type="number" min="1" max ="<?= $array["Cdb"]?>" name="db">
                                        <input type="submit"  class="btn btn-primary" value="Hozzáadom" >
                                    </form>
                                </td>
                                <td>
                                    <form action="delete.php">
                                        <input type="hidden" name="did" id="id" value="<?=$row['Pid']?>" />
                                        <input type="number" min = "1" max = "<?= $row["Pdb"]?>" name = "ddb">
                                        <input type="submit"  class="btn btn-primary" value="Törlöm" >
                                    </form>
                                </td>                                
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
            </table>
            </div>
        </body>
</html>