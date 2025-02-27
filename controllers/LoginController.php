<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarlogin();

            if(empty($alertas)){
                $usuario = Usuario::where('email',$auth->email);
                if(!$usuario || $usuario->confirmado === "0"){
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                } else {
                    if(password_verify($_POST['password'], $usuario->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        header('location: /dashboard');
                    }else{
                        Usuario::setAlerta('error','La contraseña es incorrecta');
                    }
                }
            }            
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'titulo' => 'Iniciar sesion',
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('location:/');
    }

    public static function crear(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            $existeUsuario = Usuario::where('email', $usuario->email);

            if (empty($alertas)) {
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    //hashaer password
                    $usuario->hashpassword();

                    //eliminar password2
                    unset($usuario->password2);

                    //generar token
                    $usuario->crearToken();

                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);
                if ($usuario && $usuario->confirmado) {
                    $usuario->crearToken();
                    unset($usuario->password2);
                    $usuario->guardar();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->recuperarcontraseña();
                    Usuario::setAlerta('exito', 'Enviamos un mensaje a su correo para recuperar la contraseña');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
            $alertas = Usuario::getAlertas();
        }

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router)
    {
        $alertas = [];
        $mostar = true;
        $token = s($_GET['token']);
        if (!$token) header('location: /');
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $mostar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                $usuario->hashpassword();
                unset($usuario->password2);
                $usuario->token = '';
                $resultado = $usuario->guardar();
                if ($resultado) {
                    $alertas = Usuario::setAlerta('exito', 'Cambio su contraseña correctamente');
                    $mostar = false;
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostar
        ]);
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        if (!$token) header('location: /');
        $usuario = Usuario::where('token', $token);
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token invalido');
        } else {
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'confirmar',
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'mensaje'
        ]);
    }
}
