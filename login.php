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

            $stmt = $conn -> prepare("SELECT password FROM users WHERE username = ?");
            $stmt -> bind_param("s", $username);
            $stmt -> execute();
            $result = $stmt -> get_result();

            if($result -> num_rows == 1) {
                $row = $result -> fetch_assoc();
                $hashedPassword = $row['password'];

                if(password_verify($password, $hashedPassword)) {
                    $message = "Login Successful";
                } else {
                    $message = "Login Unsuccessful";
                }
            } else{
                $message = "Login Unsuccessful";
            }
        }
    ?>

    <p style = "color:red;">
        <?php echo $message; ?>
    </p>

    </body>
</html>