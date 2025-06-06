<?php
session_start();

$_SESSION['Lives'] = 3; // Reset lives for the new lesson

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "Triolingo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch random vocab from the database
$einheit = $_POST["einheit"];
$vocab_count = $_POST["vocab_count"];
$_SESSION['lesson'] = $_POST["lesson"];

$query = "SELECT v.*, IFNULL(l.Level, 0) as Level 
          FROM vocab v 
          LEFT JOIN level l ON v.VID = l.VID AND l.UID = ? 
          WHERE v.EinID = ? 
          ORDER BY IFNULL(l.Level, 0) ASC, RAND() 
          LIMIT ?";

$stmt = $conn->prepare($query);
$userid = $_SESSION['UserID'] ?? 0;
if ($userid == 0) {
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
        
        // Get the English word from Englisch_Vocab table
        $queryEN = "SELECT Wort FROM Englisch_Vocab WHERE EID = ?";
        $stmtEN = $conn->prepare($queryEN);
        $stmtEN->bind_param("i", $row['EID']);
        $stmtEN->execute();
        $resultEN = $stmtEN->get_result();
        $englishWord = $resultEN->fetch_assoc()['Wort'];
        
        $vocabList[] = [
            'vocab' => $germanWord,
            'translation' => $englishWord,
            'level' => $row['Level'], 
            'VID' => $row['VID'],
        ];
    }
}

$vocabListJson = json_encode($vocabList);
$_SESSION['vocabList'] = $vocabList;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karteikarten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }
        .main-content-center {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 80px;
            padding-bottom: 40px;
        }
        .sticky-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            z-index: 1050;
            background: transparent;
            box-shadow: none;
            text-align: center;
            padding: 0;
        }
        .sticky-header h2 {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            letter-spacing: 1px;
            margin-bottom: 0;
            margin-top: 32px;
            color: #222;
            background: transparent;
            padding: 0;
        }
        .card-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 40px;
        }
        .progress {
            height: 22px;
            width: 340px;
            max-width: 90vw;
            margin-bottom: 24px;
        }
        .card {
            width: 340px;
            height: 180px;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            color: #222;
            font-weight: 600;
            border: none;
            margin-bottom: 24px;
            transition: transform 0.13s, box-shadow 0.13s, background 0.13s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            perspective: 1000px;
        }
        .card-inner {
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            position: relative;
        }
        .card.flipped .card-inner {
            transform: rotateY(180deg);
        }
        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 1.5rem;
            background: transparent;
            box-shadow: none;
        }
        .card-front h3, .card-back h3 {
            font-family: 'Arial', cursive;
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 0;
        }
        .card-back {
            transform: rotateY(180deg);
            background: transparent;
        }
        .navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }
        .nav-button {
            padding: 10px 15px;
            font-size: 20px;
            border-radius: 1rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            border: none;
            transition: background 0.13s, color 0.13s;
        }
        .nav-button:focus {
            outline: none;
            box-shadow: 0 0 0 2px #0d6efd33;
        }
        #finish-btn {
            font-size: 1.1rem;
            padding: 10px 22px;
        }
        .flip-hint {
            font-size: 0.95rem;
            color: #888;
            margin-bottom: 10px;
            text-align: center;
        }
        @media (max-width: 600px) {
            .main-content-center { padding-top: 70px; }
            .card { width: 98vw; min-width: 0; height: 130px; }
            .progress { width: 98vw; }
            .card-front h3, .card-back h3 { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="sticky-header">
        <h2><i class="bi bi-journal-text me-2"></i>Karteikarten</h2>
    </div>
    <div class="main-content-center">
        <div class="card-container">
            <div class="flip-hint">Klicke auf die Karte, um die Übersetzung zu sehen</div>
            <div class="progress mb-4">
                <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100">
                    <span id="progress-text" style="color: #fff; font-weight: 600;"></span>
                </div>
            </div>
            <div class="card" id="flashcard">
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
                <a href="talking_lesson.php" id="finish-btn" class="btn btn-success nav-button" style="display: none;">
                    Finish <i class="bi bi-check-lg"></i>
                </a>
            </div>
        </div>
    </div>
    <script>
        const vocabList = <?php echo $vocabListJson; ?>;
        let currentIndex = 0;
        let isFlipped = false;

        function updateCard() {
            document.getElementById('vocab-front').textContent = vocabList[currentIndex].vocab;
            document.getElementById('vocab-back').textContent = vocabList[currentIndex].translation;
            document.getElementById('flashcard').classList.remove('flipped');
            isFlipped = false;

            document.getElementById('prev-btn').disabled = (currentIndex === 0);

            // Fortschrittsbalken aktualisieren
            const progress = Math.round(((currentIndex + 1) / vocabList.length) * 100);
            document.getElementById('progress-bar').style.width = progress + "%";
            document.getElementById('progress-bar').setAttribute('aria-valuenow', progress);
            document.getElementById('progress-text').textContent = (currentIndex + 1) + " / " + vocabList.length;

            if (currentIndex === vocabList.length - 1) {
                document.getElementById('next-btn').style.display = 'none';
                document.getElementById('finish-btn').style.display = 'inline-block';
            } else {
                document.getElementById('next-btn').style.display = 'inline-block';
                document.getElementById('finish-btn').style.display = 'none';
            }
        }

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

        document.getElementById('flashcard').addEventListener('click', function() {
            isFlipped = !isFlipped;
            if (isFlipped) {
                this.classList.add('flipped');
            } else {
                this.classList.remove('flipped');
            }
        });

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
<?php
$stmt->close();
$conn->close();
?>