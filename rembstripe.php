<?php

remboursement_stripe($no_comm){

	include("produits.inc");
	include_once("sroot.php");
	$directory=SITE_ROOT."/Stripe/init.php";
	require_once($directory);

	$connection=mysqli_connect($host,$user,$password,$database)
		or die("Impossible to connect to the database in rembstripe.php");

		$query_chargeid="SELECT EncaissementID FROM commandes
						WHERE IdCommande=$no_comm";
		$result=mysqli_query($connection,$query_chargeid) or die("Impossible to find the charge id");
		if($line=mysqli_fetch_assoc($result)){
			$chid=$line['EncaissementID'];
			\Stripe\Stripe::setApiKey("sk_test_UdMAVMUEGtISiJCMRgjzjtK1");

			try{
				$refund = \Stripe\Refund::create([
					'charge' => '$chid',
					]);
			}
			catch (\Stripe\Error\ApiConnection $e) {
				die("Erreur de connexion");
				// Network problem, perhaps try again.
			}
			catch (\Stripe\Error\InvalidRequest $e) {
				die("Requete invalide");
				// You screwed up in your programming. Shouldn't happen!
				}
			catch (\Stripe\Error\Api $e) {
				die("Serveur de paiement indisponible");
				// Stripe's servers are down!
			}
			catch (\Stripe\Error\Card $e) {
				die("Mauvaises coordonnees de carte");
				// Card was declined.
			}
		}

                $update="UPDATE commandes
                         SET Remboursable=0,Rembourse=2
                         WHERE IdCommande=$no_comm";
                mysqli_query($connection,$update) or die("Impossible to set order to refunded and no refundable status");

		mysqli_close($connection);
}
?>