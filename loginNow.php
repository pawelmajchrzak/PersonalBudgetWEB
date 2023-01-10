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
  
        if ($result = @$connection->query(
        sprintf("SELECT * FROM users WHERE email='%s'",
        mysqli_real_escape_string($connection,$email))))
        {
            $howManyUsers = $result->num_rows;
            if($howManyUsers>0)
            {
                $record = $result->fetch_assoc();
                
                if (password_verify($password, $record['password']))
                {
                    $_SESSION['logged'] = true;

                    
                    $_SESSION['id'] = $record['id'];
                    $_SESSION['username'] = $record['username'];
                    $_SESSION['email'] = $record['email'];

                    unset($_SESSION['error']);
                    $result->free_result();
                    header('Location: mainmenu.php');
                }
                else
                {
                    $_SESSION['error'] = 'Nieprawidłowy email lub hasło!';
                    header('Location: login.php');
                }
                
            } 
            else 
            {
                $_SESSION['error'] = 'Nieprawidłowy email lub hasło!';
				header('Location: login.php');
            }

        }
    
        $connection->close();
    }



?>