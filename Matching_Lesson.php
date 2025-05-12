<?php
session_start();

// Initialize lives if not already set
if (!isset($I_SESSON['Lives'])) {
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo - Matching Lesson</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="text-center text-primary mb-4">Matching Practice</h1>
                <p class="text-center">Click on a German word and its matching English translation to pair them.</p>
                <p class="text-center text-danger fw-bold">Lives Remaining: <span id="lives-count"><?= $_SESSION['Lives'] ?></span></p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
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
                <div class="d-flex justify-content-center mt-4">
                    <button id="next-lesson-btn" class="btn btn-success d-none me-2">Go to Writing Lesson</button>
                    <button id="main-menu-btn" class="btn btn-danger d-none">Go to Main Menu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const germanList = document.getElementById('german-list');
        const englishList = document.getElementById('english-list');
        const resultMessage = document.getElementById('result-message');
        const livesCount = document.getElementById('lives-count');
        const nextLessonBtn = document.getElementById('next-lesson-btn');
        const mainMenuBtn = document.getElementById('main-menu-btn');

        let selectedGerman = null;
        let selectedEnglish = null;
        let correctCount = 0;
        let lives = <?= $_SESSION['Lives'] ?>;

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
                    <?= $_SESSION['Lives'] = $_SESSION['Lives'] -1 ?>;
                    livesCount.textContent = <?= $_SESSION['Lives'] ?>;

                    // Reset selections after a short delay
                    setTimeout(() => {
                        selectedGerman.classList.remove('active', 'list-group-item-danger');
                        selectedEnglish.classList.remove('active', 'list-group-item-danger');
                        selectedGerman = null;
                        selectedEnglish = null;
                    }, 1000);

                    // Check if lives are exhausted
                    if (<?= $_SESSION['Lives'] ?> === 0) {
                        resultMessage.textContent = 'You have lost all your lives. Try again!';
                        mainMenuBtn.classList.remove('d-none');
                        return;
                    }
                }

                // Check if all pairs are matched
                if (correctCount === <?= count($vocabList) ?>) {
                    resultMessage.textContent = 'Congratulations! You matched all pairs!';
                    nextLessonBtn.classList.remove('d-none');
                }
            }
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