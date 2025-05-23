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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo - Talking Lesson</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .vocab-word {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .audio-btn, .mic-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
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
            0% {
                box-shadow: 0 0 0 0 rgba(231, 74, 59, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(231, 74, 59, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(231, 74, 59, 0);
            }
        }
        .controls {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        #audioplayer {
            display: none;
        }
        .hint-text {
            margin-top: 10px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-body p-4">
                <h1 class="text-center text-primary mb-4">Talking Practice</h1>
                <p class="text-center text-danger fw-bold">Lives Remaining: <span id="lives-count"><?= $_SESSION['Lives'] ?></span></p>
                
                <div class="text-center mb-4">
                    <p class="fs-5">Word <span id="current-index" class="fw-bold">1</span> of 
                    <span id="total-words" class="fw-bold"><?= count($_SESSION['vocabList']) ?></span></p>
                </div>
                
                <div class="text-center">
                    <div class="vocab-word" id="current-word"></div>
                    <p class="hint-text">Listen to the pronunciation, then try to say the word</p>
                    
                    <div class="controls">
                        <button id="audio-btn" class="btn audio-btn">
                            <i class="fas fa-volume-up"></i>
                        </button>
                        <button id="mic-btn" class="btn mic-btn">
                            <i class="fas fa-microphone"></i>
                        </button>
                    </div>
                    
                    <audio id="audioplayer" controls></audio>
                    <div id="feedback" class="mt-3" style="display: none;"></div>
                </div>
                
                <div class="text-center mt-4">
                    <button id="next-btn" class="btn btn-primary btn-lg d-none">Next Word</button>
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
        let completedWords = 0;
        let lives = <?= $_SESSION['Lives'] ?>;
        let mediaRecorder = null;
        let isRecording = false;
        let audioChunks = [];
        let recordedBlob = null;
        let selectedVoice = null;
        let voices = [];
        
        // DOM elements
        const currentWord = document.getElementById('current-word');
        const currentIndexElem = document.getElementById('current-index');
        const totalWordsElem = document.getElementById('total-words');
        const audioBtn = document.getElementById('audio-btn');
        const micBtn = document.getElementById('mic-btn');
        const audioplayer = document.getElementById('audioplayer');
        const feedbackElem = document.getElementById('feedback');
        const nextBtn = document.getElementById('next-btn');
        const livesCount = document.getElementById('lives-count');
        const nextLessonBtn = document.getElementById('next-lesson-btn');
        const mainMenuBtn = document.getElementById('main-menu-btn');
        
        // Initialize speech synthesis
        if ('speechSynthesis' in window) {
            // Load voices when they're available
            window.speechSynthesis.onvoiceschanged = function() {
                voices = window.speechSynthesis.getVoices();
                
                // Try to find a good English voice
                const englishVoices = voices.filter(voice => 
                    voice.lang.includes('en-'));
                
                if (englishVoices.length > 0) {
                    // Prefer a female voice
                    const femaleVoices = englishVoices.filter(voice => 
                        voice.name.includes('Female') || voice.name.includes('Samantha'));
                        
                    selectedVoice = femaleVoices.length > 0 ? femaleVoices[0] : englishVoices[0];
                    console.log("Selected voice:", selectedVoice.name);
                }
            };
            
            // Trigger voice loading
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
            
            // Set up media devices
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
                    console.error('Error accessing microphone:', err);
                    feedbackElem.innerHTML = '<div class="alert alert-danger">Error accessing microphone. Please check your browser permissions.</div>';
                    feedbackElem.style.display = 'block';
                });
            
            // Speech recognition result handler
            recognition.onresult = function(event) {
                const speechResult = event.results[0][0].transcript.toLowerCase();
                console.log("Speech recognized:", speechResult);
                
                const correctWord = vocabList[currentIndex].translation.toLowerCase();
                const similarity = calculateSimilarity(speechResult, correctWord);
                
                feedbackElem.style.display = 'block';
                
                if (similarity >= 0.6) {
                    feedbackElem.innerHTML = '<div class="alert alert-success">Great job! Your pronunciation sounds good.</div>';
                    updateVocabLevel(vocabList[currentIndex].vocab, true);
                    nextBtn.classList.remove('d-none');
                } else {
                    feedbackElem.innerHTML = '<div class="alert alert-danger">Not quite right. Try again or press Next to continue.</div>';
                    updateVocabLevel(vocabList[currentIndex].vocab, false);
                    
                    // Remove life only for very poor attempts
                    if (similarity < 0.3) {
                        removeLife();
                    }
                    
                    nextBtn.classList.remove('d-none');
                }
            };
            
            recognition.onerror = function(event) {
                console.error('Speech recognition error', event.error);
                feedbackElem.innerHTML = '<div class="alert alert-warning">There was an error with the speech recognition. Please try again.</div>';
                feedbackElem.style.display = 'block';
            };
            
            // Mic button handler
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
            // Browser doesn't support speech recognition
            micBtn.disabled = true;
            feedbackElem.innerHTML = '<div class="alert alert-danger">Sorry, your browser does not support speech recognition. Try using Chrome.</div>';
            feedbackElem.style.display = 'block';
        }
        
        // Audio button handler - using only speech synthesis
        audioBtn.addEventListener('click', function() {
            const wordToPlay = vocabList[currentIndex].translation;
            
            // Use Web Speech API for TTS
            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech
                window.speechSynthesis.cancel();
                
                // Create utterance
                const speech = new SpeechSynthesisUtterance(wordToPlay);
                speech.lang = 'en-US';
                speech.rate = 0.9;  // Slightly slower for better clarity
                speech.pitch = 1;
                
                // Add a subtle visual indicator that the word is being spoken
                audioBtn.disabled = true;
                audioBtn.innerHTML = '<i class="fas fa-volume-up fa-beat"></i>';
                
                // Set the selected voice if available
                if (selectedVoice) {
                    speech.voice = selectedVoice;
                }
                
                // Reset button after speech ends
                speech.onend = function() {
                    audioBtn.disabled = false;
                    audioBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
                };
                
                // Speak the word
                window.speechSynthesis.speak(speech);
            } else {
                feedbackElem.innerHTML = '<div class="alert alert-warning">Speech synthesis not supported in your browser.</div>';
                feedbackElem.style.display = 'block';
            }
        });
        
        // Next button handler
        nextBtn.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % vocabList.length;
            completedWords++;
            
            if (completedWords >= vocabList.length) {
                // All words completed
                feedbackElem.innerHTML = '<div class="alert alert-success">Congratulations! You\'ve completed all words!</div>';
                feedbackElem.style.display = 'block';
                nextBtn.classList.add('d-none');
                nextLessonBtn.classList.remove('d-none');
            } else {
                loadCurrentWord();
                nextBtn.classList.add('d-none');
                feedbackElem.style.display = 'none';
            }
        });
        
        // Navigation button handlers
        nextLessonBtn.addEventListener('click', function() {
            window.location.href = 'completeLesson.php';
        });
        
        mainMenuBtn.addEventListener('click', function() {
            window.location.href = 'Main.php';
        });
        
        // Functions
        function loadCurrentWord() {
            if (!vocabList || vocabList.length === 0) {
                console.error("Vocab list is empty!");
                currentWord.textContent = "No vocabulary loaded";
                return;
            }
            
            const word = vocabList[currentIndex];
            currentWord.textContent = word.translation;
            currentIndexElem.textContent = currentIndex + 1;
            totalWordsElem.textContent = vocabList.length;
        }
        
        function removeLife() {
            fetch('remove_life.php', {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                console.log('Life update response:', data);
                lives = data.lives;
                livesCount.textContent = lives;
                
                // Check if lives are exhausted
                if (lives <= 0) {
                    nextBtn.classList.add('d-none');
                    mainMenuBtn.classList.remove('d-none');
                    micBtn.disabled = true;
                    feedbackElem.innerHTML = '<div class="alert alert-danger">You have lost all your lives!</div>';
                    feedbackElem.style.display = 'block';
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
                console.log('Level update response:', data);
            })
            .catch(error => {
                console.error('Error updating level:', error);
            });
        }
        
        // String similarity calculation function
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