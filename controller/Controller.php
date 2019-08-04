<?php
namespace Controller;
require_once(ROOT.'/model/Article.php');
require_once(ROOT.'/model/Comment.php');
use Model\Article;
use Model\Comment;
use Router;


class Controller {

	public function pager(){
		$articles = Article::all();
		require_once ROOT.'/view/index.php';
		return true;
	}

	public function index($page = '') {
		if($page === '') $page = 0;
		if(!is_numeric($page)) Router::response_404();
		$p = ($page < 1) ? 0: ($page - 1);
		$limit = 6;
		$offset = $limit * $p;

		$nextPage = $p + 2;
		$prevPage = $p ? $p : 1;

		$articles = Article::chunk($offset, $limit);
		if(!$articles) {
			$a = new Article(['There are not more articles', 'Try previous page']);
			$a->id = 0;
			$articles[] = $a;
		}
        require_once ROOT.'/view/index.php';
        return true;
	}

	public function create() {
		include_once ROOT.'/view/create.php';
		return;
	}

	public function store() {

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$title = $_POST['title'];
	        $text = $_POST['text'];
	        $errors = false;
	        if($e = $this->validateTitle($title))
				$errors[] = $e;
			if ($e = $this->validateText($text))
				$errors[] = $e;
			if($errors) {
				include_once ROOT.'/view/create.php';
				return;
			} else {
				$a = new Article([$title, $text]);
				$a = $a->save();
				header('Location: /blog/show/' . $a->id);
				return;
			}
		}
		include_once ROOT.'/view/create.php';
	}

	public function show(string $id) {
		if(!is_numeric($id)) Router::response_404();
		$article = Article::find($id);
        require_once ROOT.'/view/show.php';
        return true;
	}

	public function edit(string $id) {
		if(!is_numeric($id)) Router::response_404();
		if(!$a = Article::find($id)) Router::response_404();
		require_once ROOT.'/view/edit.php';
        return true;
	}

	public function update() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$errors = false;
			$id = $_POST['id'];
			if(!$a = Article::find($id)) Router::response_404();
			$title = $_POST['title'];
	        $text = $_POST['text'];
	        if($e = $this->validateTitle($title))
				$errors[] = $e;
			if ($e = $this->validateText($text))
				$errors[] = $e;
			if($errors) {
				include_once ROOT.'/view/edit.php';
				return;
			} else {
				$a->title = $title;
				$a->text = $text;
				$a = $a->save();
				header('Location: /blog/show/' . $a->id);
				return;
			}
		}
		Router::response_404();
	}

	public function destroy() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $_POST['id'];
			if(!$a = Article::find($id)) Router::response_404();
			$a->delete();
			header('Location: /blog/');
			return;
		}
	}

	// Title validation
	private function validateTitle(string $title) : string {
		if(strlen($title) < 3 || strlen($title) > 50) {
			return 'TITLE has to be more than 3 character and less 50';
		}
		return '';
	}

	// Text validation
	private function validateText(string $text) : string {
		if(strlen($text) < 50 || strlen($text) > 250) {
			return 'TEXT has to be more than 50 character and less 250';
		}
		return '';
	}

	/**
	 *  ______________ Comments Controller Block ______________ 
	 */
	public function commentsIndex() {
		$id = $_POST['id'];
		$comments = Comment::where(['post_id' => $id]);
		echo json_encode($comments);
	}

	public function commentStore() {
		$post_id = $_POST['post_id'];
		$text = $_POST['text'];
		$comment = new Comment([$post_id, $text]);
		$comment->save();
	}

	public function commentEdit() {
		$id = $_POST['id'];
		$c = Comment::find($id);
		echo json_encode($c);
	}

	public function commentUpdate() {
		$id = $_POST['id'];
		$text = $_POST['text'];
		$c = Comment::find($id);
		$c->text = $text;
		$c->save();
	}

	public function commentDestroy($id) {
		$id = $_POST['id'];
		echo Comment::destroy($id);
	}

}











