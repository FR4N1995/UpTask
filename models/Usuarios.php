<?php

namespace Model;

class Usuarios extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB= ['id', 'nombre', 'email', 'password', 'token', 'confirmar'];


    public function __construct($args=[])  {

        $this->id= $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmar = $args['confirmar'] ?? 0;



    }

    public function validarFormularioLogin(){
        if(!$this->email){
            self::$errores['error'][]= 'El email es obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['error'][]= 'Email No Valido';
        }
        if(!$this->password){
            self::$errores['error'][]= 'El password no debe ir vacio';
        }
        return self::$errores;
    }

    //validamos que en los inputs tenga un nombre y email 
    public function validarFormulario(){
        if(!$this->nombre){
            self::$errores['error'][]= 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$errores['error'][]= 'El email es obligatorio';
        }
        if(!$this->password){
            self::$errores['error'][]= 'El password no debe ir vacio';
        }
        if(strlen($this->password) < 6){
            self::$errores['error'][]= 'El password debe tener al menos 6 caracteres';
        }
        if($this->password !== $this->password2){
            self::$errores['error'][]= 'Las contraseñas deben ser iguales';
        }
        return self::$errores;

    }

    //validar Email
    public function validarEmail(){
        if(!$this->email){
            self::$errores['error'][]= 'El email es obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['error'][]= 'Email No Valido';
        }
        return self::$errores;
    }

    //Validar password
    public function validarPassword(){
        if(!$this->password){
            self::$errores['error'][]= 'El password no debe ir vacio';
        }
        if(strlen($this->password) < 6){
            self::$errores['error'][]= 'El password debe tener al menos 6 caracteres';
        }
        return self::$errores;
    }

    public function validarPerfil(){
        if(!$this->nombre){
            self::$errores['error'][]= 'El nombre es obligatorio';
        }
        if(!$this->email){
            self::$errores['error'][]= 'El email es obligatorio';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores['error'][]= 'Email No Valido';
        }
        return self::$errores;
    }

    public function nuevoPassword(){
        if(!$this->password_actual){
            self::$errores['error'][] = 'El  password actual no puede ir vacio';
        }
        if(!$this->password_nuevo){
            self::$errores['error'][] = 'El  password nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 6){
            self::$errores['error'][] = 'El  password debe tener al menos 6 caracteres';
        }
            return self::$errores;
    }

    //comprobar el password que ya se guardo al crear la cuenta

    public function comprobarPassword(){
        //esto retornara un true o false
        return password_verify($this->password_actual, $this->password);
    }

    //hashea la contraseña
    public function hashearPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //generar un Token
    public function crearToken(){
        $this->token = uniqid();
    }


}