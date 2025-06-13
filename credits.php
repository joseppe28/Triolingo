<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triolingo Credits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            background-attachment: fixed;
            font-family: Arial, 'Segoe UI', 'Roboto', sans-serif;
            color: #222;
        }
        .credits-card {
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            background: #fff;
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 700px;
            width: 100%;
            margin-top: 40px;
        }
        .credits-title {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            color: #0d6efd;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .back-arrow {
            position: absolute;
            top: 32px;
            left: 32px;
            font-size: 2rem;
            color: #0d6efd;
            text-decoration: none;
            z-index: 10;
            transition: color 0.13s;
        }
        .back-arrow:hover {
            color: #084298;
        }
        @media (max-width: 700px) {
            .credits-card { padding: 1.2rem 0.5rem; }
            .credits-title { font-size: 1.4rem; }
            .back-arrow { top: 12px; left: 12px; font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <a href="benutzerInfo.php" class="back-arrow" title="Zurück">
        <i class="bi bi-arrow-left-circle-fill"></i>
    </a>
    <div class="container d-flex justify-content-center align-items-start min-vh-100">
        <div class="credits-card shadow position-relative">
            <div class="credits-title">Danke, dass Sie Triolingo nutzen!</div>
            <div style="text-align: justify; font-size: 1.15rem;">
                <p>
                    Im Namen des gesamten Triolingo-Teams möchten wir, Josef Messner und ich,
                    uns ganz herzlich bei Ihnen bedanken, dass Sie sich für Triolingo entschieden haben.
                    Es erfüllt uns mit großer Freude und auch ein wenig Stolz, dass Sie unsere Plattform nutzen,
                    um Ihre Sprachkenntnisse zu verbessern und neue Horizonte zu entdecken.
                </p>
                <p>
                    Die Entwicklung von Triolingo war für uns nicht nur ein technisches Projekt,
                    sondern eine echte Herzensangelegenheit. Von der ersten Idee bis zur Umsetzung
                    haben wir viel Zeit, Energie und Leidenschaft investiert, um eine Lernumgebung zu schaffen,
                    die nicht nur effektiv, sondern auch motivierend und benutzerfreundlich ist.
                    Unser Ziel war es immer, eine Plattform zu gestalten, die Spaß macht, die Neugier weckt
                    und die Freude am Sprachenlernen fördert.
                </p>
                <p>
                    Wir wissen, dass das Erlernen einer neuen Sprache manchmal herausfordernd sein kann.
                    Genau deshalb haben wir Triolingo so konzipiert, dass Sie Schritt für Schritt,
                    in Ihrem eigenen Tempo und mit abwechslungsreichen Übungen lernen können.
                    Ob Vokabeltraining, Matching-Übungen, Schreibaufgaben oder Sprechübungen –
                    wir hoffen, dass Sie für sich die passenden Methoden finden und Ihre Fortschritte mit Stolz verfolgen können.
                </p>
                <p>
                    Ein großes Dankeschön gilt auch allen, die uns auf diesem Weg unterstützt haben:
                    unseren Familien, Freunden, Lehrern und natürlich den ersten Testnutzern,
                    die mit ihrem Feedback maßgeblich zur Verbesserung von Triolingo beigetragen haben.
                    Ohne diese Unterstützung wäre vieles nicht möglich gewesen.
                </p>
                <p>
                    Für uns ist Triolingo mehr als nur eine Anwendung – es ist ein Gemeinschaftsprojekt,
                    das von der Begeisterung und dem Engagement vieler Menschen lebt.
                    Wir sind überzeugt, dass Lernen am besten funktioniert, wenn man sich gegenseitig motiviert und inspiriert.
                    Deshalb freuen wir uns über jede Rückmeldung, jede Anregung und jede Erfolgsgeschichte, die uns erreicht.
                </p>
                <p>
                    Wir möchten Sie ermutigen, weiterhin neugierig zu bleiben, regelmäßig zu üben
                    und sich nicht entmutigen zu lassen, falls es einmal nicht so schnell vorangeht,
                    wie Sie es sich wünschen. Jeder kleine Fortschritt zählt und bringt Sie Ihrem Ziel näher.
                    Wir hoffen, dass Triolingo Sie auf Ihrem Weg begleitet und unterstützt –
                    und vielleicht sogar ein wenig Freude in Ihren Lernalltag bringt.
                </p>
                <p>
                    Abschließend möchten wir uns noch einmal ganz herzlich bei Ihnen bedanken.
                    Ihr Vertrauen und Ihre Motivation sind für uns die größte Belohnung.
                    Wir wünschen Ihnen weiterhin viel Erfolg, Spaß und Ausdauer beim Sprachenlernen mit Triolingo!
                </p>
                <p class="mt-4 mb-0" style="font-weight: bold;">
                    Herzliche Grüße,<br>
                    Josef Meßner &amp; Tobias Zimmermann<br>
                    Das Triolingo-Team
                </p>
            </div>
        </div>
    </div>
</body>
</html>