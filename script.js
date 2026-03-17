document.getElementById("submitBtn").onclick = function () {

    var username = document.getElementById("username").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var password = document.getElementById("password").value.trim();
    var confirmPassword = document.getElementById("confirmPassword").value.trim();

    var valid = true;

    if (username === "") {
        document.getElementById("userError").innerHTML = "Username required";
        valid = false;
    } else {
        document.getElementById("userError").innerHTML = "";
    }

    if (email === "" || email.indexOf("@") === -1 || email.indexOf(".") === -1) {
        document.getElementById("emailError").innerHTML = "Enter valid Email";
        valid = false;
    } else {
        document.getElementById("emailError").innerHTML = "";
    }

    if (phone === "" || phone.length != 10 || isNaN(phone)) {
        document.getElementById("phoneError").innerHTML = "Enter 10 digit number";
        valid = false;
    } else {
        document.getElementById("phoneError").innerHTML = "";
    }

    if (password === "" || password.length < 7) {
        document.getElementById("passError").innerHTML = "Password must be at least 7 characters";
        valid = false;
    } else {
        document.getElementById("passError").innerHTML = "";
    }

    if (confirmPassword === "" || password !== confirmPassword) {
        document.getElementById("confirmError").innerHTML = "Passwords do not match";
        valid = false;
    } else {
        document.getElementById("confirmError").innerHTML = "";
    }

   if (valid) {

    var output =
        "<div class='success-title'>Registration Successful</div>" +
        "<div class='output-item'><span class='output-label'>Username</span><span>" + username + "</span></div>" +
        "<div class='output-item'><span class='output-label'>Email</span><span>" + email + "</span></div>" +
        "<div class='output-item'><span class='output-label'>Phone</span><span>" + phone + "</span></div>";

    var card = document.getElementById("outputCard");
    card.innerHTML = output;
    card.style.display = "block";
}

};

document.getElementById("changeImgBtn").onclick = function () {
    document.getElementById("myImage").src = "logo2.png";
};

$("#submitBtn").click(function () {
    $(this).text("Submitted");
    $("body").css("background-image", "url('https://via.placeholder.com/800x600')");
    $("#username").attr("placeholder", "Enter Full Name");
});
