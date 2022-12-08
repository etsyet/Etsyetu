<?php
session_start();
    include "connexionbdd.php";
/*$password=password_hash("12345", PASSWORD_BCRYPT);
    $req="INSERT INTO login_user(username, password) VALUES('joel', '$password')";
    $reexec=mysqli_query($con, $req);*/

    function save_image(&$path_save){

        $target_dir = $path_save;
        $target_file = $target_dir . basename($_FILES["fileimage"]["name"]);
        //$target_file=file_exists($target_file)?$target_file.rand(999999, 111111):$target_file;
        //echo "target file : ".$_FILES["fileimage"]["name"];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        //echo $imageFileType;
        if(file_exists($target_file)){
            $basename=strtolower(pathinfo($target_file,PATHINFO_FILENAME));
            $basename=$basename.rand(999999, 111111).".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $target_file=$target_dir.$basename;
    
        }
        // Check if image file is a actual image or fake image
        if(isset($_POST["addArt"])) {
            $check = getimagesize($_FILES["fileimage"]["tmp_name"]);
            if($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            }else {
                //echo "File is not an image.";
                $uploadOk = 0;
                $_SESSION['image_error']=1;
                }
            }
            //verifiez si la fichier existe pas deja
            if (file_exists($target_file)) {
                echo $target_file;
               // echo "Sorry, file already exists.";
                $uploadOk = 0;
                $_SESSION['image_error']=1;
            }
            // veifiez la taille de l'image...ne doit depasser 1.5Mega
            if ($_FILES["fileimage"]["size"] > 1500000) {
                //echo "Sorry, your file is too large.";
                $uploadOk = 0;
                $_SESSION['image_error']=1;
            }
    
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo $imageFileType;
                //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
    
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                //echo "Sorry, your file was not uploaded.";
                $_SESSION['image_error']=1;
                // if everything is ok, try to upload file
            }else{
                if (move_uploaded_file($_FILES["fileimage"]["tmp_name"], $target_file)) {
                    //echo "The file ". htmlspecialchars( basename( $_FILES["fileimage"]["name"])). " has been uploaded.";
                    $_SESSION['photo_path']=$target_file;
                    unset($_SESSION['image_error']);
                }else{
                    //echo "Sorry, there was an error uploading your file.";
                    $_SESSION['image_error']=1;
                }
            }
    }


if(isset($_POST["btn_connexion"])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    
    $login_req="SELECT * FROM login_user WHERE username='$username'";
    $exec_login=mysqli_query($con, $login_req);
    if($exec_login){
        $user_data=mysqli_fetch_assoc($exec_login);
        if(mysqli_num_rows($exec_login) >0){
            //l'usernme existe
            if(password_verify($password, $user_data['password'])){
                //l'utilisateur est connecter
                $_SESSION['username']=$user_data['username'];
                $_SESSION['iduser']=$user_data['id'];
                header('Location: adminhome.php');
            }else{
                $_SESSION['message_log']="<span style='color:red'>Votre mot de passe est incorrect!</span>";
            }
        }else{
            $_SESSION['message_log']="<span style='color:red'>Il semblerait que vous n'ayez pas encore de compte!</span>";
        }
    }

    
}elseif(isset($_POST['addArt'])){
    $artname=$_POST['nomArt'];
    $prix=$_POST['prix'];
    
    //$dateAdd=$_POST['dateAjoutArt'];
    $destination_save="IMG/";
    $stock=$_POST['stock'];
   
    //check if article alred exist
    $req13="SELECT * FROM article WHERE NomArticle='$artname'";
    $execreq13=mysqli_query($con, $req13);
    if($execreq13){
        if(mysqli_num_rows($execreq13) <1){
            //on enregistre
             save_image($destination_save);
            $fileimage=$_SESSION['photo_path'];//$_POST['fileimage'];

            $req45="INSERT INTO article(NomArticle, PrixNormal, ImageArticle, DateAjoutArticle, StockArticle) VALUES('$artname', '$prix','$fileimage', NOW(), '$stock')";

            $exc=mysqli_query($con, $req45);
            if($exc){
                $_SESSION["message_op4"]="<span style='color:red'>Article ajoter avec success!</span>";
            }else{
                $_SESSION['message_op4']="<span style='color:red'>Cette article n'a pas pu etre ajouter reessayer</span>";
            }

        }else{
            $_SESSION['message_op4']="<span style='color:red'>Cette article a deja ete ajouter</span>";
        }
    }


}elseif(isset($_POST["addPromo"])){
    $artPromo=$_POST['articlename'];
    $prixpromo=$_POST['prixpromo'];
    $dateDebut=$_POST['datedebut'];
    $dateFin=$_POST['datefin'];
    //le prix promo ne doit jamais etre superieur ou egale au prix normal
    $article_info="SELECT * FROM article WHERE IdArticle='$artPromo'";
    $exec_info_art=mysqli_query($con, $article_info);
    if($exec_info_art){
        if(mysqli_num_rows($exec_info_art) >0){
            $data_art=mysqli_fetch_assoc($exec_info_art);
            if(floatval($prixpromo) >= $data_art['PrixNormal']){
                echo "<script>alert('Le prix promotionnel doit etre inferieur au prix normal!')</script>";
                return 1;
            }
        }
    }
    //deux promotion ne peuevent avoir le meme prix
    $req_check="SELECT * FROM articleenpromotion WHERE idArticle='$artPromo'";
    $exec_check=mysqli_query($con, $req_check);

    if($exec_check){
        if(mysqli_num_rows($exec_check)>0){

            while($row=mysqli_fetch_assoc($exec_check)){

                $prm_id=$row['idPromotion'];
                //verifi le prix
                $chec_prix=mysqli_query($con,"SELECT PrixPromotionnel FROM promotion WHERE IdPromotion='$prm_id'");
                if($chec_prix){
                    $xx=mysqli_fetch_assoc($chec_prix);

                    if(floatval($xx['PrixPromotionnel']) == floatval($prixpromo)){
                        echo "<script>alert('Ce prix promotionnel a deja ete ajouter')</script>";
                        return 1;
                    }
                }
            }
        }
    }

    $req_check2="SELECT * FROM articleenpromotion WHERE idArticle='$artPromo'";
    $exec_check2=mysqli_query($con, $req_check2);

    if($exec_check2){
            //le promotion peu etre enregistrer
            $req45="INSERT INTO promotion(PrixPromotionnel, DateAjoutPromotion, DateFinPromotion) VALUES('$prixpromo','$dateDebut', '$dateFin')";
            $exc=mysqli_query($con, $req45);
            if($exc){
                $promotion_id = mysqli_insert_id($con);
                //add in article en promotion
                $req0045="INSERT INTO articleenpromotion(idArticle, idPromotion) VALUES('$artPromo','$promotion_id')";
                if(mysqli_query($con, $req0045)){
                    $_SESSION["message_op4"]="<span style='color:green'>***promotion ajouter avec success!</span>";
                }else{
                    $delete_missing="DELETE FROM promotion WHERE IdPromotion='$promotion_id'";
                    $tmp=mysqli_query($con, $delete_missing);
                    if($tmp){
                        $_SESSION['message_op4']="<span style='color:red'>***Cette promotion n'a pas pu etre ajouter reessayer</span>";
                    }else{
                        $_SESSION['message_op4']="<span style='color:red'>***Cette promotion a etait mal enregistrer veuillez la supprimer depuis la base de donnees ..puis reessayer.</span>";
                    }
                }
                
            }else{
                $_SESSION['message_op4']="<span style='color:red'>***Cette promotion n'a pas pu etre ajouter reessayer</span>";
            }
        
    }

}elseif (isset($_POST['addlogin'])) {
    $username=$_POST['username'];
    $password=$_POST['password'];

    $password=password_hash($password, PASSWORD_BCRYPT);
    $req="INSERT INTO login_user(username, password) VALUES('$username', '$password')";
    $reexec=mysqli_query($con, $req);
}

?>