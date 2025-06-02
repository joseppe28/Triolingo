<?php
// filepath: c:\xampp\htdocs\Triolingo\Triolingo\talking_lesson.php
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
    <title>Triolingo - Talking Lesson</title>
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
        .talking-card-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 40px;
        }
        .talking-card {
            width: 340px;
            min-height: 120px;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
            color: #222;
            font-weight: 600;
            border: none;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
        }
        .talking-card .vocab-word {
            font-family: 'Pacifico', cursive;
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 0.7rem;
        }
        .talking-card .hint-text {
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 1.2rem;
            text-align: center;
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
        .progress {
            height: 22px;
            width: 340px;
            max-width: 90vw;
            margin: 0 auto 24px auto;
        }
        .controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0 10px 0;
        }
        .audio-btn, .mic-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 1.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            border: none;
            transition: background 0.13s, color 0.13s;
        }
        .audio-btn {
            background-color: #4e73df;
            color: white;
        }
        .mic-btn {
            background-color: #e74a3b;
            color: white;
        }
        .mic-btn.recording {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0.7);}
            70% { box-shadow: 0 0 0 10px rgba(231, 74, 59, 0);}
            100% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0);}
        }
        #audioplayer {
            display: none;
        }
        .feedback-area {
            margin-top: 18px;
            min-height: 38px;
        }
        .navigation-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 18px;
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
        @media (max-width: 600px) {
            .main-content-center { padding-top: 70px; }
            .talking-card { width: 98vw; min-width: 0; padding: 1.2rem 0.5rem 1rem 0.5rem; }
            .progress { width: 98vw; }
            .talking-card .vocab-word { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="sticky-header">
        <h2><i class="bi bi-mic me-2"></i>Talking Practice</h2>
    </div>
    <div class="main-content-center">
        <div class="talking-card-container">
            <div class="progress mb-4">
                <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100">
                    <span id="progress-text" style="color: #fff; font-weight: 600;"></span>
                </div>
            </div>
            <div class="lives-bar" id="lives-bar"></div>
            <div class="talking-card shadow">
                <div class="vocab-word" id="current-word"></div>
                <div class="hint-text">Listen to the pronunciation, then try to say the word</div>
                <div class="controls">
                    <button id="audio-btn" class="audio-btn">
                        <i class="bi bi-volume-up"></i>
                    </button>
                    <button id="mic-btn" class="mic-btn">
                        <i class="bi bi-mic"></i>
                    </button>
                </div>
                <audio id="audioplayer" controls></audio>
                <div id="feedback" class="feedback-area" style="display: none;"></div>
            </div>
            <div class="navigation-buttons">
                <button id="next-btn" class="btn btn-primary nav-button d-none">Next Word</button>
                <button id="next-lesson-btn" class="btn btn-success nav-button d-none">Go to Next Lesson</button>
                <button id="main-menu-btn" class="btn btn-danger nav-button d-none">Go to Main Menu</button>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Store vocab list from PHP session - ensure it's not empty
        const vocabList = <?= json_encode($_SESSION['vocabList']) ?>;
        let currentIndex = 0;
        let completedWords = 0;
        let lives = <?= $_SESSION['Lives'] ?>;
        let mediaRecorder = null;
        let isRecording = false;
        let audioChunks = [];
        let recordedBlob = null;
        let selectedVoice = null;
        let voices = [];
        let tries = 0;

        // DOM elements
        const currentWord = document.getElementById('current-word');
        const audioBtn = document.getElementById('audio-btn');
        const micBtn = document.getElementById('mic-btn');
        const audioplayer = document.getElementById('audioplayer');
        const feedbackElem = document.getElementById('feedback');
        const nextBtn = document.getElementById('next-btn');
        const nextLessonBtn = document.getElementById('next-lesson-btn');
        const mainMenuBtn = document.getElementById('main-menu-btn');
        const livesBar = document.getElementById('lives-bar');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');

        // Progress bar update
        function updateProgressBar() {
            const progress = Math.round(((currentIndex + 1) / vocabList.length) * 100);
            progressBar.style.width = progress + "%";
            progressBar.setAttribute('aria-valuenow', progress);
            progressText.textContent = (currentIndex + 1) + " / " + vocabList.length;
        }

        // Lives bar update
        function updateLivesBar() {
            livesBar.innerHTML = '';
            for (let i = 0; i < lives; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart-fill"></i>';
            }
            for (let i = lives; i < 3; i++) {
                livesBar.innerHTML += '<i class="bi bi-heart" style="color:#e74a3b;font-size:1.5rem;"></i>';
            }
        }

        // Initialize speech synthesis
        if ('speechSynthesis' in window) {
            window.speechSynthesis.onvoiceschanged = function() {
                voices = window.speechSynthesis.getVoices();
                const englishVoices = voices.filter(voice => voice.lang.includes('en-'));
                if (englishVoices.length > 0) {
                    const femaleVoices = englishVoices.filter(voice => voice.name.includes('Female') || voice.name.includes('Samantha'));
                    selectedVoice = femaleVoices.length > 0 ? femaleVoices[0] : englishVoices[0];
                }
            };
            window.speechSynthesis.getVoices();
        }

        // Load the first word
        loadCurrentWord();

        // Initialize speech recognition
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'en-US';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.onstart = function(e) {
                        audioChunks = [];
                        isRecording = true;
                        micBtn.classList.add('recording');
                    };
                    mediaRecorder.ondataavailable = function(e) {
                        audioChunks.push(e.data);
                    };
                    mediaRecorder.onstop = function(e) {
                        isRecording = false;
                        micBtn.classList.remove('recording');
                        recordedBlob = new Blob(audioChunks, { 'type' : 'audio/wav' });
                        const audioURL = window.URL.createObjectURL(recordedBlob);
                        audioplayer.src = audioURL;
                    };
                })
                .catch(err => {
                    feedbackElem.innerHTML = '<div class="alert alert-danger">Error accessing microphone. Please check your browser permissions.</div>';
                    feedbackElem.style.display = 'block';
                });

            recognition.onresult = function(event) {
                const speechResult = event.results[0][0].transcript.toLowerCase();
                const correctWord = vocabList[currentIndex].translation.toLowerCase();
                const similarity = calculateSimilarity(speechResult, correctWord);
                feedbackElem.style.display = 'block';
                tries++;
                if (similarity >= 0.6) {
                    feedbackElem.innerHTML = '<div class="alert alert-success">Great job! Your pronunciation sounds good.</div>';
                    updateVocabLevel(vocabList[currentIndex].vocab, true);
                    nextBtn.classList.remove('d-none');
                } else {
                    feedbackElem.innerHTML = '<div class="alert alert-danger">Not quite right. Try again or press Next to continue.</div>';
                    updateVocabLevel(vocabList[currentIndex].vocab, false);
                    if (similarity < 0.3 && tries >= 3) {
                        removeLife();
                    }
                    if(tries>= 3) {
                        nextBtn.classList.remove('d-none');
                    }
                }
            };

            recognition.onerror = function(event) {
                feedbackElem.innerHTML = '<div class="alert alert-warning">There was an error with the speech recognition. Please try again.</div>';
                feedbackElem.style.display = 'block';
            };

            micBtn.addEventListener('click', function() {
                if (isRecording) {
                    mediaRecorder.stop();
                    recognition.stop();
                } else {
                    mediaRecorder.start();
                    recognition.start();
                }
            });

        } else {
            micBtn.disabled = true;
            feedbackElem.innerHTML = '<div class="alert alert-danger">Sorry, your browser does not support speech recognition. Try using Chrome.</div>';
            feedbackElem.style.display = 'block';
        }

        audioBtn.addEventListener('click', function() {
            const wordToPlay = vocabList[currentIndex].translation;
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const speech = new SpeechSynthesisUtterance(wordToPlay);
                speech.lang = 'en-US';
                speech.rate = 0.9;
                speech.pitch = 1;
                audioBtn.disabled = true;
                audioBtn.innerHTML = '<i class="bi bi-volume-up" style="animation: beat 1s infinite alternate;"></i>';
                if (selectedVoice) {
                    speech.voice = selectedVoice;
                }
                speech.onend = function() {
                    audioBtn.disabled = false;
                    audioBtn.innerHTML = '<i class="bi bi-volume-up"></i>';
                };
                window.speechSynthesis.speak(speech);
            } else {
                feedbackElem.innerHTML = '<div class="alert alert-warning">Speech synthesis not supported in your browser.</div>';
                feedbackElem.style.display = 'block';
            }
        });

        nextBtn.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % vocabList.length;
            completedWords++;
            tries = 0; // Reset tries after removing life
            micBtn.classList.remove('recording');
            
            if (completedWords >= vocabList.length) {
                feedbackElem.innerHTML = '<div class="alert alert-success">Congratulations! You\'ve completed all words!</div>';
                feedbackElem.style.display = 'block';
                nextBtn.classList.add('d-none');
                nextLessonBtn.classList.remove('d-none');
                // Match-Button mittig und in einer anderen Farbe (z.B. blau)
                const matchBtn = document.createElement('button');
                matchBtn.className = 'btn btn-primary mt-3 d-block mx-auto'; // btn-primary für blau, mx-auto für zentriert
                matchBtn.textContent = 'Go to Matching Lesson';
                matchBtn.onclick = function() {
                    window.location.href = 'Matching_Lesson.php';
                };
                feedbackElem.appendChild(matchBtn);
            } else {
                loadCurrentWord();
                nextBtn.classList.add('d-none');
                feedbackElem.style.display = 'none';
            }
        });

        nextLessonBtn.addEventListener('click', function() {
            window.location.href = 'completeLesson.php';
        });

        mainMenuBtn.addEventListener('click', function() {
            window.location.href = 'Main.php';
        });

        function loadCurrentWord() {
            if (!vocabList || vocabList.length === 0) {
                currentWord.textContent = "No vocabulary loaded";
                return;
            }
            const word = vocabList[currentIndex];
            currentWord.textContent = word.translation;
            updateProgressBar();
            updateLivesBar();
        }

        function removeLife() {
            fetch('remove_life.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                lives = data.lives;
                updateLivesBar();
                if (lives <= 0) {
                    nextBtn.classList.add('d-none');
                    mainMenuBtn.classList.remove('d-none');
                    micBtn.disabled = true;
                    feedbackElem.innerHTML = '<div class="alert alert-danger">You have lost all your lives!</div>';
                    feedbackElem.style.display = 'block';
                }
            });
        }

        function updateVocabLevel(germanWord, isCorrect) {
            fetch('update_vocab_level.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `word=${encodeURIComponent(germanWord)}&correct=${isCorrect ? 1 : 0}`
            });
        }

        function calculateSimilarity(a, b) {
            if (a.length === 0) return b.length === 0 ? 1 : 0;
            if (b.length === 0) return 0;
            const matrix = [];
            for (let i = 0; i <= b.length; i++) matrix[i] = [i];
            for (let j = 0; j <= a.length; j++) matrix[0][j] = j;
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
    </script>
</body>
</html>