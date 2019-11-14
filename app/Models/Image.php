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
    protected static $table_name = "images";
    /**
     * @var array
     */
    public static $errorArray = [
        "empty" => "You did not select anything",
        "nophoto" => "You did not select a photo to upload",
        "photoprob" => "There appears to be a problem with the photo your are uploading",
        "large" => "The photo you selected is too large",
        "invalid" => "The photo you selected is not a valid image file"
    ];
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