<?php
    class home extends Controller {
        public function index(){
            if($this->isUserAuthorized()){ //Verify if the user is logged
                $this->view("index"); //Call the index.phtml view for the home controller
            }else{
                $this->redirectToAction("login/index");
            }
        }

       /* public function indexPartial(){
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
        }*/
    }
?>