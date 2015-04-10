<?php

require LIB_PATH . '/View.php';
require APPLICATION_PATH . '/models/ProductModel.php';

class ParseController {

    public function indexAction() {
        $view = new View('parse');
        $model = new ProductModel();


        $model->truncateProducts();


        /* PARSING CSV FILE */
        if (($handle = fopen("public/db_dumps/dump.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $model = new ProductModel();
                $model->setOTId(2);
                $model->setName($data[1]);
                $model->setHref($data[2]);

                $desc = explode(", ", $data[3]);

                $model->setPrice($desc[0]);

                unset($desc[0]);

                $desc = str_replace("&quot;", '"', $desc);
                $model->setDescription($desc);

                 if (!$model->addProduct()) throw new Exception("Fail occured while adding product info to the DB from CSV file");
            }
            fclose($handle);
            $result1 = "<p>Successfully done parsing CSV!</p>";
            $view->setVariable('result1', $result1);
        }
        else {
            $result1 = "<p>Error while parsing CSV :(</p>";
            $view->setVariable('result1', $result1);
        }



        /* PARSING JSON FILE */
        if($string = file_get_contents("public/db_dumps/dump.json")) {
            $json_a = json_decode($string, true);

            foreach ($json_a as $key => $value) {
                if (gettype($value) == "array") {
                    $model = new ProductModel();

                    foreach ($value as $key1 => $value1) {
                        //  echo ", new Key = " . $key1 . ", Value = " . $value1;
                        if($key1 == 'NAME') $model->setName($value1);
                        else if($key1 == 'HREF') $model->setHref($value1);
                        else if($key1 == 'DESCRIPTION') {
                            $desc = explode(", ", $value1);
                            $model->setPrice($desc[0]);
                            unset($desc[0]);

                            $desc = str_replace("&quot;", '"', $desc);
                            $model->setDescription($desc);
                        }
                    }
                    $model->setOTId(3);


                    if (!$model->addProduct()) throw new Exception("Fail occured while adding product info to the DB from CSV file");

                }
            }
            $result2 = "<p>Successfully done parsing JSON!</p>";
            $view->setVariable('result2', $result2);
        }
        else {
            $result2 = "<p>Error while parsing JSON :(</p>";
            $view->setVariable('result2', $result2);
        }


        /* PARSING XML FILE */
        if($products = simplexml_load_file("public/db_dumps/dump.xml")){
            foreach ($products->Row as $row) {
                $model = new ProductModel();
                $model->setOTId(4);
                $model->setName($row->NAME);
                $model->setHref($row->HREF);

                $desc = explode(", ", $row->DESCRIPTION);

                $model->setPrice($desc[0]);

                unset($desc[0]);

                $desc = str_replace("&quot;", '"', $desc);
                $model->setDescription($desc);

                if (!$model->addProduct()) throw new Exception("Fail occured while adding product info to the DB from XML file");
            }
            $result3 = "<p>Successfully done parsing XML!</p>";
            $view->setVariable('result3', $result3);
        }
        else {
            $result3 = "<p>Error while parsing XML :(</p>";
            $view->setVariable('result3', $result3);
        }

        $view->render();
    }
}