<?php

	session_start();
	
	if (!isset($_SESSION['logged']))
	{
		header('Location: login.php');
		exit();
	}
	
	if (isset($_POST['amount']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$validationCorrect=true;

		//Sprawdź poprawność kwoty
		$amount = $_POST['amount'];
		$amount = str_replace(',','.',$amount);
		if(is_numeric($amount)==false)
		{
			$validationCorrect=false;
			$_SESSION['e_amount']="Wpisz poprawny format liczby!";
		}
		else
		{
			$amount = number_format($amount, 2, ',', '');
		}

		//Sprawdź poprawność daty
		$date = $_POST['date'];
		$dateObject= new DateTime($date);
		$day= $dateObject->format("d");
		$month= $dateObject->format("m");
		$year= $dateObject->format("Y");

		if(checkdate($month, $day, $year)==false)
		{
			$validationCorrect=false;
			$_SESSION['e_date']="Wpisz poprawny format daty!";
		}

		//walidacja sposobu płatności
		
		if(isset($_POST['methodPayment']))
		{
			$methodPayment = $_POST['methodPayment'];
			$checkMethodPayment = '/(*UTF8)^[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ\040]*$/';

			if(preg_match($checkMethodPayment, $methodPayment)==false)
			{
				$validationCorrect=false;
				$_SESSION['e_methodPayment']="Metoda płatności może składać się tylko z liter i cyfr";
			}
		}
		else
		{
			$validationCorrect=false;
			$_SESSION['e_methodPayment']="Wybierz metodę płatności!";
		}

		//walidacja kategorii
		
		if(isset($_POST['category']))
		{
			$category = $_POST['category'];
			$checkCategory = '/(*UTF8)^[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ\040]*$/';

			if(preg_match($checkCategory, $category)==false)
			{
				$validationCorrect=false;
				$_SESSION['e_category']="Kategoria może składać się tylko z liter i cyfr";
			}
		}
		else
		{
			$validationCorrect=false;
			$_SESSION['e_category']="Wybierz kategorie!";
		}


		//walidacja komentarza
		$comment = $_POST['comment'];
		$checkComment = '/(*UTF8)^[a-zA-Z0-9ąćęłńóśźżĄĆĘŁŃÓŚŹŻ\040]*$/';

		if(preg_match($checkComment, $comment)==false)
		{
			$validationCorrect=false;
			$_SESSION['e_comment']="Komentarz może składać się tylko z liter i cyfr";
		}
	
		$_SESSION['fr_amount'] = $amount;
		
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
	
				if ($validationCorrect==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy wydatek do bazy
					//echo "Udana walidacja"; exit();
					$user_id = $_SESSION['id'];
					

					 

					if ($connection->query("INSERT INTO expenses VALUES (NULL, '$user_id', '$category', '$methodPayment', '$amount', '$date', '$comment')"))
					{
						$_SESSION['successfulAddExpense']=true;

						header('Location: expenseAdded.php');
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
	
	<meta name="description" content="Budżet osobisty to aplikacja internetowa, która pozwoli Tobie oraz Twojej rodzinie lepiej zarządzać finansami. Dzięki niej, zobaczysz trendy swoich przychodów i wydatków oraz analizę bilansu - co w pewnością pozwoli Ci podjąć pewne przemyślane kroki w celu polepszenia swojej sytuacji materialnej!" />
	<meta name="keywords" content="budżet, osobisty, personal, budget, przychody, wydatki, bilans, finanse, rodzinny, aplikacja, internetowa, oszczędności, portfel" />
	
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Lato:wght@300;400;700&family=Poppins:wght@200;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/fontello.css" type="text/css" />
	<link rel="icon" href="image/logo.png" type="image/png">
	<script type="text/javascript" src="javaScript.js"> </script>
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
		
			<article>
			
				<div class="container px-2 pb-2">
				
					<div class="col-12 border border-success bg-light rounded p-3 ">
						<header class="h3 text-center mb-5">
							Dodaj wydatek
						</header>
						
						<form method="post">
						
							<div class="row m-0 m-lg-3">
								<div class="col-12 col-lg-6">
									<div class="input-group mb-3">
										<?php
											if (isset($_SESSION['e_amount']))
											{
												echo '<div class="text-danger w-100 ms-2 fs-6 position-absolute start-50 translate-middle-x"><br /><br />'.$_SESSION['e_amount'].'</div>';
												unset($_SESSION['e_amount']);
											}
										?>
										<span class="input-group-text w-50">Kwota</span>
										<input type="number" value="<?php
											if (isset($_SESSION['fr_amount']))
											{
												echo $_SESSION['fr_amount'];
												unset($_SESSION['fr_amount']);
											}
										?> step="0.01" class="form-control" placeholder="Podaj kwotę w zł" aria-label="Amount" name="amount" required>
									</div>
								</div>
								
								<div class="col-12 col-lg-6">
									<div class="input-group mb-3">
										<?php
											if (isset($_SESSION['e_date']))
											{
												echo '<div class="text-danger w-100 ms-2 fs-6 position-absolute start-50 translate-middle-x"><br /><br />'.$_SESSION['e_date'].'</div>';
												unset($_SESSION['e_date']);
											}
										?>
										<span class="input-group-text w-50">Data</span>
										<input type="date" class="form-control" aria-label="Date" name="date" min="1900-01-01" value="" max="2030-12-31" required>
									</div>
								</div>
							</div>
								
								
							<div class="row m-0 m-lg-3">
								<div class="col-12 col-lg-6">
									<?php
											if (isset($_SESSION['e_methodPayment']))
											{
												echo '<div class="text-danger w-160 fs-6 position-absolute start-25"><br /><br />'.$_SESSION['e_methodPayment'].'</div>';
												unset($_SESSION['e_methodPayment']);
											}
										?>
									<div class="input-group mb-3">
										<span class="input-group-text w-50">Sposób płatności </span>
										<select class="form-select" name="methodPayment" aria-label="payment">

											<option value="none" selected disabled> </option>
											<?php
												for ($i=1; $i<=$_SESSION['iteratorMethodsPayment'];$i++)
												{
													echo '<option value="'.$_SESSION['methodPayment_id'][$i-1].'">'.$_SESSION['methodPayment_name'][$i-1].'</option>';
												}
											?>


										</select>		
									</div>
								</div>
								
								<div class="col-12 col-lg-6">
									<?php
											if (isset($_SESSION['e_category']))
											{
												echo '<div class="text-danger w-160 fs-6 position-absolute start-25"><br /><br />'.$_SESSION['e_category'].'</div>';
												unset($_SESSION['e_category']);
											}
										?>
									<div class="input-group mb-3">
										<span class="input-group-text w-50">Kategoria</span>
										<select class="form-select" name="category" aria-label="category">
											<option value="cat0" selected disabled>	</option>
											<?php
												for ($i=1; $i<=$_SESSION['iteratorExpenses'];$i++)
												{
													echo '<option value="'.$_SESSION['categoryExpense_id'][$i-1].'">'.$_SESSION['categoryExpense_name'][$i-1].'</option>';
												}
											?>


										</select>								
									</div>
								</div>
							</div>
							

							<div class="row m-0 m-lg-3">
								<div class="col-12">
									<?php
											if (isset($_SESSION['e_comment']))
											{
												echo '<div class="text-danger w-200 ms-2 fs-6 position-absolute start-50 translate-middle-x"><br /><br />'.$_SESSION['e_comment'].'</div>';
												unset($_SESSION['e_comment']);
											}
										?>
									<div class="input-group mb-3">
										<span class="input-group-text">Komentarz</span>
										<input type="text" name="comment" class="form-control" placeholder="Dodaj komentarz" aria-label="Comment">
									</div>
								</div>
							</div>
							
							<div class="btn-group btn-group-lg start-50 translate-middle mt-5" role="group">
								<button type="button" class="btn btn-outline-success me-2" onclick="location.href='mainmenu.php';">Anuluj</button>
								<button type="submit" class="btn btn-success ms-2">Dodaj</button>
							</div>

						</form>
						
					</div>
				
				</div>
		
			</article>
			
		</main>

		
	</div>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>