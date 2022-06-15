<!-- 
    Szerző: Dudás Tamás Alex
    A házi feladat a BME VIK Informatika 2 kurzushoz készült (2022 tavasz)
    Felhasznált irodalom:
        - https://www.w3schools.com/php/default.asp
        - https://www.w3schools.com/css/default.asp
        - https://www.w3schools.com/html/default.asp
        - stackoverflow (formázás,sql,php,js,.. (ide vágó kérdések))
        - https://www.youtube.com/watch?v=MMNEEdGa5eE (a hullámhoz)
-->
<!--Config fájl hívása-->
    <?php   require_once 'config.php';   ?>
<!DOCTYPE html>
<html lang="hu">
<meta charset="utf-8">
    <title>Kezdőlap</title>
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
        <!--Hullámos rész-->
        <div class="header">  
     
        <div class="inner-header flex">          
        <h1 style="font-family:Arial, Helvetica, sans-serif;"> <strong>A kávézás legalább olyan nyugtató, mint a főoldalunk. 
            <p >
                Ne habozz,<a href="registration.php">regisztrálj</a> vagy <a href="login.php">lépj be</a> és rendelj :)
                <style> 
                 a:hover
                 {
                     color:yellow
                 }
                </style>
            </p>
        </h1> </strong> 
        </div>  
        
            <div>  
                <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  
                    viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">  
                    <defs>  
                        <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />  
                    </defs>  
                            <g class="parallax">  
                                <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />  
                                <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />  
                                <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />  
                                <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />  
                            </g>  
                </svg>  
            </div>  
        
        </div>   
    </body>
</html>