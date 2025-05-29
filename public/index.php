<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\Dashboard;
use MVC\Router;
use Controllers\LoginController;
use Controllers\TareaController;

$router = new Router();



$router->get('/', [LoginController::class, 'login']) ;
$router->post('/', [LoginController::class, 'login']) ;

$router->get('/logout', [LoginController::class, 'logout']) ;


//crearCuenta
$router->get('/crear', [LoginController::class, 'crear']) ;
$router->post('/crear', [LoginController::class, 'crear']) ;

//formulario de olvide mi password
$router->get('/olvide', [LoginController::class, 'olvide']) ;
$router->post('/olvide', [LoginController::class, 'olvide']) ;

//colocar nuevo password
$router->get('/restablecer', [LoginController::class, 'restablecer']) ;
$router->post('/restablecer', [LoginController::class, 'restablecer']) ;

//confirmacion de cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']) ;
$router->get('/confirmar', [LoginController::class, 'confirmar']) ;

//Zona de Proyectos
$router->get('/dashboard', [Dashboard::class, 'index']);
$router->get('/crear-proyecto', [Dashboard::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [Dashboard::class, 'crear_proyecto']);
$router->get('/proyecto', [Dashboard::class, 'proyecto']);
$router->get('/perfil', [Dashboard::class, 'perfil']);
$router->post('/perfil', [Dashboard::class, 'perfil']);
$router->get('/cambiar-password', [Dashboard::class, 'cambiar_password']);
$router->post('/cambiar-password', [Dashboard::class, 'cambiar_password']);
 
//Aqui creamos la API para las tareas

$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);


$router -> comprobarRutas();