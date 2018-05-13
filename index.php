<?php
include 'KMeans\Space.php';
include 'KMeans\Cluster.php';

$json = file_get_contents('iris.json');
$irisList =  json_decode($json);

//$points;

for($i=0;$i<sizeof($irisList);$i++){
	$points[$i] = [$irisList[$i]->sepalLength,$irisList[$i]->sepalWidth];
}

$space = new KMeans\Space(2);
foreach ($points as $point)
    $space->addPoint($point);

$clusters = $space->solve(3);

foreach ($clusters as $i => $cluster)
    printf("Cluster %d [%d,%d]: %d points\n", $i, $cluster[0], $cluster[1], count($cluster));

?>