<?php


namespace App\Models;

use App\Lib\Model;

/**
 * Class Payment
 * @package App\Models
 */
class Payment extends Model {

    /**
     * @var string
     */
    protected static $table_name = "payments";
    /**
     * @var
     */
    protected $id;
    /**
     * @var
     */
    protected $txn_id;
    /**
     * @var
     */
    protected $mc_gross;
    /**
     * @var
     */
    protected $payment_status;
    /**
     * @var
     */
    protected $item_number;
    /**
     * @var
     */
    protected $item_name;
    /**
     * @var
     */
    protected $payer_id;
    /**
     * @var
     */
    protected $payer_email;
    /**
     * @var
     */
    protected $first_name;
    /**
     * @var
     */
    protected $last_name;
    /**
     * @var
     */
    protected $address_street;
    /**
     * @var
     */
    protected $address_city;
    /**
     * @var
     */
    protected $address_state;
    /**
     * @var
     */
    protected $address_zip;
    /**
     * @var
     */
    protected $address_country;
    /**
     * @var
     */
    protected $payment_date;

    /**
     * Payment constructor.
     *
     * @param $txn_id
     * @param $mc_gross
     * @param $payment_status
     * @param $item_number
     * @param $item_name
     * @param $payer_id
     * @param $payer_email
     * @param $first_name
     * @param $last_name
     * @param $address_street
     * @param $address_city
     * @param $address_state
     * @param $address_zip
     * @param $address_country
     * @param $payment_date
     */
    public function __construct($txn_id, $mc_gross, $payment_status, $item_number, $item_name, $payer_id, $payer_email, $first_name, $last_name, $address_street, $address_city, $address_state, $address_zip, $address_country, $payment_date) {
        $this->txn_id = $txn_id;
        $this->mc_gross = $mc_gross;
        $this->payment_status = $payment_status;
        $this->item_number = $item_number;
        $this->item_name = $item_name;
        $this->payer_id = $payer_id;
        $this->payer_email = $payer_email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->address_street = $address_street;
        $this->address_city = $address_city;
        $this->address_state = $address_state;
        $this->address_zip = $address_zip;
        $this->address_country = $address_country;
        $this->payment_date = $payment_date;
    }

    public static function generatePayment(int $id): string {
        $url = CONFIG_URL . "/payment.php?id=$id";
        $PayPalButton = <<<HEREDOC_
<a href="{$url}">
<img src="https://www.paypalobjects.com/en-US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online" border="0">
</a>
HEREDOC_;

        return $PayPalButton;
    }
}