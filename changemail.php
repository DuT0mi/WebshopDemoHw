<?php
    // Session kezdése
        session_start();
    // Config file hívás
        require_once('config.php');
    // Változók a feldolgozáshoz (hibához is)
        $new_email = $confirm_email =  "";
        $new_email_err = $confirm_email_err = "";
    // Ha rányomott az email-cím változtatás gombra
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // Új email-cím validálás
                // Ha üres a mező
                    if(empty($_POST["new_email"]))
                    {
                        $new_email_err = "Kérem adja meg új email-címét!";
                    }
                // Nem megy át a RegExen (RegEx 2.)
                elseif(!preg_match('/^[0-9a-z\.-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/', $_POST["new_email"]))
                {
                    $email_err = "Érvényes e-mail címet adjon meg.";
                }
                // Ha oké
                else
                {
                    $new_email = $mysql->real_escape_string($_POST["new_email"]);
                }
            // Megerősítés ellenőrzése
                // Ha üres
                    if(empty($_POST["confirm_email"]))
                    {
                        $confirm_email_err = "Kérem erősítse meg új email-címét!";
                    }
                // Látszólag okés
                    else
                    {   
                        $checker = true;
                        $confirm_email = $mysql->real_escape_string($_POST["confirm_email"]);
                            // Ha nem egyeznek
                            if(empty($confirm_email_err) && ($new_email != $confirm_email))
                            {
                                $confirm_email_err = "Nem egyeznek a megadott email-címek!";
                            }
                            // Ha van ilyen email-cím már
                            elseif($checker)
                            {
                                $get = $mysql->query("SELECT Vemail FROM vevo");
                                while($row = $get->fetch_assoc())
                                {
                                    $data = $row["Vemail"];
                                    if($data == $confirm_email)
                                    {
                                        $confirm_email_err = "Létező email-cím";                                  
                                    }
                                }
                            }
                    }
            // Feldolgozás
                // Ha nem volt hiba
                if(empty($new_email_err) && empty($confirm_email_err))
                {
                    // UPDATE előkészítése
                        $getter = "UPDATE vevo SET Vemail = ? WHERE Vid = ?";
                        if($stmt = mysqli_prepare($mysql, $getter))
                        {
                            // Bind
                                mysqli_stmt_bind_param($stmt,"si",$param_email,$param_id);
                            // Paraméterek beállítása
                                $param_email = $mysql->real_escape_string($new_email);
                                $param_id = $_SESSION["Vid"];
                            //  Végrehajtás rápróbálás
                                if(mysqli_stmt_execute($stmt))
                                {                                                                   
                                    // Oké az új email-cím
                                        // Session kinyírása
                                            session_destroy();
                                    // login.php-ra irányítás és exit
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
                    //...
                }
            // Kapcsolat zárása
                mysqli_close($mysql);
        }
?>

<!DOCTYPE html>
<html lang="hu">
    <!--Fej-->
        <head>
            <meta charset="utf-8">
            <title>Email-cím változtatás</title>
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
                <h2>Email-cím változtatás</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                        <div class="form-group">
                            <label>Új email-cím</label>
                            <input type="text" name="new_email" class="form-control <?php echo (!empty($new_email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_email; ?>">
                            <span class="invalid-feedback"><?php echo $new_email_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Új email-cím megerősítése</label>
                            <input type="text" name="confirm_email" class="form-control <?php echo (!empty($confirm_email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_email; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_email_err; ?></span>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Megváltoztatom">
                            <a class="btn btn-link ml-2" href="welcome.php">Mégsem</a>
                        </div>
                    </form>
            </div>
        </body>
</html>