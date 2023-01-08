<?php

    session_start();

    if ((!isset($_POST['email'])) || (!isset($_POST['password'])))
	{
		header('Location: login.php');
		exit();
	}

    require_once "connect.php";
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
	else
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $email = htmlentities($email, ENT_QUOTES, "UTF-8");
		$password = htmlentities($password, ENT_QUOTES, "UTF-8");

        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        if($result=@$connection->query($sql))
        {
            $howManyUsers = $result->num_rows;
            if($howManyUsers>0)
            {
                $_SESSION['logged'] = true;

                $record = $result->fetch_assoc();
                $_SESSION['id'] = $record['id'];
				$_SESSION['username'] = $record['username'];
				$_SESSION['email'] = $record['email'];

                unset($_SESSION['error']);
                $result->free_result();
                header('Location: mainmenu.php');
                
                
            } else {

                $_SESSION['error'] = 'Nieprawidłowy email lub hasło!';
				header('Location: login.php');


            }

        }
    


        $connection->close();
    }



?>