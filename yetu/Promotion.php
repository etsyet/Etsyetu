<?php
  include "connexionbdd.php";
  //clean promo table....retirer les promo expirer
  $req002=mysqli_query($con, "SELECT * FROM articleenpromotion");
  if($req002){
    while($row=mysqli_fetch_assoc($req002)){
      $id_promo=$row['idPromotion'];
      $promo=mysqli_query($con, "SELECT * FROM promotion WHERE idPromotion='$id_promo'");
      if($promo AND mysqli_num_rows($promo)>0){
        $data_promo=mysqli_fetch_assoc($promo);
        $today=date("Y-m-d");
        if($today > $data_promo['DateFinPromotion']){
          //cette promotion a expiré; on l'efface
          //d'abord dans la table mouvement
          mysqli_query($con, "DELETE FROM articleenpromotion WHERE idPromotion='$id_promo'");
          //ensuite dans la table des promtion
          mysqli_query($con, "DELETE FROM promotion WHERE IdPromotion='$id_promo'");
        }
      }
    }
  }
  
  $req="SELECT * FROM articleenpromotion";
  $exec_req=mysqli_query($con, $req);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="16x16" href="IMG/ARRIERE.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Promotion</title>
</head>
<body>
    <section class="vente" id="vente">
        <div class="titre">
          <h2 class="titre-texte"><span>P</span>romotion des vetements de la semaine</h2>
        </div>
         <div class="contenu">
          <?php
            if($exec_req){

              while($row=mysqli_fetch_assoc($exec_req)){
                $idArt=$row['idArticle'];
                $idPromo=$row['idPromotion'];

                $get_=mysqli_query($con, "SELECT * FROM article WHERE IdArticle='$idArt'");
                $art_data=$get_?mysqli_fetch_assoc($get_):false;
                //get promotion info
                $get__=mysqli_query($con, "SELECT * FROM promotion WHERE idPromotion='$idPromo'");
                $promo_data=$get__?mysqli_fetch_assoc($get__):false;

                ?>

                <div class="box">
            <div class="imbox">
              <img src="<?php echo $art_data['ImageArticle']?>" alt=""  style="width:100%;display:inline-block;" height="100%"  >
            </div>
            <div class="text">
            <h3><?php echo $art_data['NomArticle'];?> </h3>
            <h3>Prix normal : <?php echo $art_data['PrixNormal'];?> $</h3>
            <h3>Prix Promo: <?php echo $promo_data['PrixPromotionnel'];?> $</h3>
            <h3>Promotion : -<?php
              $prixnormal=floatval($art_data['PrixNormal']);
              $prixpromo=floatval($promo_data['PrixPromotionnel']);
              $percent=($prixpromo*100)/$prixnormal;
              echo 100-number_format((float)$percent, 2, '.', '');

            ?>%</h3>
            <h5>Expire le : <?php echo $promo_data['DateFinPromotion'] ?></h5>
             
            </div>
          </div>
          <?php
              }
            }
            ?>
        </div>

</section> 
 <!-- <section class="vente" id="vente" >
    <div class="titre">
      <h2 class="titre-texte"><span>P</span>romotion</h2>
    </div>
    <div class="contenu">
      <div class="box">
        <div class="imbox">
          <img src="img/H2.jpg" alt=""  style="width:100%;display:inline-block;" height="100%"  >
        </div>
        <div class="text">
        <h3>Jeans Femme</h3>
        <h3>made in france</h3>
        <h3> Prix :20$ </h3>
        <a href="Henri.html" class="btn1">Apropos</a> 
        </div>
      </di>
    </div>
    <div class="box">
      <div class="imbox">
        <img src="img/F2.jpg" alt=""  style="width:100%;display:inline-block;"  height="100%" >
      </div>
      <div class="text">
      <h3>chaussure : Airforce one </h3>
      <h3> made in usa </h3>
      <h3> Prix : 30$ </h3>
      <a href="force.html" class="btn1">Apropos</a> 
      </div>
    </di>
  </div>
  <div class="box">
    <div class="imbox">
      <img src="img/h&à.jpg" alt=""  style="width:100%;display:inline-block;"  height="100%" >
    </div>
    <div class="text">
    <h3>Veste Boomber rouge blanc</h3>
    <h3> made in japon</h3>
    <h3> Prix : 15$ </h3>
    <a href="blanc.html" class="btn1">Apropos</a> 
    </div>
  </di>
  </div>
  <div class="box">
  <div class="imbox">
    <img src="img/H4.jpg" alt="" style="width:100%;display:inline-block;" height="100%">
  </div>
  <div class="text">
    <div class="bouton">
  <h3>Veste : Boomber verte </h3>
  <h3>made in japon</h3>
  <h3> Prix :15$ </h3>
  <a href="rk.html" class="btn1">Apropos</a>
    </div> 
  </div>
  </di>
  </div>
  <div class="box">
  <div class="imbox">
    <img src="img/I1.jpg" alt="" style="width:100%;display:inline-block;" height="100%" >
  </div>
  <div class="text">
  <h3>Veste : Boomber noir blanc </h3>
  <h3>made in italie</h3>
  <h3> Prix : 20$ </h3>
  <a href="noir.html" target="bank" class="btn1">Apropos</a> 
  </div>
  </di>
  </div>
  <div class="box">
  <div class="imbox">
    <img src="img/H9.jpg" alt=""  style="width:100%;display:inline-block;" height="100%" >
  </div>
  <div class="text">
  <h3>Veste : Eddy Kenzo rouge bordeau </h3>
  <h3> made in italie</h3>
  <h3> Prix :180$ </h3>
  <a href="kenzo.html" target="bank" class="btn1">Apropos</a> 
  </div>
  </di>
  </div>
  <div class="box">
  <div class="imbox">
    <img src="img/H6.jpg" alt=""  style="width:100%;display:inline-block;" height="100%" >
  </div>
  <div class="text">
  <h3>Jeans blanc homme</h3>
  <h3> made in italie</h3>
  <h3> Prix : 20$ </h3>
  <a href="Eddy.html" target="bank" class="btn1">Apropos</a> 
  </div>
  </di>
  </div>
  <div class="box">
  <div class="imbox">
    <img src="img/H8.jpg" alt=""  style="width:100%;display:inline-block;" height="100%" >
  </div>
  <div class="text">
  <h3> Veste :Eddy kenzo violet </h3>
  <h3>made in italie</h3>
  <h3> Prix : 180$ </h3>
  <a href="violet.html" target="bank" class="btn1">Apropos</a> 
  </div>
  </di>
  </div>
  <div class="box">
    <div class="imbox">
      <img src="" alt="" style="width:100%;display:inline-block;" height="100%">
    </div>
    <div class="text">
      <div class="bouton">
    <h3>Veste :  </h3>
    <h3>made in japon</h3>
    <h3> Prix :15$ </h3>
    <a href="rk.html" class="btn1">Apropos</a>
      </div> 
    </div>
    </di>
    </div>

  </section>--> 
</body>
</html>