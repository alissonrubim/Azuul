<?php
	class Controller {
		private $mvc = null; //Auto-setted by the mvc.php
		private $html = null;
		private $current_view_flush = null;

		public $viewBag = array();

		function __construct(){
			$this->html = new Html($this);
		}

		public function setMVC($mvc){
			$this->mvc = $mvc;
		}

		public function getViewFlush(){
			return $this->current_view_flush;
		}

		public function allowMethod($methods){
			$splitMethods = explode(",", $methods);
			if(!in_array($_SERVER['REQUEST_METHOD'], $splitMethods)){
				throw new Exception("Error Processing Request: This action does not allow the method [".$_SERVER['REQUEST_METHOD']."].", 1);
				exit;
			}
		}

		public function view($viewName = null, $model = null, $includeLayout = true){
			$viewFile = $this->mvc->getViewsFolderPath().'/'.$this->mvc->getControllerName().'/'.$viewName.'.phtml';
			if(file_exists($viewFile)){
				extract(array("Html" => $this->html, "Model" => $model));
				
				ob_start(); //start the output buffer
				include($viewFile); //include de view
				$this->current_view_flush = ob_get_clean(); //get the output flush and clear the buffer
				
				$layout = null;
				
				$startTag = "\${";
				$endTag = "}";
				$tags = substring_all($this->current_view_flush, $startTag);
				if(sizeof($tags) > 0){
					foreach($tags as $tagposition){
						$startPosition = $tagposition;
						$endPosition = strpos($this->current_view_flush, $endTag, $startPosition) - $startPosition;
						$fulltag = substr($this->current_view_flush, $startPosition, $endPosition + strlen($endTag));
						$tag = str_replace($startTag, "", $fulltag);
						$tag = str_replace($endTag, "", $tag);
						$tagsplit = explode(":", $tag);
						if(sizeof($tagsplit) == 2){
							if(trim($tagsplit[0]) == 'layout'){
								$layout = trim($tagsplit[1]);
							}
						}
						$this->current_view_flush = str_replace($fulltag, "", $this->current_view_flush);
					}
				}

				if($includeLayout === true && $layout != null){
					include($layout);
				}else{
					echo $this->getViewFlush();
				}
				flush(); //flush the buffer
			}
		}

		public function partial($viewName, $model = null){
			$this->view($viewName, $model, false);
			flush();
		}

		protected function json($object){
			echo json_encode($object);
			flush();
		}

		public function getParameter($name){
			if(isset($_POST[$name]))
				return $_POST[$name];
			if(isset($_GET[$name]))
				return $_GET[$name];
			return null;
		}

		public function allowOnlyAuthorizedUser(){
			if(!$this->isUserAuthorized()){
				throw new Exception("Error Processing Request: This action does not allow unauthorized user.", 1);
				exit;
			}
		}

		public function setUserAuthorized($value){
			$mvc->setUserAuthorized($value);
		}

		public function isUserAuthorized(){
			if(empty($_SESSION['Azuul_isUserAuthorized'])){
				return false;
			}else{
				return $_SESSION['Azuul_isUserAuthorized'] === true;
			}
		}

			/// <summary>
		/// Redirect the current action to another one
		/// </summary>
		/// <param name="action">The action to redirect</param>
		public function redirectToAction($action){
			$this->mvc->execute($action);
		}
	}
?>