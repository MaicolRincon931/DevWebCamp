<?php

namespace Controllers;

use Classes\Paginacion;
use Model\Ponente;
use MVC\Router;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController
{

    public static function index(Router $router)
    {
        $paginaActual = $_GET['page'];
        $paginaActual = filter_var($paginaActual, FILTER_VALIDATE_INT);

        if(!$paginaActual || $paginaActual < 1){
            header('Location: /admin/ponentes?page=1');
        }

        $registrosPorPagina=8;
        $total = Ponente::total();
      
        $paginacion = new Paginacion($paginaActual,$registrosPorPagina,$total);

        if($paginacion->totalPaginas()<$paginaActual){
            header('Location: /admin/ponentes?page=1');
        }

        $ponentes = Ponente::paginar($registrosPorPagina,$paginacion->offset());

        if(!isAuth()){
            header('Location: /login');
        }
        
        if(!isAdmin()){
            header('Location: /login');
        }

        $router->render('admin/ponentes/index', [
            'titulo' => 'Ponentes / Conferencistas',
            'ponentes' => $ponentes,
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router)
    {
        if(!isAuth()){
            header('Location: /login');
        }
        
        if(!isAdmin()){
            header('Location: /login');
        }

        $alertas = [];

        $ponente = new Ponente;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Leer iamgen
            if (!empty($_FILES['imagen']['tmp_name'])) {

                $carpetaImagenes = '../public/img/speakers';
                //crear la carpeta si no existe
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes, 0777, true);
                }

                $imagenPNG = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagenWEBP = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombreImagen = md5(uniqid(rand(), true));

                $_POST['imagen'] = $nombreImagen;
            }
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);
            $ponente->sincronizar(($_POST));
            $alertas = $ponente->validar();

            //Guardar el registro
            if (empty($alertas)) {
                //Guardar las imagenes
                $imagenPNG->save($carpetaImagenes . '/' . $nombreImagen . '.png');
                $imagenWEBP->save($carpetaImagenes . '/' . $nombreImagen . '.webp');
                //Guardar en la DB
                $resultado = $ponente->guardar();

                if ($resultado) {
                    header('Location: /admin/ponentes');
                }
            }
        }
        $redes = json_decode($ponente->redes);
        $router->render('admin/ponentes/crear', [
            'titulo' => 'Registrar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => $redes
        ]);
    }

    public static function editar(Router $router)
    {
        if(!isAuth()){
            header('Location: /login');
        }
        
        if(!isAdmin()){
            header('Location: /login');
        }

        $alertas = [];

        //Validar el id
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: /admin/ponentes');
        }
        
        //Obtener Ponente a editar
        $ponente = Ponente::find($id);

        if (!$ponente) {
            header('Location: /admin/ponentes');
        }

        $ponente->imagenActual = $ponente->imagen;

        $redes = json_decode($ponente->redes);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!empty($_FILES['imagen']['tmp_name'])) {

                $carpetaImagenes = '../public/img/speakers';
                //crear la carpeta si no existe
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes, 0777, true);
                }

                $imagenPNG = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('png', 80);
                $imagenWEBP = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 800)->encode('webp', 80);

                $nombreImagen = md5(uniqid(rand(), true));

                $_POST['imagen'] = $nombreImagen;
            } else {
                $_POST['imagen'] = $_nombre_imagen;
            }

            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);
            $ponente->sincronizar($_POST);

            $alertas = $ponente->validar();
            if (empty($alertas)) {
                if (isset($nombreImagen)) {

                    $imagenPNG->save($carpetaImagenes . '/' . $nombreImagen . '.png');
                    $imagenWEBP->save($carpetaImagenes . '/' . $nombreImagen . '.webp');
                    //Guardar en la DB

                }
                $resultado = $ponente->guardar();

                if ($resultado) {
                    header('Location: /admin/ponentes');
                }
            }

        }

        $router->render('admin/ponentes/editar', [
            'titulo' => 'Editar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => $redes
        ]);
    }

    public static function eliminar() {

        if(!isAuth()){
            header('Location: /login');
        }
        
        if(!isAdmin()){
            header('Location: /login');
        }

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $id = $_POST['id'];
            $ponente = Ponente::find($id);
            if(!isset($ponente)){
                header('Location: /admin/ponentes');
            }
            $resultado = $ponente->eliminar();

            if($resultado){
                header('Location: /admin/ponentes');
            }
        }
    }
}
