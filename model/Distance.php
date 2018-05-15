<?php

namespace model;

class Distance{
	private $distance;
    private $fullData;

	public function __construct(array $distance){
        $this->fullData = $distance;
		$this->distance = $distance;
	}

    public static function getSingleLink($matrixA,$matrixB){
    	$T = $matrixA->min($matrixB);
        $T = $T->transpose()->multiply($T);
        $T = $T->toArray();
    	return sqrt($T[0][0]);
    }

    public function getDataList(){
    	return $this->distance;
    }

    public function getData($pointA,$pointB){
    	$data = $this->getDataList();
        if($pointA<$pointB){
            $A = $pointA; 
            $B = $pointB; 
        } else {
            $A = $pointB;
            $B = $pointA;
        }
        $sizeData = sizeof($data);
    	for($i=0;$i<$sizeData;$i++) {
    		if($data[$i]["clusterA"] == $A && $data[$i]["clusterB"]==$B){
                $result = $data[$i]["distance"];
                unset($data[$i]);
                $this->setData($data);
    			return $result;
    		}
    	}
    	return null;
    }

    public function remove($pointA,$pointB){
        $data = $this->fullData;
        if($pointA<$pointB){
            $A = $pointA; 
            $B = $pointB; 
        } else {
            $A = $pointB;
            $B = $pointA;
        }
        $sizeData = sizeof($data);
        for($i=0;$i<$sizeData;$i++) {
            if($data[$i]["clusterA"] == $A && $data[$i]["clusterB"]==$B){
                unset($data[$i]);
                $newData = array_values($data);
                return $newData;
            }
        }
        return null;
    }

    public function getCompleteLink($datasA,$datasB){
    	//$val = array();
        //echo json_encode($datasB[0]["index"]);
        $furthest = 0;
		foreach ($datasA as $dataA) {
			# code...
			foreach ($datasB as $dataB) {
			     # code...
				$val = $this->getData($dataA["index"],$dataB["index"]);
                if($furthest<$val){
                    $furthest = $val;
                }
			}
		}
    	//return max($val);
        return $furthest;
    }

    public function setData($distance){
        //echo "Jumlah Data :".sizeof($distance);
        $this->distance = array_values($distance);
    }
}