<?php

class topsis{
    private $category = ["pasar", "pendapatan", "infrastruktur", "transportasi"];
    private $weight = [
        "pasar" => 40,
        "pendapatan" => 70,
        "infrastruktur" => 20,
        "transportasi" => 25
    ];

    private $dataExample = [
        "Alt 1" => [
            "pasar" => 2100000,
            "pendapatan" => 7200,
            "infrastruktur" => 800,
            "transportasi" => 100
        ],
        "Alt 2" => [
            "pasar" => 2000000,
            "pendapatan" => 7200,
            "infrastruktur" => 600,
            "transportasi" => 150
        ],
        "Alt 3" => [
            "pasar" => 2450000,
            "pendapatan" => 7200,
            "infrastruktur" => 1000,
            "transportasi" => 200
        ]
    ];

    function division($data = []){
        $temp = 0;
        foreach($data as $key => $value){
            $temp += pow($value, 2);
        }
        $temp = sqrt($temp);
        return $temp;
    }

    function calculateTopsis(){
        $div = [];
        $siplus = [];
        $siminus = [];
        $final = [];
        foreach($this->category as $keyCtgr){
            $div[$keyCtgr] = $this->division(array_column($this->dataExample,$keyCtgr));
            $dataAlt = array_column($this->dataExample, $keyCtgr);
            $weighted = [];
            foreach($dataAlt as $keyExp => $dataExp){
                $weighted[] = ($dataExp/$div[$keyCtgr])*$this->weight[$keyCtgr];
            }
            foreach($weighted as $key => $value){
                if(array_key_exists($key, $siplus) == false || array_key_exists($key, $siminus) == false){
                    $siplus[$key] = [];
                    $siminus[$key] = [];
                }
                array_push($siplus[$key],pow((max($weighted) - $value), 2));
                array_push($siminus[$key], pow(($value - min($weighted)),2));
            }

        }
        foreach($siplus as $key => $data){
          # summary of si+ and si-
            $sumsi = sqrt(array_sum($data))+sqrt(array_sum($siminus[$key]));
            $final[$key] = sqrt(array_sum($siminus[$key]))/$sumsi;
        }
        # sort alt based on weight
        usort($final, function ($a, $b) { return $a < $b; });
    }
}
?>
