<?php
    //TODO ESTO ESTÁ AQUÍ CÓMO SUGERENCIA DE HERACLIO
    session_start();
    if (!isset($_SESSION['usuarioDAW215AppLoginLogoff'])) { //Si no has pasado por el login, te redirige para allá
        header("Location: login.php");
    }
    if(isset($_GET['cerrar'])){  //Observa si se ha pusado el botón de cerrar sesión y hace sus cosas
        session_destroy();
        header("Location: login.php");
    }
    //PARA EL IDIOMA
    if(isset($_GET['idioma'])){   //Si se ha pulsado una bandera, actualiza la cookie
        setcookie('idiomaDAW215', $_GET['idioma'], time()+604800); //Dura una semana 
        
    }
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
        </style>
    </head>
    <body>
        <header>
            <div>
                <h1>
                    Cuerpo del programa
                </h1>
            </div>
        </header>
        <?php
        /**
          @author Luis Mateo Rivera Uriate
          @since 03/12/2019
         */
        ?>

        <h3>Bienvenid@ <?php echo ucfirst($_SESSION['usuarioDAW215AppLoginLogoff']); ?>.</h3>
        <br/>
        <?php
        //Cambia el texto según la cookie del idioma
        switch ($_COOKIE['idiomaDAW215']){
            case "espanol":
                echo "<h3>Este texto está en español.</h3>";
                break;
            case "ingles":
                echo "<h3>This text is in English.</h3>";
                break;
            case "aleman":
                echo "<h3>Dieser Text ist in deutscher Sprache.</h3>";
                break;
            default:
                echo "<h3>Este texto está en español.</h3>";
        }
        ?>
        <br/>
        <p>Descripción del usuario: <?php echo $_SESSION['descripcionDAW215AppLoginLogoff']; ?></p>
        <?php
            if($_SESSION['ultimaConexionDAW215AppLoginLogoff'] === null){
                echo "<p>Es la primera vez que te conectas UuU</p>";
            }else{
        ?>
        <p>Fecha de la última conexión: <?php echo date('d/m/Y',$_SESSION['ultimaConexionDAW215AppLoginLogoff']); ?></p>
        <p>Hora de la última conexión: <?php echo date('H:i:s',$_SESSION['ultimaConexionDAW215AppLoginLogoff']); ?></p>
            <?php } ?>
        <p>Número de conexiones: <?php echo $_SESSION['numConexionDAW215AppLoginLogoff']; ?></p>
        <br/>
        <a href="detalle.php"><button>Ir al detalle</button></a>
        <a href="programa.php?cerrar=true"><button>Cerrar sesión</button></a>
        <div class="contenedorBanderas">
            <a href="programa.php?idioma=espanol"><img src="../webroot/images/espana.png" class="bandera" title="Español" alt="Idioma español"></a>
            <a href="programa.php?idioma=ingles"><img src="../webroot/images/inglaterra.png" class="bandera" title="English" alt="English language"></a>
            <a href="programa.php?idioma=aleman"><img src="../webroot/images/alemania.png" class="bandera" title="Deutsch" alt="Deutsche sprache"></a>
        </div>
        <footer>
            <p>
                <a href="../../..">
                    © Luis Mateo Rivera Uriarte 2019-2020
                </a>
                <a href="http://daw-usgit.sauces.local/luism/proyectoLoginlogoff/tree/master" target="_blank">
                    <img src="../webroot/images/gitLab.png" class="iconoFooter"  title="GitLab">
                </a>
            </p>
        </footer>
    </body>
</html>