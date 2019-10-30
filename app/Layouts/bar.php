<?php

use App\EXCEPTIONS\ClassException;
use App\Lib\Logger;
use App\Models\Category;

//Retrieve categories as objects
try {
    $catObjs = Category::all('cat');
} catch (ClassException $e) {
    Logger::getLogger()->critical("No results returned: ", ['exception' => $e]);
    echo "No results returned";
    die();
}
?>
<h1>Categories</h1>
<ul>
    <li><a href='index.php'>View All</a></li>

    <?php
    //Iterate over each category obj & output the name
    foreach ($catObjs as $catObj): ?>
        <li>
            <a href="index.php?id=<?php echo $catObj->get('id') ?>">
                <?php echo $catObj->get('cat') ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>