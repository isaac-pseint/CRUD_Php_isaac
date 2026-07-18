<?php
/**
 * Conexión a la Base de Datos - ServiPlus S.A.
 * 
 * Este archivo centraliza la conexión a la base de datos MySQL utilizando PDO (PHP Data Objects).
 * Implementa el patrón Singleton para evitar múltiples conexiones por request.
 * Proporciona una conexión segura y configurada con el juego de caracteres utf8mb4.
 */

class Conexion {
    // Configuración de la base de datos
    private static $host = 'localhost';
    private static $user = 'root';
    private static $pass = '';
    private static $dbname = 'serviplus_db';
    private static $charset = 'utf8mb4';

    /** @var PDO|null Instancia única de la conexión */
    private static $instancia = null;

    /**
     * Constructor privado para evitar instanciación directa (Singleton).
     */
    private function __construct() {}

    /**
     * Evitar clonación del objeto (Singleton).
     */
    private function __clone() {}

    /**
     * Obtener la instancia única de la conexión PDO.
     * 
     * @return PDO
     * @throws PDOException Si la conexión falla
     */
    public static function conectar(): PDO {
        if (self::$instancia === null) {
            try {
                //Cadena de conexion :v
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;

                // Opciones recomendadas de PDO para seguridad y eficiencia
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de errores SQL
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los resultados como arrays asociativos
                    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación para usar consultas preparadas reales de MySQL
                ];

                // Creación de la instancia PDO
                self::$instancia = new PDO($dsn, self::$user, self::$pass, $options);

            } catch (PDOException $e) {
                //error de la BD por   PDO::ATTR_EMULATE_PREPARES (LN 47)
                error_log("Error de conexión a la BD: " . $e->getMessage());
                //die -> return 
                die("Lo sentimos, ha ocurrido un error al conectar con la base de datos. Por favor, intente más tarde.");
            }
        }

        return self::$instancia;
    }
}
