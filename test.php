<?php 

include 'init.php';

class myclass {
    public $iphone = 10;
    public $Status = 'Good';
    public $space = '4GB';
}

$myiphone1 = new myclass();
$myiphone2 = new myclass();
$myiphone3 = new myclass();

echo '<pre>';
print_r($myiphone1);
echo '</pre>';

echo '<pre>';
var_dump($myiphone2);
echo '</pre>';

echo '<pre>';
var_dump($myiphone3);
echo '</pre>';

?>
<div class="container">
<div class="row">
<form action="?do=test" method="post" class="col-md-12" enctype="multipart/form-data">
    <input type="text" name="username" class="col-md-12">
    <input type="text" name="message" class='col-md-12'>
    <input type="submit" value="submit" class='col-md-3 pull-right'>
    
</form>
</div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $message = strtoupper($_POST['message']);
    $email = 'abdoshokrey111@gmail.com';
    mail($email, $name, $message);
}
?>