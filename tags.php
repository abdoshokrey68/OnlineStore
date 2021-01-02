<?php
    ob_start();
    session_start();

    $pageTitle  = 'Tag items';
    include 'init.php';

    if (isset($_GET['tagname'])) {
    $tagname = filter_var(str_replace(' ', '', $_GET['tagname']), FILTER_SANITIZE_STRING);
?>
<div class="container">
    <h1 class=""> <?php echo $tagname ?> </h1>
    <div class="row">
            <?php 
        $itemstag = Allitemfrom('*', 'items', 'itemID',"WHERE tags like '%$tagname%' AND Pending = 1");
        foreach ($itemstag as $item) {
            if (!empty($item)) {
                echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box">';
                echo '<span class="price-tag">'. $item['Price'] .'</span>';
                echo '<img src="user.jpg" alt="..." class="">';
                echo '<div class="caption">'; 
                echo '<h4 class="name-tag"><a href="items.php?itemid='. $item['itemID'] .'">'. $item['Name'] .'</h4>';
                echo '</div>'; 
                echo '</div>';
                echo '</div>';
            } else {
                echo 'error';
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

    
    
    /*    
    $itemsid = array();
$tags = Allitemfrom ('*', 'items');
    foreach ($tags as $tag) {
        if(!empty($tag['tags'])) {
            $mytags = explode(',' , $tag['tags']);
            print_r($mytags);
            echo $tagname . ' <br>';
            if (in_array($tagname, $mytags)) {
                echo $tag['itemID'] . '<br>';
                $itemsid[] = $tag['itemID'];
            } else {
                echo 'Not Exist';
            }
        }
    }
    print_r($itemsid);*/
?>

