 <?php

$config_ini_file = parse_ini_file("/etc/yangcatalog/yangcatalog.conf", true);

$servername = $config_ini_file['DB-Section']['host'];
$username = $config_ini_file['DB-Section']['user'];
$password = $config_ini_file['DB-Section']['password'];
$dbname = $config_ini_file['DB-Section']['name-users'];
$sendto = $config_ini_file['Message-Section']['email-to'];

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['insertUser']))
{
	insertUser($conn);
}
else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['deleteUser']))
{
	deleteUser($conn);
}
else if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['printAll']))
{
	printAll($conn);
}

function insertUser(mysqli $conn)
{
	$Username = $_POST['Username'];
	$Password = $_POST['Password'];
	$PasswordConfirm = $_POST['PasswordConfirm'];
	$Email = $_POST['Email'];
	$Company = $_POST['Company'];
	$FirstName = $_POST['FirstName'];
	$LastName = $_POST['LastName'];
//	$AccessRightsSdo = $_POST['AccessRightsSdo'];
//	$AccessRightsVendor = $_POST['AccessRightsVendor'];

if(empty($Username) || empty($Password) || empty($PasswordConfirm)){
	if (empty($Username)) {
		$errorUsername = '<div class="isa_error">First name is required<br></div>'; 
		echo $errorUsername;
	}

	if (empty($Password)) {
		$errorPassword = '<div class="isa_error">Password is required<br></div>';
		echo $errorPassword;
	}
	if (empty($PasswordConfirm)) {
		$errorPasswordConfirm = '<div class="isa_error">Password confirm is required<br></div>';
		echo $errorPasswordConfirm;
	}
}

else if ($PasswordConfirm !== $Password) {
	$errorPasswordMismatch = '<div class="isa_error">Passwords are not identical<br></div>';
	echo $errorPasswordMismatch;
}

else
{
	$check_username = mysqli_query($conn, "SELECT username FROM users where username = '$Username' ");
	if(mysqli_num_rows($check_username) > 0){
		$errorUsernameExists = '<div class="isa_error">Username '.$Username.' already exists<br></div>';
		echo $errorUsernameExists;
	}
	else
	{
		$hash_sha256 = hash("sha256", $Password, False);
		$sql = "INSERT INTO users_temp (Id, Username, Password, Email, ModelsProvider, FirstName, LastName) VALUES (NULL, '$Username', '$hash_sha256', '$Email', '$Company', '$FirstName', '$LastName')";

		if (mysqli_query($conn, $sql)) {
                        $subject = "Request for access confirmation";
                        $msg = "User $Username with email $Email is requesting access \n Please go to yang project run python validate.py and follow the instructions. The script can be found in yangcatalog.org under /home/miroslav/yang/tools/validate/validate.py";
			$headers = "From: $Email";
                        mail($sendto, $subject, $msg, $headers);
                        echo "User $Username created successfully, email sent for access confirmation";
                }
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
}

mysqli_close($conn);

}

function deleteUser(mysqli $conn)
{
$UsernameToDelete = $_POST['UsernameToDelete'];

$check_username = mysqli_query($conn, "SELECT username FROM users where username = '$UsernameToDelete' ");
if(mysqli_num_rows($check_username) == 0){
	$errorUsernameDoesNotExists = '<div class="isa_error">Username '.$UsernameToDelete.' does not exists<br></div>';
	echo $errorUsernameDoesNotExists;
}

else
{
	$sql = "DELETE FROM users WHERE users.Username = '$UsernameToDelete'";

	if (mysqli_query($conn, $sql)) {
		echo "Record $UsernameToDelete deleted successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
}
mysqli_close($conn);
}

function printAll(mysqli $conn)
{
$sql = "SELECT * FROM users";

if (mysqli_query($conn, $sql)) {
	$all_users=mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_assoc($all_users)) {
		echo "Username: ".$row['Username']." ";
		echo nl2br("\n");
		echo "Password: ".$row['Password'];
		echo nl2br("\n\n");
	}
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}

echo '<p><a href="javascript:history.go(-1)" title="Return to previous page">&laquo; Go back</a></p>';

?>

