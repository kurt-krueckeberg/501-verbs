<?php
//require_once("./SplClassLoader.php");
require_once("./boot-strap/SplClassLoader.php");

function boot_strap()
{
  $spl_loader = new SplClassLoader('App', 'src');

  $spl_loader->setFileExtension('.php');
  $spl_loader->register();
}
