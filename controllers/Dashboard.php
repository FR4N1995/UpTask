<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuarios;

class Dashboard{


public static function index(Router $router){
    session_start();
    //Con la funcion de abajo protejemos la url
    //con esa vemos si iniciamos sesion
    inicioSesion();
    $id = $_SESSION['id'];

    $proyectos = Proyecto::belongsTo('propietario_id', $id);
    // debuguear($proyectos);
    $router->render('dashboard/home', [
        'titulo' => 'Proyectos',
        'proyectos' => $proyectos
    ]);


}

public static function crear_proyecto(Router $router){
        session_start();
        inicioSesion();
         $alertas=[];

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {                                                                                               
         $proyecto = new Proyecto($_POST);
        //  debuguear($proyecto);
         $alertas = $proyecto->validarProyecto();

         if (empty($alertas)) {
            //geberar la url unica
              $proyecto->url = md5(uniqid());
            //asignar al propietario
            $proyecto->propietario_id = $_SESSION['id'];

            //Guardar El Proyecto
             $proyecto->guardar();

             header('Location: /proyecto?url='. $proyecto->url);

           
         }
    }

    $router->render('dashboard/crear-proyecto', [
        'titulo' => 'Crear Proyecto',
        'alertas' => $alertas
    ]);
}

public static function proyecto(Router $router){
    session_start();
    inicioSesion();

    $url = $_GET['url'];
    if (!$url) {
        header('Location: /dashboard');
    }
    $proyecto = Proyecto::where('url', $url);
    // debuguear($proyecto);

    //revisar que la persona que visita el proyecto es quien lo creo


    $router->render('dashboard/proyecto',[
       'titulo' => $proyecto->nombre_proyecto
    ]);
}

    public static function perfil(Router $router){
        session_start();
        inicioSesion();
        $alertas = [];
        $usuario = Usuarios::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if (empty($alertas)) {

                $existeUsuario = Usuarios::where('email', $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                Usuarios::seterrores('error', 'El email ya esta Registrado');
                $alertas = $usuario->getErrores();
                }else{
                $usuario->guardar();

                Usuarios::seterrores('exito', 'Guardado Correctamente');
                $alertas = $usuario->getErrores();

                //asignar el nombre nuevo a la barra
                $_SESSION['nombre'] = $usuario->nombre;

                }

            
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
             'usuario' => $usuario,
             'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router){
        session_start();
        inicioSesion();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Primeo ay que encontrar ese usuario
            $usuario = Usuarios::find($_SESSION['id']);
            //sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if (empty($alertas)) {
                
                $resultado = $usuario->comprobarPassword();

                if ($resultado) {
                     //asignaremos el nuevo password
                    $usuario->password = $usuario->password_nuevo;
                    //eliminamos las propiedades del objeto que no son necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hasheamos el nuevo password
                    $usuario->hashearPassword();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        Usuarios::seterrores('exito', 'Password Actualizado Correctamente');
                        $alertas = $usuario->getErrores();
                    }
                   

                }else{
                    Usuarios::setErrores('error', 'password Incorrecto');
                    $alertas = $usuario->getErrores();
                }
            }

        }
        
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }


}