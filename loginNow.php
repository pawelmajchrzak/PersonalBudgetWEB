<?php

    session_start();

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


                $result->free_result();
                header('Location: mainmenu.php');
                
                
            } else {


            }

        }
    


        $connection->close();
    }



?>