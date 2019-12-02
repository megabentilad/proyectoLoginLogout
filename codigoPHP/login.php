<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Luis Mateo Rivera Uriarte</title>
        <meta charset="UTF-8">
        <meta name="author" content="Luis Mateo Rivera Uriarte">
        <link rel="stylesheet" type="text/css" href="../webroot/css/styles.css" media="screen">
        <link rel="icon" type="image/png" href="../webroot/images/mifavicon.png">
        <style>
        </style>
    </head>
    <body>
        <header>
            <div>
                <h1>
                    Inicio de sesión
                </h1>
            </div>
        </header>
        <?php
        /**
          @author Luis Mateo Rivera Uriate
          @since 30/11/2019
         */
        require '../core/validacionFormularios.php'; //Importamos la libreria de validacion
        include '../config/conexionBDClase.php'; //Importo los datos de conexión
        try{
            $miBD = new PDO(CONEXION, USUARIO, PASSWORD);
            $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $excepcion){
            die("<h1>Se ha producido un error, disculpe las molestias</h1>");
        }
        $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto    
        //Inicializamos un array que se encargara de recoger los errores(Campos vacios)
        $aErrores = [
            'name' => null,
            'pass' => null
        ];
        $aFormulario = [
            'name' => null,
            'pass' => null
        ];
        if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
            //La posición del array de errores recibe el mensaje de error si hubiera

            $aErrores['name'] = validacionFormularios::comprobarAlfaNumerico($_POST['name'], 25, 1, 1);  //maximo, mínimo y opcionalidad
            $aErrores['pass'] = validacionFormularios::validarPassword($_POST['campoAlfanumerico'], 4, 1); //maximo, mínimo y opcionalidad
            
            //Autenticación
            if(isset($_POST['CodUsuario']) && isset($_POST['pass'])){
                $codUsuario = $_POST['CodUsuario'];
                $password = $_POST['pass'];
            $sql = "SELECT * FROM Usuario WHERE CodUsuario LIKE '$codUsuario'";
                $resultado = $miBD->query($sql);
                if($resultado->rowCount() === 0){
                    $aErrores['name'] = "El usuario no existe.";
                }else{
                    if($resultado->Password !== hash('sha256', $password)){
                        $aErrores['pass'] = "La contraseña es incorrecta.";
                    }
                }
            }
            
            
            foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
                if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
                    $entradaOK = false; //Cambia la condiccion de la variable
                }
            }
        }else{
            $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón
        }
        if ($entradaOK) {
            
        }else{
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="obligatorio">
                        <label for="name">Nombre de usuario: </label>
                        <input type="text" id="name" name="name" placeholder="Nombre de usuario" value="<?php if($aErrores['name'] == NULL && isset($_POST['name'])){ echo $_POST['name'];} ?>"><br>
                        <?php if ($aErrores['name'] != NULL) { ?>
                        <div class="error">
                            <?php echo $aErrores['name']; //Mensaje de error que tiene el array aErrores   ?>
                        </div>   
                    <?php } ?>                
                    </div>
                    <div class="obligatorio">
                        <label for="pass">Contraseña: </label> 
                        <input type="text" id="pass" name="pass" placeholder="Contraseña" value="<?php if($aErrores['pass'] == NULL && isset($_POST['pass'])){ echo $_POST['pass'];} ?>"><br>
                        <?php if ($aErrores['pass'] != NULL) { ?>
                        <div class="error">
                            <?php echo $aErrores['pass']; //Mensaje de error que tiene el array aErrores   ?>
                        </div>   
                    <?php } ?>                
                    </div>
                    <div>
                        <input type="submit" name="enviar" value="Iniciar sesión">
                    </div>
                </fieldset>
            </form>
        <?php } ?>

        <footer>
            <p>
                <a href="../../..">
                    © Luis Mateo Rivera Uriarte 2019-2020
                </a>
            </p>
        </footer>
    </body>
</html>