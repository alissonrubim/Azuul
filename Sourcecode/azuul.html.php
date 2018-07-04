<?php
    class Html {
        private $controller = null;

        function __construct($controller){
            $this->controller = $controller;
		}

        public function renderBody(){
            echo $this->controller->getViewFlush();
        }

        public function viewBag($str){
            return $this->controller->viewBag[$str];
        }

        public function actionLink($action){
            return get_base_url() . '/' . adjuste_url($action);
        }
    }
?>