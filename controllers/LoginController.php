<?php



namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuarios;


class LoginController{

    public static function login(Router $router){

        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarios = new Usuarios($_POST);

            $alertas = $usuarios->validarFormularioLogin();

            if (empty($alertas)) {
                //verificar que el usuario exista o no
                $usuarios = Usuarios::where('email', $usuarios->email);
                if (!$usuarios || !$usuarios->confirmar) {
                    //Si el usuario no existe o no esta confirmado mandamos este mensaje
                    Usuarios::setErrores('error', 'El usuario no Existe o No esta confirmado');
                }else{
                    //El usuario si se encuentra en la base de datos
                    //Debemos confirmar su contraseña tambien
                    if (password_verify($_POST['password'], $usuarios->password )) {
                        //La contraseña es Correcta y existe el usuario
                        //y debemos iniciar la Sesion del Usuario
                        session_start();
                        //llenamos el arreglo de sesion con los datos del usuario
                        //como su nombre, id, email, etc.
                        $_SESSION['id'] = $usuarios->id;
                        $_SESSION['nombre'] = $usuarios->nombre;
                        $_SESSION['email'] = $usuarios->email;
                        //esta variable es para darle permisos y cerrar sesion
                        $_SESSION['login'] = true;

                        //redireccionamos a su home del usuario
                        header('Location: /dashboard');

                    }else{
                        //El correo es correcto pero la contraseña es incorrecta
                        Usuarios::setErrores('error', 'La contraseña en incorrecta');

                    }
                }
            }
        }
        $alertas = Usuarios::getErrores();
        $router->render('auth/login', [
            'titulo' => 'iniciar sesion',
            'alertas' => $alertas
        ]);
    }


    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function crear(Router $router){
        $usuarios = new Usuarios;
        $alertas =[];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Creamos un objeto en memoria en reflejo con la base de datos
            $usuarios->sincronizar($_POST);
            //validamos los inputs del formulario
            $alertas = $usuarios->validarFormulario();
            //aqui validamos el arreglo de errores(alertas) este vacio
            if (empty($alertas)) {
                //si esta vacio debemos verificar que ese suario no se encuentre en la base de datos
                $usuaioExiste = Usuarios::where('email', $usuarios->email);
                if ($usuaioExiste) {
                // si ya existe mandamos una alerta al usuario
                   Usuarios::seterrores('error', 'El Usuario ya esta Registrado');
                   $alertas = Usuarios::getErrores();
                }else{
                    //Hashear el password
                    $usuarios->hashearPassword();
                    //Elimiar password2
                    unset($usuarios->password2);
                    //generar el Token
                    $usuarios->crearToken();
                    // debuguear($usuarios);
                    //crear nuevo usuario
                    $resultado = $usuarios->guardar();
                    //enviar Email para que confirme su cuenta
                    $email = new Email($usuarios->email, $usuarios->nombre, $usuarios->token);
                    $email->enviarconfirmacion();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        
        }

        $router->render('auth/crear',[
            'tiutlo' => 'crear cuenta',
            'usuarios' => $usuarios,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router){
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuarios = new Usuarios($_POST);
            $alertas = $usuarios->validarEmail();
           
            if (empty($alertas)) {
                //buscamos en la base de datos el usuario
                $usuarios = Usuarios::where('email', $usuarios->email);

                if ($usuarios && $usuarios->confirmar === "1") {
                    //Aqui buscamos en la base de datos y lo estamos actualizando varios tados
                   //generar un nuevo Token
                    $usuarios->crearToken();
                    unset($usuarios->password2);

                    //Actualizamos el usuario en la base de datos
                    $usuarios->guardar();

                    //Enviar el correo
                    $email = new Email($usuarios->email, $usuarios->nombre, $usuarios->token);
                    $email->restablecerPassword();

                    Usuarios::setErrores('exito', 'Enviamos las instrucciones a tu correo Electronico');
                }else{
                    Usuarios::setErrores('error', 'El usuario no Existe o No esta Confirmado');
                   
                }
            }
        }
    $alertas = Usuarios::getErrores();
    $router->render('auth/olvide',[
        'titulo' => 'Restablecer Password',
        'alertas' => $alertas
    ]);

    }

    public static function restablecer(Router $router){
        //primero debemos obtener el token de la url
        $token = s($_GET['token']);
        $mostrar = true;
         if (!$token) {
            header('Location: /');
         }

         $usuarios = Usuarios::where('token', $token);

         if (empty($usuarios)) {
            Usuarios::setErrores('error', 'Token No valido');
            $mostrar= false;
         }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //añadir el nuevo password
            $usuarios->sincronizar($_POST);

            //validar password
            $alertas = $usuarios->validarPassword();

            if(empty($alertas)){
                //hasheamos el nuevo password
                $usuarios->hashearPassword();
                //Eliminar el Token 
                $usuarios->token = null;
                //Aqui actualizamos el usuario en la base de datos
                $resultado = $usuarios->guardar();

                if ($resultado) {
                    header('Location: /');
                }

            }
        }
        $alertas = Usuarios::getErrores();

        $router->render('auth/restablecer', [
            'titulo' => 'Nueva Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function confirmar(Router $router){
        //Debemos obtener el token que estamos mandando por la url
        $token = s($_GET['token']);

        if(!$token){
            header('Location: /');
        }
        //Debemos encontrar el usuario que tiene ese token
        $usuarios = Usuarios::where('token', $token);
        if (empty($usuarios)) {
            Usuarios::setErrores('error', 'Token NO valido');
        }else{
            //lo que hacemos aqui es modificar el usuarion que ya tenemos en la base de datos
            //Re escribimos al usuario y lo buscamos mediante el token que se manda por la url
            $usuarios->confirmar= 1;
            $usuarios->token = null;
            unset($usuarios->password2);

            $usuarios->guardar();
            Usuarios::setErrores('exito', 'Cuenta Comprobada Correctamente');

        }
        $alertas = Usuarios::getErrores();


       $router->render('auth/confirmar', [
        'titulo'=> 'Confirma Cuenta',
        'alertas' => $alertas
       ]);
    }

    public static function mensaje(Router $router){
        
        $router->render('auth/mensaje', [
            'titulo'=> 'Cuenta Creada'
        ]);

       
    }



}