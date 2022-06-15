<?php
// Akár session_start()-t is lehetne ide, de talán beszédesebb jobban olvashatóbb, ha nem
//Csatlakozás a MYSQL adatbázishoz
    $mysql = mysqli_connect('localhost','root','','webshop');
    //Ellenőrzés
    if($mysql == false)
        die("Nem sikerült csatlakozni az adatbázishoz. ". mysqli_connect_error());
// Külön függvénybe kiszervezés, sokszor van használva
    function LoggedIn ()
    {
        return  (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
    }
// Admin Id létrehozása
    $AdminID = 1;   
// Default vásárlás
    $DEFAULT_BUY = 1;
?>