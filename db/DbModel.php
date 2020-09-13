<?php


namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey(): string;



    
    public function save()
    {
        $tableName = $this->tableName();
        $attrs = $this->attributes();
        $params = array_map(fn($m)=>":$m",$attrs);
        $stmt = self::prepare("INSERT INTO $tableName(".implode(',',$attrs).") VALUES(".implode(',',$params).")");

      foreach($attrs as $value)
      {
          $stmt->bindValue(":$value",$this->{$value});
      }

      $stmt->execute();
      return true;

    }

    public function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $sql = implode("AND ",array_map(fn($m)=>"$m = :$m",$attributes));
        $stmt = self::prepare("SELECT *FROM $tableName WHERE $sql");

        foreach($where as $key=>$value)
        {
            $stmt->bindValue(":$key",$value);
        }

        $stmt->execute();
        return $stmt->fetchObject(static::class);


    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

}

?>