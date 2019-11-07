<?php

use App\Exceptions\ClassException;
use App\Lib\Logger;
use App\Models\Bid;
use App\Models\Item;
use App\Models\User;

require_once(__DIR__ . "/../app/bootstrap.php");

$validid = pf_validate_number($_GET['id'], "redirect", CONFIG_URL);

try {
    $item = Item::findFirst(["id" => "$validid"]);
} catch (ClassException $e) {
    Logger::getLogger()->critical("Invalid Item: ", ["exception" => $e]);
    echo "Invalid Item";
    die();
}

$item->getBids();
$item->getImages();

if (isset($_GET['submit'])) {
    if (!is_numeric($_POST['bid'])) {
        header("Location: itemdetails.php?id=" . $validid . "&error=letter");
        die();
    }


    $validbid = false;

    if (count($item->get('bidObjs')) == 0) {
        if (intval($item->get('price')) <= intval($_POST['bid'])) {
            $validbid = true;
        }
    } else {
        if (intval(array_shift($item->get('bidObjs'))->get('amount')) < intval($_POST['bid'])) {
            $validbid = true;

        }
    }

    if (!$validbid) {
        header("Location: itemdetails.php?id=" . $validid);
        die();
    } else {
        $newBid = new Bid($item->get('id'), $_POST['bid'], $session->getUser()->get('id'));
        $newBid->create();
        die();
    }
}

require_once(__DIR__ . "/../app/Layouts/header.php");

$nowepoch = time();
$itemepoch = strtotime($item->get('date'));

$validAuction = false;
if ($nowepoch < $itemepoch) {
    $validAuction = true;
}

echo "<h1>" . $item->get('name') . "</h1>";
echo "<p>";

if (count($item->get('bidObjs')) == 0) {
    echo "<strong>This item has no bids</strong> - <strong>Starting Price</strong>: " . CONFIG_CURRENCY . sprintf('%.2f', $item->get('price'));
} else {
    $bids = $item->get('bidObjs');
    $highestBid = array_shift($bids);
    echo "<strong>Number Of Bids</strong>: " . count($item->get('bidObjs')) . " - <strong>Current Price</strong>: " . CONFIG_CURRENCY . sprintf('%.2f', $highestBid->get('amount'));
}

echo " - <strong>Auction ends</strong>: " . date("D jS F Y g.iA", $itemepoch);

echo "</p>";

$imgs = $item->get('imageObjs');
$img = array_shift($imgs);

if ($img == null) {
    echo "No Images";
} else {
    echo "<img src='imgs/{$img->get('name')}' width='200'>";
}

echo "<p>" . nl2br($item->get('description')) . "</p>";
echo "<a name='bidbox'></a>";
echo "<h2>Bid for this item</h2>";

if (!$session->isLoggedIn()) {
    echo "To bid, you need ot log in. Login <a href='login.php?id=" . $validid . "&ref=addbid'>here</a>";
} else {
    if ($validAuction) {
        echo "Enter the bid amount into the box below.";
        echo "<p>";
        if (isset($_GET['error'])) {
            try {
                $errorMsg = Item::displayError($_GET['error']);
            } catch (ClassException $e) {
                Logger::getLogger()->error("Invalid error code: ", ['exception' => $e]);
                die();
            }
            echo $errorMsg;
        }
        ?>

        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <table>
                <tr>
                    <td><input type="number" name="bid"/></td>
                    <td><input type="submit" name="submit" id="submit" value="Bid!"/></td>
                </tr>
            </table>
        </form>

        <?php
    } else {
        echo "This auction has now ended.";
    }

    if (count($item->get('bidObjs')) > 0) {
        echo "<h2>Bid History</h2>";
        echo "<ul>";
        $bids = $item->get('bidObjs');
        foreach ($bids as $bid) {
            $id = $bid->get('user_id');
            try {
                $user = User::findFirst(["id" => "$id"]);
            } catch (ClassException $e) {
                Logger::getLogger()->critical("Invalid User: ", ['exception' => $e]);
                echo "Invalid User";
                die();
            }

            echo "<li>{$user->get('username')}  - " . CONFIG_CURRENCY . sprintf('%.2f', $bid->get('amount')) . "</li>";
        }

        echo "</ul>";
    }
}

require_once(__DIR__ . "/../app/Layouts/footer.php");

?>