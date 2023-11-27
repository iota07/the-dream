<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto+Mono:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<link rel="stylesheet" href="style.css">

<?php
$resultMessage = ""; // Initialize a variable to store the result message

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $from_currency = isset($_GET["from"]) ? $_GET["from"] : "";
    $to_currency = isset($_GET["to"]) ? $_GET["to"] : "";
    $amount = isset($_GET["amount"]) ? $_GET["amount"] : "";

    // Make sure the required fields are not empty
    if (!empty($from_currency) && !empty($to_currency) && !empty($amount)) {
        // Make API request
        $curl = curl_init();
        $url = "https://currency-conversion-and-exchange-rates.p.rapidapi.com/convert?from=$from_currency&to=$to_currency&amount=$amount";
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: currency-conversion-and-exchange-rates.p.rapidapi.com",
                "X-RapidAPI-Key: 9449946218mshf1b337a43cde327p198c7fjsn57b8ab1cb0cd"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $resultMessage = "cURL Error #:" . $err;
        } else {
            $result = json_decode($response, true);

            // Check if the API request was successful
            if (isset($result['success']) && $result['success']) {
                $converted_amount = $result['result'];

                // Set the result message
                $resultMessage = "$amount $from_currency is equal to $converted_amount $to_currency";
            } else {
                $resultMessage = "Error - The entered amount is incorrect";
            }
        }
    } else {
        $resultMessage = "Enter an amount to convert";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Exchange</title>
</head>
<body class="d-flex align-items-center justify-content-center overflow-auto">
    

    <div class="container col-sm-12 col-md-12 col-lg-12 d-flex flex-column  align-items-center justify-content-center">
        <img class="worldmap" src="./src/7605.jpg">
        
        <div class="card col-10 col-sm-8 col-md-6 col-lg-5 appli align-items-center justify-content-center m-1">
            <h1>Live Currency Exchange</h1>

            <form action="convert.php" method="GET">
                <div class="form-group m-2">
                    <label for="from">From:</label>
                    <select class="custom-select mb-2" id="from" name="from" required>
                        
                    <?php
                        $fromCurrencyOptions = ["USD", "EUR", "JPY", "GBP", "CNY", "CAD", "AUD", "CHF"];
                        foreach ($fromCurrencyOptions as $option) {
                            echo "<option value=\"$option\"";
                            if ($option === $from_currency) {
                                echo " selected"; // Cette ligne ajoute l'attribut selected si l'option correspond à $from_currency
                            }
                            echo ">$option</option>";
                        }
                    ?>                    
                            
                    </select>
                    
                    <button class="btn btn-outline-secondary" type="button" onclick="invertSelection()"><span class="material-symbols-outlined">sync_alt</span></button>

                    <label for="to">To:</label>
                    <select class="custom-select mb-2" id="to" name="to" required>
                        
                    <?php
                        $toCurrencyOptions = ["USD", "EUR", "JPY", "GBP", "CNY", "CAD", "AUD", "CHF"];
                        foreach ($toCurrencyOptions as $option) {
                            echo "<option value=\"$option\"";
                            if ($option === $to_currency) {
                                echo " selected"; // Cette ligne ajoute l'attribut selected si l'option correspond à $to_currency
                            }
                            echo ">$option</option>";
                        }
                    ?>
                        
                    </select>
                    
                    <label for="amount">Amount:</label>
                    <input class="input-group form-control mt-1 mb-2" type="text" id="amount" name="amount" required>

                    <button class="btn btn-outline-secondary" type="submit">Convert</button>
                </div>
                
                
            </form>

            <!-- Display the result message after the form -->
            <?php echo $resultMessage; ?>
        </div>
    </div>  
     
    <script>
        function invertSelection() {
            var fromCurrency = document.getElementById('from').value;
            var toCurrency = document.getElementById('to').value;

            document.getElementById('from').value = toCurrency;
            document.getElementById('to').value = fromCurrency;
        }
    </script>

</body>
</html>

