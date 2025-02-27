<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;
$router = new Router();

//iniciar y cerrar sesion
$router->get('/',[LoginController::class, 'login']);
$router->post('/',[LoginController::class, 'login']);

$router->get('/logout',[LoginController::class, 'logout']);

//crear cuenta
$router->get('/crear-cuenta',[LoginController::class, 'crear']);
$router->post('/crear-cuenta',[LoginController::class, 'crear']);

//olvido contraseña
$router->get('/olvido-password',[LoginController::class, 'olvide']);
$router->post('/olvido-password',[LoginController::class, 'olvide']);

//nuevo password
$router->get('/restablecer',[LoginController::class, 'restablecer']);
$router->post('/restablecer',[LoginController::class, 'restablecer']);

//confirmacion de cuenta
$router->get('/confirmar',[LoginController::class, 'confirmar']);
$router->get('/mensaje',[LoginController::class, 'mensaje']);


//zona de proyectos
$router->get('/dashboard',[DashboardController::class, 'index']);
$router->get('/crear-proyectos',[DashboardController::class, 'crear']);
$router->post('/crear-proyectos',[DashboardController::class, 'crear']);
$router->get('/perfil',[DashboardController::class,'perfil']);
$router->post('/perfil',[DashboardController::class,'perfil']);
$router->get('/cambiar-password',[DashboardController::class,'cambiar_password']);
$router->post('/cambiar-password',[DashboardController::class,'cambiar_password']);
$router->get('/proyecto',[DashboardController::class,'proyecto']);

//API para tareas
$router->get('/api/tareas',[TareaController::class,'index']);
$router->post('/api/tareas',[TareaController::class,'crear']);
$router->post('/api/tareas/actualizar',[TareaController::class,'actualizar']);
$router->post('/api/tareas/eliminar',[TareaController::class,'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();