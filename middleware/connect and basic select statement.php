 <?php 
 
//$arr = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);

//echo json_encode($arr);


$servername = "localhost";
$username="root";
$password="root";
$database="vrfps";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT first_name, surname FROM users";
$result = $conn->query($sql);

 while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
    }
    
echo json_encode($myArray);

mysqli_close($conn);


?>