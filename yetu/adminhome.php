<?php
    include "logic.php";

    if(!(isset($_SESSION['username']) AND isset($_SESSION['iduser']))){
        header('Location: connexion.php');
    }

    //get article
    $re_get_art="SELECT * FROM article";
    $excdata=mysqli_query($con, $re_get_art);

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
    .nav{
        background: #154360;
        padding:15px;
        color:#fff;
        width:100%;
    }

    h3{
        width:70%;
        display:inline-block;
    }
    .btn_commande{
        width:25%;
        display:inline-block;
        
    }
    .btn_commande #home{
        background:none;
        border:1px solid #fff;
        padding:5px;
        border-radius:5px;
        color:#fff;
        text-decoration:none;
    }

    .btn_commande .logout{
        float:right;
        text-decoration: none;
        color:#fff;
        background:red;
        border-radius:5px;
        padding:5px;
    }

    .row{
        width:100%;
    }

    .addart, .addpromo{
        display:inline-block;
        vertical-align:top;
        width:47%;
        height:95vh;
    }

    .addart{
        background:green;
        padding:10px;
    }
    .addpromo{
        background:yellow;
        padding:10px;
    }

    input{
        width:95%;
        border-radius:5px;
        border:1px solid grey;
        height:30px;
    }
  </style>
</head>
<body>
    <div class="nav">
        <h3>Bienvenue dans votre espace d'administration</h3>
        <div class="btn_commande">
            <a href="index.php" id="home" >Home</a>
            <a href="logout.php" class="logout">Deconnexion</a>
        </div>
    </div>

    <div class="row">
        <div class="addart">
            <h3>Ajoouter un article</h3>
            <form method="POST" action="adminhome.php" enctype="multipart/form-data">
                <label>Nom artcile</label>
                <input type="text" name="nomArt" required>
                <label>Prix </label>
                <input type="number" name="prix" required/>
                <label>Image de l'article</label>
                <input type="file" name="fileimage" required/>
                <label>Stock</label>
                <input type="number" name="stock" required/>
                <input type="submit" name="addArt" value="Ajouter" style="padding:5px;background:yellow;color:#000;width:40%;margin-top:10px;" />
            </form>
        </div>
        <div class="addpromo">
            <h3>Publier une promotion</h3>
            <?php

            if(isset($_SESSION['message_op4'])){
                ?>
                <p><?php echo $_SESSION['message_op4'];?></p>
                <?php

                unset($_SESSION['message_op4']);
            }
            ?>
            <form method="POST" action="adminhome.php">
                <label>Selectionner article</label>
                <select style="width:95%; height:30px;" name="articlename" required>
                    <?php
                        if($excdata){
                            while($row=mysqli_fetch_assoc($excdata)){
                                ?>
                                <option value="<?php echo $row['IdArticle']?>"><?php echo $row['NomArticle'];?></option>
                                <?php
                            }
                        }
                    ?>
                    
                </select>
                <label>Prix promotion(<span style="color:green">* Doit etre inferieur au prix normal de l'article)</span></label>
                <input type="text" name="prixpromo" required>
                <label>Date Debut </label>
                <input type="date"  name="datedebut" required/>
                <label>Date fin</label>
                <input type="date" name="datefin" required/>
               <input type="submit" name="addPromo" value="Ajouter" style="padding:5px;background:green;color:#fff;width:40%;margin-top:10px;"/>
                
            </form>

            <form style="margin-top:30px;" method="POST" action="adminhome.php">
                <h4>Ajouter un admin</h4>
                <label>Username</label>
                <input type="text" name="username">
                <label>Mot de passe</label>
                <input type="password" name="password">
                <input type="submit" name="addlogin" value="Ajouter" style="padding:5px;background:green;color:#fff;width:40%;margin-top:10px;"/>
            </form>
        </div>
    </div>
</body>
</html>