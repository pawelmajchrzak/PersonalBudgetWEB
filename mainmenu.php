<?php

	session_start();
	
	if (!isset($_SESSION['logged']))
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
		$startOfCurrentMonth = date('Y-m-01');
		$startOfNextMonth = date('Y-m-01',strtotime('+1 month',time()));
		//$currentDate += 86400;
		//$currentDate = curdate();
		//echo $startOfCurrentMonth;
		//echo '<br>';
		//echo $startOfNextMonth;
		//exit();




		if ($resultIncomes = @$connection->query(
			sprintf("SELECT * FROM incomes WHERE user_id='%s' AND date_of_income>='%s' AND date_of_income<'%s'",
			mysqli_real_escape_string($connection,$_SESSION['id']),
			mysqli_real_escape_string($connection,$startOfCurrentMonth),
			mysqli_real_escape_string($connection,$startOfNextMonth))))
			{
				$sumOfIncomes=0;
				while ($recordIncomes = $resultIncomes->fetch_assoc())
				{
					$_SESSION['dateOfIncome'] = $recordIncomes['date_of_income'];
					$sumOfIncomes += $recordIncomes['amount'];
				}
			}

		if ($resultExpenses = @$connection->query(
			sprintf("SELECT * FROM expenses WHERE user_id='%s' AND date_of_expense>='%s' AND date_of_expense<'%s'",
			mysqli_real_escape_string($connection,$_SESSION['id']),
			mysqli_real_escape_string($connection,$startOfCurrentMonth),
			mysqli_real_escape_string($connection,$startOfNextMonth))))
			{
				$sumOfExpenses=0;
				while ($recordExpenses = $resultExpenses->fetch_assoc())
				{
					$_SESSION['dateOfExpense'] = $recordExpenses['date_of_expense'];
					$sumOfExpenses += $recordExpenses['amount'];
				}
			}

			$balance = $sumOfIncomes-$sumOfExpenses;







		$connection->close();
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
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Lato:wght@300;400;700&family=Poppins:wght@200;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/fontello.css" type="text/css" />
	<link rel="icon" href="image/logo.png" type="image/png">
	<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
	<span class="text-success position-absolute top-0 end-0 me-3 fs-6 mt-1">Użytkownik: <?php echo $_SESSION['username'];?></span>
	<a href="logout.php" class="btn btn-lg btn-outline-secondary position-absolute end-0 me-3 mt-3">Wyloguj się</a>
	
	<header class="col-12 col-lg-11 col-xl-10 col-xxl-8 px-3 m-auto mt-3 pt-4">
		<h1 class="logo"><a href="mainmenu.php" class="cleanLink"><i class="icon-money"></i>  Budżet osobisty</a></h1>
	</header>
	
	<div class="col-lg-11 col-xl-10 col-xxl-8 bg-white m-2 m-lg-auto border border-light rounded p-2 shadow-lg">	
		
		<nav class="navbar navbar-light text-center navbar-expand-lg pt-3 px-3 mb-4">
	
			<button class="navbar-toggler"
					type="button"
					data-bs-toggle="collapse"
					data-bs-target="#menu"
					aria-controls="menu"
					aria-expanded="false"
					aria-label="Przełącznik nawigacji"
			>
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="menu">
			
				<ol class="navbar-nav m-lg-auto col-12">
					<li class="nav-item col-lg-3"><a class="nav-link" href="addIncome.php"><i class="icon-money-1"></i>Dodaj przychód</a></li>
					<li class="nav-item col-lg-3"><a class="nav-link" href="addExpense.php"><i class="icon-basket"></i>Dodaj wydatek</a></li>
					<li class="nav-item col-lg-3"><a class="nav-link" href="viewBalanceSheet.php"><i class="icon-chart-pie"></i>Przeglądaj bilans</a></li>
					<li class="nav-item dropdown col-lg-3"><a class="nav-link" href="" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"><i class="icon-cog-alt"></i>Ustawienia <i class="icon-down-open"></i></a>
						<ul class="dropdown-menu" aria-labelledby="submenu">
							<li><a class="dropdown-item" href="#">Edytuj konto</a></li>
							<li><a class="dropdown-item" href="#">Preferencje użytkownika</a></li>
							<li><a class="dropdown-item" href="#">Edytuj/Usuń operację</a></li>
						</ul>					
					</li>
				</ol>
			</div>
			
		</nav>
		
		<main>
	
			<div class="row px-2">
				<div class="col-12 col-lg-5 my-0" >
				
					<article>
				
						<div class="mb-3 border border-success bg-light rounded p-2">
						
							<header class="h4 text-center mb-3">
								<div class="d-inline-block">Ten miesiąc:  </div>
								
								<div class="d-inline-block">
									<?php
										echo date('Y-m');
									?>

								</div>
							</header>
							
							<section>
							
								<div class="col-7 text-start d-inline-block ms-2 my-3">Suma przychodów:</div>
								<div class="col-4 text-end d-inline-block me-2 my-3"><span class="text-success pe-1">+</span><?php echo $sumOfIncomes;?> zł</div>

								<div class="col-7 text-start d-inline-block ms-2 my-3">Suma wydatków:</div>
								<div class="col-4 text-end d-inline-block me-2 my-3"><span class="text-danger pe-1">-</span><?php echo $sumOfExpenses;?> zł</div>

								<div class="border-bottom border-success my-2"></div>

								<div class="col-7 text-start d-inline-block ms-2 my-3 fw-bold">Bilans:</div>
								<div class="col-4 text-end d-inline-block me-2 my-3 fw-bold"> <span class="text-success pe-1"><?php if($balance>=0) echo '+';?></span><?php echo $balance;?> zł</div>
			
							</section>
							
						</div>
						
					</article>
					
					<article>
					
						<div class="my-3 border border-success bg-light rounded p-2">
						
							<header class="h4 text-center mb-3">
								Przychody
							</header>
							
							<section class="pb-2">	
								<div class="pie2">
									<svg viewBox="0 0 32 32">
									  <circle r="16" cx="16" cy="16" />
									</svg>
								</div>
							</section>
						
						</div>
					
					</article>
					
				</div>
				
				<div class="col-12 col-lg-7">
					<article>
						<div class="mb-3 border border-success bg-light rounded p-2" style="height: 525px;">
							
								<header class="h4 text-center mb-3">
									Wydatki
								</header>
								
								<section>
										<div class="pie">
											<svg viewBox="0 0 32 32">
											  <circle r="16" cx="16" cy="16" />
											</svg>
										</div>
								</section>
							
						</div>
					</article>
				</div>
				
			</div>
		</main>	
			
	</div>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="javaScript.js"></script>
		
</body>
</html>