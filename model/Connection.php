<?php

namespace Model;
use PDO;

class Connection {
    public static function getConnection() {
        $params = require(ROOT.'/model/db.php');
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['pass']);
        return $db;
    }
}

    //     tune_up();
/**
 * Method allows to test db-connection
 * And perform seed mock data for tables
 */
function tune_up() {
	$util = new DbUtil();
	$util->run();
}

/**
 * DbUtil perfroms CRUD operation on data base
 * with customised table and fields
 */
class DbUtil {

	// public $table = 'articles';
    // public $fields = ['title', 'text'];

    public $table = 'comments';
    public $fields = ['post_id', 'text'];
    
    private static $db;

    public function __construct() {
        self::$db = Connection::getConnection();
    }

    public function run() {

    	// $this->seedComments();

    	// $this->seedTable(20, ["Title", "Text of the Article!"]);
        // $this->showResult($this->deleteAll());
        // $this->showResult($this->selectId(202));
        // $this->showResult($this->insert(['Inserted Title', 'Inserted Text']));
        // $this->showResult($this->update(202, ['Updated Title', 'Updated Text']));
        $this->showResult($this->selectAll());
        die();
    }

    public function seedComments() {
    	for($i = 223; $i <= 242; $i ++) {
    		$this->insert([$i, 'Comment\'s Text for Post #' . $i]);
    	}
    }

    private function delete(int $id) {
    	$query = "DELETE FROM $this->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $res->execute();
        return $res->rowCount();
    }

    private function update(int $id, array $a) {
    	foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $q .= "$f = :$f, ";
        $q = trim($q, ', '); // field = :field, field = :field
        $query = "UPDATE $this->table SET $q WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id);
        foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $res->bindParam(":$f", $a[$n]);
        return $res->execute();
    }

    private function insert(array $a = []) {
		foreach ($this->fields as $n => $f)
            if(is_numeric($n)) {
                $names .= "$f, ";
                $wildcards .= ":$f, ";
            }
        $names = trim($names, ', ');              // field, field ...
        $wildcards = trim($wildcards, ', ');      // :field, :field ...
        $query = "INSERT INTO $this->table ($names) VALUES ($wildcards)"; 
        $res = self::$db->prepare($query);
        foreach ($this->fields as $n => $f)
            if(is_numeric($n))
                $res->bindParam(":$f", $a[$n]);
        if ($res->execute()) {
            return self::$db->lastInsertId();
        }
        return 0;
    }

    private function selectId(int $id) {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        return $res->fetch();
    }

    private function selectAll() {
    	$query = "SELECT * FROM $this->table";
        $res = self::$db->prepare($query);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $i = 0;
        while ($row = $res->fetch()) {
        	$m['id'] = $row['id'];
            foreach ($this->fields as $n => $f)
                if(is_numeric($n))
                    $m[$f] = $row[$f];
        	$resultSet[$i] = $m;
            $i++;
        }
        return $resultSet;
    }

    private function seedTable(int $amount, array $a) {
    	for($i = 1; $i <= $amount; $i ++) {
    		$this->insert($a);
    	}
    }

    private function deleteAll() {
    	$query = "DELETE FROM $this->table";
        $res = self::$db->prepare($query);
        $result = $res->execute();
        return $res->rowCount();
    }

    private function showResult($a) {
    	echo "<style>body{padding:50px;font-family:Verdana;background:#fafafa}</style><br/>";
        echo($a);
    	echo "<br/>";
        var_dump($a);
        echo "<br/>";
        echo "<pre>";
        print_r($a);
        echo "</pre>";
	    die();
    }
}





