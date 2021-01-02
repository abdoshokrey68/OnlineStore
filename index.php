<?php
session_start();
$pageTitle = 'HomePage';
include 'init.php';

?>

<div class="container home-page">
        <h1>Online Story Home</h1>

        <?php

        $allcat = Allitemfrom('*', 'category');
        foreach ($allcat as $cat) {
                $catid = $cat['ID'];
                $allitem = Allitemfrom('*', 'items', 'itemID', "WHERE Pending = 1 AND Cat_ID = $catid");
                if (!empty($allitem)) {
                        echo '<div>';
                        echo '<h4 class="text-bold text-info"><a href="category.php?pageid=' . $cat['ID'] . '&pagename=' . str_replace('', '-', $cat['Name']) . '">' . $cat['Name'] . '</a></h4 class="text-bold text-info">';
                        echo '<hr class="main-hr">';

                        // Get All Items In This Category ID 
                        foreach ($allitem as $item) {
                                echo '<div class="col-sm-6 col-md-3 home-cont">';
                                echo '<div class="thumbnail item-box">';
                                echo '<span class="price-tag">' . $item['Price'] . '</span>';
                                if (empty($item['image'])) {
                                        echo '<img src="uploaded/items/item_def.png" alt="..." class="item-image">';
                                } else {
        ?>
                                        <img src="uploaded/items/<?php echo $item['image'] ?>" alt="item image" class="item-image">
        <?php
                                }
                                echo '<div class="caption">';
                                echo '<h4 class="name-tag"><a href="items.php?itemid=' . $item['itemID'] . '">' . $item['Name'] . '</a></h4>';
                                // echo '<p class="">'.  $item['Des'] .'</p>';
                                echo '<p class="pull-right leaden">' .  $item['Item_Date'] . '</p>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="clear"></div>';
                }
        }


        ?>
</div>

<?php include  $temp . "footer.php" ?>