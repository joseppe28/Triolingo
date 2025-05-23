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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo - Writing Lesson</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .german-word {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body p-4">
                <h1 class="text-center text-primary mb-4">Writing Practice</h1>
                <p class="text-center text-danger fw-bold">Lives Remaining: <span id="lives-count"><?= $_SESSION['Lives'] ?></span></p>
                
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
                        <div id="feedback" class="mt-2" style="display: none;"></div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button id="action-button" class="btn btn-primary btn-lg px-4">Check</button>
                </div>
                <div class="text-center mt-3">
                    <button id="next-lesson-btn" class="btn btn-success d-none">Go to Next Lesson</button>
                    <button id="main-menu-btn" class="btn btn-danger d-none">Go to Main Menu</button>
                </div>
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
        
        // Load the first word
        loadCurrentWord();

        function loadCurrentWord() {
            if (!vocabList || vocabList.length === 0) {
                console.error("Vocab list is empty!");
                document.getElementById('german-word').textContent = "No vocabulary loaded";
                return;
            }
            
            // Make sure we're accessing the correct properties
            const currentWord = vocabList[currentIndex];
            
            // Use the vocab property to display the word (not german)
            document.getElementById('german-word').textContent = currentWord.vocab;
            document.getElementById('current-index').textContent = currentIndex + 1;
            
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
                // Check the answer
                checkAnswer();
            } else {
                // Go to next word
                currentIndex = (currentIndex + 1) % vocabList.length;
                completedWords++;
                
                // Check if we've completed all words
                if (completedWords >= vocabList.length) {
                    document.getElementById('action-button').classList.add('d-none');
                    document.getElementById('next-lesson-btn').classList.remove('d-none');
                    document.getElementById('feedback').innerHTML = '<div class="alert alert-success">Congratulations! You\'ve completed all words!</div>';
                    document.getElementById('feedback').style.display = 'block';
                    
                    // Save lives back to session
                    updateLives(lives);
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
            const correctAnswer = vocabList[currentIndex].translation.toLowerCase(); // Use translation instead of english
            const feedbackElement = document.getElementById('feedback');
            var VID = vocabList[currentIndex].VID;
            
            document.getElementById('answer-input').disabled = true;
            feedbackElement.style.display = 'block';
            
            if (userAnswer === correctAnswer) {
                // Correct answer
                feedbackElement.innerHTML = '<div class="alert alert-success">Correct!</div>';

                lowerMistake(VID);
                
                // Increase vocab level
                updateVocabLevel(vocabList[currentIndex].vocab, true); // Use vocab instead of german
            } else {
                // Incorrect answer - check for minor mistakes
                const similarity = calculateSimilarity(userAnswer, correctAnswer);
                
                if (similarity >= 0.7) {
                    // Minor mistake
                    feedbackElement.innerHTML = '<div class="alert alert-warning">Almost correct! The correct answer is: ' + correctAnswer + '</div>';
                } else {
                    // Major mistake
                    feedbackElement.innerHTML = '<div class="alert alert-danger">Incorrect. The correct answer is: ' + correctAnswer + '</div>';
                    
                    // Decrease vocab level
                    updateVocabLevel(vocabList[currentIndex].vocab, false); // Use vocab instead of german
                    
                    console.log("VID: " + VID);
                    // Raise mistake count
                    raiseMistake(VID);
                    // Decrease lives
                    lives--;
                    document.getElementById('lives-count').textContent = lives;
                    
                    // Also update session
                    updateLives(lives);
                    
                    // Check if out of lives
                    if (lives <= 0) {
                        document.getElementById('action-button').classList.add('d-none');
                        document.getElementById('main-menu-btn').classList.remove('d-none');
                        feedbackElement.innerHTML += '<div class="alert alert-danger mt-2">You have lost all your lives!</div>';
                        return;
                    }
                }
            }
            
            document.getElementById('action-button').textContent = 'Next';
            isChecking = false;
        }
        
        // Function to update lives in session
        function updateLives(newLives) {
            <?= $_SESSION['Lives'] = $_SESSION['Lives'] -1 ?>;
        }
        
        // Function to update vocab level
        function updateVocabLevel(germanWord, isCorrect) {
            // Create a request to update the level
            fetch('update_vocab_level.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `word=${encodeURIComponent(germanWord)}&correct=${isCorrect ? 1 : 0}`
            })
            .then(response => response.text())
            .then(data => {
                console.log('Level update response:', data);
            })
            .catch(error => {
                console.error('Error updating level:', error);
            });
        }
        
        // Add event listeners for navigation buttons
        document.getElementById('next-lesson-btn').addEventListener('click', function() {
            window.location.href = 'completeLesson.php'; // Or any next lesson page
        });
        
        document.getElementById('main-menu-btn').addEventListener('click', function() {
            window.location.href = 'Main.php';
        });
        
        // Function to calculate similarity between two strings (Levenshtein distance based)
        function calculateSimilarity(a, b) {
            if (a.length === 0) return b.length === 0 ? 1 : 0;
            if (b.length === 0) return 0;
            
            const matrix = [];
            
            // Initialize matrix
            for (let i = 0; i <= b.length; i++) {
                matrix[i] = [i];
            }
            
            for (let j = 0; j <= a.length; j++) {
                matrix[0][j] = j;
            }
            
            // Fill matrix
            for (let i = 1; i <= b.length; i++) {
                for (let j = 1; j <= a.length; j++) {
                    const cost = b.charAt(i - 1) === a.charAt(j - 1) ? 0 : 1;
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j] + 1,      // deletion
                        matrix[i][j - 1] + 1,      // insertion
                        matrix[i - 1][j - 1] + cost // substitution
                    );
                }
            }
            
            // Calculate similarity as a value between 0 and 1
            const maxLen = Math.max(a.length, b.length);
            const distance = matrix[b.length][a.length];
            return 1 - distance / maxLen;
        }

        function raiseMistake(VID){
            if (!VID) return;
            // Send AJAX request to PHP script to raise mistake count for this vocab
            fetch('raise_mistake.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `VID=${encodeURIComponent(VID)}`
            })
            .then(response => response.text())
            .then(data => {
                console.log('Mistake raised:', data);
            })
            .catch(error => {
                console.error('Error raising mistake:', error);
            });
        }

        function lowerMistake(VID){
            if (!VID) return;
            // Send AJAX request to PHP script to lower mistake count for this vocab
            fetch('lowerMistake.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `VID=${encodeURIComponent(VID)}`
            })
            .then(response => response.text())
            .then(data => {
                console.log('Mistake lowered:', data);
            })
            .catch(error => {
                console.error('Error lowering mistake:', error);
            });
        }
    </script>
</body>
</html>