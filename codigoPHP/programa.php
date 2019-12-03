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
          @since 30/11/2019
         */
        
            session_start();
            if(isset($_GET['romper'])){
                session_destroy();
                header("Refresh:0");
            }
            if(!isset($_SESSION['usuarioDAW215AppLoginLogoff'])){
                header("Location: login.php");
            }
        ?>
        <h3>Bienvenid@ <?php echo ucfirst($_SESSION['usuarioDAW215AppLoginLogoff']); ?>.</h3>
        <br/>
        <a href="detalle.php"><button>Ir al detalle</button></a>
        <a href="programa.php?romper=true"><button>Cerrar sesión</button></a>
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