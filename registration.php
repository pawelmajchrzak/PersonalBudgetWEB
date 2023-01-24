<?php

    session_start();

	if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$validationCorrect=true;

		//Sprawdź poprawność imienia
		$username = $_POST['username'];

		//Sprawdzenie długości imienia
		if ((strlen($username)<2) || (strlen($username)>20))
		{
			$validationCorrect=false;
			$_SESSION['e_username']="Imię musi posiadać od 2 do 20 znaków!";
		}

		$checkName = '/(*UTF8)^[A-ZŁŚ]{1}+[a-ząęółśżźćń]+$/';

		if(preg_match($checkName, $username)==false)
		{
			$validationCorrect=false;
			$_SESSION['e_username']="Imię może zawierać tylko litery! Zacznij od wielkiej litery!";
		}

		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$validationCorrect=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}

		//Sprawdź poprawność hasła
		$password = $_POST['password'];

		if ((strlen($password)<8) || (strlen($password)>20))
		{
			$validationCorrect=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 20 znaków!";
		}

		$password_hash = password_hash($password, PASSWORD_DEFAULT);
		
		//Czy zaakceptowano regulamin?
		if (!isset($_POST['statute']))
		{
			$validationCorrect=false;
			$_SESSION['e_statute']="Potwierdź akceptację regulaminu!";
		}	

		//Bot or not? Oto jest pytanie!
		$secretKey = "6LeQ1OQjAAAAAAS2VjtWBMwrNIM_uL9U5YHAwgml";
		
		$checkCaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
		
		$answerCaptcha = json_decode($checkCaptcha);
		
		if ($answerCaptcha->success==false)
		{
			$validationCorrect=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
		}	

		//Zapamiętaj wprowadzone dane
		$_SESSION['fr_username'] = $username;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_password'] = $password;
		if (isset($_POST['statute'])) $_SESSION['fr_statute'] = true;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);

		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email już istnieje?
				$result = $connection->query("SELECT id FROM users WHERE email='$email'");
				
				if (!$result) throw new Exception($connection->error);
				
				$howManyEmails = $result->num_rows;
				if($howManyEmails>0)
				{
					$validationCorrect=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}		
	
				if ($validationCorrect==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
					//echo "Udana walidacja"; exit();
					
					if ($connection->query("INSERT INTO users VALUES (NULL, '$username', '$password_hash', '$email')"))
					{
						//skopiowanie defaultowych kategorii przychodów i wydatków
							//pobieranie id usera

						$resultUsers = $connection->query("SELECT id FROM users WHERE email='$email'");
						$user = $resultUsers->fetch_assoc();
						$userId=$user['id'];

							//pobieranie i kopiawanie typowych przychodow
						$resultIncomes = $connection->query("SELECT * FROM incomes_category_default");
						
						while($defaultIncomes = $resultIncomes->fetch_assoc())
						{
							$singleTypeOfIncome = $defaultIncomes['name'];
							$connection->query("INSERT INTO incomes_category_assigned_to_users VALUES (NULL, '$userId', '$singleTypeOfIncome')");
						}
						
							//pobieranie i kopiawanie typowych wydatkow
						$resultExpenses = $connection->query("SELECT * FROM expenses_category_default");
						
						while($defaultExpenses = $resultExpenses->fetch_assoc())
						{
							$singleTypeOfExpense = $defaultExpenses['name'];
							$connection->query("INSERT INTO expenses_category_assigned_to_users VALUES (NULL, '$userId', '$singleTypeOfExpense')");
						}


							//pobieranie i kopiawanie typowych metod płatności
						$resultMethodsPayment = $connection->query("SELECT * FROM payment_methods_default");
					
						while($defaultMethodPayment = $resultMethodsPayment->fetch_assoc())
						{
							$singleTypeOfMethodPayment = $defaultMethodPayment['name'];
							$connection->query("INSERT INTO payment_methods_assigned_to_users VALUES (NULL, '$userId', '$singleTypeOfMethodPayment')");
						}

						$_SESSION['successfulRegistration']=true;
						header('Location: welcome.php');
					}
					else
					{
						throw new Exception($connection->error);
					}			
				}
				
				$connection->close();
			}

		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span><br />';
			//echo '<br />Informacja developerska: '.$e;
		}

	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	
	<title>Budżet osobisty</title>
	
	<meta name="description" content="Budżet osobisty to aplikacja internetowa, która pozwoli Tobie oraz Twojej rodzinie lepiej zarządzać finansami. Dzięki niej, zobaczysz trendy swoich przychodów i wydatków oraz analizę bilansu - co z pewnością pozwoli Ci podjąć pewne przemyślane kroki w celu polepszenia swojej sytuacji materialnej!" />
	<meta name="keywords" content="budżet, osobisty, personal, budget, przychody, wydatki, bilans, finanse, rodzinny, aplikacja, internetowa, oszczędności, portfel" />
	
	
	<link rel="stylesheet" href="css/bootstrap.min.css">	
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Lato:wght@300;400;700&family=Poppins:wght@200;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/fontello.css" type="text/css" />
	<link rel="icon" href="image/logo.png" type="image/png">

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

	<a href="login.php" class="btn btn-lg btn-outline-secondary position-absolute end-0 me-3">Logowanie</a>
	
	<header class="col-12 col-md-8 col-lg-7 col-xl-6 col-xxl-5 px-3 m-auto mt-3 pt-4">
		<h1 class="logo"><i class="icon-money"></i>  Budżet osobisty</h1>
	</header>
	
	<article>
	
		<div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5 bg-white m-2 m-md-auto border border-light rounded p-2 shadow-lg">
		
			<header class="h3 text-start m-3" style="letter-spacing: 2px;">
				Rejestracja
			</header>
			
			<form method="post">

				<div class="input-group pt-3 pb-4 m-auto">
					<span class="input-group-text w-25">Imię</span>
					<?php
						if (isset($_SESSION['e_username']))
						{
							echo '<div class="text-danger w-100 ms-2 fs-6 position-absolute start-50 translate-middle-x"><br /><br />'.$_SESSION['e_username'].'</div>';
							unset($_SESSION['e_username']);
						}
					?>
					<input type="text" value="<?php
						if (isset($_SESSION['fr_username']))
						{
							echo $_SESSION['fr_username'];
							unset($_SESSION['fr_username']);
						}
					?>" class="form-control" placeholder="Podaj imię" aria-label="Name" name="username" required>

				</div>


				<div class="input-group pt-3 pb-4 m-auto">
					<span class="input-group-text w-25">E-mail</span>
					<?php
						if (isset($_SESSION['e_email']))
						{
							echo '<div class="text-danger w-100 ms-2 fs-6 position-absolute start-50 translate-middle-x"><br /><br />'.$_SESSION['e_email'].'</div>';
							unset($_SESSION['e_email']);
						}
					?>
					<input type="email" value="<?php
						if (isset($_SESSION['fr_email']))
						{
							echo $_SESSION['fr_email'];
							unset($_SESSION['fr_email']);
						}
					?>" class="form-control" placeholder="Podaj adres Email" aria-label="Email" name="email" required>
				</div>

				<div class="input-group pt-3 pb-4 m-auto">
					<span class="input-group-text w-25">Hasło</span>
					<?php
						if (isset($_SESSION['e_password']))
						{
							echo '<div class="text-danger w-100 ms-2 fs-6 position-absolute start-50 translate-middle-x"><br /><br />'.$_SESSION['e_password'].'</div>';
							unset($_SESSION['e_password']);
						}
					?>
					<input type="password" value="<?php
						if (isset($_SESSION['fr_password']))
						{
							echo $_SESSION['fr_password'];
							unset($_SESSION['fr_password']);
						}
					?>" class="form-control" placeholder="Podaj hasło" aria-label="Password" name="password" required>
				</div>
				
				<div class="col-12 col-md-11 col-lg-9 m-auto">
					<div class="ms-3 p-3 fs-6">
						<label>
							<input type="checkbox" class="form-check-input" value="a" name="statute" <?php
								if (isset($_SESSION['fr_statute']))
								{
									echo "checked";
									unset($_SESSION['fr_statute']);
								}
							?>/> Akceptuję <a href="" target="_blank" class="link">Regulamin</a> i <a href="" target="_blank" class="link">Politykę prywatności</a>
						</label>
						<br />
						Masz już konto? <a href="login.php" class="link">Zaloguj się</a>
						<?php
							if (isset($_SESSION['e_statute']))
							{
								echo '<span class="text-danger w-100 ms-2 fs-6 ">'.$_SESSION['e_statute'].'</span>';
								unset($_SESSION['e_statute']);
							}
						?>
					</div>
					
					<div class="position-absolute start-50 translate-middle-x">
						<?php
							if (isset($_SESSION['e_bot']))
							{
								echo '<div class="text-danger w-100 ms-2 mt-2 fs-6 position-absolute start-50 translate-middle-x" style="z-index:-1;"><br /><br /><br />'.$_SESSION['e_bot'].'</div>';
								unset($_SESSION['e_bot']);
							}
						?>
						<div class="g-recaptcha" data-sitekey="6LeQ1OQjAAAAADZ_Iswn1RcAXpgAWXbxjNq0Go0n"></div>
						
					</div>
					<br /><br /><br />
				</div>


				<div class="btn-group btn-group-lg mt-5 start-50 translate-middle">
					<button type="submit" class="btn btn-success">Zarejestruj się</button>
				</div>
				
			</form>	
		
		</div>

	</article>


	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>

</body>
</html>