<?php
	class MVC {
		private $declaredControllers = array();

		public function isDeclaredController($controller){
			$declared = false;
			for($i = 0; $i < sizeof($this->declaredControllers); $i++){
				if(strtolower($this->declaredControllers[$i]) == strtolower($controller)){
					$declared = true;
					break;
				}
			}
			return $declared;
		}

		public function execute($controller, $action){
			if(file_exists('controllers/'.$controller.'.controller.php')){
				if(!$this->isDeclaredController($controller)){	
					$this->declaredControllers[] = $controller;
					include('controllers/'.$controller.'.controller.php');
				}
				if(class_exists($controller)){
					$reflector = new $controller();
					$reflector->mvc = $this;
					$reflector->controllerName =$controller;
					if(method_exists($reflector, $action)){
						$reflectionMethod = new ReflectionMethod($controller, $action);
						$reflectionMethod->invoke($reflector);
					} 
					else{
						throw new Exception("Error Processing Request: ".$action." not exists in ".$controller.".", 1);
					}
				}
				else{
					throw new Exception("Error Processing Request: ".$controller." not founded.", 1);
				}
			}else{
				throw new Exception("Error Processing Request: ".$controller." file not exists.", 1);
			}
		}
	}
?>