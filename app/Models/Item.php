<?php


namespace App\Models;


/**
 * Class Item
 * @package App\Models
 */
class Item {
    /**
     * @var string
     */
    protected static $table_name = "items";
    /**
     * @var int
     */
    protected $id = 0;
    /**
     * @var
     */
    protected $user_id;
    /**
     * @var
     */
    protected $cat_id;
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $price;
    /**
     * @var
     */
    protected $description;
    /**
     * @var
     */
    protected $date;
    /**
     * @var int
     */
    protected $notified = 0;

    /**
     * Item constructor.
     *
     * @param $user_id
     * @param $cat_id
     * @param $name
     * @param $price
     * @param $description
     * @param $date
     */
    public function __construct($user_id, $cat_id, $name, $price, $description, $date) {
        $this->user_id = $user_id;
        $this->cat_id = $cat_id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->date = $date;
    }
}