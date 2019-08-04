<?php
namespace Model;
require_once(ROOT . '/model/Model.php');

use Model\Model;

class Comment extends Model {
    public $table = 'comments';
    public $fields = ['post_id', 'text'];
}
