<?php
// Admin
    $adminPw = "admin";
// Session kezdése
    session_start();
// Config fájlok hívása
    require_once('config.php'); 
// Ha bevan lépve, akkor welcome.php-ra való irányítás

    if(LoggedIn())
    {
        header("location: welcome.php");
        exit;    
    }
// Változók defininálása
    $username = $password = "";
    // Hibához
    $username_err = $password_err = $login_err = "";
// Form feldolgozás
    // Ha elküldte
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Felhasználó név validálás
            // Ha üres a felhasználó név
                if(empty($_POST["username"]))
                {
                    $username_err = "Kérem adja meg felhasználó nevét!";
                }
                // oké
                else
                {
                    // SQl injection elleni védelemmel
                    $username = $mysql->real_escape_string($_POST["username"]);                   
                }
        // Jelszó validálás
                // Ha üres
                if(empty($_POST["password"]))
                {
                    $password_err = "Kérem adja meg  jelszavát!";
                }
                else
                {
                    // SQl injection elleni védelemmel
                    $password = $mysql->real_escape_string($_POST["password"]);
                }
        // Belépés próbálása
                // Ha nem volt hiba
                if(empty($username_err) && empty($password_err))
                {
                    $getter = "SELECT Vid, Vname, Vpassword FROM vevo WHERE Vname = ?";

                    // Előkészítés
                    if($stmt = mysqli_prepare($mysql, $getter))
                    {
                        // Bind
                        mysqli_stmt_bind_param($stmt, "s", $P_username);
                        // SQL injection már levan kezelve jelszóra és f.névre
                        // Paraméter beállítása
                        $P_username = $mysql->real_escape_string($username);
                        // Végrehajtás próbálása
                        if(mysqli_stmt_execute($stmt))
                        {
                            // Tárolás
                            mysqli_stmt_store_result($stmt);
                            // Ha létezik ez a felhasználó név
                            if(mysqli_stmt_num_rows($stmt) == 1)
                            {
                                // Bind eredmény
                                mysqli_stmt_bind_result($stmt, $id, $mysql->real_escape_string($username),$hashed_password);
                                if(mysqli_stmt_fetch($stmt))
                                {
                                    //Ha jó a jelszó
                                    if(password_verify($mysql->real_escape_string($password), $hashed_password))
                                    {
                                        // Új session nyitása
                                        session_start();
                                        // Szuper, globális változók deklarása, későbbiekben könnyeb ezekrehivatkozni
                                        $_SESSION["loggedin"] = true; // config miatt
                                        $_SESSION["Vid"] = $id;
                                        $_SESSION["Vname"] = $username;                                                                               
                                        // Welcome.php - ra irányítás
                                        header("location: welcome.php");
                                    }
                                    //Ha admin
                                    elseif((($mysql->real_escape_string($password))==($adminPw)))
                                    {
                                        // Új session nyitása
                                            session_start();
                                        // Szuper, globális változók deklarása, későbbiekben könnyeb ezekrehivatkozni
                                            $_SESSION["loggedin"] = true; // config miatt
                                            $_SESSION["Vid"] = $id;
                                            $_SESSION["Vname"] = $username;                                                                 
                                        // Welcome.php - ra irányítás
                                            header("location: welcome.php");
                                    }
                                    // Nem jó jelszó
                                    else
                                    {
                                        $login_err = "Érvénytelen jelszó";
                                    }
                                }
                            }
                            // Nincs ilyen felhasználó név
                            else
                            {
                                $login_err = "Nem létező felhasználó név!";
                            }
                        }
                        // Nem sikerült
                        else
                        {
                            echo "Hiba lépett fel, dolgozunk rajta";
                        }
                        // Zárás
                        mysqli_stmt_close($stmt);
                    }
                }
                else
                {
                    //...
                }
                // Kapcsolat zárása
                mysqli_close($mysql);
    }

?>
<!DOCTYPE html>
<html lang="hu">
            <meta charset="UTF-8">
            <title>Belépés</title>
    <!--Fej-->
        <head>
            <?php include 'navbar.html'; ?>
            <link rel="stylesheet" type="text/css" href="head.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!--W3SCHOOL css-->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">              
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <!--Belépő form formázása-->
                <style>
                    body{background-image: url('c8.jpg');background-repeat: no-repeat;height: 100%;background-position: center;background-size: cover; }
                    .wrapper{ width: 360px; padding: 20px; margin: auto; }
                    
                </style>
        </head>
    <!--Test-->
        <body> 
            <div class="wrapper" style="font: 14px sans-serif;">
                <h2 style="color: white;">Belépés</h2>
                <?php 
                if(!empty($login_err))
                {
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label style="color: white">Felhasználó név</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="kérem adja meg felhasználó nevét">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group">
                        <label style="color: white;">Jelszó</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="kérem adja meg jelszavát">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Belépek">
                    </div>
                    <h6 style="color: white;">Nincs fiókja? <a href="registration.php" style="color: yellow;">Regisztráljon most</a>.</h6>
                </form>
            </div>
        </body>
</html>