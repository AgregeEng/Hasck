<!DOCTYPE html>
<html>
<head>
<title>Hasck : payer</title>
</head>
<body>

<?php
ini_set('display_errors',1);
include_once("accueil.php");
include_once("sroot.php");
$directory=SITE_ROOT."/Stripe/init.php";
require_once($directory);
define("exec_debutacheter",0);
include_once("acheter.php");
//die("payer atteint");

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey("sk_test_UdMAVMUEGtISiJCMRgjzjtK1");

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
$montant=$_POST['montant'];
echo "montant : $montant";
$mail=$_POST['stripeEmail'];
$bogus=$_POST['bogus'];
//$quantite=$_POST['qte'];

echo "<br>payer, debut<br>";
echo "token, $token<br>";
echo "montant, $montant<br>";
echo "mail, $mail<br>";
echo "bogus, $bogus<br>";

try{
	$charge = \Stripe\Charge::create([
		'amount' => $montant,
		'currency' => 'usd',
		'description' => 'Example charge',
		'source' => $token,
		]);
}
catch (\Stripe\Error\ApiConnection $e) {
	die("Erreur de connexion");
    // Network problem, perhaps try again.
} catch (\Stripe\Error\InvalidRequest $e) {
	die("Requete invalide");
    // You screwed up in your programming. Shouldn't happen!
} catch (\Stripe\Error\Api $e) {
	die("Serveur de paiement indisponible");
    // Stripe's servers are down!
} catch (\Stripe\Error\Card $e) {
	die("Mauvaises coordonnees de carte");
    // Card was declined.
}

$chargeID=charge->id;

//Paramètre quantité passé à 0, car dans "acheter.php", on récupérera la quantité dans les paniers
//Le paramètre quantité n'a d'utilité que pour faire marcher la fonction correspondante dans l'application
vide_panier($mail,$bogus,0,0,$chargeID);

?>

</body>
</html>