<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    protected $email;
    protected $nombre;
    protected $token;

    public function  __construct($email, $nombre, $token){

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;



    }

    public function enviarconfirmacion(){
         //creando el objeto de la libreria phpmiler 
        //que se instalo con (composer)
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        // $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        // $mail->Port = 587;
        $mail->Username = 'db967d856c3a71';
        // $mail->Username = 'pruebaskha@gmail.com';
         $mail->Password = '0b22321b04704b';
        // $mail->Password = 'vraqarmjrnmflwgj';
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress($this->email, 'uptask.com');
        $mail->Subject = 'confirma tu cuenta';

        //set html
        $mail->isHTML(true);
        $mail->Charset = 'UFT-8';

        $contenido = "<html>";
        $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has creado tu cuenta en UpTask
        , solo debes Confirmarla precionando el siguiente enlace </p>";
        $contenido .= "<p>Preciona aqui: <a href='http://localhost:3000/confirmar?token=". $this->token . "'> Confirmar Cuenta </a> </p>";
        $contenido .= "<p> si tu no solicitaste esta cuenta ignora el mensaje </p> ";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //enviar email
        $mail ->send();
    }


    public function restablecerPassword(){
        //creando el objeto de la libreria phpmiler 
       //que se instalo con (composer)
       $mail = new PHPMailer();
       $mail->isSMTP();
       $mail->Host = 'smtp.mailtrap.io';
       // $mail->Host = 'smtp.gmail.com';
       $mail->SMTPAuth = true;
       $mail->Port = 2525;
       // $mail->Port = 587;
       $mail->Username = 'db967d856c3a71';
       // $mail->Username = 'pruebaskha@gmail.com';
        $mail->Password = '0b22321b04704b';
       // $mail->Password = 'vraqarmjrnmflwgj';
       $mail->SMTPSecure = 'tls';

       $mail->setFrom('cuentas@uptask.com');
       $mail->addAddress($this->email, 'uptask.com');
       $mail->Subject = 'Restablecer tu Contraseña';

       //set html
       $mail->isHTML(true);
       $mail->Charset = 'UFT-8';

       $contenido = "<html>";
       $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has solicitado Restablecer tu Contraseña en UpTask
       , solo debes precionar el siguiente Enlace </p>";
       $contenido .= "<p>Preciona aqui: <a href='http://localhost:3000/restablecer?token=". $this->token . "'> Restablecer Contraseña </a> </p>";
       $contenido .= "<p> si tu no solicitaste esta cuenta ignora el mensaje </p> ";
       $contenido .= "</html>";

       $mail->Body = $contenido;

       //enviar email
       $mail ->send();
   }

}