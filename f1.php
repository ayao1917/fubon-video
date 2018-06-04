<?php

$a = new udb();
$a->init();

echo $a->getUnitHTML();

class udb{
    private $dsn;
    private $dbh;

    private $dsn_target;
    private $dbh_target;

    private $row_id;
    private $array_sg;

    public function init() {
        $this->dsn_target = 'sqlite:/home/fubon/FDATA/u.new';
        $this->dbh_target = new PDO($this->dsn_target, '', '');
        $this->dbh_target->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    }

    public function traverse($node="-1") {
        $this->rowid=0;
        $this->array_sg=array();
        $this->getChildren($node, 0, 0);

        return $this->array_sg;
    }


    public function getUnitHTML() {

        $result = $this ->traverse("ROOT");

        $parents = array();
        $path = array();
        $indents = array();

        $count = 0;
        $last_indent = 0;
        $last_name = '';
        $current_system="";

        $html = "";

        foreach ($result as $data) {
            $data['name'] = preg_replace('/　/', '', $data['name']);
            $data['indent']--;
            if ($data['parent'] == "ROOT") {
                $data['parent'] = "-1";
                $current_system = $data['name'];
            }


            $pre= '';
            $post = '';
            $diff = $last_indent - $data['indent'];

            switch ($diff) {

                case -1: array_push($parents, $count-1);
                        array_push($path, $last_name);
                        break;
                case 0: break;
                default:
                        for ($j=0; $j<$diff; $j++) { array_pop($path); array_pop($parents); $pre.="</div>";}
            }

            $data['system'] = $current_system;
            $data['path'] = join('->', $path)."->".$data['name'];

            $parent_id = array();
            foreach ($parents as $value) array_push($parent_id, "p_".$value);

            $data["check"] = "false";

            $s = '';
            for ($i=0; $i<$data["indent"]; $i++) { $s = $s . '　　　';}

            $allchildren = "<span class='notree '></span>";

            //if (sizeof($result)>$count && $result[$count+1] && ($result[$count+1]["indent"] > $data['indent']+1)) {
            if (sizeof($result)>$count+1 && $result[$count+1] && ($result[$count+1]["indent"] > $data['indent']+1)) {
                $s = $s ."<span class='toggle collapse'> </span>" . $data['name'];
                $post = "\n<div class='p_region pp_".$count."' >";
                $allchildren = "<span class='tree all_".$count."'></span>";
                array_push($indents, $data['indent']);
            } else {
                $s = $s ."<span class='toggle' > </span>" . $data['name'];
            }

            $html .= "$pre<li id='code_". $data['code'] . "' class='" . join(" ", $parent_id) .  " not_selected '>$allchildren<span class='check'></span><span class='unit_name'>" . $s. "</span><span class='system_name'>".$data["system"] . "</span> </li>$post\n";

            $last_indent = $data['indent'];
            $last_name = $data['name'];
            $count++;
        }


        return $html;


    }

    private function prepare($item, $level, $parent_id) {

        $temp = array();
        $temp['name'] = $item['NAME'];
        $temp['code'] = $item['CODE'];
        $temp['id'] = 'id_'.$this->rowid++;
        $temp['indent'] = $level;
        $temp['parent'] = $parent_id;

        array_push($this->array_sg, $temp);
        return $temp;
    }

    private function getChildren($node, $level, $parent_id) {

        if ($node!='ROOT') {

            $sql = "SELECT * FROM UNITS WHERE CODE='".$node."'";

            $sth = $this->dbh_target->prepare($sql);
            $sth->execute();
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
            if (count($rows) == 0) return null;

            $this->prepare($rows[0], $level, $parent_id);
        }

        $my_id = $this->rowid;
        $sql = "SELECT * FROM UNITS WHERE PARENT='".$node."' ORDER BY CODE ASC";

        $sth = $this->dbh_target->prepare($sql);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $item) {
            $this->getChildren($item['CODE'], $level+1, $my_id);
        }

        return;
    }
}

?>
