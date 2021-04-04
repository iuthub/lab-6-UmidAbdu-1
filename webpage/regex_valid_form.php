<?php

$pattern = "";
$text    = "";
$r_text  = "";
$rd_text = "";

$method  = "";
$string  = "";
$output  = "";

$match   = "Not checked yet.";

function sel($a): string {
    global $method;
    return $method == $a? "selected": "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["check_btn"])){
        $pattern = $_POST["pattern"];
        $text    = $_POST["text"   ];
        $r_text  = $_POST["r_text" ];

        $rd_text = preg_replace("/$pattern/", $r_text, $text);
        $match   = preg_match  ("/$pattern/", $text)? "Match!": "Does not match!";

        // Version 1:
        if (preg_match("/.*quick.*/", $text))
            $match = "Match: quick";
        elseif (preg_match("/^[\w\d]+@[\w\d]+[\w\d.]+$/", $text))
            $match = "Match: email";
        elseif (preg_match("/^\+998-?[\d]{2}-?[\d]{3}-?[\d]{2}-?[\d]{2}$/", $text))
            $match = "Match: phone number";

//        // Version 2:
//        $matches = [
//            "/.*quick.*/" => "quick",
//            "/[\w\d]+@[\w\d]+.[\w\d]+/" => "email",
//            "/^\+998-?[\d]{2}-?[\d]{3}-?[\d]{2}-?[\d]{2}$/" => "phone number"
//        ];
//        foreach ($matches as $key => $value) {
//            if (preg_match($key, $text)) { $match = "Match: ".$value; break;}
//        }

    } elseif (isset($_POST["run_btn"])){
        $mapping = [
            "ws" => "/\s+/"   , // whitespace
            "nn" => "/[^0-9]/", // nonnumerical
            "nl" => "/\R/"    , // new line
        ];
        $method = $_POST["method"];
        $string = $_POST["string"];

        if (key_exists($method, $mapping)){
            $output = preg_replace($mapping[$method], "", $string);
        }
        elseif ($method == "et"){ // extract text
            $matches = array();
            preg_match_all("/[\[(](.*)[)\]]/", $string, $matches);
            $output = implode(", ", $matches[1]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Valid Form</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <div class="form">
        <form action="regex_valid_form.php" method="post">
            <dl>
                <dt>Pattern      </dt>
                <dd><label><input type="text" name="pattern" value="<?=$pattern?>"></label></dd>

                <dt>Text         </dt>
                <dd><label><input type="text" name="text"    value="<?=$text?>">   </label></dd>

                <dt>Replace Text </dt>
                <dd><label><input type="text" name="r_text"  value="<?=$r_text?>"> </label></dd>

                <dt>Output Text  </dt>
                <dd><?=$match?></dd>

                <dt>Replaced Text</dt>
                <dd><code><?=$rd_text?></code></dd>

                <dt>&nbsp;</dt>
                <input type="submit" name="check_btn" value="Check">
            </dl>
        </form>
    </div>
    <div class="form">
        <form action="regex_valid_form.php" method="post" id="additional">
            <div class="block">
                <p>String:</p>
                <div id="output">
                    <label for="txtArea"></label>
                    <textarea form="additional" name="string" id="txtArea" wrap="soft"><?=$string?></textarea>
                </div>
            </div>

            <div class="block">
                <p>Output:</p>
                <div id="output"><?=$output?></div>
            </div>

            <div class="block">
                <label for="method">Choose method:</label>
                <select id="method" name="method">
                    <option <?=sel("ws")?> value="ws"> Remove whitespaces             </option>
                    <option <?=sel("nn")?> value="nn"> Remove nonnumerical characters </option>
                    <option <?=sel("nl")?> value="nl"> Remove new lines               </option>
                    <option <?=sel("et")?> value="et"> Extract text                   </option>
                </select>
            </div>
            <input type="submit" name="run_btn" value="Run">
        </form>
    </div>
</body>
</html>
