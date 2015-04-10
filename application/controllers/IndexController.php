<?php

require LIB_PATH . '/View.php';
require APPLICATION_PATH . '/models/ProductModel.php';

class IndexController {

    public function indexAction() {
        $view = new View('index');
        $model = new ProductModel();


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $products = $model->getAll($_POST['select'], $_POST['select1']);
        }
        else {
            $products = $model->getAll();
        }
        $result = $this->printResult($products);
        $view->setVariable('result', $result);

        $view->render();
    }

    public function printResult($products) {
        $result = '</br><span class="bold">All products:</span>';
        $result = $result . '<div class="table-responsive"><table class="table table-bordered table-condensed table-hover res-table"><th>ID</th><th>OT_id</th><th>Name</th><th>Href</th><th>Price</th><th>Attribute #1</th><th>Attribute #2</th><th>Attribute #3</th><th>Attribute #4</th><th>Attribute #5</th><th>Attribute #6</th><th>Attribute #7</th>';
        foreach ($products as $productModel) {
            $result = $result . "<tr><td>" . $productModel->getId() . "</td>";
            $result = $result . "<td>" . $productModel->getOTId() . "</td>";
            $result = $result . "<td>" . $productModel->getName() . "</td>";
            $result = $result . "<td>" . $productModel->getHref() . "</td>";
            $result = $result . "<td>" . $productModel->getPrice() . "</td>";
            foreach($productModel->getDescription() as $val) {
                $result = $result . "<td>" . ($val? $val : "NULL") . "</td>";
            }
        }
        $result = $result . '</table></div>';
        return $result;
    }
}