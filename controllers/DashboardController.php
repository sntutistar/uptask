<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        session_start();

        isAuth();

        $proyectos = Proyecto::belongsto('propietarioid', $_SESSION['id']);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear(Router $router)
    {
        session_start();

        isAuth();
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                $proyecto->propietarioid = $_SESSION['id'];
                $proyecto->guardar();
                header('location: /proyecto?url=' . $proyecto->url);
            }
        }


        $router->render('dashboard/crear', [
            'titulo' => 'Crear nuevo proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();

            if (empty($alertas)) {
                $existeusuario = Usuario::where('email', $usuario->email);

                if (!$existeusuario || $usuario->email == $_SESSION['email']) {
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Guardado correctamente');

                    $_SESSION['nombre'] = $usuario->nombre;
                } else {
                    Usuario::setAlerta('error', 'Correo pertenece a otra cuenta');
                }
                $alertas = Usuario::getAlertas();
            }
        }


        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function proyecto(Router $router)
    {
        session_start();

        isAuth();

        $token = $_GET['url'];
        if (!$token) header('location: /dashboard');
        $proyecto = Proyecto::where('url', $token);
        if ($proyecto->propietarioid !== $_SESSION['id']) {
            header('location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function cambiar_password(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevoPassword();

            if (empty($alertas)) {
                $resultado = $usuario->comprobarPassword();
                if ($resultado) {
                    unset($usuario->password_actual);
                    unset($usuario->password2);
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_nuevo);
                    $usuario->hashpassword();
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Contraseña se cambio de manera exitosa');
                        $alertas = Usuario::getAlertas();
                    }else{
                        Usuario::setAlerta('error', 'Ocurrio un error vuelve a intentarlo');
                        $alertas = Usuario::getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'La contraseña actual es incorrecta');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->render('dashboard/password', [
            'titulo' => 'Cambiar password',
            'alertas' => $alertas
        ]);
    }
}
