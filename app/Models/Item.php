<?php


namespace App\Models;


use App\Lib\Model;

/**
 * Class Item
 * @package App\Models
 */
class Item extends Model {
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
     * @var array Images of the item
     */
    protected $imageObjs = [];

    /**
     * @var array Bids on the item
     */
    protected $bidObjs = [];

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

    public function getImages() {
        $this->imageObjs = Image::find(["item_id" => $this->id]);
        return $this->imageObjs;
    }

    public function getBids() {
        $this->bidObjs = Bid::find(["item_id" => $this->id], null, "amount DESC");
        return $this->bidObjs;
    }
}