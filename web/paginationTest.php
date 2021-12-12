<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="lib/dataTables/css/data.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet">
    <title>Watson - Test Pagination </title>
</head>
          <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-target">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" id="logo" href="../web/">Watson</a>
                </div>
            </div><!-- /.container -->
          </nav>

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

$liensParPage=5; //Nous affichons 5 liens par page.

//Nous récupérons le contenu de la requête dans $result
$sql = "SELECT COUNT(*) AS total FROM tl_liens";
$result = mysqli_query($app, $sql);


$donnees_total=mysqli_fetch_assoc($result);


$total=$donnees_total['total'];

$nombreDePages=ceil($total/$liensParPage);


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
          echo ' <a href="paginationTest.php?page='.$i.'">'.$i.'</a> ';
     }
}
echo '</p>';
?>
