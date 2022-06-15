<?php
// Session elkezdése
    session_start();
// Törlési folyamat
        $_SESSION = array();
            // "Session" törlése
            session_destroy();
                header("location: index.php");
    exit;
?>
