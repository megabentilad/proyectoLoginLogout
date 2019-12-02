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
        </style>
    </head>
    <body>
        <h1>
            Variables globales
        </h1>
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
        <footer>
            <p>
                <a href="../../../..">
                    © Luis Mateo Rivera Uriarte 2019-2020
                </a>
                <a href="http://daw-usgit.sauces.local/luism/proyectoTema5/tree/master" target="_blank">
                    <img src="webroot/images/gitLab.png" class="iconoFooter">
                </a>
            </p>
        </footer>
    </body>
</html>