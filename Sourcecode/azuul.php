<?php
	session_start();
	
	include "azuul.functions.php";
	include "azuul.controller.php";
	include "azuul.route.php";
	include "azuul.html.php";

	class Azuul {
		private $registered_areas = array();
		private $registered_routers = array();
		private $declared_controllers = array();
		private $current_router = null;
		private $current_controller = null;
		private $controller_postfix = 'Controller';

		public function setControllerPostFix($value){
			$this->controller_postfix = $value;
		}

		public function getCurrentController(){
			return $this->current_controller;
		}

		public function getControllersFolderPath(){
			$area = $this->getAreaName();
			if($area != null)
				return 'areas/'.$area.'/controllers';
			return 'controllers';
		}

		public function getViewsFolderPath(){
			$area = $this->getAreaName();
			if($area != null)
				return 'areas/'.$area.'/views';
			return 'views';
		}

		public function getAreaName(){
			$routerArea = isset($this->current_router->keys['{area}']) ? $this->current_router->keys['{area}'] : null;
			if($routerArea != null && in_array($routerArea, $this->registered_areas)){
				return $routerArea;
			}else{
				return null;
			}
		}

		public function getControllerName(){
			return isset($this->current_router->keys['{controller}']) ? $this->current_router->keys['{controller}'] : null;
		}

		public function getActionName(){
			return isset($this->current_router->keys['{action}']) ? $this->current_router->keys['{action}'] : null;
		}

		/// <summary>
		/// Register an area to be used on the router
		/// </summary>
		/// <param name="areaName">The area name to be registered</param>
		public function registerArea($areaName){
			if(!in_array($areaName, $this->registered_areas)){
				$this->registered_areas[] = $areaName;
			}else{
				throw new Exception("Error Registering the Area: Area [".$areaName."] already registered.", 1);
			}
		}

		/// <summary>
		/// Register an router to be used to catch the variables
		/// </summary>
		/// <param name="routerName">The router name to be registered</param>
		/// <param name="routerPattern">The router pattern to be processed</param>
		public function registerRoute($routerName, $routerPattern, $pattersDefaults = null){
			$path = adjuste_url($routerPattern);
			if($this->getRouteByName($routerName) == null){
				$this->registered_routers[] = new Route($routerName, $routerPattern, $pattersDefaults);
			}else{
				throw new Exception("Error Registering the Route: Already exists an router with this name [".$priority.", ".$routerName."].", 1);
			}
		}

		public function execute($path){
			$path = adjuste_url($path);

			$this->current_router = null;
			foreach ($this->registered_routers as $route){
				if($route->isValidPath($path)){
					$this->current_router = $route;
					break;
				}
			}

			if($this->current_router != null){
				$this->executeRoute($this->current_router);
			}else{
				throw new Exception('Error Processing Request: Router not found for this requestion.', 1);
			}
		}

		//#region private
		private function executeRoute($route){
			$controller = $this->getControllerName();
			$action = $this->getActionName();

			$file_controller = $this->getControllersFolderPath().'/'.$controller.'.controller.php';
			if(file_exists($file_controller)){
				if(!$this->isDeclaredController($controller)){	//Ferify if the controller was not included yet
					$this->declared_controllers[] = $controller;
					include($file_controller);
				}

				$controllerClass = $controller;
				if($this->controller_postfix != null)
					$controllerClass .= $this->controller_postfix;

				if(class_exists($controllerClass)){
					$this->current_controller = new $controllerClass();
					if($this->current_controller instanceof Controller){
						$this->current_controller->setMVC($this);
						if(method_exists($this->current_controller, $action)){
							$reflectionMethod = new ReflectionMethod($controllerClass, $action);
							$methosParameters = $reflectionMethod->getParameters();
							if($methosParameters != null && sizeof($methosParameters) > 0){
								$parametersValues = array();
								foreach($methosParameters as $param){
									$paramName = '{'.$param->name.'}';
									if(array_key_exists($paramName, $route->keys)){
										$parametersValues[$param->name] = $route->keys[$paramName];
									}else if(!empty($this->current_controller->getParameter($param->name))){
										$parametersValues[$param->name] = $this->current_controller->getParameter($param->name);
									}
								
								}
								$reflectionMethod->invokeArgs($this->current_controller, $parametersValues);
							}else{
								$reflectionMethod->invoke($this->current_controller);
							}
						} 
						else{
							throw new Exception("Error Processing Request: Action [".$action."] not exists in the controller [".$controller."].", 1);
						}
					}else{
						throw new Exception("Error Processing Request: Controller [".$action."] isn't an instance of Controller.", 1);
					}
				}
				else{
					throw new Exception("Error Processing Request: Controller class [".$controller."] not found in the file [".$file_controller."].", 1);
				}
			}else{
				throw new Exception("Error Processing Request: Controller file [".$file_controller."] file not exists.", 1);
			}
		}

		private function getRouteByName($name){
			foreach ($this->registered_routers as $router){
				if($router->Name == $name){
					return $router;
				}
			}
			return null;
		}

		private function isDeclaredController($controller){
			$declared = false;
			for($i = 0; $i < sizeof($this->declared_controllers); $i++){
				if(strtolower($this->declared_controllers[$i]) == strtolower($controller)){
					$declared = true;
					break;
				}
			}
			return $declared;
		}
		//#endregion private
	}
?>