<html>
	<head>
		<title> Title of Page </title>
	</head>
	<body>
		<p>Paragraph of the Page.</p>
		<?php
			echo "My first PHP script<br>" ;
			echo date('l, F dS Y.')."<br>";
		?>
	</body>
</html>
<?php
// $link = mysqli_connect('localhost', 'root', 'password'); 
$link = mysqli_connect('localhost','root', '','test'); 
if (!$link) 
{ 
  $output = 'Unable to connect to the database server.'; 
  include 'output.html.php'; 
  exit(); 
}

$result = mysqli_query($link, 'SELECT * FROM purchase_request_payment');  
if (!$result)  
{  
  $error = 'Error fetching jokes: ' . mysqli_error($link);  
  include 'error.html.php';  
  exit();  
}

// echo mysqli_fetch_array($result);
// echo '<pre>';
// print_r(mysqli_fetch_array($result));
// echo '</pre>';

// echo '<pre>';
// print_r(mysqli_fetch_assoc($result));
// echo '</pre>';

// while ($row = mysqli_fetch_array($result))  
while ($row = mysqli_fetch_assoc($result))  
{
	foreach ($row as $key => $value) {
		print_r($key);
		echo '<br>';
		print_r($value);
		echo '<br>';
	}
	echo '<pre>';
	print_r($row);
	echo '</pre>';  
} 
?>