<?php
/**
 * Conexión a la Base de Datos - ServiPlus S.A.
 * 
 * Este archivo centraliza la conexión a la base de datos MySQL utilizando PDO (PHP Data Objects).
 * Proporciona una conexión segura y configurada con el juego de caracteres utf8mb4.
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'serviplus_db');
define('DB_CHARSET', 'utf8mb4');

try {
    // Configuración del DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    // Opciones recomendadas de PDO para seguridad y eficiencia
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de errores SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los resultados como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación para usar consultas preparadas reales de MySQL
    ];

    // Creación de la instancia PDO
    $conexion = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // Manejo de errores de conexión
    // Nota: En producción, es preferible registrar el error en un log y mostrar un mensaje genérico.
    error_log("Error de conexión a la BD: " . $e->getMessage());
    die("Lo sentimos, ha ocurrido un error al conectar con la base de datos. Por favor, intente más tarde.");
}
