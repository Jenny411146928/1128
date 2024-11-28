<?
echo "兩數相加(用include引入) <br />";
include("addition.php");
#add(5,4);
echo 5 , " + " , 4 , " = " , addition(5 , 4) , "<br />";
echo "兩數相減(用require引入) <br />";
require("subtraction.php");
#sub(10,4);
echo 10 , " - " , 4 , " = " , subtraction(10 , 4) , "<br />";
?>
