<?php
    //TODO ESTO ESTÄ AQUÍ CÓMO SUGERENCIA DE HERACLIO
    session_start();
    if (!isset($_SESSION['usuarioDAW215AppLoginLogoff'])) { //Si no has pasado por el login, te redirige para allá
        header("Location: login.php");
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
            tr :first-child{
                background-color: #ccf; 
            }
            tr{
                background-color: #ddd; 
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
                    Detalles del programa
                </h2>
            </div>
        </header>
        <?php
            /**
             * @author Luis Mateo Rivera Uriarte
             * @since 29/11/2019
             */
            foreach ($GLOBALS as $todo => $variable) {
                if (is_array($variable)) {
                    if ($variable != $GLOBALS) {
                        echo"<h2>" . $todo . "</h2><table>";
                        foreach ($variable as $indice => $contenido) {
                            echo'<tr><td>' . $indice . '</td><td>[' . "'" . $contenido . "']</td></tr>";
                        }
                        echo"</table><br/>";
                    }
                } else {
                    echo $variable . "<br/>";
                }
            
            }
            phpinfo();
        ?>
        <div class="volver">
            <a href="programa.php"><img src="../webroot/images/inicio.png" alt="Enlace al índice" title="Volver" class="icono"></a>
        </div>
        <footer>
            <p>
                <a href="../../../..">
                    © Luis Mateo Rivera Uriarte 2019-2020
                </a>
                <a href="http://daw-usgit.sauces.local/luism/proyectoLoginlogoff/tree/master" target="_blank">
                    <img src="../webroot/images/gitLab.png" class="iconoFooter"  title="GitLab">
                </a>
            </p>
        </footer>
    </body>
</html>