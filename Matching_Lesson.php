<?php
session_start();

// Initialize lives if not already set
if (!isset($_SESSION['Lives'])) {  // Fixed typo in variable name
    $_SESSION['Lives'] = 3;
}

// Check if vocabList exists in session, otherwise create a sample one
if (!isset($_SESSION['vocabList']) || empty($_SESSION['vocabList'])) {
    $_SESSION['vocabList'] = [
        ['vocab' => 'Haus', 'translation' => 'house'],
        ['vocab' => 'Auto', 'translation' => 'car'],
        ['vocab' => 'Katze', 'translation' => 'cat'],
        ['vocab' => 'Hund', 'translation' => 'dog'],
        ['vocab' => 'Schule', 'translation' => 'school']
    ];
}

// Shuffle the vocab list for randomness
$vocabList = $_SESSION['vocabList'];
$germanWords = array_column($vocabList, 'vocab');
$englishWords = array_column($vocabList, 'translation');
shuffle($englishWords);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo - Matching Lesson</title>
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
        .matching-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            padding: 2.2rem 1.2rem 1.2rem 1.2rem;
            margin-bottom: 24px;
            max-width: 700px;
            width: 100%;
        }
        .matching-title {
            font-family: 'Pacifico', cursive;
            font-size: 2rem;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 0.7rem;
        }
        .matching-instructions {
            font-size: 1.1rem;
            color: #555;
            text-align: center;
            margin-bottom: 0.5rem;
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
        .list-group-item {
            font-size: 1.15rem;
            border-radius: 1rem !important;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: background 0.13s, color 0.13s, box-shadow 0.13s;
            background: #fff;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .list-group-item.active, .list-group-item:active {
            background: #a8edea;
            color: #0d6efd;
            font-weight: bold;
        }
        .list-group-item-success {
            background: #d4edda !important;
            color: #155724 !important;
        }
        .list-group-item-danger {
            background: #f8d7da !important;
            color: #721c24 !important;
        }
        .list-group-item.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        .matching-btns {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        @media (max-width: 700px) {
            .matching-card { padding: 1.2rem 0.2rem; }
        }
        @media (max-width: 600px) {
            .main-content-center { padding-top: 70px; }
            .matching-title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="sticky-header">
        <h2><i class="bi bi-link-45deg me-2"></i>Matching Practice</h2>
    </div>
    <div class="main-content-center">
        <div class="matching-card mx-auto">
            <div class="matching-instructions">Click on a German word and its matching English translation to pair them.</div>
            <div class="lives-bar mb-3" id="lives-bar"></div>
            <div class="row mt-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <ul class="list-group" id="german-list">
                        <?php foreach ($germanWords as $german): ?>
                            <li class="list-group-item" data-german="<?= $german ?>"><?= $german ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group" id="english-list">
                        <?php foreach ($englishWords as $english): ?>
                            <li class="list-group-item" data-english="<?= $english ?>"><?= $english ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div id="result-message" class="text-center mt-4 fw-bold"></div>
            <div class="matching-btns">
                <button id="next-lesson-btn" class="btn btn-success d-none">Go to Writing Lesson</button>
                <button id="main-menu-btn" class="btn btn-danger d-none">Go to Main Menu</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const germanList = document.getElementById('german-list');
        const englishList = document.getElementById('english-list');
        const resultMessage = document.getElementById('result-message');
        const livesCount = document.getElementById('lives-count');
        const nextLessonBtn = document.getElementById('next-lesson-btn');
        const mainMenuBtn = document.getElementById('main-menu-btn');
        const livesBar = document.getElementById('lives-bar');

        let selectedGerman = null;
        let selectedEnglish = null;
        let correctCount = 0;
        let lives = <?= $_SESSION['Lives'] ?>;
        const maxLives = 3;

        // Render hearts for lives
        function renderLivesBar() {
            livesBar.innerHTML = '';
            for (let i = 0; i < lives; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart-fill"></i>';
            }
            for (let i = lives; i < maxLives; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart"></i>';
            }
        }
        renderLivesBar();

        // Handle German word selection
        germanList.addEventListener('click', (e) => {
            if (e.target.classList.contains('list-group-item') && !e.target.classList.contains('list-group-item-success')) {
                if (selectedGerman) {
                    selectedGerman.classList.remove('active');
                }
                selectedGerman = e.target;
                selectedGerman.classList.add('active');
                checkPair();
            }
        });

        // Handle English word selection
        englishList.addEventListener('click', (e) => {
            if (e.target.classList.contains('list-group-item') && !e.target.classList.contains('list-group-item-success')) {
                if (selectedEnglish) {
                    selectedEnglish.classList.remove('active');
                }
                selectedEnglish = e.target;
                selectedEnglish.classList.add('active');
                checkPair();
            }
        });

        // Check if a pair is selected
        function checkPair() {
            if (selectedGerman && selectedEnglish) {
                const germanWord = selectedGerman.getAttribute('data-german');
                const englishWord = selectedEnglish.getAttribute('data-english');

                if (<?= json_encode(array_column($vocabList, 'translation', 'vocab')) ?>[germanWord] === englishWord) {
                    selectedGerman.classList.add('list-group-item-success');
                    selectedEnglish.classList.add('list-group-item-success');
                    correctCount++;

                    // Remove matched items
                    selectedGerman.classList.remove('active');
                    selectedEnglish.classList.remove('active');
                    selectedGerman = null;
                    selectedEnglish = null;
                } else {
                    selectedGerman.classList.add('list-group-item-danger');
                    selectedEnglish.classList.add('list-group-item-danger');
                    
                    // Remove life with AJAX
                    removeLife();

                    // Reset selections after a short delay
                    setTimeout(() => {
                        selectedGerman.classList.remove('active', 'list-group-item-danger');
                        selectedEnglish.classList.remove('active', 'list-group-item-danger');
                        selectedGerman = null;
                        selectedEnglish = null;
                    }, 1000);
                }
                
                // Check if all pairs are matched
                if (correctCount === <?= count($vocabList) ?>) {
                    resultMessage.textContent = 'Congratulations! You matched all pairs!';
                    nextLessonBtn.classList.remove('d-none');
                }
            }
        }

        // Function to remove life with AJAX
        function removeLife() {
            fetch('remove_life.php', {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                lives = data.lives;
                renderLivesBar();
                
                // Check if lives are exhausted
                if (lives <= 0) {
                    resultMessage.innerHTML = '<div class="alert alert-danger">You have lost all your lives. Try again!</div>';
                    mainMenuBtn.classList.remove('d-none');
                    
                    // Disable all word selections
                    const allListItems = document.querySelectorAll('.list-group-item');
                    allListItems.forEach(item => {
                        if (!item.classList.contains('list-group-item-success')) {
                            item.classList.add('disabled');
                            item.style.pointerEvents = 'none';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error updating life:', error);
            });
        }

        // Redirect to Writing Lesson
        nextLessonBtn.addEventListener('click', () => {
            window.location.href = 'Writing_Lesson.php';
        });

        // Redirect to Main Menu
        mainMenuBtn.addEventListener('click', () => {
            window.location.href = 'Main.php';
        });
    </script>
</body>
</html>