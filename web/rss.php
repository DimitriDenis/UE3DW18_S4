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

$liensParPage=5; //Nous affichons 5 liens par page.

//Nous récupérons le contenu de la requête dans $result
$sql = "SELECT COUNT(*) AS total FROM tl_liens";
$result = mysqli_query($app, $sql);


$donnees_total=mysqli_fetch_assoc($result);


$total=$donnees_total['total'];
echo $total;

$nombreDePages=ceil($total/$liensParPage);

echo '<p>Il y a </p>'.$nombreDePages.'<p>pages </p> ';

if(isset($_GET['page'])) // Si la variable $_GET['page'] existe
{
     $pageActuelle=intval($_GET['page']);
 
     if($pageActuelle>$nombreDePages) 
     {
          $pageActuelle=$nombreDePages;
          echo $pageActuelle;
     }
}
else 
{
     $pageActuelle=1;    
}

$premiereEntree=($pageActuelle-1)*$liensParPage; 
 
// La requête sql pour récupérer les liens de la page actuelle.
$sql2 = "SELECT * FROM tl_liens ORDER BY id DESC LIMIT ";
$retour_liens=mysqli_query($app, 'SELECT * FROM tl_liens ORDER BY lien_id DESC LIMIT '.$premiereEntree.', '.$liensParPage.'');

 
while($donnees_liens=mysqli_fetch_assoc($retour_liens)) // On lit les entrées grâce à une boucle while
{
     
     echo '<table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                     <td><strong>Ecrit par : '.$donnees_liens['lien_url'].'</strong></td>
                </tr>
                <tr>
                     <td>'.nl2br($donnees_liens['lien_titre']).'</td>
                </tr>
            </table><br /><br />';
      
}

echo '<p align="center">Page : '; 
for($i=1; $i<=$nombreDePages; $i++) 
{
     
     if($i==$pageActuelle) 
     {
         echo ' [ '.$i.' ] '; 
     }    
     else 
     {
          echo ' <a href="rss.php?page='.$i.'">'.$i.'</a> ';
     }
}
echo '</p>';
?>
