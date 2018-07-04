<?php
	class Route {
        public $name = null;
        public $pattern = null;
        public $patternDefaults = null;
        public $keys = array();

        function __construct($name, $pattern, $patternDefaults){
            $this->name = $name;
            $this->pattern = adjuste_url($pattern);
            $this->patternDefaults = $patternDefaults;
        }

        public function isValidPath($path){
            $this->keys = array();
            $splitPath = explode("/", $path);
            $splitRouter = explode("/", $this->pattern);
           
            $isValid = true;

            if(sizeof($splitPath) > sizeof($splitRouter)){
                $isValid = false;
            }

            if($isValid){
                foreach ($splitRouter as $routeIndex => $routeKey){
                    if(substr($routeKey, 0,1) == "{" && substr($routeKey,-1) == "}"){ //if is a variable
                        if(!empty($splitPath[$routeIndex])){
                            $this->keys[$routeKey] = $splitPath[$routeIndex];
                        }else{
                            if($this->patternDefaults != null && array_key_exists($routeKey, $this->patternDefaults)){
                                $this->keys[$routeKey] = $this->patternDefaults[$routeKey];
                            }else{
                                $isValid = false;
                                break;
                            }
                        }
                    }else if(strtolower($routeKey) == strtolower($splitPath[$routeIndex])){ //if constains the path
                        continue;
                    }else{
                        $isValid = false;
                        break;
                    }
                }
            }
            return $isValid;
        }        
    }
?>