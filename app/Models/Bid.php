<?php

namespace App\Models;

/**
 * Class Bid
 * @package App\Models
 */
class Bid {
    /**
     * @var string
     */
    protected static $table_name = "bids";
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
    protected $amount;
    /**
     * @var
     */
    protected $user_id;

    /**
     * Bid constructor.
     *
     * @param $item_id
     * @param $amount
     * @param $user_id
     */
    public function __construct($item_id, $amount, $user_id) {
        $this->item_id = $item_id;
        $this->amount = $amount;
        $this->user_id = $user_id;
    }
}