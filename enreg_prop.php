<!DOCTYPE html>
<html>
<head>
<title>Hasck : compte</title>
</head>
<body>

<?php
ini_set('display_errors',1);
include("produits.inc");
include_once("accueil.php");
include_once("sroot.php");

//print("debut enreg_prop");

$nom=$_POST['nom'];
$prix=$_POST['prix'];
$descr=$_POST['descr'];
$bogus=$_POST['bogus'];

$target_dir = "/Uploads/";
$target_file = SITE_ROOT.$target_dir . basename($_FILES["nom_image"]["name"]);
$target_file2=$target_dir.basename($_FILES["nom_image"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["nom_image"]["tmp_name"]);
    if($check !== false) {
        echo "Le fichier est une image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "Le fichier n est pas une image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Desole, le fichier existe deja.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["nom_image"]["size"] > 500000) {
    echo "Desole, l image est trop volumineuse.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Desole, seuls les fichiers aux formats JPG, JPEG, PNG & GIF sont autorises.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Mince, votre fichier n a pas pu etre charge.";
// if everything is ok, try to upload file
} else {
	//echo "nom du fichier temporaire : ".$_FILES['nom_image']['tmp_name'];
	//echo "nom du fichier de destination : $target_file";
    if (move_uploaded_file($_FILES["nom_image"]["tmp_name"], $target_file)) {
        echo "Le fichier ". basename( $_FILES["nom_image"]["name"]). " a ete charge.";
    } else {
        echo "Mince, une erreur est survenue lors du chargement de votre fichier.";
    }
}

$connection=mysqli_connect($host,$user,$password,$database)
	or die("Impossible to connect to the database in enreg_prop.php");

$query="INSERT INTO propositions(Nom,Image,Prix,Description)
			VALUES('$nom','$target_file2',$prix,'$descr')";
$query_successful=mysqli_query($connection,$query) or die("Impossible to add the proposition in enreg_prop.php, erreur : mysqli_error($connection)");
if($query_successful!==false){
	echo "Proposition enregistree";
}
else{
	echo "Proposition NON enregistree";
}

mysqli_close($connection);

afficheAccueil("",$bogus);

?>

</body>
</html>