<?php
$co = new PDO('mysql:host=localhost;dbname=watson;charset=utf8','watson','watson');
$sql = " SELECT * FROM tl_liens ORDER BY lien_id DESC LIMIT 15";
$result = $co->prepare($sql);
$result->execute();
$res = $result->fetchAll();
$i=0;
$xmlFile = new DOMDocument('1.0', 'utf-8');
$xmlFile->appendChild($rss = $xmlFile->createElement('channel'));
foreach ($res as $re) {
        $rss->appendChild($lien = $xmlFile->createElement('item'));
        $lien->appendChild($xmlFile->createElement('title', $re['lien_titre']));
        $lien->appendChild($xmlFile->createElement('link', $re['lien_url']));
        $lien->appendChild($xmlFile->createElement('description', $re['lien_desc']));
}
$xmlFile->formatOutput = true;
$xmlFile->save('rss.xml');
$lin="<a href='rss.xml'>Ici pour voir le fichier</a>";
echo "Le flux rss à été créé ! ".$lin;

?>



<!-- 
 PAGINATION  -->
<?php

$hostname = 'localhost';
$username = 'watson';
$password = 'watson';
$db = 'watson';

//On établit la connexion
$app = new mysqli($hostname, $username, $password, $db);

//On vérifie la connexion
if($app->connect_error){
    die('Erreur : ' .$app->connect_error);
}
echo 'Connexion réussie';

$messagesParPage=5; //Nous allons afficher 5 messages par page.

//Nous récupérons le contenu de la requête dans $result
$sql = "SELECT COUNT(*) AS total FROM tl_liens";
$result = mysqli_query($app, $sql);

//On range retour sous la forme d'un tableau.
$donnees_total=mysqli_fetch_assoc($result);

//On récupère le total pour le placer dans la variable $total.
$total=$donnees_total['total'];
echo $total;

$nombreDePages=ceil($total/$messagesParPage);

echo '<p>Il y a </p>'.$nombreDePages.'<p>pages </p> ';

if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
{
     $pageActuelle=intval($_GET['page']);
 
     if($pageActuelle>$nombreDePages) // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
     {
          $pageActuelle=$nombreDePages;
          echo $pageActuelle;
     }
}
else // Sinon
{
     $pageActuelle=1; // La page actuelle est la n°1    
}

$premiereEntree=($pageActuelle-1)*$messagesParPage; // On calcul la première entrée à lire
 
// La requête sql pour récupérer les messages de la page actuelle.
$sql2 = "SELECT * FROM tl_liens ORDER BY id DESC LIMIT ";
$retour_messages=mysqli_query($app, 'SELECT * FROM tl_liens ORDER BY id DESC LIMIT '.$premiereEntree.', '.$messagesParPage.'');

 
while($donnees_messages=mysqli_fetch_assoc($retour_messages)) // On lit les entrées une à une grâce à une boucle
{
     //Je vais afficher les messages dans des petits tableaux. C'est à vous d'adapter pour votre design...
     //De plus j'ajoute aussi un nl2br pour prendre en compte les sauts à la ligne dans le message.
     echo '<table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                     <td><strong>Ecrit par : '.$donnees_messages['pseudo'].'</strong></td>
                </tr>
                <tr>
                     <td>'.nl2br($donnees_messages['message']).'</td>
                </tr>
            </table><br /><br />';
    //J'ai rajouté des sauts à la ligne pour espacer les messages.   
}

echo '<p align="center">Page : '; //Pour l'affichage, on centre la liste des pages
for($i=1; $i<=$nombreDePages; $i++) //On fait notre boucle
{
     //On va faire notre condition
     if($i==$pageActuelle) //S'il s'agit de la page actuelle...
     {
         echo ' [ '.$i.' ] '; 
     }    
     else //Sinon...
     {
          echo ' <a href="index.php?page='.$i.'">'.$i.'</a> ';
     }
}
echo '</p>';
?>