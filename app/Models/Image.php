<?php


namespace App\Models;


use App\Lib\Model;

/**
 * Class Image
 * @package App\Models
 */
class Image extends Model {
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
    protected $item_id;
    /**
     * @var
     */
    protected $name;

    /**
     * Image constructor.
     *
     * @param int $id
     * @param     $item_id
     * @param     $name
     */
    public function __construct($item_id, $name) {
        $this->item_id = $item_id;
        $this->name = $name;
    }
}