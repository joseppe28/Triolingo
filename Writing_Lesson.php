<?php
// filepath: c:\xampp\htdocs\Triolingo\Triolingo\Writing_Lesson.php
session_start();

// Ensure we have a prev_page to return to, default to Main.php if not set
if (!isset($_SESSION['prev_page'])) {
    $_SESSION['prev_page'] = 'Main.php';
}

// Initialize lives if not already set and this is a lesson
if (!isset($_SESSION['Lives'])) {
    $_SESSION['Lives'] = 3;
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
        /* Close button styling */
        .close-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: white;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            border: none;
            transition: transform 0.2s, background-color 0.2s;
            z-index: 1100;
        }
        .close-btn:hover, .close-btn:focus {
            background-color: #f8f9fa;
            transform: scale(1.1);
            color: #dc3545;
        }
        @media (max-width: 700px) {
            .writing-card { padding: 1.2rem 0.2rem; }
        }
        @media (max-width: 600px) {
            .main-content-center { padding-top: 70px; }
            .writing-title { font-size: 1.2rem; }
            .writing-progress { width: 98vw; }
            .close-btn { 
                top: 10px;
                right: 10px;
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Close Button -->
    <button id="close-btn" class="close-btn" title="Zurück zur vorherigen Seite">
        <i class="bi bi-x-lg"></i>
    </button>

    <div class="sticky-header">
        <h2><i class="bi bi-pencil me-2"></i>Writing Practice</h2>
    </div>
    <div class="main-content-center">
        <div class="writing-card mx-auto">
            <?php if ($_SESSION['is_lesson']): ?>
            <!-- Only show lives if this is a lesson -->
            <div class="text-center mb-3">
                <div class="lives-bar mb-3" id="lives-bar"></div>
            </div>
            <?php endif; ?>
            
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
        let completedWords = 0;
        let lives = <?= $_SESSION['Lives'] ?>;
        const maxLives = 3;
        const isLesson = <?php echo json_encode($_SESSION['is_lesson'] ?? false); ?>;
        const prevPage = <?php echo json_encode($_SESSION['prev_page']); ?>;

        // Render hearts for lives if this is a lesson
        if (isLesson) {
            renderLivesBar();
        }
        
        function renderLivesBar() {
            const livesBar = document.getElementById('lives-bar');
            if (!livesBar) return;
            
            livesBar.innerHTML = '';
            for (let i = 0; i < lives; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart-fill"></i>';
            }
            for (let i = lives; i < maxLives; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart"></i>';
            }
        }

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
                    
                    // If it's a lesson, go to next lesson, otherwise return to prev page
                    if (!isLesson) {
                        document.getElementById('next-lesson-btn').classList.remove('d-none');
                        document.getElementById('next-lesson-btn').textContent = 'Return to Previous Page';
                        document.getElementById('next-lesson-btn').addEventListener('click', function() {
                            window.location.href = prevPage;
                        });
                    } else {
                        document.getElementById('next-lesson-btn').classList.remove('d-none');
                        document.getElementById('next-lesson-btn').textContent = 'Back to Main Menu';
                        document.getElementById('next-lesson-btn').addEventListener('click', function() {
                            window.location.href = 'completeLesson.php';
                        });
                        document.getElementById('return-btn').addEventListener('click', function() {
                            window.location.href = 'Main.php';
                        });
                    }
                    
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
            const VID = vocabList[currentIndex].VID;

            document.getElementById('answer-input').disabled = true;
            feedbackElement.style.display = 'block';

            if (userAnswer === correctAnswer) {
                feedbackElement.innerHTML = '<div class="alert alert-success">Correct!</div>';
                if (VID) lowerMistake(VID);
                updateVocabLevel(vocabList[currentIndex].vocab, true);
            } else {
                const similarity = calculateSimilarity(userAnswer, correctAnswer);
                if (similarity >= 0.7) {
                    feedbackElement.innerHTML = '<div class="alert alert-warning">Almost correct! The correct answer is: ' + correctAnswer + '</div>';
                } else {
                    feedbackElement.innerHTML = '<div class="alert alert-danger">Incorrect. The correct answer is: ' + correctAnswer + '</div>';
                    updateVocabLevel(vocabList[currentIndex].vocab, false);
                    if (VID) raiseMistake(VID);
                    
                    // Only reduce lives if this is a lesson
                    if (isLesson) {
                        removeLife();
                    }
                }
            }

            document.getElementById('action-button').textContent = 'Next';
            isChecking = false;
        }

        // Close button event handler - redirect to previous page
        document.getElementById('close-btn').addEventListener('click', function() {
            window.location.href = prevPage;
        });

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

        // Function to remove life with AJAX - only if this is a lesson
        function removeLife() {
            if (!isLesson) return;
            
            fetch('remove_life.php', {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                lives = data.lives;
                if (document.getElementById('lives-count')) {
                    document.getElementById('lives-count').textContent = lives;
                }
                renderLivesBar();
                
                // Check if lives are exhausted
                if (lives <= 0) {
                    document.getElementById('action-button').classList.add('d-none');
                    document.getElementById('return-btn').classList.remove('d-none');
                    document.getElementById('return-btn').textContent = 'Return to Main Menu';
                    document.getElementById('return-btn').addEventListener('click', function() {
                        window.location.href = 'Main.php';
                    });
                    document.getElementById('feedback').innerHTML = '<div class="alert alert-danger">You have lost all your lives!</div>';
                    document.getElementById('feedback').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error updating life:', error);
            });
        }

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