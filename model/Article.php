<?php
namespace Model;
require_once(ROOT . '/model/Model.php');

use Model\Model;

class Article extends Model {
    public $table = 'articles';
    public $fields = ['title', 'text'];
}
