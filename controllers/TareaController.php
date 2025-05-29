<?php

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;

class TareaController{


    public static function index(){
            //vamos a imprimir mediante la API

            session_start();
            $proyecto_id = $_GET['url'];
            // debuguear($proyecto_id);
            if (!$proyecto_id) {
                header('Location: /dashboard');
            }
            $proyecto = Proyecto::where('url', $proyecto_id);
            // debuguear($proyecto);
            if (!$proyecto || $proyecto->propietario_id !== $_SESSION['id']) {
                header('Location: /404');
            }

            $tareas = Tarea::belongsTo('proyecto_id', $proyecto->id );
            
            echo json_encode(['tareas' => $tareas]);
    }

    public static function crear(){
        

        if ($_SERVER['REQUEST_METHOD']=== 'POST') {
            //iniciamos sesion para tener acceso a los atributos del usuario que inicio sesion
            session_start();
            //leemos lo que mandamos de tareas.js en el modal
            //le asignamos el valor a una variable
            $proyecto_id = $_POST['proyecto_id'];
            // debuguear($proyecto_id);
            
             $proyecto = Proyecto::where('url', $proyecto_id);
             if (!$proyecto || $proyecto->propietario_id !== $_SESSION['id'] ) {
                $repuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];
                echo json_encode($repuesta);
                return;
             }
            //Todo bien, instanciar y crear la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyecto_id = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada Correctamente',
                'proyecto_id' => $proyecto->id
            ];
            echo json_encode($respuesta);
           
        }

    }


    public static function actualizar(){
        

        if ($_SERVER['REQUEST_METHOD']=== 'POST') {
            session_start();
            $proyecto = Proyecto::where('url', $_POST['proyecto_id'] );

            if (!$proyecto || $proyecto->propietario_id !== $_SESSION['id'] ) {
                $repuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la Tarea'
                ];
                echo json_encode($repuesta);
                return;
             }
             $tarea = new Tarea($_POST);
             $tarea->proyecto_id = $proyecto->id;
             
             $resultado = $tarea->guardar();

             if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'mensaje' => 'Tarea Actualizada Correctamente',
                    'proyecto_id' => $proyecto->id
                ];
                echo json_encode(['respuesta' => $respuesta]);
             }

           
        }

    }


    public static function eliminar(){
        
        session_start();
        if ($_SERVER['REQUEST_METHOD']=== 'POST') {

            $proyecto = Proyecto::where('url', $_POST['proyecto_id'] );

            if (!$proyecto || $proyecto->propietario_id !== $_SESSION['id'] ) {
                $repuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la Tarea'
                ];
                echo json_encode($repuesta);
                return;
             }

             $tarea = new Tarea($_POST);
             $tarea->proyecto_id = $proyecto->id;
             $resultado = $tarea->eliminar();

             $resultado = [
                'resultado' => $resultado,
                'tipo' => 'exito',
                'mensaje' => 'Eliminado Correctamente'
             ];

            echo json_encode($resultado);
           
            
        }

    }
}