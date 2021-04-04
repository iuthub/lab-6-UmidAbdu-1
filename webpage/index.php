<?php
$name    = $email   = $uname = $pwd    = $c_pwd  = $date = "";
$gender  = $marital = $addr  = $city   = $postal = $web  = "";
$h_phone = $m_phone = $card  = $card_e = $salary = $gpa  = "";

$mapping = [
    "name"    => [
        "Name", "It has to contain at least 2 chars. It should not contain any number",
        "([^0-9.@]){2,50}"
    ],
    "email"   => [
        "Email", "It should correspond to valid email format",
        "[\w\-+]+([.][\w]+)?@[\w\-+]+([.][a-z]{2,})+"
    ],
    "uname"   => [
        "Username", "It has to contain at least 5 chars",
        "[a-z][^ !@#$%^&*()=\[\]]{4,}"
    ],
    "pwd"     => [
        "Password", "It has to contain at least 8 chars",
        "[^\n]{8,}"
    ],
    "c_pwd"   => [
        "Confirm Password", "It has to be equal to Password field",
        "[^\n]{8,}"
    ],
    "date"    => [
        "Date of Birth", "It should be written in dd.mm.yyyy format",
        "[0-3][0-9]\.[0-1][0-2]\.[1-9][0-9]{3}"
    ],
    "gender"  => [
        "Gender", "Only 2 options accepted: Male, Female",
        "([Ff]e)?[Mm]ale"
    ],
    "marital" => [
        "Marital Status", "Only 4 options accepted: Single, Married, Divorced, Widowed",
        "([Ss]ingle|[Mm]arried|[Dd]ivorced|[Ww]idowed)"
    ],
    "addr"    => [
        "Address", "It should contain at least 10 characters",
        ".{10,}",
    ],
    "city"    => [
        "City", "It should contain at least 2 characters",
        ".{2,}",
    ],
    "postal"  => [
        "Postal Code", "It should follow 6 digit format",
        "(?(?=.*[1-9])[0-9]|[1-9])[0-9]{5}",
    ],
    "h_phone" => [
        "Home Phone", "It should follow 9 digit format",
        "[0-9]{9}",
    ],
    "m_phone" => [
        "Mobile Phone", "It should follow 9 digit format",
        "[0-9]{9}"
    ],
    "card"    => [
        "Credit Card Number", "It should follow 16 digit format",
        "[0-9]{16}"
    ],
    "card_e"  => [
        "Credit Card Expiry Date", "It should be written in dd.mm.yyyy format",
        "[0-3][0-9].[0-1][0-2].[1-9][0-9]{3}"
    ],
    "salary"  => [
        "Monthly Salary", "It should be written in following format UZS 200,000.00",
        "UZS [1-9][0-9]*(\.[0-9]{2})?"
    ],
    "web"     => [
        "Web Site URL", "It should match URL format. For ex, http://github.com",
        "(?:(?:https?|ftp):\/\/)?[\w\/\-?=%.]+\.[\w\/\-&?=%.]+"
    ],
    "gpa"     => [
        "Overall GPA", "It should be a floating point number less than 4.5",
        "(?(?=..[0-5])[0-4]|[0-3])\.[0-9]"
    ]
];

$pwd_validation = "/\A(?=\w{6,10}\z)(?=[^a-z]*[a-z])(?=(?:[^A-Z]*[A-Z]){3})\D*\d.*\z/";

$msg = $info = "";
$color = "red";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $match = True;
    foreach ($mapping as $key => $value) ${$key} = $_POST[$key];

    foreach ($mapping as $key => $value){
        if ($_POST[$key] == ""){
            $msg = "Not all fields are filled!";
            $match = False;
            break;
        }

        if (!preg_match("/^$value[2]$/", $_POST[$key])){
            $msg = "Field '$value[0]' filled incorrectly!";
            $info = $value[1];
            $match = False;
            break;
        }
    }
    if ($match){
        if ($pwd != $c_pwd){
            $msg = "Passwords don't match!";
            $match = False;
        } elseif (!preg_match($pwd_validation, $pwd)){
            $msg = "Password validation failed!";
            $info = "It must be 6 to 10 chars and must contain at least 1 lowercase, 3 uppercase, 1 digit characters";
            $match = False;
        }
    }

    if ($match){
        $msg = "Registration Successful";
        $info = "Thank you for choosing us!";
        $color = "green";
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <title>Validating Forms</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>
	
<body>
    <div class="form">
        <h1>Registration Form</h1>

        <p>This form validates user input and then displays "Thank You" page.</p>
        <hr/>

        <h2>Please, fill below fields correctly</h2>
        <form action="index.php" method="post">
        <dl>
            <?php
            foreach($mapping as $var => $title){
                echo "<dt>$title[0]</dt>\n";
                echo "<dd><label><input name=$var value='${$var}'></label></dd>\n";
            }
            ?>
        </dl>
        <?= "<div class='status' style='color: $color'>$msg<br/>$info</div>" ?>
        <div>
            <input type="submit" value="Register">
        </div>
        </form>
    </div>
</body>
</html>