<?php
    // Session kezdése
        session_start();
    // Config file hívás
        require_once ('config.php');
    // Változók a feldolgozáshoz ( hibához is)
        $new_password = $confirm_password = "";
        $new_password_err = $confirm_password_err = "";
    // Ha rányomott a jelszó változtatás gombra
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // Új jelszó validálása
                // Ha üres a mező
                if(empty($_POST["new_password"]))
                {
                    $new_password_err = "Kérem adja meg az új jelszavát!";
                }
                // Nem megy a jelszó RegExen (RegeEx 3.)
                elseif(!preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,15}$/', $_POST["new_password"]))
                {
                    $new_password_err = "Az új jelszó nem felel meg a formai követelményeknek! (legalább 1 szám, nagy és kisbetű illetve legalább 6 és legfeljebb 15 karakter hosszú lehet)!";
                }
                // Ha oké
                else
                {
                    $new_password = $mysql->real_escape_string($_POST["new_password"]);
                }
            // Megerősítés ellenőrzése
                // Ha üres
                if(empty($_POST["confirm_password"]))
                {
                    $confirm_password_err = "Kérem erősítse meg új jelszavát!";
                }
                // Látszólag okés
                else
                {
                    $confirm_password = $mysql->real_escape_string($_POST["confirm_password"]);
                        // Ha nem egyeznek
                        if(empty($new_password_err) && ($new_password != $confirm_password))
                        {
                            $confirm_password_err = "Nem egyeznek a megadott jelszavak!";
                        }  
                }
            // Feldolgozás
                // Ha nem volt hiba
                    if(empty($new_password_err) && empty($confirm_password_err))
                    {
                        // UPDATE előkészítes
                            $getter = "UPDATE vevo SET Vpassword = ? WHERE Vid = ?";
                            if($stmt = mysqli_prepare($mysql, $getter))
                            {
                                // Bind
                                    mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                                // Paraméterek beállítása 
                                    $param_password = password_hash($mysql->real_escape_string($new_password),PASSWORD_DEFAULT);
                                    $param_id = $_SESSION["Vid"];
                                //  Végrehajtás rápróbálás
                                if(mysqli_stmt_execute($stmt))
                                {
                                    // Az új jelszó átlett állítva
                                        // Session kinyírása
                                            session_destroy();
                                        // Bejelentkezéshez irányítás és exit
                                            header("location: login.php");                                            
                                            exit();
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
    <!--Fej-->
        <head>
            <meta charset="UTF-8">
            <title>Jelszó változtatás</title>
            <link rel="stylesheet" type="text/css" href="head.css">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body{ font: 14px sans-serif; }
                .wrapper{ width: 360px; padding: 20px; }
            </style>
        </head>
    <!--Test-->
        <body>
            <?php include 'navbar.html'; ?>
            <div class="wrapper">
                <h2>Jelszó változtatás</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <div class="form-group">
                        <label>Új jelszó</label>
                        <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                        <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Új jelszó megerősítése</label>
                        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Megváltoztatom">
                        <a class="btn btn-link ml-2" href="welcome.php">Mégsem</a>
                    </div>
                </form>
            </div>    
        </body>
</html>