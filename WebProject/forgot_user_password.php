<?php
include_once 'util.php';
include_once 'menu.php';
if (!isset($_SESSION)){
    my_session_start();
}
?>
<html>
<head>
    <title>Password Reset</title>
    <style type="text/css">
        .w225p{width: 225px;}
    </style>
</head>

<body>
<div class="container">
    <div class="medium-8 column">
        <br><br><br><br><br><br>
        <section>
            <form id="add_user_profile_form" name="add_user_profile_form" method="post" action="forgot_user_password_mediator.php" enctype="multipart/form-data">
                <fieldset>
                    <legend><strong>Forgot your password?</strong></legend>

                    <?php
                    if (isset($_SESSION["forgot_password_message"])) {
                        echo "<font color=red>".$_SESSION["forgot_password_message"]."</font>";
                    }
                    ?>
                    <div class="small-12 column mrt15">
                        <input  type="email" autocomplete="off" type="text" id="user_email_address" name="user_email_address" required placeholder = "Email Address" value =""/>
                    </div>
                    <div class="small-12 column mrt15">
                        <button class="secondary hollow button" type="submit" name="password_change_submit">
                            <span>Request Change Password</span>
                        </button>
                        <?php
                        if (isset($_SESSION["incorrect_email"])) {
                            echo '<a href="add_user_profile.php" style="float:right;margin-top: 9px"><u>Create New Account</u></a>';
                            my_session_unset();
                        }
                        ?>
                    </div>
                </fieldset>
            </form>
        </section>
    </div>
</div>
</body>
</html>