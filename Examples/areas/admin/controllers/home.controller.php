<?php
    class home extends Controller {
        public function index(){
            $this->view("index");
        }

        public function showValue($id){
            echo "achou - admin!".$id;
        }
    }
?>