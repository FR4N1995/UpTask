<?php

namespace Model;

class Proyecto extends ActiveRecord{

    protected static $tabla = 'proyectos';
    protected static $columnasDB= ['id', 'nombre_proyecto', 'url', 'propietario_id'];

    public function __construct($args=[]){

        $this->id= $args['id'] ?? null;
        $this->nombre_proyecto = $args['nombre_proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietario_id = $args['propietario_id'] ?? '';
        
    }        


    public function validarProyecto(){

        if (!$this->nombre_proyecto) {
            self::$errores['error'][] = 'El nombre del proyecto es obligatorio';
        }

        return self::$errores;
    }


   
}
