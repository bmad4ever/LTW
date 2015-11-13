//adaptado de http://stackoverflow.com/questions/18072466/using-md5-on-login-page

<php?

session_start();
//???session_regenerate_id(true);???
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Username and password sent from form in HTML
    $myusername = $_POST['username'];
    $mypassword = $_POST['password'];

	$db = new PDO('sqlite:database.db');
	$stmt = $db->prepare('SELECT * FROM users WHERE username = ? and password = ?');
	$stmt->execute(array($username, md5($password))); 
    $sql    = "SELECT id FROM users WHERE username='$myusername' and password='$mypassword'";
    $result = mysql_query($sql);
    $row    = mysql_fetch_array($result);
    $active = $row['active'];
    $count  = mysql_num_rows($result);

    // If result matched $myusername and $mypassword, table row must be 1 row
    if ($count == 1) {
        session_register("myusername");
        $_SESSION['login_user'] = $myusername;

        //header("location: welcome.php");
    } else {
        $error = "Your username or password is invalid";
		//???session_destroy();???
    }
}
?>

// remove all session variables
//session_unset(); 
// destroy the session 
//session_destroy(); 