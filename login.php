<?php
ob_start();
$pageTitle = 'Login & Register';
include 'init.php';
session_start();

if (isset($_SESSION['user'])) {
    header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username   = filter_var(($_POST['username']), FILTER_SANITIZE_STRING);
        $pass       = filter_var(($_POST['password']), FILTER_SANITIZE_STRING);
        $password   = sha1($pass);
        $stmt = $dbconnect->prepare("SELECT * FROM mylogin WHERE Username = ? AND Password = ?");
    
        $stmt->execute(array($username, $password));
    
        $getstmt = $stmt->fetch();

        $check = $stmt->rowCount();
    
        if ($check == 1) {
            $_SESSION['uid'] = $getstmt['UserID'];
            $_SESSION['user'] = $username;
            header('location: index.php');
            exit();
        } else {
            header('location: login.php');
        }
    } else {
        $full       = filter_var(($_POST['fullname']), FILTER_SANITIZE_STRING);
        $username   = filter_var(($_POST['username']), FILTER_SANITIZE_STRING);
        $email      = filter_var(($_POST['email']), FILTER_VALIDATE_EMAIL);
        $pass       = filter_var(($_POST['password']), FILTER_SANITIZE_STRING);
        $password   = sha1($pass);

        $errormsg = array();

        if (empty($username)) {
            $errormsg[] = 'Can\'t Take Username Empty ' ;
        } 
        if (strlen($username) < 4) {
            $errormsg[] = 'Your Username is small ' ;
        }
        if (empty($email)) {
            $errormsg[] = 'Can\'t Take Email Empty ' ;
        } 
        if (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
            $errormsg[] = 'This Email Is Not Correct' ;
        } 
        if (empty($pass)) {
            $errormsg[] = 'Can\'t Take password Empty ' ;
        } 
        if (strlen($pass) < 8) {
            $errormsg[] = 'Your Password is Very Easy Try Again ' ;
        }

        $mycheck = checkitem('mylogin', 'Username', $username);

        if ($mycheck > 0) {
            $errormsg[] = 'Your Username Is Exist .. Try Eny Username Again';
        }

        if (empty($errormsg)) {
            $mystmt = $dbconnect->prepare("INSERT INTO `mylogin` (`Username`, `Password`, `Email`, `FullName`, `Date`)
                                            VALUES(:zuser, :zpass, :zemail, :zfull, now())");
            $mystmt->execute(array(
                'zuser'         => $username,
                'zpass'         => $password,
                'zemail'        => $email,
                'zfull'         => $full,
            ));

            
            echo $mystmt->rowCount();

            echo '<h4> Register Succssesfuly ... Pleace Login Now ! </h4>';
            $_SESSION['user'] = $username;

            $user = Allitemfrom('*', 'mylogin','', 'WHERE Username = ?');
            $_SESSION['uid'] = $user['UserID'];
            header("location:index.php");
        }
    }
}
?>
    <div class="container">
        <div class="row">
            <div class="templet login-page">
                <h2 >
                    <span class="login-title" data-class="registerd">
                        Register
                    </span>
                    <span> | </span>
                    <span class="login-title active" data-class="login-user">
                        Login
                    </span>
                </h2>
                    <form 
                            action="<?php echo $_SERVER['PHP_SELF'] ?>" 
                            class="login-form form-toggle login-user <?php  ?>"
                            method="post" 
                    >
                        <!-- Start Username -->
                        <div class="user-form">
                            <p class="col-md-3 title-input"> Username : </p>
                            <input 
                                type="text" 
                                name="username" 
                                class="form-control col-md-9" 
                                placeholder="Username" 
                                autocomplete="off" 
                                required>
                        </div>
                        <!-- End  Username -->

                        <div class="clear"></div>
                        <!-- Start Password -->
                        <div class="password-form">
                            <p class="col-md-3 title-input"> Password : </p>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-control col-md-12" 
                                placeholder="Password" 
                                autocomplete="new-password" 
                                required
                                >
                        </div>
                        <!-- End Password -->
                        <div class="clear"></div>

                        <!-- Start remember -->
                        <div class="forgit-form">
                            <a href="#">Forgit Password</a>
                            <a href="#">Forgit Username</a>
                        </div>
                        <!-- End remember -->
                        <div class="clear"></div>

                        <!-- Start Submit -->
                        <div class="Submit-form">
                            <br>
                            <input type="submit" name="login" class="btn btn-primary btn-block pull-right" value="Submit">
                        </div>
                        <!-- End Submit -->
                    </form>


                    <!-- Start Register page **************************** -->

                    <form 
                            action="<?php echo $_SERVER['PHP_SELF']  ?>" 
                            class="login-form form-toggle registerd <?php ?>"
                            method="post"
                    >
                        <!-- Start Full Name -->
                        <div class="user-form">
                            <p class="col-lg-3 title-input"> Full Name :</p>
                            <input pattern=".{10,}" title="Full Name Must Be taller Than 8 Caracters For Security" type="text" name="fullname" class="form-control col-md-9" placeholder="Enter Your Full Name" required>
                        </div>
                        <!-- End  Full Name -->
                        <div class="clear"></div>

                        <!-- Start Username -->
                        <div class="user-form">
                            <p class="col-lg-3 title-input"> Username : </p>
                            <input pattern=".{7,}" title="Username Must Be taller Than 7 Caracters" type="text" name="username" class="form-control col-md-9" placeholder="Username" required>
                        </div>
                        <!-- End  Username -->

                        <div class="clear"></div>
                        <!-- Start Username -->
                        <div class="user-form">
                            <p class="col-lg-3 title-input"> Email : </p>
                            <input type="email" name="email" class="form-control col-md-9" placeholder="email@example.com" required>
                        </div>
                        <!-- End  Username -->

                        <div class="clear"></div>
                        <!-- Start Password -->
                        <div class="password-form">
                            <p class="col-lg-3 title-input"> Password :</p>
                            <input pattern=".{8,}" title="Password Must Be taller Than 8 Caracters" type="password" name="password" class="form-control col-md-9" placeholder="Password" required>
                        </div>
                        <!-- End Password -->
                        <div class="clear"></div>

                        <!-- Start Submit -->
                        <div class="Submit-form">
                            <input type="submit" class="btn btn-primary col-md-3 btn-block" value="Submit">
                        </div>
                        <!-- End Submit -->
                    </form>
                    
                        <?php 
                            if (!empty($errormsg)) {
                                echo '<div class="error-msg">';
                                foreach ($errormsg as $msg) {
                                    echo '<div>'. $msg . '</div>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>



<?php
include $temp . 'footer.php';
ob_end_flush();
?>