<?php
    include "mvc/mvc.php";

    $mvc = new MVC();
    $mvc->registerArea("admin");
    $mvc->registerRoute("default", "{controller}/{action}/{id}", array("{controller}" => "home", "{action}" => "index", "{id}" => null));
    $mvc->registerRoute("default_admin", "{area}/{controller}/{action}/{id}", array("{controller}" => "home", "{action}" => "index", "{id}" => null));
    $mvc->execute("home/indexWithModel"); 
?>