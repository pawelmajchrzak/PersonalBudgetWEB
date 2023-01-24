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

        //Zapamiętaj wprowadzone dane
	    $_SESSION['fr_email'] = $email;
  
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

                    if ($resultIncomes = @$connection->query(
                    sprintf("SELECT * FROM incomes_category_assigned_to_users WHERE user_id='%s'",
                    mysqli_real_escape_string($connection,$_SESSION['id']))))
                    {
                        $i=0;
                        while ($recordIncomes = $resultIncomes->fetch_assoc())
                        {
                            
                            $_SESSION['categoryIncome_id'][$i] = $recordIncomes['id'];
                            $_SESSION['categoryIncome_name'][$i] = $recordIncomes['name'];
                            $_SESSION['categoryIncome_user_id'][$i] = $recordIncomes['user_id'];
                            $i++;
                        }
                        $_SESSION['iteratorIncomes'] = $i;
                    }

                    if ($resultExpenses = @$connection->query(
                        sprintf("SELECT * FROM expenses_category_assigned_to_users WHERE user_id='%s'",
                        mysqli_real_escape_string($connection,$_SESSION['id']))))
                    {
                        $i=0;
                        while ($recordExpenses = $resultExpenses->fetch_assoc())
                        {
                            
                            $_SESSION['categoryExpense_id'][$i] = $recordExpenses['id'];
                            $_SESSION['categoryExpense_name'][$i] = $recordExpenses['name'];
                            $_SESSION['categoryExpense_user_id'][$i] = $recordExpenses['user_id'];
                            $i++;
                        }
                        $_SESSION['iteratorExpenses'] = $i;
                    }

                    if ($resultMethodsPayment = @$connection->query(
                        sprintf("SELECT * FROM payment_methods_assigned_to_users WHERE user_id='%s'",
                        mysqli_real_escape_string($connection,$_SESSION['id']))))
                    {
                        $i=0;
                        while ($recordMethodsPayment = $resultMethodsPayment->fetch_assoc())
                        {
                            
                            $_SESSION['methodPayment_id'][$i] = $recordMethodsPayment['id'];
                            $_SESSION['methodPayment_name'][$i] = $recordMethodsPayment['name'];
                            $_SESSION['methodPayment_user_id'][$i] = $recordMethodsPayment['user_id'];
                            $i++;
                        }
                        $_SESSION['iteratorMethodsPayment'] = $i;
                    }

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