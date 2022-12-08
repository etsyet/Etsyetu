<?php 
session_start();
require "connection.php";


$email = $_SESSION['email'];
$password = $_SESSION['password'];
if(isset($_SESSION['is_admin'])){
  $is_admin=$_SESSION['is_admin'];
  }else{
    //pas d'acces admin pour ce compte
    header('Location: home.php');
  
  }
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
            if($code != 0){
                header('Location: reset-code.php');   
            }
        }else{
            header('Location: user-otp.php');
        }
    }
}else{
    header('Location: login-user.php');
}


function save_image(&$path_save){

    $target_dir = $path_save;
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    //$target_file=file_exists($target_file)?$target_file.rand(999999, 111111):$target_file;
    echo "target file : ".$_FILES["photo"]["name"];
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if(file_exists($target_file)){
        $basename=strtolower(pathinfo($target_file,PATHINFO_FILENAME));
        $basename=$basename.rand(999999, 111111).".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $target_file=$target_dir.$basename;

    }
    // Check if image file is a actual image or fake image
    if(isset($_POST["btnsub"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        }else {
            echo "File is not an image.";
            $uploadOk = 0;
            $_SESSION['image_error']=1;
            }
        }
        //verifiez si la fichier existe pas deja
        if (file_exists($target_file)) {
            echo $target_file;
            echo "Sorry, file already exists.";
            $uploadOk = 0;
            $_SESSION['image_error']=1;
        }
        // veifiez la taille de l'image...ne doit depasser 1.5Mega
        if ($_FILES["photo"]["size"] > 1500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
            $_SESSION['image_error']=1;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo $imageFileType;
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            $_SESSION['image_error']=1;
            // if everything is ok, try to upload file
        }else{
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["photo"]["name"])). " has been uploaded.";
                $_SESSION['photo_path']=$target_file;
                unset($_SESSION['image_error']);
            }else{
                echo "Sorry, there was an error uploading your file.";
                $_SESSION['image_error']=1;
            }
        }
}

//save recette
if(isset($_POST['saveserv'])){
	//$serv_id=(int) $_GET['idservice'];

    $designation=isset($_POST['designation'])?$_POST['designation']:header('Location:addservice.php');
    $prix=isset($_POST['prix'])?(float)$_POST['prix']:header('Location:addservice.php');
    $duree=isset($_POST['duree'])?(int) $_POST['duree']:header('Location:addservice.php');
    $description=isset($_POST['description'])?$_POST['description']:header('Location:addservice.php');
    $description=addslashes($description);
    $photo=isset($_SESSION['photo_path'])?$_SESSION['photo_path']:header('Location:addservice.php');
    $destination_save='images/';

    //save phot to destination
    save_image($destination_save);
    if(isset($_SESSION['image_error'])){
        $_SESSION['message_add']="<span style='color: #e74c3c;'>*La photo que vous avez ajouter est invalide, (les criteres: doit etre JPG, PNG ou GIF de taille inferieur ou egale a 1.5Mega)</span>";
        header('Location: addservice.php');
    }else{
        $req="INSERT INTO services(designation, description, prix, duree, photo) VALUES('$designation','$description', '$prix', '$duree','$photo')";
        $res=mysqli_query($con, $req);
        if($res){
            $_SESSION['message_add']="<span style='color: #2ecc71'>Le service a ete ajouter avec success!</span>";
            header('Location: addservice.php');
        }else{
            $_SESSION['message_add']="<span style='color:red'>*Une erreur s'est produit, VEUILLEZ REESSAYEZ SVP(Astuce: verifiez que cette catgorie n'existe pas deja)</span>";
            header('Location: addservice.php');
        }
    }
    
}else{
    header('Location: addservice.php');
}

?>