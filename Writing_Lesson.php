<?php
session_start();

if(!isset($_SESSION['Lives'])) {
    $_SESSION['Lives'] = 3; // Initialize lives if not already set
}

// Check if vocablist exists in session, otherwise create a sample one
if (!isset($_SESSION['vocabList']) || empty($_SESSION['vocabList'])) {
    // For testing purposes, if no vocab list exists, create a sample one
    $_SESSION['vocabList'] = [
        ['german' => 'Haus', 'english' => 'house'],
        ['german' => 'Auto', 'english' => 'car'],
        ['german' => 'Katze', 'english' => 'cat'],
        ['german' => 'Hund', 'english' => 'dog'],
        ['german' => 'Schule', 'english' => 'school']
    ];
}
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
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Store vocab list from PHP session
        const vocabList = <?= json_encode($_SESSION['vocabList']) ?>;
        let currentIndex = 0;
        let isChecking = true;

        // Load the first word
        loadCurrentWord();

        function loadCurrentWord() {
            document.getElementById('german-word').textContent = vocabList[currentIndex].vocab;
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
                loadCurrentWord();
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
            
            document.getElementById('answer-input').disabled = true;
            feedbackElement.style.display = 'block';
            
            if (userAnswer === correctAnswer) {
                // Correct answer
                feedbackElement.innerHTML = '<div class="alert alert-success">Correct!</div>';
            } else {
                // Incorrect answer - check for minor mistakes
                const similarity = calculateSimilarity(userAnswer, correctAnswer);
                
                if (similarity >= 0.7) {
                    // Minor mistake
                    feedbackElement.innerHTML = '<div class="alert alert-warning">Almost correct! The correct answer is: ' + correctAnswer + '</div>';
                } else {
                    // Major mistake
                    feedbackElement.innerHTML = '<div class="alert alert-danger">Incorrect. The correct answer is: ' + correctAnswer + '</div>';
                }
            }
            
            document.getElementById('action-button').textContent = 'Next';
            isChecking = false;
        }
        
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
    </script>
</body>
</html>
