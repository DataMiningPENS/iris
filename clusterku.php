<?php

include("Phpml\Math\Matrix.php");
use Phpml\Math\Matrix;

include getcwd()."\model\Distance.php";
use model\Distance;

set_time_limit(300);
//1. Convert Json to Cluster data
$json = file_get_contents('iris.json');
$irisList =  json_decode($json);

$cluster = [];
$data = array();
for($i=0;$i<sizeof($irisList);$i++) {
	$key = $irisList[$i];
	$data[] = array(
		"matrix"=>[
			[$key->sepalLength],
			[$key->sepalWidth],
			[$key->petalLength],
			[$key->petalWidth]
		],
		"species"=>$key->species
	);
	$cluster[] = array($i);
}

//2. Count Distance between Points
$distance = array();
for($i=0;$i<sizeof($cluster);$i++){
	for($j=$i+1;$j<sizeof($cluster);$j++){
		$distance[$i][$j] = floor(Distance::getSingleLink(
								new Matrix($data[$i]["matrix"]),
								new Matrix($data[$j]["matrix"])
							)*100000)/100000;
	}
}
//3. Initialize Target Cluster
$n = 3;
while(sizeof($cluster)>$n){
	//4. Find Farest but Nearest each Cluster
	//For every Cluster
	$sizeCluster = sizeof($cluster);
	$smallest = 99999;
	for($src=0;$src<$sizeCluster;$src++){
		for($trg=$src+1;$trg<$sizeCluster;$trg++){
			$farest = 0;
			//Foreach data in cluster
			foreach ($cluster[$src] as $source) {
				foreach ($cluster[$trg] as $target) {
					//Filter Farest Distance between Cluster Source and Cluster Target
					if($source<$target){
					    if($distance[$source][$target]>$farest){
							$farest = $distance[$source][$target];
							$farestIndexSource = $src;
							$farestIndexTarget = $trg;
						}
					}else{
						if($distance[$target][$source]>$farest){
							$farest = $distance[$target][$source];
							$farestIndexSource = $trg;
							$farestIndexTarget = $src;
						}
					}
				}
			}
			//Find Smallest Distance of Every Cluster
			if($farest < $smallest){
				$smallest = $farest;
				$smallestClusterSource = $farestIndexSource;
				$smallestClusterTarget = $farestIndexTarget;
			}
		}
	}
	foreach($cluster[$smallestClusterTarget] as $dataTarget){
		$cluster[$smallestClusterSource][] = $dataTarget;
	}
	unset($cluster[$smallestClusterTarget]);
	$cluster = array_values($cluster);
}
$clusterParse = array();
foreach ($cluster as $icluster) {
	$items = [];
	foreach ($icluster as $item) {
		 $items[] = $data[$item];
	}
	$clusterParse[] = $items;
}
echo json_encode($clusterParse);
