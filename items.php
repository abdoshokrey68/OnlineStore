<?php

ob_start();
session_start();
$pageTitle = 'Item';
include 'init.php';
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

$stmt = $dbconnect->prepare("SELECT items.*, category.Name AS CatName, mylogin.FullName FROM items
                                INNER JOIN mylogin ON mylogin.UserID = items.MemberID
                                INNER JOIN category ON category.ID = items.Cat_ID  WHERE itemID = ?");

$stmt->execute(array($itemid));

$item = $stmt->fetch(PDO::FETCH_ASSOC);

$count = $stmt->rowCount();

if ($item['Pending'] == 1 ) {

if ($count == 1) {

?>
    <!--  Start Items Show ***************************************** -->
    <div class="show-item">
        <div class="container">
            <h1 class=""><?php echo $item['Name'] ?></h1>
            <div class="row">
                <div class="my-item">
                    <div class="col-md-4 show-item-img">
                        <img src="user.jpg" alt="item image">
                    </div>
 
                    <?php
                    echo '<div class="col-md-8 show-item-info">';
                    echo '<h3 class="text-bold price-tag">$' . $item['Price'] . '</h3>';
                    echo '<div class="clear"> </div>';
                    echo '<h2 class="text-bold">Added By : <span class="text-uppercase"><a>' . $item['FullName'] . '</a></span></h2>';
                    echo '<p class="leaden">' . $item['Item_Date'] . '</p>';


                    echo '<h4 class="des-tag col-md-12 cont-info"><span class="col-md-3">  Item Descreption : </span> <span class="col-md-9 text-box">'. $item['Des'] . '</span></h4>';

                    echo '<h4 class="col-md-12"><span class="col-md-3"> Made In :</span> <span class="col-md-9>">' . $item['Made'] . 'test</span></h4>';
                    if (!empty($item['tags'])) {
                        $itemtags = explode(',', $item['tags']);
                        echo '<h4 class="col-md-12"> <span class="col-md-3"> Item Tags : </span> <span class="col-md-9">';
                        foreach ($itemtags as $tags) {
                            $tag = str_replace(' ', '', $tags);
                            echo '<a href="tags.php?tagname='. $tag .'" class="tag-tag">'.$tag . '</a>';
                        }
                        echo '</span><h4>';
                    }

                    echo '<h4 class="col-md-12"><span class="col-md-3"> Category : </span> <span class="col-md-9"> <a href="category.php?pageid=' . $item['Cat_ID'] . '&pagename=' . str_replace(' ', '-', $item['CatName']) . '">' . $item['CatName'] . '</a></span></h4>';
                    
                    echo '</div>';
                    ?>

                </div>
            </div>
            <!--  End Items Show ***************************************** -->
            <div class="comment-box">
                <div class="row">
                    <div class="add-comment">
                        <div class="my-comments">
                            <h4 class="header-tag"> Comments Members </h4>
                            <?php
                            $itemid = $item['itemID'];
                            $gtstmt = $dbconnect->prepare("SELECT comment.*, mylogin.Username FROM comment 
                            INNER JOIN mylogin ON mylogin.UserID = comment.member_ID WHERE item_ID = ? ");
                            $gtstmt->execute(array($itemid));
                            $comments = $gtstmt->fetchAll(PDO::FETCH_ASSOC);

                            echo '<h4 class="h4-comment">';
                            foreach ($comments as $comment) {
                                ?>
                                <div class="row">
                                    <div class="old-comments">
                                        <div class="comment-only">
                                            <div class="col-md-3 col-xs-3">
                                                <img src="user.jpg" class="user-img">
                                                <span><?php echo $comment['Username'] ?> : </span>
                                            </div>
                                            <div class="col-md-9 col-xs-9 comment-select">
                                                <p>
                                                <?php echo $comment['Comment'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                            echo '</h4>';
                            ?>
                        </div>
                    </div>
                </div>
                <?php if (isset($_SESSION['user'])) {
                ?>
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['itemID'] ?>" class="col-md-offset-4 col-md-8" method="post">
                        <h4 class="header-tag">Add Your Comment </h4>
                        <input type="hidden" name="itemid" value="<?php echo $item['itemID'] ?>">
                        <input type="hidden" name="memberid" value="<?php echo $item['MemberID'] ?>">
                        <input pattern=".{4,}" title="The Comment Is Small" type="text" name="comment" class="col-md-12 input-comment" placeholder="Input Your Comment Here.. " required>
                        <input type="submit" class="btn btn-success col-md-offset-10 col-md-2" value="Send">
                    </form>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $itemid         = $_POST['itemid'];
                        $memberid       = $_POST['memberid'];
                        $comment        = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

                        $cstmt = $dbconnect->prepare("INSERT INTO comment(Comment, c_date, Status, item_ID, member_ID)
                                                        VALUES(:zcomment, now(), 0, :zitem, :zmember)");

                        $cstmt->execute(array(
                            'zcomment'      => $comment,
                            'zitem'         => $itemid,
                            'zmember'       => $memberid
                        ));

                        $row = $cstmt->rowCount();
                        header("location: items.php?itemid=$itemid");
                    }
                } else {
                    echo '<h4 class="col-md-offset-4 col-md-8"> <a href="login.php"> login & Register </a> To Can Comment  </h4>';
                }
                ?>
            </div>
        </div>
    </div>



<?php
    } else {
        $msg = 'You Have Error In Page Plece Try Again';
        msg($msg, 5);
    } 
} else {
    $msg = 'This Item Is Not Activation Wait for the admin to activate it';
    msg($msg, 5);
    
}
ob_end_flush();

?>