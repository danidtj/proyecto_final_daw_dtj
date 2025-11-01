<?php
namespace ControllerUtilidades;
class SessionStorageController{

    public function __construct()
    {
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        
    }

    public function setSessionStorage(string $key, array $value): void{
        $_SESSION[$key] = $value;
    }

    public function getSessionStorage(string $key){
        return $_SESSION[$key] ?? null;
    }

    //Elimina lo almacenado en la variable $_SESSION con el parámetro $key que se le pasa, pero no destruye la sesión.
    public function removeSessionStorage(string $key){
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

    //Elimina todas las variables de sesión y destruye la sesión.
    public function clearSession(){
        $_SESSION = array();
        session_destroy();
    }

}

