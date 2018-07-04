<?php
    include "mvc/azuul.php";

    $mvc = new Azuul();
    $mvc->registerArea("admin");
    $mvc->registerRoute("default", "{controller}/{action}/{id}", array("{controller}" => "home", "{action}" => "index", "{id}" => null));
    $mvc->registerRoute("default_admin", "{area}/{controller}/{action}/{id}", array("{controller}" => "home", "{action}" => "index", "{id}" => null));
    $mvc->execute($_GET['controller']."/".$_GET['action']); 
?>