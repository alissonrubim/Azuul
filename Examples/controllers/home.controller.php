<?php
    class home extends Controller {
        public function index(){
            $this->view("index");
        }

        public function indexPartial(){
            $this->partial("index");
        }

        public function indexWithValue($id){
            echo "This is the value - ".$id;
        }

        public function indexWithViewBag($id){
            $this->viewBag["id"] = $id;
            $this->view("index");
        }

        public function indexWithModel(){
            include "models/ModelExample.php";
            $model = new ModelExample();
            $model->Name = 'Alisson';
            $model->Age = 26;
            $this->view("indexModel", $model);
        }
    }
?>