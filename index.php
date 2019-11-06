<?php

define('ROOT', __DIR__);
define('BASE', '/blog');
require_once(ROOT.'/Router.php');
$r = new Router();
$r->run();
