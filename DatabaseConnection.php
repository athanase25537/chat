<?php
namespace Application\Lib;

use PDO;
use Exception;
class DatabaseConnection
{

    public ?PDO $database = null;

    public function getConnection(): ?PDO
    {
        try{
            if($this->database == null){
                $this->database = new PDO(
                    'mysql:host=localhost;dbname=porfolio;charset=utf8',
                    'root',
                    '',
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            }
            return $this->database;
        }catch(Exception $e){
            $e->getMessage();
        }
    }
}