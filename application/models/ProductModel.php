<?php

require LIB_PATH . '/Model.php';

class ProductModel extends Model {

	private $id;
    private $ot_id;
    private $name;
    private $price;
    private $description = array();
    private $href;

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        if (empty($description))
            $this->description = null;
        $this->description = $description;
    }

    public function getHref() {
        return $this->href;
    }

    public function setHref($href) {
        if (empty($href))
            throw new InvalidArgumentException('Param $href should be not empty!');
        $this->href = $href;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        if (empty($id))
            throw new InvalidArgumentException('Param $id should be not empty!');
        $this->id = $id;
    }

    public function getOTId() {
        return $this->ot_id;
    }

    public function setOTId($ot_id) {
        if (empty($ot_id))
            throw new InvalidArgumentException('Param $object_type_id should be not empty!');
        $this->ot_id = $ot_id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        if (empty($name))
            throw new InvalidArgumentException('Param $name should be not empty!');
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getAll($object='object_id', $order='ASC') {
        $query1 = 'SELECT o.object_id, o.object_type_id AS ot_id,  o.name, o.href, p.number_value AS price, p1.value AS description FROM objects o JOIN params p ON (o.object_id = p.object_id) JOIN params p1 ON (o.object_id = p1.object_id) WHERE p.attr_id = 150 AND p1.attr_id = 151';
        $query1 = $query1 . ' ORDER BY ' . $object . ' '. $order;

        $query = Model::$db->query($query1);


        $result = array();

        $current_obj = 1;
        $desc = array();
        $id = 0; $ot_id = 0; $name = ''; $href= ''; $price = ''; $row_count = 0;
        foreach($query as $row) {
            if($current_obj == $row['object_id']) {
                $desc[] = $row['description'];
                $id = $row['object_id'];
                $ot_id = $row['ot_id'];
                $href = $row['href'];
                $price = $row['price'];
                $name = $row['name'];
            }
            else {
                $current_obj = $row['object_id'];
                if($id != 0) {
                    $m = new ProductModel();
                    $m->setId($id);
                    $m->setOTId($ot_id);
                    $m->setName($name);
                    $m->setHref($href);
                    $m->setPrice($price);
                    $m->setDescription($desc);
                    $result[] = $m;
                }
                unset($desc);
                $desc[] = $row['description'];
            }
            $row_count++;
            if($row_count == $query->rowCount()) {
                $m = new ProductModel();
                $m->setId($id);
                $m->setOTId($ot_id);
                $m->setName($name);
                $m->setHref($href);
                $m->setPrice($price);
                $m->setDescription($desc);
                $result[] = $m;
                unset($desc);

                $desc[] = $row['description'];
            }
        }
        return $result;
    }

    public function truncateProducts() {
        $query = Model::$db->query("TRUNCATE TABLE objects;");
        $query = Model::$db->query("TRUNCATE TABLE params;");
        return true;
    }

    public function addProduct() {
        $query = Model::$db->query("SELECT IFNULL(MAX(object_id), 0) object_id FROM objects;");
        foreach($query as $row) {
            $this->setId($row['object_id'] + 1);
        }

        $query = Model::$db->query("INSERT INTO objects VALUES(" . $this->getId() . ", " . $this->getOTId() . ", '"  . $this->getName() . "', '" . $this->getHref() ."');");
        if(is_numeric($this->getPrice()))
            $query = Model::$db->query("INSERT INTO params VALUES(" . $this->getId() . ", 150, '', " . $this->getPrice() . ");");
        else
            $query = Model::$db->query("INSERT INTO params VALUES(" . $this->getId() . ", 150, '" . $this->getPrice() . "', '');");

        foreach($this->getDescription() as $val) {
           $query = Model::$db->query("INSERT INTO params VALUES(" . $this->getId() . ", 151, '" . $val . "', '');");
        }
        return true;
    }

    public function updateProduct() {
        $query = Model::$db->query("UPDATE objects SET name = '" . $this->getName() . "', href = '" . $this->getHref() . "' WHERE object_id = " . $this->getId() . ";");
        $query = Model::$db->query("UPDATE params SET number_value = " . $this->getPrice() . " WHERE object_id = " . $this->getId() . " AND attr_id = 150;");
        $query = Model::$db->query("DELETE FROM params WHERE object_id = " . $this->getId() . " AND attr_id = 151;");

        foreach($this->getDescription() as $val) {
            $query = Model::$db->query("INSERT INTO params VALUES(" . $this->getId() . ", 151, '" . $val . "', '');");
        }
        return true;
    }

    public function deleteProduct() {
        if($this->getId()) {
            $query = Model::$db->query("DELETE FROM objects WHERE object_id = " . $this->getId() . ";");
            $query = Model::$db->query("DELETE FROM params WHERE object_id = " . $this->getId() . ";");
        }
        else if ($this->getName()) {
            $query = Model::$db->query("SELECT object_id FROM objects WHERE name = " . $this->getName() . ";");
            foreach($query as $row) {
                $this->setId($row['object_id']);
            }
            $query = Model::$db->query("DELETE FROM objects WHERE object_id = " . $this->getId() . ";");
            $query = Model::$db->query("DELETE FROM params WHERE object_id = " . $this->getId() . ";");
        }
        else if ($this->getPrice()) {
            $query = Model::$db->query("SELECT object_id FROM params WHERE attr_id = 150 AND value = " . $this->getPrice() . ";");
            foreach($query as $row) {
                $this->setId($row['object_id']);
            }
            $query = Model::$db->query("DELETE FROM objects WHERE object_id = " . $this->getId() . ";");
            $query = Model::$db->query("DELETE FROM params WHERE object_id = " . $this->getId() . ";");
        }
        return true;
    }

    public function selectProduct($user_query) {
        $query = Model::$db->query($user_query);

        $result = array();
        foreach($query as $row) {
            return $row[0];
        }
    }
    public function doCustomQuery($query) {
        $result = Model::$db->query($query);
        return $result;
    }
} 