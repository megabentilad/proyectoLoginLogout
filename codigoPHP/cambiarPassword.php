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
    } catch (PDOException $excepcion) {
        die("<h1>Se ha producido un error, disculpe las molestias</h1>");
    }
    $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto    
    //Inicializamos un array que se encargara de recoger los errores(Campos vacios)
    $aErrores = [
        'desc' => null,
        'pass' => null,
        'pass2' => null
    ];
    if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
        //La posición del array de errores recibe el mensaje de error si hubiera
        $aErrores['desc'] = validacionFormularios::comprobarAlfaNumerico($_POST['desc'], 25, 1, 1); //maximo, mínimo y opcionalidad
        $aErrores['pass'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass'], 25, 4, 1); //maximo, mínimo y opcionalidad
        $aErrores['pass2'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass2'], 25, 4, 1); //maximo, mínimo y opcionalidad

        //Autenticación con la base de datos
        if (isset($_POST['desc']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
            if ($_POST['pass'] === $_POST['pass2']) {
                $password = $_POST['pass'];
                $sql = "SELECT * FROM Usuario WHERE CodUsuario LIKE '" . $_SESSION['usuarioDAW215AppLoginLogoff'] . "';";
                $resultado = $miBD->query($sql);
                if(hash('sha256', $_SESSION['usuarioDAW215AppLoginLogoff'] . $password) !== $resultado->Password){
                    $aErrores['pass'] = "La nueva contraseña es igual a la antigua.";
                }
            }else{
                $aErrores['pass2'] = "Las contraseñas no son iguales.";
            }
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
        $sqlRegistrar = "UPDATE Usuario SET DescDepartamento = :descripcion, VolumenNegocio = :volumen WHERE CodDepartamento = :codig;";             //SEGUIR POR AQUÍ
        $registro = $miBD->prepare($sqlRegistrar);
        $registro->execute(array(':codigo' => $_POST['name'], ':desc' => $_POST['desc'], ':pass' => $_POST['name'] . $_POST['pass']));
        //Guardamos los datos en $_SESSION
        $_SESSION['descripcionDAW215AppLoginLogoff'] = $_POST['desc'];
        //Actualizar fecha de la última conexion
        $sqlActualizarFecha = "UPDATE Usuario SET FechaHoraUltimaConexion = " . time() . " WHERE CodUsuario = :codigo;";
        $ActFecha = $miBD->prepare($sqlActualizarFecha);
        $ActFecha->execute(array(':codigo' => $_POST['name']));
        //Actualizar Número de conexiones
        $sqlActualizarNumConexiones = "UPDATE Usuario SET NumConexiones = NumConexiones + 1 WHERE CodUsuario = :codigo;";
        $ActConexiones = $miBD->prepare($sqlActualizarNumConexiones);
        $ActConexiones->execute(array(':codigo' => $_POST['name']));
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
            #name, #pass, #pass2, #desc{
                width: 130px;
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
            #ename{
                top: 160px;
            }
            #edesc{
                top: 230px;
            }
            #epass{
                top: 295px;
            }
            #epass2{
                top: 360px;
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
                    Registro de usuario
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
                        <input type="text" id="name" name="name" placeholder="Usuario" width="10" height="20" value="<?php if($aErrores['name'] == NULL && isset($_POST['name'])){ echo $_POST['name'];} ?>"><br>   
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="desc">Descripción del usuario: </label>
                        <input type="text" id="desc" name="desc" placeholder="Descripción" width="10" height="20" value="<?php if($aErrores['desc'] == NULL && isset($_POST['desc'])){ echo $_POST['desc'];} ?>"><br>   
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="pass">Contraseña: </label> 
                        <input type="password" id="pass" name="pass" placeholder="Contraseña" value="<?php if($aErrores['pass'] == NULL && isset($_POST['pass'])){ echo $_POST['pass'];} ?>"><br>      
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="pass2">Repita la contraseña: </label> 
                        <input type="password" id="pass2" name="pass2" placeholder="Contraseña"><br>        
                    </div>
                    <br/>
                    <div>
                        <input type="submit" name="enviar" value="Iniciar sesión">
                        <a href="login.php"><input type="button" name="volver" value="Cancelar"></a>
                    </div>
                </fieldset>
            </form>
            <?php } ?>
            <?php if ($aErrores['name'] != NULL) { ?>
                <div class="error" id="ename">
                    <?php echo $aErrores['name']; //Mensaje de error que tiene el array aErrores   ?>
                </div>   
            <?php }   
            if ($aErrores['desc'] != NULL) { ?>
                <div class="error" id="edesc">
                    <?php echo $aErrores['desc']; //Mensaje de error que tiene el array aErrores   ?>
                </div>   
            <?php }   
            if ($aErrores['pass'] != NULL) { ?>
                <div class="error" id="epass">
                    <?php echo $aErrores['pass']; //Mensaje de error que tiene el array aErrores   ?>
                </div> 
            <?php }
            if ($aErrores['pass2'] != NULL) { ?>
                <div class="error" id="epass2">
                    <?php echo $aErrores['pass2']; //Mensaje de error que tiene el array aErrores   ?>
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