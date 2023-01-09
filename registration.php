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

		if($validationCorrect==true)
		{
			//Hurra, wszystkie testy zaliczone!
			echo "Udana walidacja"; exit();
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
					<input type="text" class="form-control" placeholder="Podaj imię" aria-label="Name" name="username" required>

				</div>


				<div class="input-group pt-3 pb-4 m-auto">
					<span class="input-group-text w-25">E-mail</span>
					<input type="email" class="form-control" placeholder="Podaj adres Email" aria-label="Email" name="email" required>
				</div>

				<div class="input-group pt-3 pb-4 m-auto">
					<span class="input-group-text w-25">Hasło</span>
					<input type="password" class="form-control" placeholder="Podaj hasło" aria-label="Password" name="password" required>
				</div>
				
				<div class="col-12 col-md-11 col-lg-9 m-auto">
					<div class="ms-3 p-3 fs-6">
						<label>
							<input type="checkbox" class="form-check-input" value="a" name="statute" required> Akceptuję <a href="" target="_blank" class="link">Regulamin</a> i <a href="" target="_blank" class="link">Politykę prywatności</a>
						</label>
						<br />
						Masz już konto? <a href="login.php" class="link">Zaloguj się</a>
					</div>
					
					<div class="position-absolute start-50 translate-middle-x">
						<div class="g-recaptcha" data-sitekey="6LeQ1OQjAAAAADZ_Iswn1RcAXpgAWXbxjNq0Go0n"></div>
						
					</div>
					<br /><br />
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