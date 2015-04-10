<?php

require LIB_PATH . '/View.php';
require APPLICATION_PATH . '/models/ProductModel.php';

class DeleteController {
    public function printResult($productModel, &$result) {
        $result = $result . "<tr><td>" . $productModel->getId() . "</td>";
        $result = $result . "<td>" . $productModel->getName() . "</td>";
        $result = $result . "<td>" . $productModel->getHref() . "</td>";
        $result = $result . "<td>" . $productModel->getPrice() . "</td>";
        foreach($productModel->getDescription() as $val) {
            $result = $result . "<td>" . $val . "</td>";
        }
    }
    public function indexAction() {
        $view = new View('delete');
        $model = new ProductModel();
        $model1 = new ProductModel();
        $products = $model1->getAll();


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = '</br><span class="bold">Deleted:</span><br><br>';
            $result = $result . '<div class="table-responsive"><table class="table table-bordered table-condensed table-hover res-table"><th>ID</th><th>Name</th><th>Href</th><th>Price</th><th>Attribute #1</th><th>Attribute #2</th><th>Attribute #3</th><th>Attribute #4</th><th>Attribute #5</th><th>Attribute #6</th><th>Attribute #7</th>';

            if($_POST['select'] == 'obj_id') {
                $model->setId($_POST['word']);
                foreach ($products as $productModel) {
                    if ($productModel->getId() == $model->getId()) {
                        $productModel->deleteProduct();
                        $this->printResult($productModel, $result);
                    }
                }
            }
            else if($_POST['select'] == 'name') {
                $model->setName($_POST['word']);
                foreach ($products as $productModel) {
                    if ($productModel->getName() == $model->getName()) {
                        $productModel->deleteProduct();
                        $this->printResult($productModel, $result);
                    }
                }
            }
            else if($_POST['select'] == 'price') {
                $model->setPrice($_POST['word']);
                foreach ($products as $productModel) {
                    if ($productModel->getPrice() == $model->getPrice()){
                        $productModel->deleteProduct();
                        $this->printResult($productModel, $result);
                    }
                }
            }
        }
        $result = $result . '</table></div>';
        $view->setVariable('result', $result);
        $view->render();
    }
}