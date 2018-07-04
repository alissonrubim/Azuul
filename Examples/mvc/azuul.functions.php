<?php
    function substring_all($html, $needle, $lastPos = 0){
        $lastPos = 0;
        $positions = array();

        while (($lastPos = strpos($html, $needle, $lastPos)) !== false) {
        
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($needle);
        }
        return $positions;
    }

    function adjuste_url($pattern){
        $pattern = trim($pattern);
        $pattern = ltrim($pattern, '/');
        $pattern = rtrim($pattern, '/');
        return $pattern;
    }

    function get_base_url(){
        return sprintf(
          "%s://%s%s",
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
          $_SERVER['SERVER_NAME'],
          $_SERVER['REQUEST_URI']
        );
      }
?>