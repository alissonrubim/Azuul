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
?>