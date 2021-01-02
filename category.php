<?php
    ob_start();
    session_start();
    $pageid     = $_GET['pageid'];
    $pagename   = str_replace('-', ' ', $_GET['pagename']);
    $pageTitle  = $pagename;
    include 'init.php'; 

    $checkname  = '';
    $checkid    = '';

    if (isset($_GET['pageid']) && isset($_GET['pagename'])) {
        $checkname = checkitem('category', 'Name', $pagename);
        $checkid = checkitem('category', 'ID', $pageid);
    }
    $checkparent = checkitem('category', 'parent', 0);
if ($checkname == 1 && $checkid == 1 && $checkparent > 1){
?>
<div class="container category-page">
    <h1 class=""> <?php echo $pagename ?> </h1>
    <div class="row">
            <?php 
        $allitem = Allitemfrom('*', 'items', 'itemID',"WHERE Pending = 1 AND Cat_ID = $pageid");
        foreach ($allitem as $item) {
            if (!empty($item)) {
                echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box">';
                echo '<span class="price-tag">'. $item['Price'] .'</span>';
                if (empty($item['image'])) {
                    echo '<img src="uploaded/items/item_def.png" alt="..." class="item-image">';
                } else {
                    ?>
                        <img src="uploaded/items/<?php echo $item['image'] ?>" alt="item image" class="item-image">
                    <?php
                }
                echo '<div class="caption">'; 
                echo '<h4 class="name-tag"><a href="items.php?itemid='. $item['itemID'] .'">'. $item['Name'] .'</h4>';
                echo '</div>'; 
                echo '</div>';
                echo '</div>';
            } else {
                echo '<h3> Add A New Item In This Category <a href="additem.php"> From Here <a></h3>';
            }
        }
        ?>
    </div>
</div>

<?php
} else {
    $msg = "This Page Is Not Found";
    msg($msg, 2, 'index.php');
}
    $temp .'footer.php'; 
    ob_end_flush();
?>