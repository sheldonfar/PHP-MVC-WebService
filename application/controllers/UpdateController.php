<?php

require LIB_PATH . '/View.php';
require APPLICATION_PATH . '/models/ProductModel.php';

class UpdateController {

    public function indexAction() {
        $view = new View('update');
        $model = new ProductModel();

        $view->render();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model->setId($_POST['id']);
            $model->setName($_POST['name']);
            $model->setHref($_POST['href']);
            $model->setPrice($_POST['price']);
            $desc = explode(", ", $_POST['description']);
            $model->setDescription ($desc);
            if (!$model->updateProduct()) throw new Exception("Fail occured while updating product info");

            header('Location: ' . '/index');
        }
    }
}