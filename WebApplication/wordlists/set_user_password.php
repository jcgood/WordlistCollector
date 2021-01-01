<?php
include_once 'util.php';
include_once 'menu.php';
if (!isset($_SESSION)){
    my_session_start();
}
unset($_SESSION['login_message']);
unset($_SESSION['login_message_success']);
unset($_SESSION['add_user_profile']);
?>
<html>
<head>
    <title>Set Your Password</title>
    <style type="text/css">
		.back_light_green{background: lightgreen;}
		.font_bolder{font-weight: bolder;}
		.font_size{font-size: 120%}
		.w225p{width: 225px;}
	</style>
</head>

<body>
<div class="container">
    <div class="medium-8 column">
        <br><br><br><br><br><br>
		<section>
			<form id="update_user_profile_form" name="update_user_profile_form" method="post" action="user_profile_mediator.php" enctype="multipart/form-data">
				<fieldset>
					<div class="row">
						<legend>
							<strong>A "one time" password has been sent to your email. You will receive the email within 100 seconds.<br>Please check for it, and once you receive it, fill out the below form to reset your password.</strong>
	                	</legend>
	                </div>
					
					<div id = 'mess'>
					</div>

					<div class="row">
						<div class="small-12 column">
							<label for="user_email_address">Email Address
								<large style="color:red;font-weight: bold;" > *</large><br>
								<input  type="email" readonly autocomplete="off" type="text" id="user_email_address" name="user_email_address" <?php if(isset($_SESSION["email"])) echo "value = ".$_SESSION["email"]; ?> placeholder="Email Address" required class="w225p"/>
							</label>
						</div>
					</div>
					<div class="row">
						<div class="small-12 column">
							<label for="user_one_time_password">One-time Password
								<large style="color:red;font-weight: bold;"> *</large><br>
								<input  autocomplete="off" type="text" id="user_one_time_password" name="user_one_time_password" placeholder="One-time Password" required class="w225p"/>
							</label>
							<p id="otperror"></p>
						</div>
					</div>
					<div class="row">
						<div class="small-12 column">
							<label for="user_new_password">New Password
								<large style="color:red;font-weight: bold;"> *</large><br>
								<input  autocomplete="off" type="password" id="user_new_password" name="user_new_password" placeholder="New Password" required class="w225p"/>
							</label>
							<p id="passerror"></p>
						</div>
					</div>
					<div class="row">
						<div class="small-12 column">
							<label for="user_confirm_password">Re-enter New Password
								<large style="color:red;font-weight: bold;"> *</large><br>
								<input  autocomplete="off" type="password" id="user_confirm_password" name="user_confirm_password" placeholder="Re-enter New Password" required class="w225p"/>
							</label>
							<p id="checkpasserror"></p>
						</div>
					</div>
					<div class="row">
						<div class="small-12 column">
						<button id ="submit_user_password_form" type="button" class="secondary hollow button">Submit
						</button>
						</div>
					</div>
					<input  type="hidden" id="session_otp" name="session_otp" <?php if(isset($_SESSION["user_one_time_password"])) echo "value = ".$_SESSION["user_one_time_password"]; ?> />
					<input type="hidden" name="form_action" id="form_action" value="update_user">
				</fieldset>
			</form>
		</section>
	</div>
</div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/2.9.0/jquery.serializejson.min.js"></script>
<script type="text/javascript">
function passSet(maindta) {
	var email = document.getElementById('user_email_address').value;
	var password = document.getElementById('user_new_password').value;
	var url = 'set_user_password_mediator.php';
	var form = $('<form action="' + url + '" method="post">' +
	'<input type="text" name="email" value="' + email + '" />' +
	'<input type="text" name="pwd" value="' + password + '" />' +
	'</form>');
	$('body').append(form);
	form.submit();  
}

function checkForm(){
	var one_time_password = $("#user_one_time_password").val();
	var new_password = $("#user_new_password").val();
	var confirm_password = $("#user_confirm_password").val();
	result = true;
	if (!new_password || new_password == ""){
		document.getElementById("user_new_password").style.borderColor = "#E34234";
		document.getElementById("passerror").innerHTML = "Password can not be empty!";
		document.getElementById("passerror").style.color = "red";
		result = false;
    }
    else{
		document.getElementById("user_new_password").style.borderColor = "#CACACA";
		document.getElementById("passerror").innerHTML = "";
    }

    if (!confirm_password || confirm_password == ""){
		document.getElementById("user_confirm_password").style.borderColor = "#E34234";
		document.getElementById("checkpasserror").innerHTML = "Password can not be empty!";
		document.getElementById("checkpasserror").style.color = "red";
		result = false;
    }
    else{
        document.getElementById("user_confirm_password").style.borderColor = "#CACACA";
        document.getElementById("checkpasserror").innerHTML = "";
    }

    if (!one_time_password || one_time_password == ""){
		document.getElementById("user_one_time_password").style.borderColor = "#E34234";
		document.getElementById("otperror").innerHTML = "One Time Password can not be empty!";
		document.getElementById("otperror").style.color = "red";
		result = false;
    }
    else{
    	if (one_time_password != $("#session_otp").val()){
			document.getElementById("user_one_time_password").style.borderColor = "#E34234";
			document.getElementById("otperror").innerHTML = "One Time Password is invalid!";
			document.getElementById("otperror").style.color = "red";
			result = false;
    	}
        else{
        	document.getElementById("user_one_time_password").style.borderColor = "#CACACA";
        	document.getElementById("otperror").innerHTML = "";
        }
    }

    if(new_password != confirm_password) {
        document.getElementById("user_new_password").style.borderColor = "#E34234";
        document.getElementById("user_confirm_password").style.borderColor = "#E34234";
        document.getElementById("checkpasserror").innerHTML = "Passwords do not match!";
        document.getElementById("checkpasserror").style.color = "red";
        result = false;
    }
    else{
		document.getElementById("user_new_password").style.borderColor = "#CACACA";
		document.getElementById("user_confirm_password").style.borderColor = "#CACACA";
    }

    return result;
}

$("#submit_user_password_form").on("click",function() {
	if(checkForm())
	{
		console.log("A");
		console.log($("#session_otp").val());
		$("#update_user_profile_form").submit();
	}
});
</script>