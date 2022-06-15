<!--Session kezdése és  belépés sikerességének ellenőrzése, ha nem sikerült akkor login.php-r való irányítás-->
    <?php
        session_start();
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
        {
            header("location: login.php");
            exit;
        }
    ?>
<!DOCTYPE html>
<html lang="hu">
<meta charset="utf-8">
    <!--Config file hívás-->
        <?php  include 'config.php';?>
    <!--Fej-->
    <title>Profilom</title>
        <head>
            <?php include 'navbar.html'; ?>
            <link rel="stylesheet" type="text/css" href="head.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!--W3SCHOOL css-->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">              
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <!--CSS tárolás teszt-->
            
                

        </head>
    <!--Test-->
        <body>
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                    <strong>Üdvözlünk viszont <b><?php echo htmlspecialchars($_SESSION["Vname"]); ?></b> az oldalon! </strong> 
                </div>
                    
                    <h6>
                    <details>
                        <summary>Fiókom adatai</summary>
                        <ul class="w3-ul w3-hoverable">
                            <li>Felhasználó Név:    <?php echo htmlspecialchars($_SESSION["Vname"]); ?> </li>
                            <li>Email-cím:       <?php  
                                                    $getter = $mysql->query("SELECT Vemail FROM vevo WHERE Vid = {$_SESSION["Vid"]}");                       
                                                    $res = $getter->fetch_assoc();                                                
                                                    echo "{$res["Vemail"]}";
                                                ?>   
                            </li> 
                        </ul>                     
                    </details>
                </h6>
                <h6>
                    <details>
                            <summary>Beállítások</summary>
                                    <details>
                                            <summary>Fiókkal adatainak változtatása</summary>
                                            <ul class="w3-ul w3-hoverable">
                                                <li><a href="changepw.php" class="btn btn-success">jelszó változtatás</a></li>
                                                <li><a href="changemail.php" class="btn btn-warning">email-cím változtatás</a></li>
                                    </details>
                                    <details>
                                        <summary>Fiók törlése</summary>
                                        <ul class="w3-ul w3-hoverable">
                                        <li><a href="deleteacc.php" class="btn btn-danger">fiókom törlése</a></li>
                                    </details>
                    <p></p>
                    </details>
                </h6>                           
                    <a href="logout.php" class="btn btn-danger ml-3">Kilépés</a>                  
        </body>
</html>

