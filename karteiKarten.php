<?php

Session_start(); // Start the session
// db_connection.php
$_SESSION['Lives'] = 3; // Initialize lives if not already set

$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "root";            // Replace with your database password
$dbname = "Triolingo"; // Replace with your database name

// Create a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch random vocab from the database
$einheit = $_POST["einheit"]; // The selected Einheit ID
$vocab_count = $_POST["vocab_count"]; // Number of vocab to fetch

// First get all vocabulary for this Einheit and join with level data
$query = "SELECT v.*, IFNULL(l.Level, 0) as Level 
          FROM vocab v 
          LEFT JOIN level l ON v.VID = l.VID AND l.UID = ? 
          WHERE v.EinID = ? 
          ORDER BY IFNULL(l.Level, 0) ASC, RAND() 
          LIMIT ?";

$stmt = $conn->prepare($query);
$userid = $_SESSION['UserID'] ?? 0; // Get user ID from session or default to 0
if ($userid == 0) {
    // Handle case where user ID is not set
    echo "User ID not set.";
    exit();
}
$stmt->bind_param("iii", $userid, $einheit, $vocab_count);
$stmt->execute();
$result = $stmt->get_result();

$vocabList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get the German word from Deutsch_Vocab table
        $queryDE = "SELECT Wort FROM Deutsch_Vocab WHERE DID = ?";
        $stmtDE = $conn->prepare($queryDE);
        $stmtDE->bind_param("i", $row['DID']);
        $stmtDE->execute();
        $resultDE = $stmtDE->get_result();
        $germanWord = $resultDE->fetch_assoc()['Wort'];
        
        // Get the English word from English_vocab table
        $queryEN = "SELECT Wort FROM Englisch_Vocab WHERE EID = ?";
        $stmtEN = $conn->prepare($queryEN);
        $stmtEN->bind_param("i", $row['EID']);
        $stmtEN->execute();
        $resultEN = $stmtEN->get_result();
        $englishWord = $resultEN->fetch_assoc()['Wort'];
        
        // Store both words and the level in the vocab list
        $vocabList[] = [
            'vocab' => $germanWord,
            'translation' => $englishWord,
            'level' => $row['Level']
        ];
    }
}

// Convert PHP array to JSON for JavaScript
$vocabListJson = json_encode($vocabList);
$_SESSION['vocabList'] = $vocabList;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocab Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 300px;
            height: 200px;
            perspective: 1000px;
            margin-bottom: 20px;
        }
        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .card:hover .card-inner {
            transform: rotateY(180deg);
        }
        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .card-back {
            transform: rotateY(180deg);
            background-color: #f8f9fa;
        }
        .navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .nav-button {
            padding: 10px 15px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-container">
            <div class="card">
                <div class="card-inner">
                    <div class="card-front">
                        <h3 id="vocab-front"></h3>
                    </div>
                    <div class="card-back">
                        <h3 id="vocab-back"></h3>
                    </div>
                </div>
            </div>
            
            <div class="navigation-buttons">
                <button id="prev-btn" class="btn btn-primary nav-button">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button id="next-btn" class="btn btn-primary nav-button">
                    <i class="bi bi-arrow-right"></i>
                </button>
                <a href="Matching_Lesson.php" id="finish-btn" class="btn btn-success nav-button" style="display: none;">
                    Finish <i class="bi bi-check-lg"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Get vocab list from PHP
        const vocabList = <?php echo $vocabListJson; ?>;
        let currentIndex = 0;
        
        // Function to update card content
        function updateCard() {
            document.getElementById('vocab-front').textContent = vocabList[currentIndex].vocab;
            document.getElementById('vocab-back').textContent = vocabList[currentIndex].translation;
            
            // Update navigation buttons
            document.getElementById('prev-btn').disabled = (currentIndex === 0);
            
            if (currentIndex === vocabList.length - 1) {
                document.getElementById('next-btn').style.display = 'none';
                document.getElementById('finish-btn').style.display = 'inline-block';
            } else {
                document.getElementById('next-btn').style.display = 'inline-block';
                document.getElementById('finish-btn').style.display = 'none';
            }
        }
        
        // Event listeners for navigation
        document.getElementById('prev-btn').addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCard();
            }
        });
        
        document.getElementById('next-btn').addEventListener('click', function() {
            if (currentIndex < vocabList.length - 1) {
                currentIndex++;
                updateCard();
            }
        });
        
        // Initialize the card
        window.onload = function() {
            if (vocabList.length > 0) {
                updateCard();
            } else {
                document.querySelector('.card-container').innerHTML = "<p>No vocab found for the selected Einheit.</p>";
            }
        };
    </script>
</body>
</html>
