<html>
	<head>
		<title> Tippy </title>
	</head>
	<style>
		body {background-color: mintcream;}
		h2 {color: deepskyblue;}
		h3 {color: deepskyblue;}
	</style>

	<?php
		// Variables to keep track of
		$bill = NULL; 
		$percentage = 10; 
		$customPerc = NULL; 
		$split = 1; 
		// Potential errors
		$invalidBill = false; 
		$invalidCustom = false; 
		$invalidSplit = false;

		// Functions
		function calculateTip() {
			if ($GLOBALS["percentage"] == -1) { // if custom
				return $GLOBALS["bill"] * $GLOBALS["customPerc"] / 100;
			}
			return $GLOBALS["bill"] * $GLOBALS["percentage"] / 100;
		}

		// Check for errors in bill
		function checkBill($userInput) {
			if ((isset($userInput) && !is_numeric($userInput)) || $userInput <= 0) {
				$GLOBALS["invalidBill"] = true;
			}
			else {
				$GLOBALS["invalidBill"] = false;
			}
		}

		// Check for errors in custom percentage
		function checkPerc($userInput) {
			if ($GLOBALS["percentage"] == -1) {
				if ((isset($userInput) && !is_numeric($userInput)) || $userInput <= 0) {
					$GLOBALS["invalidCustom"] = true;
				}
				else {
					$GLOBALS["invalidCustom"] = false;
				}
			}
			else {
				$GLOBALS["invalidCustom"] = false;
			}
		}

		// Check for errors in split
		function checkSplit($userInput) {
			// check input exists, is a number, is > 0, and is a valid integer
			if ((isset($userInput) && !is_numeric($userInput)) || $userInput <= 0 || (floatval($userInput) != intval($userInput))) {
				$GLOBALS["invalidSplit"] = true;
			}
		}

		// Form Request
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$bill = str_replace(",", "", $_POST["bill"]);
			$percentage = $_POST["percentage"];
			$customPerc = $_POST["customPerc"];
			$split = str_replace(",", "", $_POST["split"]);
			// Validate the inputs
			checkBill($bill);
			checkPerc($customPerc);
			checkSplit($split);
		}
	?>

		<center>

			<h2> Tip Calculator </h2>

			<form method="post" action=" <?php echo $_SERVER["PHP_SELF"]; ?>">
		
				<p> Bill subtotal: $ <input type="text" name="bill" style="width:75px"
 					value="<?php echo $bill; ?>"> 
  					<?php
  						if ($invalidBill) {
    						echo '<p style="color:red"> Please enter a valid bill amount </p>';
  						}
  					?>
  				</p>

  				<p> Tip Percentage: </p>
					<?php 
						// RADIO BUTTONS DONE IN A FOR LOOP
						for ($i = 10; $i <= 20; $i+=5) {
							echo '<input type="radio" name="percentage" value="', $i, '"';
							if ($i == $percentage) {
								echo "checked";
							}
							echo '> ', $i, '%';	
						}
						// Custom radio button
						echo "<br />";
						echo '<input type="radio" name="percentage" value="-1"';
						if ($percentage == -1) {
							echo "checked";
						} 
  						echo '> Custom percentage: ';
  						echo '<input type="text" name="customPerc" style="width:25px" value="', $customPerc, '">%';
  						if ($invalidCustom) {
    						echo '<p style="color:red"> Please enter a valid percentage </p>';
  						}
  						//show the split option
  						echo '<p> Number of people: ';
  						echo '<input type="text" name="split" style="width:25px" value="', $split, '">';
  						if ($invalidSplit) {
    						echo '<p style="color:red"> Please enter a valid number of people </p>';
  						}
  					?>
  				<p> <button type="submit"> Calculate </button> </p>
			</form>
			<?php
				if (isset($bill) && !$invalidBill && !$invalidCustom && !$invalidSplit) {
					echo "<h3> Your Total </h3>";
					echo "Total: $", $bill + calculateTip(), "</p>";
					// if splitting the bill with others
					if ($split > 1) {
						$splitTip = ceil(calculateTip()/$split*100)/100; // round up the number
						$splitTotal = ceil(($bill + calculateTip())/$split*100)/100;
						echo "Split total: $", $splitTotal, "</p>";
					}
				}
			?>
		</center>
	</body>
</html>

