<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo CONFIG_AUCTIONNAME ?></title>
    <link rel="stylesheet" href="<?php echo CONFIG_URL ?>public/css/stylesheet.css" type="text/css">
</head>
<body>
<div id="header">
    <h1><?php echo CONFIG_AUCTIONNAME ?></h1>
</div>
<div id="menu">
    <a href="index.php">Home</a> &bull;
    <?php
    if ($session->isLoggedIn()) {
        echo "<a href='logout.php'>Logout</a> &bull;";
    } else {
        echo "<a href='login.php'>Login</a> &bull;";
    }
    ?>
    <a href="newitem.php">New Item</a> &bull;
    <a href="processauctions.php">Process Auction</a> &bull;
</div>
<div id="container">
    <div id="bar">
        <?php
        require_once("bar.php");
        ?>
    </div>
    <div id="main">
