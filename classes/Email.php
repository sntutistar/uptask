<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //crear el objeto email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->SMTPSecure = 'tls';
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];

        $mail->setFrom('santiagotutistar289@gmail.com', 'UpTask.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'confirma tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<hmtl>";
        $contenido .= "<p><strong>Hola ".$this->nombre."</strong> Has creado tu cuenta en uPTask</p>";
        $contenido .= "<p>Solo debes confirmarla</p>";
        $contenido .= "<p>preciona el siguiente enlace</p>";
        $contenido .= "<p>presiona aqui: <a href='".$_ENV['APP_URL'] ."/confirmar?token=".$this->token."'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste la cuenta, ignora y elimina el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar correo
        $mail->send();
    }

    public function recuperarcontraseña()
    {
        //crear el objeto email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['MAIL_PORT'];
        $mail->SMTPSecure = 'tls';
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASS'];

        $mail->setFrom('santiagotutistar289@gmail.com', 'UpTask.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Restablece tu contraseña';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<hmtl>";
        $contenido .= "<p><strong>Hola ".$this->nombre."</strong> Has olvidado tu contraseña</p>";
        $contenido .= "<p>preciona el siguiente enlace</p>";
        $contenido .= "<p>presiona aqui: <a href='".$_ENV['APP_URL'] ."/restablecer?token=".$this->token."'> Para cambiar tu contraseña</a></p>";
        $contenido .= "<p>Si tu no solicitaste, ignora y elimina el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar correo
        $mail->send();
    }
}
