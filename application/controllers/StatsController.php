<?php

require LIB_PATH . '/View.php';
require APPLICATION_PATH . '/models/ProductModel.php';

class StatsController {

    public function indexAction() {
        $view = new View('stats');
        $model = new ProductModel();


        $max_price = array();
        $min_price = array();
        $avg_price = array();
        $sum_price = array();
        $count = array();
        $longest_name = array();

        $max_price[] = $model->selectProduct("SELECT p.number_value FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE p.attr_id = 150 AND p.number_value = (SELECT MAX(p1.number_value) FROM params p1 WHERE p1.attr_id = 150);");
        $min_price[] = $model->selectProduct("SELECT p.number_value FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE p.attr_id = 150 AND p.number_value != 0 AND p.number_value = (SELECT MIN(p1.number_value) FROM params p1 WHERE p1.attr_id = 150 AND p1.number_value != 0);");
        $avg_price[] = $model->selectProduct("SELECT AVG( p.number_value ) object_id FROM params p WHERE p.number_value != 0 AND p.attr_id = 150;");
        $sum_price[] = $model->selectProduct("SELECT SUM( p.number_value ) object_id FROM params p WHERE p.number_value != 0 AND p.attr_id = 150;");
        $count[] = $model->selectProduct("SELECT COUNT( DISTINCT object_id ) object_id FROM params");
        $longest_name[] = $model->selectProduct("SELECT MAX(LENGTH(name)) object_id FROM objects");

        $otypes = $model->doCustomQuery("SELECT COUNT(*) as ot_count FROM object_types WHERE object_type_id != 1;");
        $ot_count = 0;
        foreach($otypes as $row) {
            $ot_count = $row['ot_count'];
        }
        for($i = 1; $i <= $ot_count; $i++) {
            $max_price[$i] = $model->selectProduct("SELECT p.number_value FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE o.object_type_id = " . ($i+1) . " AND p.attr_id = 150 AND p.number_value = (SELECT MAX(p1.number_value) FROM objects o1 JOIN params p1 ON(o1.object_id = p1.object_id) WHERE o1.object_type_id = " . ($i+1) . " AND p1.attr_id = 150);");
            $min_price[$i] = $model->selectProduct("SELECT p.number_value FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE o.object_type_id = " . ($i+1) . " AND p.attr_id = 150 AND p.number_value != 0 AND p.number_value = (SELECT MIN(p1.number_value) FROM objects o1 JOIN params p1 ON(o1.object_id = p1.object_id) WHERE o1.object_type_id = " . ($i+1) . " AND p1.attr_id = 150 AND p1.number_value != 0);");
            $avg_price[$i] = $model->selectProduct("SELECT AVG(p.number_value) object_id FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE o.object_type_id = " . ($i+1) . " AND p.number_value != 0 AND p.attr_id = 150;");
            $sum_price[$i] = $model->selectProduct("SELECT SUM(p.number_value) object_id FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE o.object_type_id = " . ($i+1) . " AND p.number_value != 0 AND p.attr_id = 150;");
            $count[] = $model->selectProduct("SELECT COUNT(DISTINCT o.object_id) object_id FROM objects o JOIN params p ON (o.object_id = p.object_id) WHERE o.object_type_id = " . ($i+1));
            $longest_name[] = $model->selectProduct("SELECT MAX(LENGTH(name)) object_id FROM objects where object_type_id = " . ($i+1));

            $view->setVariable('max_price_' . $i, $max_price[$i]);
            $view->setVariable('min_price_' . $i, $min_price[$i]);
            $view->setVariable('avg_price_' . $i, $avg_price[$i]);
            $view->setVariable('sum_price_' . $i, $sum_price[$i]);
            $view->setVariable('count_' . $i, $count[$i]);
            $view->setVariable('longest_name_' . $i, $longest_name[$i]);
        }

        $view->setVariable('max_price_global', $max_price[0]);
        $view->setVariable('min_price_global', $min_price[0]);
        $view->setVariable('avg_price_global', $avg_price[0]);
        $view->setVariable('sum_price_global', $sum_price[0]);
        $view->setVariable('count_global', $count[0]);
        $view->setVariable('longest_name_global', $longest_name[0]);

        $view->render();
    }
}