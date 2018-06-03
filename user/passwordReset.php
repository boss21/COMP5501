<?php

// Include config file
require_once '../dbconfig.php';

if($_GET['email'] && $_GET['hash']){
    $email = $_GET['email'];
    $hash = $_GET['hash'];

    $sql = "SELECT email, hash FROM users WHERE email = '".$email."' AND hash = '".$hash."'";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) == 1){
        //HTML START
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
            <title>The Financial Wizard</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.css">
        </head>
        <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 text-center">
                    <br>
                    <a href="../index.php"><img class="img-fluid" src="../images/logo.png" alt="TFW"></a>
                    <br>
                    <br>
                    <form action="passReset.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                        <input type="hidden" name="hash" value="<?php echo $hash; ?>">
                        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <label>New Password:</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                            <span class="help-block">
                                <?php echo $password_err; ?>
                            </span>
                        </div>
                        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <label>Confirm Password:</label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                            <span class="help-block">
                                <?php echo $confirm_password_err; ?>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-sm-4"></div>
            </div>
        </div>
        </body>
        </html>
        <?php
        //HTML END
    }
    mysqli_free_result($result);
    mysqli_close($link);
} else{
    echo "<script type='text/javascript'>alert('Invalid approach, please use the link that was sent to your email address.');window.location='../index.php';</script>";
}
?>