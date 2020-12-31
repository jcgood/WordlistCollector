
function loadH() {
    // console.log("HTML is loaded");
}

function validateSetForm() {
	console.log("Validate");
  console.log($("#user_new_password").val())
    var pass1 = document.getElementById("user_new_password").value;
    var pass2 = document.getElementById("user_rnew_password").value;
    var otp = document.getElementById("user_one_time_password").value;

    let ok = true;

    if (!pass1 || pass1 == ""){
      document.getElementById("user_new_password").style.borderColor = "#E34234";
      document.getElementById("passerror").innerHTML = "Password can not be empty!";
      document.getElementById("passerror").style.color = "red";
      ok = false;
    }else{
      document.getElementById("user_new_password").style.borderColor = "#CACACA";
      document.getElementById("passerror").innerHTML = "";
    }

    if (!pass2 || pass2 == ""){
      document.getElementById("user_rnew_password").style.borderColor = "#E34234";
      document.getElementById("checkpasserror").innerHTML = "Password can not be empty!";
      document.getElementById("checkpasserror").style.color = "red";
      ok =  false;
    }else{
        document.getElementById("user_rnew_password").style.borderColor = "#CACACA";
        document.getElementById("checkpasserror").innerHTML = "";
    }

    if (!otp || otp == ""){
      document.getElementById("user_one_time_password").style.borderColor = "#E34234";
      document.getElementById("otperror").innerHTML = "One Time Password can not be empty!";
      document.getElementById("otperror").style.color = "red";
      ok =  false;
    }else{
        document.getElementById("user_one_time_password").style.borderColor = "#CACACA";
        document.getElementById("otperror").innerHTML = "";
    }

    if(pass1 != pass2) {
        document.getElementById("user_new_password").style.borderColor = "#E34234";
        document.getElementById("user_rnew_password").style.borderColor = "#E34234";
        document.getElementById("checkpasserror").innerHTML = "Passwords do not match!";
        document.getElementById("checkpasserror").style.color = "red";
        ok = false;
    }else{
      document.getElementById("user_new_password").style.borderColor = "#CACACA";
      document.getElementById("user_rnew_password").style.borderColor = "#CACACA";

    }

    return ok;
}
