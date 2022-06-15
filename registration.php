 <?php
// előkészítő fájl meghívása + Session kezdése
    require_once ('config.php');
    session_start();
// Ha bevan jelentkezve vissza a welcome.php-ra
        if(LoggedIn())
        {
            header("location: welcome.php");
            exit;
        }
// Változók a feldolgozáshoz ( hibához is)
        $email = $username = $password = $confirm_password = "";
        $email_err = $username_err = $password_err = $confirm_password_err = "";

// Ha a formot elküldte
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    // Felhaszáló név validálás
        // Ha üres a form a felhasználó név fülnél
        if(empty($_POST["username"]))
        {
            $username_err = "Kérem adja meg a felhasználó nevét! ";
        }
        //Ha nem ment át a felhasználó név követelményein
                    //RegEx 1.
                    /*
                    - kis és nagybetű a-z / A-Z
                    - szám  0 - 9
                    - egyéb _
                    */
        elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $_POST["username"]))
        {
            $username_err = "A felhasználó név nem felel meg a formai követelményeknek! (csak kis/nagy betű, szám és aláhúzás)";
        }
        //Minden rendben volt
        else 
        {
            $getter = "SELECT Vid FROM vevo WHERE Vname = ?";
            if($stmt = mysqli_prepare($mysql,$getter))
            {
                // Bind
                mysqli_stmt_bind_param($stmt,"s",$Pusername);

                //Paraméter beállítása
                $Pusername = $_POST["username"];
                // Végrehajtás (try)
                if(mysqli_stmt_execute($stmt))
                {
                    // Érték eltárolása
                    mysqli_stmt_store_result($stmt);

                    //Ha már van ilyen felhasználó név
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $username_err = "Foglalt felhasználó név!";
                    }
                    else
                    {
                        // SQL injection elleni védelemmel
                        $username = $mysql->real_escape_string($_POST["username"]);
                    }
                }
                else // 38.sorban lévő IF-hez
                {
                    echo "Valami hiba lépett fel, dolgozunk rajta";
                }
                // lezárás
                mysqli_stmt_close($stmt);
            }
        }
    // Email-cím validálás
        // Ha üres volt a form
        if(empty($_POST['email']))
        {
            $email_err = "Kérem adja meg e-mail címét.";
        }
        // Nem megy át a RegExen
                    //RegEx 2.
                    /*
                    - start ^, end $
                    -                                                                              "." = \
                    - egy vagy több alfanumerikus karakter:                                        [0-9a-z-]+
                    - egy vagy több alfanumerikus karakter és kötőjel meg pont is megengedett:     [0-9a-z\.-]
                    - aztán @
                    - egy vagy több alfanumerikus karakter:                                        [0-9a-z-]+
                    - A mintát egy  legalább 2 legfejlebb 4 betüből álló rész zárja                [a-z]{2,4}
                    */
        elseif(!preg_match('/^[0-9a-z\.-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/', $_POST["email"]))
        {
            $email_err = "Érvényes e-mail címet adjon meg.";
        }
        // Minden rendben volt
        else
        {
            $getter_email = "SELECT Vid FROM vevo WHERE Vemail = ?";
            if($stmt = mysqli_prepare($mysql,$getter_email))
            {
                // Bind
                mysqli_stmt_bind_param($stmt,"s",$Pemail);

                //Paraméter beállítása
                $Pemail = $_POST["email"];
                // Végrehajtás (try)
                if(mysqli_stmt_execute($stmt))
                {
                    // Érték eltárolása
                    mysqli_stmt_store_result($stmt);

                    //Ha már van ilyen email
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $email_err = "Foglalt email-cím!";
                    }
                    else
                    {
                        // SQL injection elleni védelemmel
                        $email = $mysql->real_escape_string($_POST["email"]);
                    }
                }
                else // 38.sorban lévő IF-hez
                {
                    echo "Valami hiba lépett fel, dolgozunk rajta";
                }
                // lezárás
                mysqli_stmt_close($stmt);
            }
        }
    // Jelszó validás
        //Ha üres a form
        if(empty($_POST["password"]))
        {
            $password_err = "Kérem adja meg a jelszót";
        }
        // Nem megy a jelszó RegEx-en
        //RegEx 3.
        /*
        - Legalább 1 nagy betű                 (?=.*?[A-Z])
        - Legalább 1 kis betű                  (?=.*?[a-z])
        - Legalább 1 szám                      (?=.*?[0-9])
        - Legalább 6 digit,legfeljebb 15       .{6,15}
        */
        elseif(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,15}$/', $_POST["password"]))
        {
            $password_err = "A jelszó nem felel meg a formai követelményeknek! (legalább 1 szám, nagy és kisbetű illetve legalább 6 és legfeljebb 15 karakter hosszú lehet)";
        }
        // Minden rendben volt
        else
        {   
            // SQL injection elleni védelemmel
            $password = $mysql->real_escape_string($_POST["password"]);
        }
    // Jelszó megerősítő validálás
        // Ha üres
        if(empty($_POST["confirm_password"]))
        {
            $confirm_password_err = "kérem erősítse meg jelszavát";
        }
        // látszólag oké
        else
        {
            // persze sql injection elleni védelem
            $confirm_password = $mysql->real_escape_string($_POST["confirm_password"]);
                // De, ha nem egyeznek
                if(empty($password_err) && ($password != $confirm_password))
                {
                    $confirm_password_err = "Nem egyeznek a jelszavak";
                }
        } 
    // Adatbázisban való bevitel
        // Ha nem volt hiba
        if(empty($password_err) && empty($confirm_password_err) && empty($username_err) && empty($email_err))
        {
            // SQl injection ellen levan kezelve minden
            // elegánsabban, bindolással oldottam meg ezért van (?,?,?)
            $setter = "INSERT INTO vevo (Vname,Vpassword,Vemail) VALUES (?,?,?)";
            if($stmt = mysqli_prepare($mysql,$setter))
            {
                // Bind
                mysqli_stmt_bind_param($stmt,"sss",$P_username,$P_password,$P_email);
                
                // Paraméterek beállítása
                $P_username = $mysql->real_escape_string($username);
                $P_email = $mysql->real_escape_string($email);
                    // jelszó hash
                        // default elég lesz
                    $P_password = password_hash($mysql->real_escape_string($password),PASSWORD_DEFAULT);
                // Végrehajtás rápróbálás
                if(mysqli_stmt_execute($stmt))
                {
                    // login.php - ra irányítás
                    header("location: login.php");
                }
                // Valahol elszállt
                else
                {
                    echo "Hiba lépett fel, dolgozunk rajta!";
                }
                // Lezárni
                mysqli_stmt_close($stmt);
            }
        }
        else
        {
            // ...
        }
        // Kapcsolat zárása
        mysqli_close($mysql);
    }
?>

<!DOCTYPE html>
<html lang="hu">
        <meta charset="UTF-8">
        <title>Regisztráció</title>
<!--Fej-->
    <head>
            <?php include 'navbar.html'; ?>
            <link rel="stylesheet" type="text/css" href="head.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!--W3SCHOOL css-->
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">              
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            body{background-image: url('c8.jpg');background-repeat: no-repeat;height: 100%;background-position: center;background-size: cover; }
            .wrapper{ width: 360px; padding: 20px; margin: auto;}
        </style>
    </head>
<!--Test-->
    <body>
    <!--Regisztrációs felület-->
        <div class="wrapper" style="font: 14px sans-serif;">
            <h2 style="color: white;">Regisztráció</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label style="color: white;">Felhasználó név</label>
                    <input type="text" name="username"  class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="kérem adja meg a felhasználó nevet">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group">
                    <label style="color: white;">Email-cím</label>
                    <input type="text" name="email"  class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="kérem adja meg email-címét">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>    
                <div class="form-group">
                    <label style="color: white;">Jelszó</label>
                    <input type="password" name="password"   class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="kérem adja meg a jelszót">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label style="color: white;">Jelszó megerősítése</label>
                    <input type="password" name="confirm_password"  class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>"placeholder="kérem adja meg a jelszót">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Küldés">
                    <input type="reset" class="btn btn-secondary ml-2" value="Törlés">
                </div>
                <h6 style="color: white;">Van már felhasználói fiókja? <a href="login.php" style="color: yellow;">Lépjen be</a>.</h6>
            </form>
        </div>    
    </body>
</html>