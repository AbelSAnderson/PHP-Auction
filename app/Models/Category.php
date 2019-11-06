<?php

namespace App\Models;

use App\Lib\Model;

/**
 * Class Category
 * @package App\Models
 */
class Category extends Model {
    /**
     * @var string
     */
    protected static $table_name = "categories";
    /**
     * @var int
     */
    protected $id = 0;
    /**
     * @var
     */
    protected $cat;

    /**
     * Category constructor.
     *
     * @param $cat
     */
    public function __construct($cat) {
        $this->cat = $cat;
    }
}