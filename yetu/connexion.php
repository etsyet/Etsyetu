<?php
    include "logic.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" sizes="16x16" href="IMG/ARRIERE.jpg">
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <title>Ets yetu</title>
  <style>
    .contenu{
        border:1px solid #F8F9F9;
        padding:10px;
        width:40%;
        border-radius:5px;
        color:#fff;
        background: rgba(0,0,0,0.5);
    }

    .contenu form{
        width:100%;
        text-align:left;  
    }

    .contenu input{
        width:100%;
        height:30px;
        border:none;
        background:#fff;
        border-radius:4px;
        margin:3px;

    }
  </style>
</head>
<body>
<header>
<a href="#" class="logo"><span>E</span>ts yetu</a>
<ul class="navbar">
  <li><a href="index.php" class="btn1" >Acceuil</a></li>  
</ul>
</header> 
<section  class="banniere" id="banniere">
<div class="contenu">
    <?php
        if(isset($_SESSION['message_log'])){
            echo $_SESSION['message_log'];
            unset($_SESSION['message_log']);
        }
    ?>
    <form method="post" action="connexion.php">
        <label>Username</label>
        <input type="text" name="username" />

        <label>Mot de passe</label>
        <input type="password"  name="password" />

        <input type="submit" name="btn_connexion" value="connecter" style="background:#3498DB;color:#fff;"/>
    </form>
    </html>
</div>  
</section>
</body>
</html>
 