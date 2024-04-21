<?php 
namespace Controllers;


use Model\Ponente;

class APIPonentes {

    public static function index(){
       
        $ponente = Ponente::all();
        echo json_encode($ponente);
    }
}