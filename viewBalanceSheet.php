<?php

session_start();

if (!isset($_SESSION['logged']))
{
	header('Location: login.php');
	exit();
}





if (isset($_POST['timePeriod'])||isset($_POST['startPeriod']))
{
	//echo $_POST['timePeriod'];
	//exit();
	if (!isset($_POST['timePeriod']))
	{
		$_POST['timePeriod']=5;
	}

	if($_POST['timePeriod']==1)
	{
		$startOfPeriodTime = date('Y-m-01');
		$endOfPeriodTime = date('Y-m-01',strtotime('+1 month',time()));
	}
	elseif ($_POST['timePeriod']==2)
	{
		$startOfPeriodTime = date('Y-m-01',strtotime('-1 month',time()));
		$endOfPeriodTime = date('Y-m-01');
	}
	elseif ($_POST['timePeriod']==3)
	{
		$startOfPeriodTime = date('Y-01-01');
		$endOfPeriodTime = date('Y-01-01',strtotime('+1 Year',time()));
	}
	elseif ($_POST['timePeriod']==4)
	{
		$startOfPeriodTime = date('Y-01-01',strtotime('-1 month',time()));
		$endOfPeriodTime = date('Y-01-01');
	}
	elseif ($_POST['timePeriod']==5)
	{
		$startOfPeriodTime=$_POST['startPeriod'];
		$endOfPeriodTime=$_POST['endPeriod'];
		$dateObject= new DateTime($endOfPeriodTime);
		$dateObject->modify( '+1 day' );
		$endOfPeriodTime = $dateObject->format('Y-m-d');
	}



	echo $startOfPeriodTime;
	echo '<br>';
	echo $endOfPeriodTime;
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
		

		
		
			<div class="container">
				
				<div class="row px-2">
					<div class="col-12 border border-success bg-light rounded p-3 text-center h3">
						Przeglądaj bilans
					</div>
				</div>
				
				<div class="row">
					<div class="col-12 col-lg-6 p-2">
						<div class="border border-success bg-light rounded p-4">
							<section>
								<form method="post">
									<div class="row my-3">					
										<div class="col-6 text-center">
											Przedział czasowy
										</div>
										<div class="col-6 pb-1">
											
											<select class="form-select border border-success col-6" name="timePeriod" onchange="this.form.submit()">
												
												<option value="0" selected disabled>	Wybierz zakres	</option>
												<option value="1">	Ten miesiąc		</option>
												<option value="2">				Ubiegły miesiąc	</option>
												<option value="3" >				Ten rok			</option>
												<option value="4">				Ubiegły rok		</option>
												
											</select>
										
										</div>
										
									</div>
								</form>
								
								<div class="d-grid gap-2 col-6 mx-auto">
									<button
									  class="btn btn-outline-success btn-lg"
									  data-bs-toggle="modal"
									  data-bs-target="#timeRange"
									>
									  Wybierz własny zakres...
									</button>
								</div>
								
							</section>
						</div>
					</div>
					<div class="col-12 col-lg-6 p-2">
						<div class="border border-success bg-light rounded p-3">
							<section>
								<div class="row my-3 px-4">
									<div class="col-6 h4 text-center fw-bold">Bilans:</div>
									<div class="col-5 h4  text-center fw-bold"><span class="text-success">+ </span>1300 zł</div>
								</div>
								<div class="fs-6 text-success text-center">Gratulacje! Dobrze gospodarujesz swoimi pieniędzmi!</div>
								
							</section>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-12 col-lg-6 p-2">
						<div class="border border-success bg-light rounded py-3 px-4">
							<section>
								
								<header class="h4 text-center mb-3">
										Przychody
								</header>					
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Wynagrodzenie:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-success pe-1">+</span>4500 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Odsetki bankowe:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-success pe-1">+</span>125 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Sprzedaż online:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-success pe-1">+</span>350 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Inne:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-success pe-1">+</span>0 zł</div>
								
								<div class="border-bottom border-success my-2"></div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2 fw-bold">Suma:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2 fw-bold"><span class="text-success pe-1">+</span>4975 zł</div>
								
							</section>
						</div>
					</div>
					<div class="col-12 col-lg-6 p-2">
						<div class="border border-success bg-light rounded py-3 px-4">
							<section>
								<header class="h4 text-center mb-3">
									Wydatki
								</header>		

								<div class="col-7 text-start d-inline-block ms-2 my-2">Jedzenie:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>3200 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Mieszkanie:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>125 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Transport:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>350 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Telekomunikacja:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Opieka zdrowotna:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Ubranie:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Higiena:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Dzieci:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Rozrywka:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Wycieczka:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Szkolenia:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Książki:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Emerytura:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Spłata długów:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Darowizna:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2">Inne wydatki:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>0 zł</div>
								
								<div class="border-bottom border-success my-2"></div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2 fw-bold">Suma:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2 fw-bold"><span class="text-danger pe-1">-</span>3675 zł</div>
														
							</section>
						</div>
					</div>
				</div>
					

					
			</div>

		
		</main>

	</div>
	
	
	
	
	
	<div
      class="modal fade"
      id="timeRange"
      tabindex="-1"
      aria-labelledby="timeRangeLabel"
      aria-hidden="true"
    >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="enrollLabel">Wybierz zakres</h5>
				<button
				type="button"
				class="btn-close"
				data-bs-dismiss="modal"
				aria-label="Close"
				></button>
			</div>
				<form method="post">
					<div class="modal-body">
						<div class="row m-0 m-lg-3">
							<div class="col-12 col-lg-6">
								<div class="input-group mb-3">
									<span class="input-group-text w-25">Od</span>
									<input type="date" class="form-control" aria-label="Date" aria-describedby="date" name="startPeriod" min="1900-01-01" value="" max="2030-12-31" required>
								</div>
							</div>
							
							<div class="col-12 col-lg-6">
								<div class="input-group mb-3">
									<span class="input-group-text w-25">Do</span>
									<input type="date" class="form-control" aria-label="Date" aria-describedby="date" name="endPeriod" min="1900-01-01" value="" max="2030-12-31" required>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button
						type="button"
						class="btn btn-secondary"
						data-bs-dismiss="modal"
						>
						Zamknij
						</button>
						<button type="submit" class="btn btn-success">Wybierz</button>
					</div>
				</form>
			</div>
      	</div>
    </div>
	
	
	
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="javaScript.js"></script>
	
	

</body>
</html>