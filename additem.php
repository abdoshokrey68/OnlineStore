<?php

ob_start();
session_start();
$pageTitle = 'Add Item';

include 'init.php';
if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $des        = filter_var($_POST['des'], FILTER_SANITIZE_STRING);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $made       = filter_var($_POST['made'], FILTER_SANITIZE_STRING);
        $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        $status     = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $cat        = filter_var($_POST['cat'], FILTER_SANITIZE_NUMBER_INT);
        $userid     = $_SESSION['uid'];
        $imageup    = $_FILES['image'];
        // img information 
        $imgname    = $imageup['name'];
        $imgtype    = $imageup['type'];
        $imgsize    = $imageup['size'];
        $imgtmp     = $imageup['tmp_name'];
        $the_ex     = array('jpeg', 'jpg', 'png', 'gif');
        $ex         = explode('.', $imgname);
        $myex       = strtolower(end($ex));

        $errormsg = array();

        if (strlen($name) < 4) {
            $errormsg[] = 'Your name Must Be Taller Than 4 Character';
        }
        if (strlen($price) < 1) {
            $errormsg[] = 'Your Price Is Not Correct Try Again';
        }
        if (empty($des)) {
            $errormsg[] = 'Your Descreption Not Have Value input Eny Value';
        }
        if ($cat == 0) {
            $errormsg[] = 'Select Item Category To Can Added';
        }
        if ($status == 0) {
            $errormsg[] = 'Select You item Status becose The Member Know him';
        }
        if (empty($imgname)) {
            $errormsg[] = 'Select Eny image To Your item';
        } elseif(!in_array($myex, $the_ex)) {
            $errormsg[] = 'This Not Image Select Eny Image';
        }

        // INSERT NEW ITEM INTO #DB
        if (empty($errormsg)) {
            $item_image = rand(0 , 1000000000000) . '_' . $imgname;

            move_uploaded_file($imgtmp, 'uploaded/items/' . $item_image);

            $nstmt = $dbconnect->prepare("INSERT INTO items (Name, Des, Price, tags, Item_Date, Status, Made, Cat_ID, MemberID, image)
                                        VALUES(:zname, :zdes, :zprice, :ztags, NOW(), :zstatus, :zmade, :zcat, :zmember, :zimage)");
            $nstmt->execute(array(
                'zname'         => $name, 
                'zdes'          => $des,
                'zprice'        => $price, 
                'ztags'         => $tags,
                'zstatus'       => $status, 
                'zmade'         => $made, 
                'zcat'          => $cat, 
                'zmember'       => $userid,
                'zimage'        => $item_image
            ));
            $check = $nstmt->rowCount();
            
            if ($check == 1) {
                
                $msg = ' Item Is Adding ';
                msg($msg);
                
            }
        }

    } else 
?>

    <div class="information add-item block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"> Item Information </div>
                <div class="panel-body">
                    <div class="row">
                        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="username" value="<?php echo $_SESSION['user'] ?>">
                        <div class="col-md-8">
                            <!-- Start Name Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Item Name </p>
                                <div class="input-cunt col-md-9 col-xm-12">
                                    <input type="text" class="form-controler name-new col-md-12 col-xm-12" placeholder='Item Name' name="name" required>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <!-- End Name Form -->

                            <!-- Start Description Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Description </p>
                                <div class="input-cunt col-md-9">
                                    <input type="text" class="form-controler des-new col-md-12" placeholder='Item Description' name="des" required>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End Description Form -->

                            <!-- Start Price Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Item Price </p>
                                <div class="input-cunt col-md-9">
                                    <input type="text" class="form-controler price-new col-md-12" placeholder='Item Price' name="price" required>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End Price Form -->

                            <!-- Start Made Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Country Made</p>
                                <div class="input-cunt col-md-9">
                                    <input type="text" class="form-controler col-md-12" placeholder='Country of manufacture Of The Item' name="made">
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End Made Form -->

                            <!-- Start image Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Image</p>
                                <div class="input-cunt col-md-9">
                                    <input type="file" name="image" id="image" class="form-controler col-md-12">
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End image Form -->

                            <!-- Start Status Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Status </p>
                                <div class="input-cunt col-md-9">
                                    <select name="status" id="status" class="form-control select-box col-md-12" required>
                                        <option value="0">Select Your Item Status</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End Status Form -->

                            <!-- Start Category Form -->
                            <div class="myform">
                                <p class="edit-title col-md-3"> Category </p>
                                <div class="input-cunt col-md-9">
                                    <select name="cat" id="cat" class="form-control select-box" required>
                                        <option value="0">Select Your Category</option>
                                        <?php
                                        $rows = Allitemfrom('*', 'category');
                                        foreach ($rows as $row) {
                                            echo '<option value=" ' . $row['ID'] . ' " > ' . $row['Name'] . ' </option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End Category Form -->
                            <!-- Start Tags Form -->
                            <div class="myform">
                            <p class="edit-title col-md-3"> Tags </p>
                            <div class="input-cunt col-md-9">
                                <input 
                                    type="text" 
                                    class="form-controler col-md-12" 
                                    placeholder='Separate the crown with a sign (,)' 
                                    name="tags"
                                    >
                            </div>
                            </div>
                            <div class="clear"></div>
                            <!-- End Tags Form -->
                            <!-- Start Submit Form -->
                            <div class="clear"></div>
                            <div class="myform">
                                <div class="col-md-offset-3 col-md-9 ">
                                    <input type="submit" class="btn btn-success pull-right save-edit" value="Add New Item">
                                </div>
                            </div>
                            <!-- End Submit Form -->
                            </form>

                        </div>
                        <div class="col-md-4">
                                <?php
                                    echo '<div class="col-sm-12 col-md-12">';
                                    echo '<div class="thumbnail item-box">';
                                    echo '<span class="price-tag price-live">0</span>';
                                    echo '<img src="user.jpg" alt="..." class="">';
                                    echo '<div class="caption">';
                                    echo '<h3 class="name-tag name-live">Item Name </h3>';
                                    echo '<h5 class="des-tag des-live"> Item Descreption </h5>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                ?>
                        </div>
                    </div>

                    <div>
                        <?php 
                            if (!empty($errormsg)) {
                                foreach($errormsg as $msges) {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo $msges;
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php

} else {
    header('location: index.php');
}

include 'inc/temp/footer.php';
ob_end_flush();
?>