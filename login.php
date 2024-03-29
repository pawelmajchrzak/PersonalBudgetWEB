<?php

	session_start();
	
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
		header('Location: mainmenu.php');
		exit();
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
</head>

<body>

	<a href="registration.php" class="btn btn-lg btn-outline-secondary position-absolute end-0 me-3">Rejestracja</a>
	
	<header class="col-12 col-md-8 col-lg-7 col-xl-6 col-xxl-5 px-3 m-auto mt-3 pt-4">
		<h1 class="logo"><i class="icon-money"></i>  Budżet osobisty</h1>
	</header>
	
	<article>
	
		<div class="col-md-8 col-lg-7 col-xl-6 col-xxl-5 bg-white m-2 m-md-auto border border-light rounded p-2 shadow-lg">
		
			<header class="h3 text-start m-3" style="letter-spacing: 2px;">
				Logowanie
			</header>
			
			<form action="loginNow.php" method="post">

				<div class="input-group p-3 m-auto">
					<span class="input-group-text w-25">E-mail</span>
					<input type="email" value="<?php
						if (isset($_SESSION['fr_email']))
						{
							echo $_SESSION['fr_email'];
							unset($_SESSION['fr_email']);
						}
					?>" class="form-control" placeholder="Podaj adres E-mail" aria-label="Email" name="email" required>
				</div>

				<div class="input-group p-3 m-auto">
					<span class="input-group-text w-25">Hasło</span>
					<input type="password" class="form-control" placeholder="Podaj hasło" aria-label="Password" name="password" required>
				</div>
				<span class="fs-6 ms-4 text-danger">
				<?php
					if(isset($_SESSION['error']))
					{
						echo $_SESSION['error'];
						unset($_SESSION['error']);
					}
				?>
				</span>
				
				<div class="col-8 m-auto">
					<div class="ms-3 p-2 fs-6">
						<a href="" class="link"> Przypomnienie hasła </a>
						<br />
						Nie masz konta? <a href="registration.php" class="link">Zarejestruj się</a>
					</div>
				</div>
				
				<div class="btn-group btn-group-lg mt-5 start-50 translate-middle">
					<button type="submit" class="btn btn-success">Zaloguj się</button>
				</div>
				

			</form>	
		
		</div>
	
	</article>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>