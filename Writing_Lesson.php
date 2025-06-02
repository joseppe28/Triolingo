<?php
session_start();

if(!isset($_SESSION['Lives'])) {
    $_SESSION['Lives'] = 3; // Initialize lives if not already set
}

// Check if vocabList exists in session, otherwise create a sample one
if (!isset($_SESSION['vocabList']) || empty($_SESSION['vocabList'])) {
    // For testing purposes, if no vocab list exists, create a sample one
    $_SESSION['vocabList'] = [
        ['vocab' => 'Haus', 'translation' => 'house', 'level' => 1],
        ['vocab' => 'Auto', 'translation' => 'car', 'level' => 1],
        ['vocab' => 'Katze', 'translation' => 'cat', 'level' => 1],
        ['vocab' => 'Hund', 'translation' => 'dog', 'level' => 1],
        ['vocab' => 'Schule', 'translation' => 'school', 'level' => 1]
    ];
}

// Debug output
// echo "<pre>"; print_r($_SESSION['vocabList']); echo "</pre>";
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo - Writing Lesson</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            background-attachment: fixed;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
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
        .main-content-center {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 90px;
            padding-bottom: 40px;
        }
        .writing-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            padding: 2.2rem 1.2rem 1.2rem 1.2rem;
            margin-bottom: 24px;
            max-width: 700px;
            width: 100%;
        }
        .writing-title {
            font-family: 'Pacifico', cursive;
            font-size: 2rem;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 0.7rem;
        }
        .lives-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin-bottom: 1.2rem;
        }
        .lives-bar .bi-heart-fill {
            color: #e74a3b;
            font-size: 1.5rem;
        }
        .lives-bar .bi-heart {
            color: #e74a3b;
            font-size: 1.5rem;
            opacity: 0.3;
        }
        .writing-progress {
            height: 22px;
            width: 340px;
            max-width: 90vw;
            margin: 0 auto 24px auto;
        }
        .german-word {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
            font-family: 'Arial', cursive;
            text-align: right;
        }
        .form-control-lg {
            font-size: 1.2rem;
            border-radius: 1rem;
        }
        .feedback-area {
            min-height: 38px;
        }
        .writing-btns {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        @media (max-width: 700px) {
            .writing-card { padding: 1.2rem 0.2rem; }
        }
        @media (max-width: 600px) {
            .main-content-center { padding-top: 70px; }
            .writing-title { font-size: 1.2rem; }
            .writing-progress { width: 98vw; }
        }
    </style>
</head>
<body>
    <div class="sticky-header">
        <h2><i class="bi bi-pencil me-2"></i>Writing Practice</h2>
    </div>
    <div class="main-content-center">
        <div class="writing-card mx-auto">
            <div class="lives-bar mb-3" id="lives-bar"></div>
            <div class="writing-progress progress mb-4">
                <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100">
                    <span id="progress-text" style="color: #fff; font-weight: 600;"></span>
                </div>
            </div>
            <div class="text-center mb-4">
                <p class="fs-5">Word <span id="current-index" class="fw-bold">1</span> of 
                <span id="total-words" class="fw-bold"><?= count($_SESSION['vocabList']) ?></span></p>
            </div>
            <div class="row align-items-center justify-content-center mb-4">
                <div class="col-md-4 text-md-end">
                    <div class="german-word" id="german-word"></div>
                </div>
                <div class="col-md-6">
                    <input type="text" id="answer-input" class="form-control form-control-lg" 
                           placeholder="Type the English translation">
                    <div id="feedback" class="mt-2 feedback-area" style="display: none;"></div>
                </div>
            </div>
            <div class="writing-btns">
                <button id="action-button" class="btn btn-primary btn-lg px-4">Check</button>
                <button id="next-lesson-btn" class="btn btn-success d-none">Go to Next Lesson</button>
                <button id="main-menu-btn" class="btn btn-danger d-none">Go to Main Menu</button>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Store vocab list from PHP session - ensure it's not empty
        const vocabList = <?= json_encode($_SESSION['vocabList']) ?>;
        let currentIndex = 0;
        let isChecking = true;
        let lives = <?= $_SESSION['Lives'] ?>;
        let completedWords = 0;

        // Render hearts for lives
        function renderLivesBar() {
            const livesBar = document.getElementById('lives-bar');
            livesBar.innerHTML = '';
            for (let i = 0; i < lives; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart-fill"></i>';
            }
            for (let i = lives; i < 3; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart"></i>';
            }
        }
        renderLivesBar();

        // Fortschrittsbalken aktualisieren
        function updateProgressBar() {
            const progress = Math.round(((currentIndex + 1) / vocabList.length) * 100);
            document.getElementById('progress-bar').style.width = progress + "%";
            document.getElementById('progress-bar').setAttribute('aria-valuenow', progress);
            document.getElementById('progress-text').textContent = (currentIndex + 1) + " / " + vocabList.length;
        }

        // Load the first word
        loadCurrentWord();

        function loadCurrentWord() {
            if (!vocabList || vocabList.length === 0) {
                console.error("Vocab list is empty!");
                document.getElementById('german-word').textContent = "No vocabulary loaded";
                return;
            }
            const currentWord = vocabList[currentIndex];
            document.getElementById('german-word').textContent = currentWord.vocab;
            document.getElementById('current-index').textContent = currentIndex + 1;
            updateProgressBar();
            renderLivesBar();

            // Reset interface
            document.getElementById('answer-input').value = '';
            document.getElementById('answer-input').disabled = false;
            document.getElementById('answer-input').focus();
            document.getElementById('feedback').style.display = 'none';
            document.getElementById('action-button').textContent = 'Check';
            isChecking = true;
        }

        document.getElementById('action-button').addEventListener('click', function() {
            if (isChecking) {
                checkAnswer();
            } else {
                currentIndex = (currentIndex + 1) % vocabList.length;
                completedWords++;
                if (completedWords >= vocabList.length) {
                    document.getElementById('action-button').classList.add('d-none');
                    document.getElementById('next-lesson-btn').classList.remove('d-none');
                    document.getElementById('feedback').innerHTML = '<div class="alert alert-success">Congratulations! You\'ve completed all words!</div>';
                    document.getElementById('feedback').style.display = 'block';
                } else {
                    loadCurrentWord();
                }
            }
        });

        document.getElementById('answer-input').addEventListener('keypress', function(event) {
            if (event.key === 'Enter' && isChecking) {
                checkAnswer();
            }
        });

        function checkAnswer() {
            const userAnswer = document.getElementById('answer-input').value.trim().toLowerCase();
            const correctAnswer = vocabList[currentIndex].translation.toLowerCase();
            const feedbackElement = document.getElementById('feedback');
            var VID = vocabList[currentIndex].VID;

            document.getElementById('answer-input').disabled = true;
            feedbackElement.style.display = 'block';

            if (userAnswer === correctAnswer) {
                feedbackElement.innerHTML = '<div class="alert alert-success">Correct!</div>';
                lowerMistake(VID);
                updateVocabLevel(vocabList[currentIndex].vocab, true);
            } else {
                const similarity = calculateSimilarity(userAnswer, correctAnswer);
                if (similarity >= 0.7) {
                    feedbackElement.innerHTML = '<div class="alert alert-warning">Almost correct! The correct answer is: ' + correctAnswer + '</div>';
                } else {
                    feedbackElement.innerHTML = '<div class="alert alert-danger">Incorrect. The correct answer is: ' + correctAnswer + '</div>';
                    updateVocabLevel(vocabList[currentIndex].vocab, false);
                    raiseMistake(VID);
                    removeLife();
                }
            }

            document.getElementById('action-button').textContent = 'Next';
            isChecking = false;
        }

        function removeLife() {
            fetch('remove_life.php', {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                lives = data.lives;
                renderLivesBar();
                if (lives <= 0) {
                    document.getElementById('action-button').classList.add('d-none');
                    document.getElementById('main-menu-btn').classList.remove('d-none');
                    document.getElementById('feedback').innerHTML += '<div class="alert alert-danger mt-2">You have lost all your lives!</div>';
                }
            })
            .catch(error => {
                console.error('Error updating life:', error);
            });
        }

        function updateVocabLevel(germanWord, isCorrect) {
            fetch('update_vocab_level.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `word=${encodeURIComponent(germanWord)}&correct=${isCorrect ? 1 : 0}`
            })
            .then(response => response.text())
            .then(data => {
                // Optional: handle response
            })
            .catch(error => {
                console.error('Error updating level:', error);
            });
        }

        document.getElementById('next-lesson-btn').addEventListener('click', function() {
            window.location.href = 'completeLesson.php';
        });

        document.getElementById('main-menu-btn').addEventListener('click', function() {
            window.location.href = 'Main.php';
        });

        function calculateSimilarity(a, b) {
            if (a.length === 0) return b.length === 0 ? 1 : 0;
            if (b.length === 0) return 0;
            const matrix = [];
            for (let i = 0; i <= b.length; i++) {
                matrix[i] = [i];
            }
            for (let j = 0; j <= a.length; j++) {
                matrix[0][j] = j;
            }
            for (let i = 1; i <= b.length; i++) {
                for (let j = 1; j <= a.length; j++) {
                    const cost = b.charAt(i - 1) === a.charAt(j - 1) ? 0 : 1;
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j] + 1,
                        matrix[i][j - 1] + 1,
                        matrix[i - 1][j - 1] + cost
                    );
                }
            }
            const maxLen = Math.max(a.length, b.length);
            const distance = matrix[b.length][a.length];
            return 1 - distance / maxLen;
        }

        function raiseMistake(VID){
            if (!VID) return;
            fetch('raise_mistake.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `VID=${encodeURIComponent(VID)}`
            })
            .then(response => response.text())
            .then(data => {
                // Optional: handle response
            })
            .catch(error => {
                console.error('Error raising mistake:', error);
            });
        }

        function lowerMistake(VID){
            if (!VID) return;
            fetch('lowerMistake.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `VID=${encodeURIComponent(VID)}`
            })
            .then(response => response.text())
            .then(data => {
                // Optional: handle response
            })
            .catch(error => {
                console.error('Error lowering mistake:', error);
            });
        }
    </script>
</body>
</html>