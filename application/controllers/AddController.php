<?php

require LIB_PATH . '/View.php';
require APPLICATION_PATH . '/models/ProductModel.php';

class AddController {

    public function indexAction() {
        $view = new View('add');
        $model = new ProductModel();

        $view->render();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model->setId($_POST['id']);
            $model->setOTId($_POST['ot_id']);
            $model->setName($_POST['name']);
            $model->setHref($_POST['href']);
            $model->setPrice($_POST['price']);
            $desc = explode(", ", $_POST['description']);
            $model->setDescription ($desc);
            if (!$model->addProduct()) throw new Exception("Fail occured while adding new product info");

            header('Location: ' . '/index');
        }
    }
}