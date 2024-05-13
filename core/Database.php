<?php

namespace core;

use PDO;

class Database
{
    protected $host;
    protected $user;
    protected $password;
    protected $database;
    public static $connection;

    public function __construct()
    {
        $this->host = env('DBHOST');
        $this->user = env('DBUSER');
        $this->password = env('DBPASSWORD');
        $this->database = env('DBNAME');

        self::$connection = new PDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->user, $this->password);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function db()
    {
        return self::$connection;
    }

    /**
     * @return int id
     */
    public static function lastInsertId()
    {
        return self::$connection->lastInsertId();
    }

    //select all
    public function select($table, $condition = null, $select = "*")
    {
        if (is_array($select)) {
            $selectQuery = implode(',', array_values($select));
        } else {
            $selectQuery = $select;
        }

        $sql = "SELECT " . $selectQuery . " FROM " . $table;

        if ($condition !== null) {
            $sql .= " WHERE ";
        }

        $values = [];
        if (is_array($condition)) {
            //conditions
            foreach ($condition as $key => $value) {
                $sql .= $key . " = ?";
                $values[] = $value;

                end($condition);
                if (key($condition) != $key) {
                    $sql .= " and ";
                }
            }
        } else {
            $sql .= $condition;
        }

        if (count(@$values) > 0) {
            $result = $this->db()->prepare($sql);
            $result->execute($values);
            $result = $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // echo $sql; die;
            $result = $this->db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    /** insert */
    public function insert($table, $data){
        if(!is_array($data)){
            Response::json(['status' => 'error', 'message' => 'Data format is incorrect'], 400);
        }
        foreach ($data as $key => $d){
            if(gettype($d) == 'string'){
                $data[$key] = "\"$d\"";
            }
        }

        $columns = implode(',', array_keys($data));
        $values = implode(',', array_values($data));

        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
        $create = $this->db()->query($sql);
        return $create;
    }

    /** UPDATE */
    public function update($table, $data, $condition)
    {
        if(!is_array($data)){
            Response::json(['status' => 'error', 'message' => 'Data format is incorrect'], 400);
        }

        $sql = "UPDATE " . $table . " SET ";

        //key value
        foreach ($data as $key => $value) {
            $sql .= $key . " = \"" . $value . "\",";;
        }

        $sql = rtrim($sql, ', ');

        $sql .= " WHERE ";

        if (is_array($condition)) {
            //conditions
            foreach ($condition as $key => $value) {
                $sql .= $key . " = " . "\"" . $value . "\"";

                end($condition);
                if (key($condition) != $key) {
                    $sql .= " and ";
                }
            }
        } else {
            $sql .= $condition;
        }
        // echo $sql; die;
        $update = $this->db()->query($sql);
        return $update;
    }

    /** DELETE */
    public function delete($table, $condition)
    {
        $sql = "DELETE FROM " . $table . " WHERE ";

        if (is_array($condition)) {
            //conditions
            foreach ($condition as $key => $value) {
                $sql .= $key . " = " . "\"" . $value . "\"";

                end($condition);
                if (key($condition) != $key) {
                    $sql .= " and ";
                }
            }
        } else {
            $sql .= $condition;
        }

        $result = $this->db()->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function __destruct()
    {
        unset($this->connection);
    }
}
