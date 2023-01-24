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
	
	$dateObject= new DateTime($endOfPeriodTime);
	$dateObject->modify( '-1 day' );
	$workingDate = $dateObject->format('Y-m-d');

	$_SESSION['periodTime'] = $startOfPeriodTime.' -zakres czasu- '.$workingDate;


	//echo $_SESSION['periodTime'];
	//echo '<br>';
	//echo $endOfPeriodTime;
	//exit();

	require_once "connect.php";
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
	else
    {
		
		if ($resultCategoryIncomes = @$connection->query(
		sprintf("SELECT * FROM incomes_category_assigned_to_users WHERE user_id='%s'",
		mysqli_real_escape_string($connection,$_SESSION['id']))))
		{
			$i=0;
			$generalSumOfIncomes = 0;
			while ($recordCategoryIncomes = $resultCategoryIncomes->fetch_assoc())
			{
				
				$categoryIncome_id[$i] = $recordCategoryIncomes['id'];
				$categoryIncome_name[$i] = $recordCategoryIncomes['name'];

				$resultIncomes = @$connection->query(
					sprintf("SELECT * FROM incomes WHERE user_id='%s' AND income_category_assigned_to_user_id='%s' AND date_of_income>='%s' AND date_of_income<'%s' ",
					mysqli_real_escape_string($connection,$_SESSION['id']),
					mysqli_real_escape_string($connection,$categoryIncome_id[$i]),
					mysqli_real_escape_string($connection,$startOfPeriodTime),
					mysqli_real_escape_string($connection,$endOfPeriodTime)));

					
					$sumOfIncomes[$i]=0;
					while ($recordIncomes = $resultIncomes->fetch_assoc())
					{
						$sumOfIncomes[$i]+=$recordIncomes['amount'];
					}
				
				$generalSumOfIncomes += $sumOfIncomes[$i];
				$i++;
			}
			$iteratorIncomes = $i;
		}

		if ($resultCategoryExpenses = @$connection->query(
		sprintf("SELECT * FROM expenses_category_assigned_to_users WHERE user_id='%s'",
		mysqli_real_escape_string($connection,$_SESSION['id']))))
		{
			$i=0;
			$generalSumOfExpenses = 0;
			while ($recordCategoryExpenses = $resultCategoryExpenses->fetch_assoc())
			{
				
				$categoryExpense_id[$i] = $recordCategoryExpenses['id'];
				$categoryExpense_name[$i] = $recordCategoryExpenses['name'];

				$resultExpenses = @$connection->query(
					sprintf("SELECT * FROM expenses WHERE user_id='%s' AND expense_category_assigned_to_user_id='%s' AND date_of_expense>='%s' AND date_of_expense<'%s' ",
					mysqli_real_escape_string($connection,$_SESSION['id']),
					mysqli_real_escape_string($connection,$categoryExpense_id[$i]),
					mysqli_real_escape_string($connection,$startOfPeriodTime),
					mysqli_real_escape_string($connection,$endOfPeriodTime)));

					
					$sumOfExpenses[$i]=0;
					while ($recordExpenses = $resultExpenses->fetch_assoc())
					{
						$sumOfExpenses[$i]+=$recordExpenses['amount'];
					}
				
				$generalSumOfExpenses += $sumOfExpenses[$i];
				$i++;
			}
			$iteratorExpenses = $i;
		}

				$balance = $generalSumOfIncomes-$generalSumOfExpenses;

				if($balance >= 200)
				{
					$commentToBalance= 'Gratulacje! Dobrze gospodarujesz swoimi pieniędzmi!';
					$colorText='success';
				}
				elseif ($balance >= -200)
				{
					$commentToBalance= 'Uważaj! Jesteś na granicy płynności!';
					$colorText='warning';
				}
				else
				{
					$commentToBalance= 'Źle gospodarujesz swoimi pieniędzmi! Czas na zmiany...';
					$colorText='danger';
				}






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
									<div class="col-5 h4  text-center fw-bold"><span class="text-success"><?php if(isset($balance)) if($balance>=0) echo '+' ?> </span><?php if(isset($balance)) echo $balance; else echo '0'; ?> zł</div>
								</div>

								<div class="fs-6 text-center <?php echo 'text-'.$colorText.'">'.$commentToBalance ?></div>
								<?php
									if (isset($_SESSION['periodTime']))
									{
										echo '<div class="text-success fs-6 text-center "><br>'.$_SESSION['periodTime'].'</div>';
										unset($_SESSION['periodTime']);
									}
								?>
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
								
								<?php
									if(isset($balance))
									{
										for ($i=0; $i<$iteratorIncomes;$i++)
										{	
											if($sumOfIncomes[$i]>0)
											{
											echo '<div class="col-7 text-start d-inline-block ms-2 my-2">'.$categoryIncome_name[$i].': </div>';
											echo '<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-success pe-1">+</span>'.$sumOfIncomes[$i].' zł</div>';
											}
										}
									}
								?>

								<div class="border-bottom border-success my-2"></div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2 fw-bold">Suma:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2 fw-bold"><span class="text-success pe-1">+</span><?php if(isset($balance)) echo $generalSumOfIncomes; else echo '0'; ?> zł</div>
								
							</section>
						</div>
					</div>
					<div class="col-12 col-lg-6 p-2">
						<div class="border border-success bg-light rounded py-3 px-4">
							<section>
								<header class="h4 text-center mb-3">
									Wydatki
								</header>	
								
								<?php
									if(isset($balance))
									{
										for ($i=0; $i<$iteratorExpenses;$i++)
										{
											if($sumOfExpenses[$i]>0)
											{
											echo '<div class="col-7 text-start d-inline-block ms-2 my-2">'.$categoryExpense_name[$i].': </div>';
											echo '<div class="col-4 text-end d-inline-block me-2 my-2"><span class="text-danger pe-1">-</span>'.$sumOfExpenses[$i].' zł</div>';
											}
										}
									}
								?>

								<div class="border-bottom border-success my-2"></div>
								
								<div class="col-7 text-start d-inline-block ms-2 my-2 fw-bold">Suma:</div>
								<div class="col-4 text-end d-inline-block me-2 my-2 fw-bold"><span class="text-danger pe-1">-</span><?php if(isset($balance)) echo $generalSumOfExpenses; else echo '0'; ?> zł</div>
														
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