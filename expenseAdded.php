<?php

	session_start();
	
	if (!isset($_SESSION['successfulAddExpense']))
	{
		header('Location: login.php');
		exit();
	}
	else
	{
		unset($_SESSION['successfulAddExpense']);
	}
	
	//Usuwanie zmiennych pamiętających wartości wpisane do formularza
	if (isset($_SESSION['fr_amount'])) unset($_SESSION['fr_amount']);
	
	//Usuwanie błędów rejestracji
	if (isset($_SESSION['e_amount'])) unset($_SESSION['e_amount']);
	if (isset($_SESSION['e_date'])) unset($_SESSION['e_date']);
	if (isset($_SESSION['e_category'])) unset($_SESSION['e_category']);
	if (isset($_SESSION['e_comment'])) unset($_SESSION['e_comment']);
	if (isset($_SESSION['e_methodPayment'])) unset($_SESSION['e_methodPayment']);
	
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
</head>

<body>

	<a href="login.php" class="btn btn-lg btn-outline-secondary position-absolute end-0 me-3">Logowanie</a>

	<header class="col-12 col-md-8 col-lg-7 col-xl-6 col-xxl-5 px-3 m-auto mt-3 pt-4">
		<h1 class="logo"><i class="icon-money"></i>  Budżet osobisty</h1>
	</header>
	
	<article>
	
		<div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5 bg-white m-2 m-md-auto border border-light rounded p-2 shadow-lg">
		
			<header class="h3 text-start m-3" style="letter-spacing: 2px;">
				Wydatek został dodany do bazy danych!
			</header>
			
				<div class="mx-auto" style="width: 300px;">Powrót do menu głównego: </div>
				
			
				<br />
				<div class="mx-auto" style="width: 150px;">
					<a href="mainmenu.php" class="btn btn-lg btn-success m-auto">Menu główne</a>
					
				</div>
			
					
		</div>
	
	</article>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>

