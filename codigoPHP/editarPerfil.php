<?php
    session_start();
    if (!isset($_SESSION['usuarioDAW215AppLoginLogoff'])) { //Si no has pasado por el login, te redirige para allá
        header("Location: login.php");
    }
    require '../core/validacionFormularios.php'; //Importamos la libreria de validacion
    include '../config/conexionBDClase.php'; //Importo los datos de conexión
    try {
        $miBD = new PDO(CONEXION, USUARIO, PASSWORD);
        $miBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Conexion con la base de datos
        //Sacar todos los datos del usuario
        $resultado = $miBD->query("SELECT * FROM Usuario WHERE CodUsuario = '" . $_SESSION['usuarioDAW215AppLoginLogoff'] . "';");
        $resultadoEnObjeto = $resultado->fetchObject();
        $datos = [
            'codigo' => $resultadoEnObjeto->CodUsuario,
            'descripcion' => $resultadoEnObjeto->DescUsuario,
            'tipo' => $resultadoEnObjeto->Perfil,
            'fecha' => $resultadoEnObjeto->FechaHoraUltimaConexion,
            'conexiones' => $resultadoEnObjeto->NumConexiones
        ];
    } catch (PDOException $excepcion) {
        echo $excepcion->getMessage();
        die("<h1>Se ha producido un error, disculpe las molestias</h1>");
    }
    $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto    
    //Inicializamos un array que se encargara de recoger los errores(Campos vacios)
    $aErrores = [
        'desc' => null
    ];
    if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
        //La posición del array de errores recibe el mensaje de error si hubiera
        $aErrores['desc'] = validacionFormularios::comprobarAlfaNumerico($_POST['desc'], 255, 1, 1); //maximo, mínimo y opcionalidad

        //Permitir entrada
        if($_POST['desc'] === $datos['descripcion']){
            $aErrores['desc'] = "La descripción es la misma que la anterior.";
        }
        foreach ($aErrores as $campo => $error) { //Recorre el array en busca de mensajes de error
            if ($error != null) { //Si lo encuentra vacia el campo y cambia la condiccion
                $entradaOK = false; //Cambia la condiccion de la variable
            }
        }
    } else {
        $entradaOK = false; //Cambiamos el valor de la variable porque no se ha pulsado el botón
    }
    if ($entradaOK) {
        //Editamos el usuario
        $sqlActualizar = "UPDATE Usuario SET DescUsuario = :descripcion WHERE CodUsuario = :codigo;";             //SEGUIR POR AQUÍ
        $registro = $miBD->prepare($sqlActualizar);
        $registro->execute(array(':codigo' => $_SESSION['usuarioDAW215AppLoginLogoff'], ':descripcion' => $_POST['desc']));
        //Guardamos los datos en $_SESSION
        $_SESSION['descripcionDAW215AppLoginLogoff'] = $_POST['desc'];
        //Volvemos al programa
        header("Location: programa.php");
    } else {
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Luis Mateo Rivera Uriarte</title>
        <meta charset="UTF-8">
        <meta name="author" content="Luis Mateo Rivera Uriarte">
        <link rel="stylesheet" type="text/css" href="../webroot/css/styles.css" media="screen">
        <link rel="icon" type="image/png" href="../webroot/images/mifavicon.png">
        <style>
            label{
                float: left;
                height: 40px;
                margin-top: 10px;
                font-size: 20px;
            }
            input{
                font-size: 20px;
            }
            input[type="text"]{
                width: 250px;
                height: 40px;
                border-radius: 10px;
                font-size: 20px;
                float: right;
            }
            .obligatorio{
                height: 50px;
            }
            .error{
                background-color: #ff708c;
                width: 15%;
                padding: 0.5%;
                border-radius: 10px;
                display: inline-block;
                position: absolute;
            }
            #edesc{
                top: 230px;
            }
            .volver{
                position: absolute;
                top: 20px;
            }
            .icono{
                width: 80px;
            }
        </style>
    </head>
    <body>
        <header>
            <div>
                <h1>
                    Proyecto LogIn LogOff
                </h1>     
                <h2>
                    Editar perfil
                </h2>
            </div>
        </header>
        <?php
        /**
          @author Luis Mateo Rivera Uriate
          @since 10/12/2019
         */
        
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="obligatorio">
                        <label for="name">Nombre del usuario: </label>
                        <input type="text" id="name" name="name" width="10" height="20" value="<?php echo $datos['codigo'] ?>" disabled><br>   
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="desc">Descripción del usuario: </label>
                        <input type="text" id="desc" name="desc" placeholder="Descripción" value="<?php echo $datos['descripcion']; ?>" width="10" height="20"><br>   
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="tipo">Tipo de usuario: </label> 
                        <input type="text" id="tipo" name="tipo" value="<?php echo $datos['tipo'] ?>" disabled><br>      
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="conexiones">Número de conexiones: </label> 
                        <input type="text" id="conexiones" name="conexiones" value="<?php echo $datos['conexiones'] ?>" disabled><br>        
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="fecha">Fecha de la última conexión: </label> 
                        <input type="text" id="fecha" name="fecha" value="<?php echo date('d/m/Y - H:i:s',$datos['fecha']) ?>" disabled><br>        
                    </div>
                    <br/>
                    <div>
                        <input type="submit" name="enviar" value="Aceptar">
                        <a href="login.php"><input type="button" name="volver" value="Cancelar"></a>
                    </div>
                </fieldset>
                <br/>
                <a href="cambiarPassword.php"><input type="button" name="volver" value="Cambiar contraseña"></a>
            </form>
            <?php }
            if ($aErrores['desc'] != NULL) { ?>
                <div class="error" id="edesc">
                    <?php echo $aErrores['desc']; //Mensaje de error que tiene el array aErrores   ?>
                </div>   
            <?php } ?>    
        <footer>
            <p>
                <a href="../../..">
                    © Luis Mateo Rivera Uriarte 2019-2020
                </a>
                <a href="http://daw-usgit.sauces.local/luism/proyectoLoginlogoff/tree/developer" target="_blank">
                    <img src="../webroot/images/gitLab.png" class="iconoFooter"  title="GitLab">
                </a>
            </p>
        </footer>
    </body>
</html>