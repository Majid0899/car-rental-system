<?php
/**
 * Database Class
 * Handles database connection and common database operations
 */

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    protected $conn;
    protected $stmt;
    
    /**
     * Constructor - Establishes database connection
     */
    public function __construct() {
        $this->connect();
    }
    
    /**
     * Create database connection
     */
    private function connect() {
    $this->conn = null;

    try {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname,
            DB_PORT
        );

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");

    } catch (Exception $e) {
        die("Database Connection Error: " . $e->getMessage());
    }
}

    /**
     * Prepare SQL statement
     * @param string $sql
     */
    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
        
        if (!$this->stmt) {
            throw new Exception("Statement preparation failed: " . $this->conn->error);
        }
        
        return $this;
    }
    
    /**
     * Bind parameters to prepared statement
     * @param string $types - Data types (i=integer, d=double, s=string, b=blob)
     * @param mixed ...$params - Parameters to bind
     */
    public function bind($types, ...$params) {
        if ($this->stmt) {
            $this->stmt->bind_param($types, ...$params);
        }
        return $this;
    }
    
    /**
     * Execute prepared statement
     * @return bool
     */
    public function execute() {
        if ($this->stmt) {
            return $this->stmt->execute();
        }
        return false;
    }
    
    /**
     * Get single row result
     * @return object|null
     */
    public function single() {
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->fetch_object();
    }
    
    /**
     * Get multiple rows result
     * @return array
     */
    public function resultSet() {
        $this->execute();
        $result = $this->stmt->get_result();
        $rows = [];
        
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    /**
     * Get row count
     * @return int
     */
    public function rowCount() {
        return $this->stmt->affected_rows;
    }
    
    /**
     * Get last inserted ID
     * @return int
     */
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->conn->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->conn->rollback();
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->stmt) {
            $this->stmt->close();
        }
        if ($this->conn) {
            $this->conn->close();
        }
    }
    
    /**
     * Escape string for security
     * @param string $string
     * @return string
     */
    public function escape($string) {
        return $this->conn->real_escape_string($string);
    }
}