<?php

// ---------------
// Clase para manejar una conexión a la base de datos
// ---------------
class Database {
    
    public $error;
    
    private $stmt;
    private $host;
    private $user;
    private $pass;
    private $dbnombre;
    private $dbh;
    private $puerto;
    
    private $sql = "";
    private $tabla;

    private $seleccionados = [];
    private $seteados = [];
    private $insertar = [];
    
    private $joins = [];
    private $donde = [];
    
    // ---------------
    // Construimos el objeto de base de datos
    /**
    * ***************
    * @param $config type Array
    * ***************
    */
    public function __construct(array $config) {
        
        $this->user     = $config["user"];
        $this->pass     = $config["pass"];
        $this->host     = $config["host"];
        $this->dbnombre = $config["dbName"];
        $this->port     = $config["port"];
        
        $con = $config["type"] . ":host=" . $this->host;
        $con .= ";port=" . $this->port;
        $con .= ";dbname=" . $this->dbnombre;
        
        // ---------------
        // Mandamos que la conexión no sea persistente y manejador de errores
        // ---------------
        $options = array(
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        // ---------------
        // Se intenta la conexión
        // ---------------
        try {
            $this->dbh = new PDO($con, $this->user, $this->pass, $options);
        }
        catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    
    // ---------------
    // Prepara una query para ejectuar
    /**
     * ***************
     * @param $query string
     * ***************
     */
    public function query(string $query) : Database {
        $this->stmt = $this->dbh->prepare($query);
        return $this;
    }

    // ---------------
    // Devuelve una query
    // preparada en string
    // ---------------
    public function dump_query() : string {
        return $this->stmt->queryString;
    }

    // ---------------
    // Ajusta los parámetros
    /**
     * ***************
     * @param $param string
     * @param $val string || int || bool
     * ***************
     */
    public function bind($param, $val, $tipo = null) : Database {
        
        if (is_null($tipo)) {
            switch (true) {
                case is_int($val):
                    $tipo = PDO::PARAM_INT;
                    break;
                case is_bool($val):
                    $tipo = PDO::PARAM_BOOL;
                    break;
                case is_null($val):
                    $tipo = PDO::PARAM_NULL;
                    break;
                default:
                    $tipo = PDO::PARAM_STR;
                    if (is_array($val))
                        $val = json_encode($val);
            }
        }
        $this->stmt->bindValue($param, $val, $tipo);
        
        return $this;
    }
    
    // ---------------
    // Ejectuamos la query
    // ---------------
    public function ejecutar() : bool {
        $var = false;
        
        try {
            if ($this->stmt->execute())
                $var = true;
        }
        catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
        
        return $var;
    }
    
    // ---------------
    // Me devuelve un array de todos los resultados del query
    // ---------------
    public function todos() : array {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    // ---------------
    // Me devuelve un solo registro
    // ---------------
    public function simple() : array {
        $field = $this->stmt->fetch(PDO::FETCH_ASSOC);

        if (!$field) {
            $field = [];
        }

        return $field;
    }
    
    // ---------------
    // Cuenta la cantidad de campos encontrados
    // ---------------
    public function contar() : int {
        return $this->stmt->rowCount();
    }
    
    // ---------------
    // Devuelve el id del último insert
    // ---------------
    public function id() : int {
        return $this->dbh->lastInsertId();
    }
        
    // ---------------
    // Terminar la conexión
    // ---------------
    public function fin() : bool {
        $this->dbh  = NULL;
        $this->stmt = NULL;
        return true;
    }
    
    // ---------------
    // Método para manejar las transacciones
    // ---------------
    /**
     * ***************
     * @param $tipo string
     * ***************
     */
    public function transaccion(string $tipo = "") : Database {
        
        // ---------------
        // Handler de transacciones
        // ---------------
        switch (mb_strtolower($tipo)) {
            case "init":
                // ---------------
                // Iniciar una transacción
                // ---------------
                $this->dbh->beginTransaction();
            break;
            
            case "fin":
                // ---------------
                // Terminar una transacción
                // ---------------
                $this->dbh->commit();
            break;
            
            case "cancel":
                // ---------------
                // Cancelar una transacción
                // ---------------
                $this->dbh->rollBack();
            break;
            
            default:
                // ---------------
                // Si no hay nada
                // ---------------
                return trigger_error("Transacción [".$tipo."] incorrecta", E_USER_ERROR);
                
        }
        
        // ---------------
        // Devolvemos el objeto
        // ---------------
        return $this;        
    }
    
    // ---------------
    // Agregar multiples valores a una query
    // ---------------
    /**
     * ***************
     * @param $params Array asociativo
     * ***************
     */
    public function multi_bind(array $params) {
        
        // ---------------
        // Iteramos para colocar los valores
        // ---------------
        foreach ($params as $key => $val) {
            $this->bind($key, $val);
        }
        
        return $this;
    }

    // ---------------
    // Método para optimizar
    // tablas mysql
    // ---------------
    /**
     * ***************
     * @param $tablas Array no asociativo
     * ***************
     */
    public function optimizar($tablas = "") {

        // ---------------
        // Si no envió tablas, buscar las tablas en el objeto y
        // optimizarlas
        // ---------------
        if(empty($tablas)){

            $this->query("SHOW TABLES");

            if(!$this->ejecutar() || $this->contar() < 1)
                return;

            $tablas = [];

            foreach($this->todos() as $tabla){
                foreach($tabla as $nombre){
                    $tablas[] = $nombre;
                }
            }

        }

        $tablas = join(", ", $tablas);

        $this->query("ANALYZE TABLE ".$tablas)
           ->ejecutar();
        $this->query("OPTIMIZE TABLE ".$tablas)
           ->ejecutar();
    }

    // ---------------
    // Método para obtener si hay un error o no
    // ---------------
    public function hasError() : bool {
        return !empty($this->error);
    }


    // ---------------
    // Método para limpiar un error
    // ---------------
    public function cleanError() : Database {

        $this->error = "";
        return $this;
    }
}

?>