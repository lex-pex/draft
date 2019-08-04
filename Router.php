<?php

define('ROOT', __DIR__);
require_once(ROOT.'/controller/Controller.php');
use Controller\Controller;

// Global Helper Route Builder
function route(string $route, string $param = '') {
	echo BASE .'/'. trim($route, '/') . ($param ? '/' . $param : '');
}
// Middleware class matches routes to their actons
class Router {
	// Very Strart of App
	public function run() {
		$urn = $this->urn();
		if($urn == '/') {
			$path = $urn;
		} elseif (is_numeric($urn)) {
			$path = '/';
			$a[1] = $urn;
		} else {
			$a = explode('/', $urn);
			$path = $a[0];
		}
		$action = $this->routes[$path];
		if($action) {
			$p = $a[1] ? $a[1] : '';
			$this->call($action, $p);
		} else {
			Router::response_404();
		}
	}
    // Calls actual action on controller
	private function call(string $action, string $id) {
		$c = new Controller();
		call_user_func_array([$c, $action], [$id]);
	}
    // Distinguishes base url from local resource name
	private function urn() {
		$b = trim(BASE, '/');
		$root = $this->getRootURN();
		if ($root == $b)
			return '/';
		return str_replace(($b.'/'), '', $root);
	}
    // Returns base url separated by slash 
	private function getRootURN() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    private $routes = [ 
    	'pager' => 'pager', // get (int param) 
    	 // Articles RESTfull 
    	'/' => 'index',         // GET + 
    	'create' => 'create',   // GET 
    	'store' => 'store',     // POST 
    	'show' => 'show',       // GET (param) + 
    	'edit' => 'edit',       // GET (param) 
    	'update' => 'update',   // POST (param) 
    	'destroy' => 'destroy', // POST (param) 
		 // Comments RESTfull 
    	'commentsIndex' => 'commentsIndex',   // post (int param) 
    	'commentStore' => 'commentStore',     // post (int param) 
    	'commentEdit' => 'commentEdit',       // GET (param) 
    	'commentUpdate' => 'commentUpdate',   // POST (param) 
    	'commentDestroy' => 'commentDestroy', // POST (param) 
    ];

    // Error Redirect for page 404
    public static function response_404(){
    	http_response_code(404);
		include(ROOT . '/view/404.php');
		exit();
    }
}






















