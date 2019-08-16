<?php
namespace Model;
require_once(ROOT . '/model/Connection.php');

use Model\Connection;
use PDO;


class Model {

    public $table = '';
    public $fields = [];
    public $id = 0;
    private static $db;

    public function __construct(array $fields = null) {
        $idx = 0;
        foreach ($this->fields as $key) {
            $this->fields[$key] = isset($fields[$idx]) ? $fields[$idx] : null;
            $idx ++;
        }
        self::$db = Connection::getConnection();
    }

    public function __set($name, $value) {
        if(array_key_exists($name, $this->fields)) {
            $this->fields[$name] = $value;
        }
    }

    public function __get($name) {
        return $this->fields[$name];
    }

    /**
     * Determine whether current object is new
     * Call accordingly either "insert" or "update"
     */
    public function save() {
        if($this->id) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * Insert the mapping of current object into db table
     * @return object $this on success, or null
     */
    private function insert() {
        $names = '';
        $wildcards = '';
        foreach ($this->fields as $n => $f)
            if(is_numeric($n) && $this->fields[$f] !== null) {
                $names .= "$f, ";
                $wildcards .= ":$f, ";
            }
        $names = trim($names, ', ');              // field, field ...
        $wildcards = trim($wildcards, ', ');      // :field, :field ...
        $query = "INSERT INTO $this->table ($names) VALUES ($wildcards)";
        $res = self::$db->prepare($query);
        foreach ($this->fields as $n => $f)
            if(is_numeric($n) && $this->fields[$f] !== null)
                $res->bindParam(":$f", $this->fields[$f]);
        if ($res->execute()) {
            $this->id = self::$db->lastInsertId();
            return $this;
        }
        return null;
    }

    /**
     * Update the mapping of current object into db table
     * @return object $this on success, or null
     */
    private function update() {
        $q = '';
        foreach ($this->fields as $n => $f)
            if(is_numeric($n))
                $q .= "$f = :$f, ";
        $q = trim($q, ', '); // field = :field, field = :field
        $query = "UPDATE $this->table SET $q WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $this->id);
        foreach ($this->fields as $n => $f)
            if(is_numeric($n))
                $res->bindParam(":$f", $this->fields[$f]);
        if ($res->execute()) return $this;
        return null;
    }

    /**
     * Delete the record of current object from db table
     * @return int as amount of deleted table rows
     */
    public function delete() {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $this->id, PDO::PARAM_INT);
        $res->execute();
        return $res->execute();
    }

    /**
     *  Below are Static Functions' Interfaces and General Methods
     */

    /**
     * Static Method for check whether the record exists
     * @param string $key name of record
     * @param string $value is needed value of record
     * @return boolean is such record exists
     */
    public static function exists(string $key, string $value) {
        $className = get_called_class();
        $m = new $className();
        if(count($m->condition($key, $value, '=')))
            return true;
        return false;
    }

    /**
     * Static Method for delete a record in one touch
     * Delete the record of current object from db table
     * @return int as amount of deleted table rows
     */
    public static function destroy(int $id) {
        $className = get_called_class();
        $m = new $className();
        $table = $m->table;
        $query = "DELETE FROM $table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->execute();
        return $res->execute();
    }

    /**
     * Static Interface for object fucntion select()
     * Retrieve an instance from Db table and wrap it into this object
     * @param int $id exact position in the database
     * @return object $this model on success filled with db row, or null
     */
    public static function find(int $id) {
        $className = get_called_class();
        $m = new $className();
        return $m->select($id);
    }

    /**
     * Static Interface for object function selectAll()
     * Retrieve all rows from Db table and wrap its into $this object
     * @return array objects $this model on success filled with db row, or null
     */
    public static function all() {
        $className = get_called_class();
        $m = new $className();
        return $m->selectAll();
    }

    /**
     * Static Interface for object fucntion slice()
     * Retrieve subset from table in Descending order
     * @param  int $offset start position from zero (excluding)
     * @param  int $limit end position (including) of subset from offset
     * @return int as amount of deleted table rows
     */
    public static function chunk(int $offset, int $limit) {
        $className = get_called_class();
        $m = new $className();
        return $m->slice($offset, $limit);
    }

    /**
     * Static Interface for object function condition()
     * Retrieve subset from table according "where" clause
     * @param string $key, $value clause as key and needed value
     * @return array of model objects, or null
     */
    public static function where(string $key, string $value, string $symbol = '=') {
        $className = get_called_class();
        $m = new $className();
        return $m->condition($key, $value, $symbol);
    }

    /**
     * Retrieve an instance from Db table and wrap it into this object
     * @param int $id according id field in database
     * @return object $this model on success filled with db row, or null
     */
    public function select($id) {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $row = $res->fetch();
        $className = get_class($this);
        if($row) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            return $m;
        }
        return null;
    }

    /**
     * Retrieve all rows from Db table and wrap its into $this object
     * @return array objects $this model on success filled with db row, or null
     */
    public function selectAll() {
        $query = "SELECT * FROM $this->table";
        $res = self::$db->prepare($query);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $className = get_class($this);
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }

    /**
     * Retrieve subset from table in Descending order
     * @param  int $offset start position from zero (excluding)
     * @param  int $limit end position (including) of subset from offset
     * @return array as result set of table rows
     */
    public function slice(int $offset, int $limit) {
        $query = "SELECT * FROM $this->table ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $res = self::$db->prepare($query);
        $res->bindParam(':offset', $offset, PDO::PARAM_INT);
        $res->bindParam(':limit', $limit, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $className = get_class($this);
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }

    /**
     * Static Interface for object function condition()
     * Retrieve subset from table according "where" clause
     * @param string $key = $valie clause as key and needed value
     * @return array of model objects, empty on bad condition
     */
    public function condition(string $key, string $value, string $symbol = '=') {
        $query = "SELECT * FROM $this->table WHERE $key $symbol :value";
        $res = self::$db->prepare($query);
        $res->bindParam(':value', $value);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $className = get_called_class();
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }
}

