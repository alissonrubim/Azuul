<?php
    class login extends Controller {
        public function index(){
            $this->view("index");
        }

        public function doLogin($username = null, $password = null){
            $this->allowMethod("POST"); //Just allow POST methods
            if($username == "admin" && $password == "admin"){
                $this->setUserAuthorized(true);
                $this->redirectToAction("home/index");
            }else{
                $this->redirectToAction("login/index");
            }
        }

        public function doLogout(){
            $this->setUserAuthorized(false);
        }
        
    }
?>