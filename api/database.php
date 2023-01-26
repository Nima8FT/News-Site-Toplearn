<?php

namespace database;

use PDO;
use PDOException;

class Database
{

    public $dbname = DB_NAME;
    public $servername = DB_HOST;
    public $username = DB_USERNAME;
    public $password = DB_PASSWORD;
    public $con;


    function __construct()
    {
        try {
            $this->con = new PDO("mysql:host=$this->servername;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "ok";
        } catch (PDOException $e) {
            echo "connection failed: " . $e->getMessage();
        }
    }

    public function insert($Table, $Fields, $Values)
    {
        try {

            $i = 1;
            $fi = '';
            $qi = '';

            foreach ($Fields as $field) {
                if (count($Fields) == $i) {
                    $fi .= $field;
                    $qi .= '?';
                } else {
                    $fi .= $field . ',';
                    $qi .= '?,';
                }
                $i++;
            }

            $res = $this->con->prepare('INSERT INTO ' . $Table . ' (' . $fi . ') VALUES (' . $qi . ')');

            for ($i = 1; $i <= count($Values); $i++) {
                $res->bindValue($i, $Values[$i - 1]);
            }

            $res->execute();

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update($Table, $Fields, $Values, $id)
    {
        try {

            $i = 1;
            $fi = '';
            foreach ($Fields as $field) {

                if (count($Fields) == $i)
                    $fi .= $field . '=?';
                else
                    $fi .= $field . '=? ,';


                $i++;
            }
            $fi .= ' WHERE id=' . $id;

            $res = $this->con->prepare('UPDATE ' . $Table . ' SET ' . $fi);


            for ($i = 1; $i <= count($Values); $i++)
                $res->bindValue($i, $Values[$i - 1]);


            $res->execute();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function delete($Table, $ID)
    {
        try {
            $res = $this->con->prepare('DELETE FROM ' . $Table . ' WHERE id=?');
            $res->bindValue(1, $ID);
            $res->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function query($qry)
    {
        try {
            $res = $this->con->prepare($qry);
            $res->execute();
            if ($res->rowCount() > 1) {
                $json = $res->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $json = $res->fetch(PDO::FETCH_ASSOC);
            }
            echo json_encode($json);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

}