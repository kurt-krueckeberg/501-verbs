<?php
require_once("./SplClassLoader.php");

function boot_strap()
{
  $spl_loader = new SplClassLoader('App', 'src');

  $spl_loader->setFileExtension('.php');
  $spl_loader->register();
}
