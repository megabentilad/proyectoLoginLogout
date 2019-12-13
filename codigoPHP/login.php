<?php
    //TODO ESTO ESTÄ AQUÍ CÓMO SUGERENCIA DE HERACLIO
    session_start();
    if (isset($_SESSION['usuarioDAW215AppLoginLogoff'])) {  //Si el usuario está definido, entras al programa
        header("Location: programa.php");
        exit;
    }
    if(!isset($_COOKIE['idiomaDAW215'])){
        setcookie('idiomaDAW215', 'espanol', time()+604800);     //Coockie de idioma. Dura una semana
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
        'name' => null,
        'pass' => null
    ];
    if (isset($_POST['enviar'])) { //Si se ha pulsado enviar
        //La posición del array de errores recibe el mensaje de error si hubiera
        $aErrores['name'] = validacionFormularios::comprobarAlfaNumerico($_POST['name'], 25, 1, 1);  //maximo, mínimo y opcionalidad
        $aErrores['pass'] = validacionFormularios::comprobarAlfaNumerico($_POST['pass'], 25, 4, 1); //maximo, mínimo y opcionalidad

        //Autenticación con la base de datos
        if (isset($_POST['name']) && isset($_POST['pass'])) {
            if ($_POST['pass'] !== "") {
                $codUsuario = $_POST['name'];
                $password = $_POST['pass'];
                $sql = "SELECT * FROM Usuario WHERE CodUsuario LIKE '$codUsuario'";
                $resultado = $miBD->query($sql);
                if ($resultado->rowCount() === 0) {
                    $aErrores['name'] = "El usuario no existe.";
                } else {
                    $datos = $resultado->fetchObject();
                    if ($datos->Password !== hash('sha256', $codUsuario . $password)) {
                        $aErrores['pass'] = "La contraseña es incorrecta.";
                    }
                }
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
        //Guardamos los datos en $_SESSION
        $_SESSION['usuarioDAW215AppLoginLogoff'] = $datos->CodUsuario;
        $_SESSION['descripcionDAW215AppLoginLogoff'] = $datos->DescUsuario;
        $_SESSION['ultimaConexionDAW215AppLoginLogoff'] = $datos->FechaHoraUltimaConexion;
        $_SESSION['numConexionDAW215AppLoginLogoff'] = $datos->NumConexiones+1;
        //Actualizar fecha de la última conexion
        $sqlActualizarFecha = "UPDATE Usuario SET FechaHoraUltimaConexion = " . time() . " WHERE CodUsuario = :codigo;";
        $ActFecha = $miBD->prepare($sqlActualizarFecha);
        $ActFecha->execute(array(':codigo' => $datos->CodUsuario));
        //Actualizar Número de conexiones
        $sqlActualizarNumConexiones = "UPDATE Usuario SET NumConexiones = NumConexiones + 1 WHERE CodUsuario = :codigo;";
        $ActConexiones = $miBD->prepare($sqlActualizarNumConexiones);
        $ActConexiones->execute(array(':codigo' => $datos->CodUsuario));
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
            #name, #pass{
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
                top: 110px;
            }
            #epass{
                top: 180px;
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
                    Inicio de sesión
                </h2>
            </div>
        </header>
        <?php
        /**
          @author Luis Mateo Rivera Uriate
          @since 30/11/2019
         */
        
            ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="obligatorio">
                        <label for="name">Nombre de usuario: </label>
                        <input type="text" id="name" name="name" placeholder="Usuario" width="10" height="20" value="<?php if($aErrores['name'] == NULL && isset($_POST['name'])){ echo $_POST['name'];} ?>"><br>
                              
                    </div>
                    <br/>
                    <div class="obligatorio">
                        <label for="pass">Contraseña: </label> 
                        <input type="password" id="pass" name="pass" placeholder="Contraseña" value="<?php if($aErrores['pass'] == NULL && isset($_POST['pass'])){ echo $_POST['pass'];} ?>"><br>
                               
                    </div>
                    <br/>
                    <div>
                        <input type="submit" name="enviar" value="Iniciar sesión">
                        <a href="registro.php"><input type="button" name="registro" value="Registrarse"></a>
                    </div>
                </fieldset>
            </form>
            <?php } ?>
            <?php if ($aErrores['name'] != NULL) { ?>
                <div class="error" id="ename">
                    <?php echo $aErrores['name']; //Mensaje de error que tiene el array aErrores   ?>
                </div>   
            <?php }   
            if ($aErrores['pass'] != NULL) { ?>
                <div class="error" id="epass">
                    <?php echo $aErrores['pass']; //Mensaje de error que tiene el array aErrores   ?>
                </div>   
            <?php } ?>    
        <div class="volver">
            <a href="../../proyectoTema5/indexProyectoTema5.html"><img src="../webroot/images/inicio.png" alt="Enlace al índice" title="Volver" class="icono"></a>
        </div>

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