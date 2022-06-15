<?php
    // Session kezdése
        session_start();
    // Config file hívás
        require_once ('config.php');
    // // Változók a feldolgozáshoz ( hibához is)
        $torlom = $confirm_torlom = "";
        $torlom_err = $confirm_torlom_err = "";
    // Ha rányomott a jelszó változtatás gombra
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // TORLOM ellenőrzése
                // üres
                    if(empty($_POST["torlom"]))
                    {
                        $torlom_err = "Kérem írja be, hogy törlöm";
                    }
                else
                    {
                        $torlom = $mysql->real_escape_string($_POST["torlom"]);
                    }
            // Megerősítés ellenőrzése
                // Ha üres
                if(empty($_POST["confirm_torlom"]))
                {
                    $confirm_torlom_err = "Kérem írja be, hogy TORLOM";
                }
                else
                {
                    $confirm_torlom = $mysql->real_escape_string($_POST["confirm_torlom"]);
                    // Ha nem egyeznek
                    if(empty($torlom_err) && ($torlom != $confirm_torlom))
                    {
                        $confirm_torlom_err = "Nem egyeznek!";
                    }
                }
            // Feldolgozás
                if(empty($torlom_err) && empty($confirm_torlom_err))
                {
                    // Felhasználó törlése
                        //Vevő id-ja
                            $deleteID = $_SESSION["Vid"];
                        // Ha volt rendelése 
                            // Adatok lekérése
                                $getter = $mysql->query("SELECT * FROM purchase WHERE vevoID = $deleteID");
                                $row = $getter->fetch_assoc();
                            // Vásárlásai (db)
                                $buys = intval($row["Pdb"]);
                            // Kávé ID
                                $coffeID = $row["coffeeID"];
                                // Ha nem volt semmi
                                    if(intval($buys)== null || intval($coffeID == null)) {goto delete;}
                            // Vásárlás törlése
                                $mysql->query("DELETE FROM purchase WHERE vevoID = $deleteID ");
                            // Készlet frissítése
                                $mysql->query("UPDATE coffee SET Cdb = (Cdb + $buys) WHERE Cid = $coffeID ");
                        // Törlés
                            delete: $mysql->query("DELETE FROM vevo WHERE Vid = $deleteID ");
                        // Kilépés és session zárása
                            $_SESSION = array();
                            session_destroy();
                        // Főoldalra irányítás
                            header("location: index.php");
                            exit;    

                }
        }
?>

<!DOCTYPE html>
<html lang="hu">
    <!--Fej-->
        <head>
            <meta charset="UTF-8">
            <title>Fiók törlése</title>
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
                <h2>Fiók törlése</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                    <div class="form-group">
                        <label>Kérem írja be, hogy TORLOM a fiók törléséhez</label>
                        <input type="text" name="torlom" class="form-control <?php echo (!empty($torlom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $torlom; ?>">
                        <span class="invalid-feedback"><?php echo $torlom_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Kérem írja be, hogy TORLOM a fiók törlésének megerősítéhez</label>
                        <input type="text" name="confirm_torlom" class="form-control <?php echo (!empty($confirm_torlom_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_torlom_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Törlöm a fiókom">
                        <a class="btn btn-link ml-2" href="welcome.php">Mégsem</a>
                    </div>
                </form>
            </div>    
        </body>
</html>