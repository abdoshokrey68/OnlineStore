<?php

ob_start();
session_start();
$pageTitle = 'My Profile';
include 'init.php';

if (isset($_SESSION['user'])) {
    $getst = $dbconnect->prepare("SELECT * FROM mylogin WHERE Username = ?");
    $getst->execute(array($_SESSION['user']));
    $user = $getst->fetch(PDO::FETCH_ASSOC);

    if (isset($_GET['do'])) {
        if ($_GET['do'] == 'edit') {  
            $errors = array();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') { // start Update Page ************************************************************************************************
                $username       = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $opassword      = filter_var($_POST['oldpassword'], FILTER_SANITIZE_STRING);
                $password       = sha1($opassword);
                $email          = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $fullname       = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
                $p_img          = $_FILES['image'];
                $userid         = $_SESSION['uid'];

                $p_name = $p_img['name'];
                $p_size = $p_img['size'];
                $p_type = $p_img['type'];
                $p_tmp  = $p_img['tmp_name'];

                $theex  = array('jpeg', 'jpg', 'png', 'gif');
                $ex     = explode('.', $p_name);
                $my_ex  = strtolower(end($ex));

                $check = checkitem('mylogin', 'Username', $username, "AND UserID != $userid");


                if ($check > 0) {
                    $errors[] = 'This Username Is Exist Select Eny New Username ';
                }
                if (strlen($username) < 4) {
                    $errors[] = 'Your Username Shoud Be Toller Than 4 character';
                }
                if (strlen($password) < 8) {
                    $errors[] = 'Your Password Shoud Be Toller Than 8 character';
                }
                if (strlen($fullname) < 8) {
                    $errors[] = 'Your Password Shoud Be Toller Than 8 character';
                }
                if (!empty($p_name)) {
                    if (!in_array($my_ex, $theex)) {
                        $errors[] = 'This Is Not Image Check Him ';
                    }
                }
                if (empty($errors)) {
                    $prof_image = '';
                    if (!empty($p_name)) {
                        $prof_image = rand(0, 1000000000) . '_' . $p_name;

                        move_uploaded_file($p_tmp, 'uploaded/userimage/' . $prof_image);
                    }
                    $stmt = $dbconnect->prepare("UPDATE mylogin SET Username = ?, Password = ?, Email = ?, FullName = ?, image = ? WHERE UserID = ?");

                    $stmt->execute(array($username, $password, $email, $fullname, $prof_image, $userid));

                    $check_row = $stmt->rowCount();
                    if ($check_row > 0) {
                        $msg = "Update Profile Information Is Successful";
                        msg($msg, 3, 'profile.php');
                    }
                }
            }   // start Edit Page ************************************************************************************************
                $userid = $_SESSION['uid'];
                $stmt = $dbconnect->prepare("SELECT * From mylogin WHERE UserID = ? limit 1 ");
                $stmt->execute(array($userid));
                $row = $stmt->fetch();

                $check = $stmt->rowCount();

                if ($check > 0) {

?>
                    <div class="control-editor container edit-information">
                        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                            <h1 class="edit-users"> Edit Users </h1>
                            <div class="row">
                                <div class="panel panel-primary">
                                    <div class="panel-heading"> Edit My Information
                                    </div>
                                </div>
                                <div class="panel-body col-md-offset-2 col-md-8">
                                    <!-- Start Username Form -->
                                    <div class="myform">
                                        <p class="edit-title col-md-3"> User name </p>
                                        <div class="input-cunt">
                                            <input type="text" class="form-controler col-md-9" value="<?php echo $row['Username'] ?>" placeholder='Username ..' name="username" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <!-- End Username Form -->
                                    <div class="clear"></div>
                                    <!-- Start Password Form -->
                                    <div class="myform">
                                        <p class="edit-title col-md-3"> Password </p>
                                        <div class="input-cunt">
                                            <input type="password" class="form-controler showpass col-md-9" placeholder="Input Your password . . . " name="oldpassword" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <!-- End Password Form -->
                                    <div class="clear"></div>

                                    <!-- Start Email Form -->
                                    <div class="myform">
                                        <p class="edit-title col-md-3"> Email </p>
                                        <div class="input-cunt">
                                            <input type="text" class="form-controler col-md-9" value="<?php echo $row['Email']; ?>" placeholder='Email ..' name="email" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <!-- End Email Form -->
                                    <div class="clear"></div>

                                    <!-- Start Full name Form -->
                                    <div class="myform">
                                        <p class="edit-title col-md-3"> Full name </p>
                                        <div class="input-cunt">
                                            <input type="text" class="form-controler col-md-9" value="<?php echo $row['FullName'] ?>" placeholder='Full Name ..' name="fullname" required>
                                        </div>
                                    </div>
                                    <!-- End Full name Form -->
                                    <div class="clear"></div>

                                    <!-- Start Image Form -->
                                    <div class="myform">
                                        <p class="edit-title col-md-3"> Image </p>
                                        <div class="input-cunt">
                                            <input type="file" name="image" id="myomage" class="form-controler col-md-9">
                                        </div>
                                    </div>
                                    <!-- End Image Form -->
                                    <div class="clear"></div>

                                    <!-- Start submit Form -->
                                    <div class="myform">
                                        <div class="">
                                            <input type="submit" class="btn btn-info save-edit pull-right" value="Save">
                                        </div>
                                    </div>
                                    <!-- End submit Form -->
                                    <?php 
                                        if (!empty($errors)) {
                                            foreach ($errors as $error) {
                                                echo '<div class="col-md-12 alert alert-danger">'. $error .'</div>';
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>

        <?php

                } else {
                    $msg = "This is Not ID";
                    // msg($msg, 4, 'profile.php');
                }
        } else {
            header('location:profile.php');
        }
    } else {
        ?>
        <!-- start Profile Page ******************************************************************************************************** -->
        <div class="information block">
            <div class="container">
                <h1>My Profile</h1>
                <div class="panel panel-primary">
                    <div class="panel-heading"> My Information
                        <span class="pull-right edit-btn">
                            <a href="?do=edit" class="btn btn-dark"> Edit </a>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="img-box">
                            <!-- Image User ********************************** -->
                            <?php
                            if (empty($user['image'])) {
                                echo '<img src="uploaded/userimage/user.jpg" alt="Profile Image" class="profile-img">';
                            } else {
                            ?>
                                <img src='uploaded/userimage/<?php echo $user['image'] ?>' alt='Profile Image' class='profile-img'>
                            <?php
                            }
                            ?>

                        </div>
                        <ul>
                            <li class="col-lg-12">
                                <span class="col-lg-3"> Full Name : </span>
                                <span class="col-lg-6"> <?php echo $user['FullName'] ?> </span>
                            </li>
                            <li class="col-lg-12">
                                <span class="col-md-3"> Username : </span>
                                <span class="col-md-6"> <?php echo $user['Username'] ?> </span>
                            </li>
                            <li class="col-lg-12">
                                <span class="col-md-3"> Email : </span>
                                <span class="col-md-6"> <?php echo $user['Email'] ?> </span>
                            </li>
                            <li class="col-lg-12">
                                <span class="col-md-3"> Register Date : </span>
                                <span class="col-md-6"> <?php echo $user['Date'] ?> </span>
                            </li>
                            <li class="col-lg-12">
                                <span class="col-md-3"> Favorite Category : </span>
                                <span class="col-md-6"> <?php echo 'Category' ?> </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-ads block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"> My Items </div>
                    <div class="panel-body">

                        <?php
                        $id = $user['UserID'];
                        $items = getitems($id, 'MemberID');
                        if (empty($items)) {
                            echo '<div>';
                            echo '<h4>Your Items Is Empty <a href="additem.php"> Add Item </a> </h4>';
                            echo '</div';
                        } else {
                            foreach ($items as $item) {
                                echo '<div class="col-sm-6 col-md-3 the-item">';
                                echo '<div class="thumbnail item-box">';
                                echo '<span class="price-tag">' . $item['Price'] . '</span>';
                                if ($item['Pending'] == 0) {
                                    echo '<p class="pending-tag"> Awaiting Activation </p>';
                                }
                                if (empty($item['image'])) {
                                    echo '<img src="uploaded/items/item_def.png" alt="..." class="item-image">';
                                } else {
                        ?>
                                    <img src="uploaded/items/<?php echo $item['image'] ?>" alt="item image" class="item-image">
                        <?php
                                }
                                echo '<div class="caption">';
                                echo '<h4 class="name-tag"><a href="items.php?itemid=' . $item['itemID'] . '">' . $item['Name'] . '</a></h4>';
                                echo '<div class="">' . $item['Des'] . '</div>';
                                echo '<div class="pull-right text-bold">' . $item['Item_Date'] . '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="latest-comment block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"> Latest Comment </div>
                    <div class="panel-body">
                        <?php
                        $comments = mycomment($user['UserID']);
                        if (!empty($comments)) {
                            foreach ($comments as $comment) {
                                echo '<div class="panel panel-dark comments"><a href="items.php?itemid=' . $comment['item_ID'] . '">' . $comment['Comment'] . '</a></div>';
                            }
                        } else {
                            echo '<div class="panel panel-dark comments"> You Comments Is Empty </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
} else {
    header('location: index.php');
}

include 'inc/temp/footer.php';
ob_end_flush();
?>