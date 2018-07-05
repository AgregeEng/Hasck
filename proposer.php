<!DOCTYPE html>
<html>
<head>
<title>Hasck : proposer un produit</title>
</head>
<body>

<?php

$bogus=$_POST['bogus'];

echo "<form action='enreg_prop.php' method='POST' enctype='multipart/form-data'>";
echo "<input type='text' name='nom' placeholder='Nom du produit'>";
echo "<input type='file' name='nom_image' id='id_image'>";
echo "<input type='textarea' col='30' row='40' name='descr' placeholder='Description'>";
echo "<input type='number' name='prix' value='Prix'>";
echo "<input type='hidden' name='bogus' value=$bogus>";
echo "<input type='submit' value='Envoyer proposition'>";
echo "</form>";

?>

</body>
</html>