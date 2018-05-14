<?php
	class Controller {
		public $controllerName = null;
		public $mvc = null;

		public function view($viewName, $model = null, $layout = '_layout'){
			if($layout != null){
				$layoutResource = 'views/'.$this->controllerName.'/'.$viewName.'.phtml';
				include('views/_shared/'.$layout.'.phtml');
			}else{
				include('views/'.$this->controllerName.'/'.$viewName.'.phtml');
			}
			flush();
		}

		public function redirectTo($controller, $action){
			$this->mvc->execute($controller, $action);
		}

		public function partial($viewName, $model = null){
			$this->view($viewName, $model, null);
			flush();
		}

		protected function json($json){
			echo json_encode($json);
			flush();
		}

		public function getParameter($name){
			if(isset($_POST[$name]))
				return $_POST[$name];
			if(isset($_GET[$name]))
				return $_GET[$name];
			return null;
		}
	}
?>