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

    <hr>
    <h2>JOIN Query Results</h2>

    <?php
    function displayTable($result, $title) {
        if($result && $result -> num_rows > 0) {
            echo "<h3>$title</h3>";
            echo "table border = '1' cellpadding = '5' cellspacing = '0'>";

            //Table headers
            echo "<tr>";
            while($field = $result -> fetch_field()) {
                echo "<th>{$field -> name}</th>";
            }
            echo "</tr>";

            //Reset pointer
            $result -> data_seek(0);

            //Table rows
            while($row = $result -> fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }

            echo "</table><br>";
        } else {
            echo "<h3>$title</h3><p>No results found.</p>";
        }
    }

    //Natural Join
    $natural = $conn -> query ("
        select * 
        from users
        natural join UserDetails
    ");
    displayTable($natural, "Natural Join");

    //Inner Join
    $inner = $conn -> query ("
        select * 
        from users
        inner join UserDetails
        on users.username = UserDetails.username
    ");
    displayTable($inner, "Inner Join");

    //Left Outer Join
    $left = $conn -> query ("
        select * 
        from users
        left outer join UserDetails
        on users.username = UserDetails.username
    ");
    displayTable($left, "Left Outer Join");

    //Right Outer Join
    $right = $conn -> query ("
        select * 
        from users
        right outer join UserDetails
        on users.username = UserDetails.username
    ");
    displayTable($right, "Right Outer Join");

    //Full Outer Join (Simulated with Union)
    $full = $conn -> query ("
        select * 
        from users
        left join UserDetails
        on users.username = UserDetails.username

        union

        select * 
        from users
        right join UserDetails
        on users.username = UserDetails.username
    ");
    displayTable($full, "Full Outer Join (Simulated)");
    ?>

    <?php if($message != ""): ?>
        <p style = "color:red;">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>
    </body>
</html>