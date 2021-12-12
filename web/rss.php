<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="lib/dataTables/css/data.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet">
    <title>Watson - RSS </title>
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
echo "<div class='container'>Le flux rss à été créé ! ".$lin."</div>";
?>