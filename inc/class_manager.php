<?php

include_once('config.php');
include_once('utils.php');

include_once('class_db_main.php');
class Manager{


    private $columns;
    public function init() {


        global $PERMISSION;
        
        $this->columns = $PERMISSION;
        array_push($this->columns, 'ID');
        array_push($this->columns, 'PASSWORD');

        $temp = $PERMISSION;
        foreach($temp as $i=>$item)  $temp[$i] .= " INTEGER";
        
        $query = 'CREATE TABLE IF NOT EXISTS MANAGER(ID TEXT PRIMARY KEY, PASSWORD TEXT, ' .join(',', $temp) . ')';        
        $sth = MainDB::getConnection()->prepare($query);
        $sth->execute();

        if (count($this->load("root"))==0) { 
            
            $data['ID']='root';
            //$data['PASSWORD'] = h('root'.__SLT__);
            $data['PASSWORD'] = 'admin';
            foreach($PERMISSION as $item)  $data["$item"] = 3;

            $this->create($data);
        } 
        $sth = MainDB::getConnection()->prepare('PRAGMA TABLE_INFO(MANAGER)');
        $sth->execute();
        $result = $sth->fetchAll();
        
        $current_columns =array();
        foreach($result as $item) array_push($current_columns, $item['name']);
        
        foreach($PERMISSION as $element) {
            if (!in_array($element, $current_columns)) $this->addColumn($element, 'Integer');
        }        
    }

    
    public function deleteManager($id="") {
        $sql = "DELETE FROM MANAGER ";
        if ($id != '') $sql .= "WHERE ID = '$id'";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function addColumn($name, $type) {
        $sql = "ALTER TABLE MANAGER ADD COLUMN $name $type ";
        $sth = MainDB::getConnection()->prepare($sql);
        return $sth->execute();
    }

    public function create($data) {
        global $PERMISSION;        
        $temp = $PERMISSION;
        foreach($temp as $i=>$item)  $temp[$i] = ":".$temp[$i];

        $data = filterInput($this->columns, $data);

        try { 
            $sql = 'INSERT INTO MANAGER (ID, PASSWORD, ' . join(',', $PERMISSION) . ') VALUES (:ID, :PASSWORD, ' . join(',', $temp) . ')';

            $sth = MainDB::getConnection()->prepare($sql);
            
            $sth->execute((array) $data);
            $result = MainDB::getConnection()->lastInsertId('ID');
            
        } catch(PDOExecption $e) { 
            $result = $e->getMessage(); 
        } catch (Exception $e) {
            $result = $e->getMessage(); 
                    
           // deal with all other kinds of exceptions
        } 
        return $result;
    }
    
    public function authentication($user, $password) {

        $sql = "SELECT count(1) as c FROM MANAGER WHERE ID='$user' AND PASSWORD='$password'";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $result = $sth->fetch();

        return ($result['c']>0);    
        
    }
    
    public function updateManager($data) {
        global $PERMISSION;        
        $temp = $PERMISSION;

        if (isset($data['jtRecordKey']) && !(isset($data['ID']))) {
             $data['ID'] = $data['jtRecordKey'];
             unset($data['jtRecordKey']);
        }

        $data = filterInput($this->columns, $data);
        $data["ANNOUNCEMENT"]="3";

        if (!isset($data['ID']))return;

        foreach($temp as $i=>$item)  $temp[$i] = ":".$temp[$i];
        
        $sql = 'INSERT OR REPLACE INTO MANAGER (ID, PASSWORD, ' . join(',', $PERMISSION) . ') VALUES (:ID, :PASSWORD, ' . join(',', $temp) . ')';
        $sth = MainDB::getConnection()->prepare('INSERT OR REPLACE INTO MANAGER (ID, PASSWORD, ' . join(',', $PERMISSION) . ') VALUES (:ID, :PASSWORD, ' . join(',', $temp) . ')');
        $sth->execute((array) $data);
    }
    public function updateField($id, $field, $content) {
        $sth = MainDB::getConnection()->prepare("UPDATE MANAGER SET $field=".$content." WHERE ID='".$id."'");
        $sth->execute();
    }
    public function check($module, $id) {

        $sql = "SELECT $module FROM MANAGER WHERE ID='$id' limit 1";
        $sth = MainDB::getConnection()->prepare($sql);
        $sth->execute();
        $result = $sth->fetch();

        return $result[$module];
    }
    public function load($id=-1, $range_modifier='', $order_modifier='') {

        $sql = "SELECT * FROM MANAGER";
        if ($id!=-1) $sql .= " WHERE ID = '$id' ";
	$sql .= " $order_modifier $range_modifier ";

        $sth = MainDB::getConnection()->prepare($sql);

        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}

?>
