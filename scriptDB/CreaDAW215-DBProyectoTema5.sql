/**
 * Author:  David Garcia
 * Created: 26-nov-2019
 */

--Está copiado muy fuertemente

-- Cambiar nombre de la base de datos y el de usuario --
-- ProyectoTema5 || LoginLogoffTema5 || MtoDepartamentosPDOTema5 --
-- Crear base de datos --
    CREATE DATABASE if NOT EXISTS DAW215DBProyectoTema5;
-- Uso de la base de datos. --
    USE DAW215DBProyectoTema5;
-- Crear tablas. --
    CREATE TABLE IF NOT EXISTS Departamento(
        CodDepartamento char(3) PRIMARY KEY,
        DescDepartamento varchar(255) NOT null,
        FechaBajaDepartamento int DEFAULT null, -- Valor por defecto null, ya que cuando lo creas no puede estar de baja logica
        FechaCreacionDepartamento int NOT null,
        VolumenNegocio float NOT null
    );
    CREATE TABLE IF NOT EXISTS Usuario(
        CodUsuario varchar(15) PRIMARY KEY,
        DescUsuario varchar(250) NOT null,
        Password varchar(64) NOT null,
        Perfil enum('administrador', 'usuario') default 'usuario', -- Valor por defecto usuario
        FechaHoraUltimaConexion int,
        NumConexiones int default 0,
        ImagenUsuario mediumblob
    );
-- Crear del usuario --
    CREATE USER IF NOT EXISTS 'usuarioDAW215DBProyectoTema5'@'%' identified BY 'paso'; 
-- Dar permisos al usuario --
    GRANT ALL PRIVILEGES ON DAW215DBProyectoTema5.* TO 'usuarioDAW215DBProyectoTema5'@'%'; 
-- Hacer el flush privileges, por si acaso --
    FLUSH PRIVILEGES;
