<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController
{
    public static function index()
    {
        session_start();
        isAuth();

        $proyectoid = $_GET['url'];
        if (!$proyectoid) header('location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoid);
        if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) header('location: /404');

        $tareas = Tarea::belongsto('proyectoid', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            $proyectoid = $_POST['proyectoid'];

            $proyecto = Proyecto::where('url', $proyectoid);

            if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada exitosamente',
                'proyectoid' => $proyecto->id
            ];
            echo json_encode($respuesta);
        }
    }

    public static function actualizar()
    {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            $proyecto = Proyecto::where('url', $_POST['proyectoid']);

            if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;

            $resultado = $tarea->guardar();

            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoid' => $proyecto->id,
                    'mensaje' => 'Tarea actualizada correctamente'
                ];
                echo json_encode(['respuesta' => $respuesta]);

                
            }
        }
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            isAuth();

            $proyecto = Proyecto::where('url', $_POST['proyectoid']);

            if (!$proyecto || $proyecto->propietarioid !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al eliminar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectoid = $proyecto->id;

            $resultado = $tarea->eliminar();

            if ($resultado) {
                $respuesta = [
                    'tipo' => 'error',
                    'id' => $tarea->id,
                    'proyectoid' => $proyecto->id,
                    'mensaje' => 'Tarea eliminada correctamente'
                ];
                echo json_encode(['respuesta' => $respuesta]);

                
            }
        }
    }
}
