<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		
	<h2>Login Page</h2>

	<form method = "POST">
		Username: <input type = "text" name = "username" required><br><br>
		Password: <input type = "password" name = "password" required><br><br>
		<button type = "submit">Login</button>
	</form>

    <?php
        $conn = new mysqli("localhost", "root", "", "SocialMediaDB");

        $message = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM users where 
            username = '$username' and password = '$password'";

            $result = $conn -> query($sql);

            if($result -> num_rows > 0) {
                $message = "Login Successful";
            }
            else
                $message = "Login Unsuccessful";
        }
    ?>

    <p style = "color:red;">
        <?php echo $message; ?>
    </p>

    </body>
</html>