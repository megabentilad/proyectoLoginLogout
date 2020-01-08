# Puesta en marcha de un servidor de desarrollo
### Pasos iniciales
Partiendo de una máquina virtual limpia con Ubuntu server 18
instalado realizamos las siguientes comprobaciones.
```bash
ping 8.8.8.8
```
Para comprobar si la máquina dispone de conexxión a internet.

```bash
apt-get update
```
Para actualizar los enlaces de instalación.

### Apache2
#### Instalación
Para instalarlo utilizamos el siguiente comando:
```bash
sudo apt-get install apache2
```

Para comprobar que está instalado y operativo, escribimos:
```bash
sudo service apache2 status
```
#### Configuración
Vamos al archivo de configuración de apache:
```bash
sudo nano /etc/apache2/apache2.conf
```
Buscamos en el archivo la siguiente línea y donde pone "None" lo cambiamos por "All"
```html
<Directory /var/www>
  Options Indexes FollowSymLinks
  AllowOverride None  <!-- Este none lo cambiamos por "All" -->
  Require all granted
</Directory>
```
#### Crear un operador web
Para crear un operador web escribimos los siguientes comandos:
```bash
sudo adduser --home /var/www/html --no-create-home --ingroup www-data operadorweb
sudo chmod 775 -R /var/www/html
sudo chown operadorweb:www-data -R /var/www/html
```

### PHP
#### Instalación
Para instalar PHP utilizamos el siguiente comando:
```bash
sudo apt-get install php
```

#### Configuración
Vamos al archivo de configuración de PHP:
```bash
sudo nano /etc/php/7.2/apache2/php.ini
```
Buscamos la línea "display-errors = Off" y lo cambiamos a "On"

### Xdebug
#### Instalación
Para instalar este módulo de php que nos permite trabajar paso a paso con netbeans escribimos:
```bash
sudo apt install php-xdebug
```

Para comprobar que se instaló correctamente escribimos:
```bash
php -m | grep xdebug
```
Si aparece en rojo es que se instaló correctamente.

#### Configuración
Para que pueda funcionar el módulo hay que modificar dos archivos.
En el primero, al que se accede con este comando:
```bash
sudo nano /etc/php/7.2/mods-available/xdebug.ini
```
Debemos escribir las siguientes líneas.
```
zend_extension=xdebug.so
xdebug.remote_enable=on
xdebug.remote_handler=dbgp
xdebug.remote_host=localhost
xdebug.remote_port=9000
xdebug.idkey=netbeans-xdebug
xdebug.show_error_trace=1
xdebug.remote_connect_back=1
```

El segundo archivo es el de configuración de php al que accedimos previamente con este comando:
```bash
sudo nano /etc/php/7.2/apache2/php.ini
```
Buscamos la línea "output_buffering = On" y lo cambiamos a "Off"

### MySQL
#### Instalación
Para instalar la base de datos MySQL escribimos:
```bash
sudo apt install mysql-server
```

Para comprobar que se instaló correctamente escribimos:
```bash
sudo service mysql status
```
 #### Configuración
 Hay que crear un usuario super administrador y permitir las conexiones remotas.
Para lo primero nos metemos en MySQL escribiendo:
```bash
sudo mysql
```
Una vez dentro usaremos estos tres comandos para crear el usuario superadministrador:
```mysql
CREATE USER 'dbadmin'@'%' IDENTIFIED BY 'paso';
GRANT ALL PRIVILEGES ON *.* TO 'dbadmin'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```
Usamos el comando "exit" para salir de MySQL.
```mysql
exit;
```

Para permitir las conexiones remotas nos vamos al archivo de configuración de MySQL:
```bash
sudo nano /etc/mysql/mysql.config.d/mysqld.cnf
```
Buscamos la línea "bind-address" y la comentamos dejándola así: "# bind-address"

### PHPmyadmin
#### Instalación
Para instalar esta herramienta escribimos:
```bash
sudo apt install phpmyadmin
```
Nos saldran un par de ventanas de elección. Escogemos "apache2" y "<Yes>" respectivamente.
A continuación nos pedirá una contraseña para que solo usará PHPmyadmin de forma interna. No importa si se rellena o no.
  
#### Configuración
Para que se pueda acceder desde la página web a la herramienta hay que crear un acceso directo.
Para ello utilizamos el siguiente comando:
```bash
sudo ln -s /usr/share/phpmyadmin/ /var/www/html/
```

# Fin
