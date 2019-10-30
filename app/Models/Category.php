<?php

namespace App\Models;

/**
 * Class Category
 * @package App\Models
 */
class Category {
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