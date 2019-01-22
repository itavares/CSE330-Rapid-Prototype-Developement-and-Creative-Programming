<?php

$num1 = $_GET['num1_input'];
$num2 = $_GET['num2_input'];
$calculator = $_GET['calc_op'];

switch ($calculator) {
	case 'Add':
	$result = $num1+$num2;
	echo($result);
	echo"<br><<a href='calculator.html'>Back</a>";
	break;

	case 'Sub':
	$sub_result = $num1 - $num2;
	echo ($sub_result);
	echo"<br><a href='calculator.html'>Back</a>";
	break;

	case 'Mult':
	$mult_result = $num1 * $num2;
	echo($mult_result);
	echo"<br><a href='calculator.html'>Back</a>";
	break;

	case 'Div':
	if($num2 != 0){
		$div_result = $num1 / $num2;
		echo($div_result);
		echo"<br>";
		echo"<a href='calculator.html'>Back</a>";

	}
	else{
		echo"can't divide by 0";
		echo"<br>";
		echo"redirecting to homepage...";
		echo'<meta http-equiv="refresh" content="2;URL=calculator.html" />';
	}
	break;
	default:
	break;
}
// 


?>