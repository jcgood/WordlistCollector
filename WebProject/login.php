<?php
include_once 'util.php';
include_once 'menu.php';
if (!isset($_SESSION)){
	my_session_start();
}
?>
<html>
<head>
	<title>Account Login - Access Database</title>
	<style type="text/css">
		.w225p{width: 225px;}
	</style>
</head>

<body>
	
	<div class="container">
		<br><br><br><br><br><br>
        <div class="banner_content text-center row">
            <div style="width: 50%;">
				<form id="login_form" name="login_form" method="post" action="login_mediator.php" enctype="multipart/form-data">
					<div class="login_section" style="height:394;">
						<fieldset>
							<h5><strong>Login to your EXISTING ACCOUNT</strong><br><br></h5>
							<?php
                                if (isset($_SESSION["login_message"])) {
                                    echo "<font color=red>".$_SESSION["login_message"]."</font>";
                                }
                            ?>
                            <?php
                                if (isset($_SESSION["login_message_success"])) {
                            		echo "<font color=green>".$_SESSION["login_message_success"]."</font>";
                                }
                            ?>
							<div class="large-12 column mrt15">
								<input  type="email" autocomplete="off" id="user_email_address" name="user_email_address" placeholder="Email Address" required maxlength="100" class="w225p">
							</div>
							<div class="large-12 column mrt15">
								<input  autocomplete="off" type="password" id="user_password" name="user_password" placeholder="Password" required maxlength="255" minlength="1" class="w225p">
							</div>
							<div class="large-12 column mrt15">
								<button class="primary button" type="submit" name="login_submit">
									<span>Login </span>
								</button>&nbsp;&nbsp;
								<a href="forgot_user_password.php" style="margin-top: 9px"><u>Forgot Password?</u></a>
							</div>
						</fieldset>

					</div>
				</form>
			</div>
        	<div style="width: 50%;">
				<form id="add_user_profile_form" name="add_user_profile_form" method="post" action="user_profile_mediator.php" enctype="multipart/form-data">
					<div class="signin_section" style="height:394;">
						<fieldset>
							<h5><strong>Create your NEW ACCOUNT</strong><br><br></h5>
							<?php
                                if (isset($_SESSION["add_user_profile"])) {
                                	if ($_SESSION["add_user_profile_status"] == "success"){
                                		echo "<font color=green>".$_SESSION["add_user_profile"]."</font>";
                                	}
                                	else{
                                		echo "<font color=red>".$_SESSION["add_user_profile"]."</font>";
                                	}
                                }
                            ?>
                            <div class="large-12 column mrt15">
								<input  autocomplete="off" type="text" id="first_name" name="first_name" placeholder="First Name" required maxlength="100" class="w225p"/>
							</div>
							<input type="hidden" name="form_action" id="form_action" value="register_user">
							<div class="large-12 column mrt15">
								<input  autocomplete="off" type="text" id="last_name" name="last_name" placeholder="Last Name" required maxlength="100" class="w225p"/>
							</div>
							<div class="large-12 column mrt15">
								<input  autocomplete="off" type="email" id="user_email_address" name="user_email_address" placeholder="Email Address" required maxlength="255" class="w225p"/>
							</div>
							<div class="large-12 column mrt15">
								<input  autocomplete="off" type="password" id="user_password" name="user_password" placeholder="Password" required maxlength="255" minlength="1" class="w225p"/>
							</div>
							<div class="large-12 column mrt15">
								<input  autocomplete="off" type="password" id="user_confirm_password" name="user_confirm_password" placeholder="Confirm Password" required maxlength="255" minlength="1" class="w225p"/>
							</div>
							<div class="large-12 column mrt15">
								<button class="primary button" type="submit" name="login_submit">
									<span>Register</span>
								</button>
							</div>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	var dialog_open = false;
	$('#add_user_profile_form').submit(function(event){
      // manual checks
    });
</script>