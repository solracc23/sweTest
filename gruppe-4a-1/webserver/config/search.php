<?php

return [
    'searchable_content' => [
        [
            'keywords' => ['Homepage', 'Startseite', 'Home', 'Hauptseite'],
            'route' => '/index',
            'params' => [],
            'category' => 'Navigation',
            'title' => 'Startseite'
        ],
        [
            'keywords' => ['Fächer', 'Fachauswahl', 'Themen', 'Aufgaben'],
            'route' => '/fachauswahl',
            'params' => [],
            'category' => 'Navigation',
            'title' => 'Fächer'
        ],
        [
            'keywords' => ['Wochenaufgaben', 'Woche', 'Weekly', 'Hausaufgaben'],
            'route' => '/wochenaufgaben',
            'params' => [],
            'category' => 'Navigation',
            'title' => 'Wochenaufgaben'
        ],
        [
            'keywords' => ['Login', 'Anmelden', 'Einloggen'],
            'route' => '/login',
            'params' => [],
            'category' => 'System',
            'title' => 'Login'
        ],
        [
            'keywords' => ['Datenschutz', 'Daten', 'Schutz'],
            'route' => '/datenschutz',
            'params' => [],
            'category' => 'System',
            'title' => 'Datenschutz'
        ],
        [
            'keywords' => ['Impressum', 'Impress', 'Wer', 'Wo', 'Adresse', 'Post'],
            'route' => '/impressum',
            'params' => [],
            'category' => 'System',
            'title' => 'Impressum'
        ],
        [
            'keywords' => ['Kontakt', 'Bewerbung', 'Beschwerde', 'Meldung','Probleme'],
            'route' => '/kontakt',
            'params' => [],
            'category' => 'System',
            'title' => 'Kontakt'
        ],

        // Fachauswahl/Test
        [
            'keywords' => ['Englisch', 'E' , 'En' , 'Eng','English'],
            'route' => '/themen',
            'params' => ['fach'=>'englisch'],
            'category' => 'Englisch',
            'title' => 'Englisch'
        ],
        [
            'keywords' => ['Deutsch', 'German', 'D', 'DE'],
            'route' => '/themen',
            'params' => ['fach'=>'deutsch'],
            'category' => 'Deutsch',
            'title' => 'Deutsch'
        ],
        [
            'keywords' => ['Addition', 'addieren', 'plus', 'zusammenzählen', 'rechnen'],
            'route' => '/themen',
            'params' => ['thema' => 0],
            'category' => 'Mathe',
            'title' => 'Addition'
        ],
        [
            'keywords' => ['Subtraktion', 'subtrahieren', 'minus', 'abziehen'],
            'route' => '/themen',
            'params' => ['thema' => 1],
            'category' => 'Mathe',
            'title' => 'Subtraktion'
        ],
        [
            'keywords' => ['Multiplikation', 'multiplizieren', 'mal', 'malnehmen'],
            'route' => '/themen',
            'params' => ['thema' => 2],
            'category' => 'Mathe',
            'title' => 'Multiplikation'
        ],
        [
            'keywords' => ['Division', 'dividieren', 'teilen', 'geteilt'],
            'route' => '/themen',
            'params' => ['thema' => 3],
            'category' => 'Mathe',
            'title' => 'Division'
        ],

        // Settings
        [
            'keywords' => ['Einstellungen', 'Settings', 'Profil', 'Account', 'Konto'],
            'route' => '/settings',
            'params' => [],
            'category' => 'System',
            'title' => 'Einstellungen'
        ],
        [
            'keywords' => ['Darstellung', 'Barrierefreiheit', 'Barriere', 'Kontrast', 'Hintergrund'],
            'route' => '/settings',
            'params' => ['section' => 'darstellung'],
            'category' => 'System',
            'title' => 'Darstellung und Barrierefreiheit'
        ],
        [
            'keywords' => ['Passwort', 'Password', 'ändern', 'Sicherheit'],
            'route' => '/settings',
            'params' => ['section' => 'password'],
            'category' => 'System',
            'title' => 'Passwort ändern'
        ],
        [
            'keywords' => ['Lehrer', 'Teacher'],
            'route' => '/settings',
            'params' => ['section' => 'lehrer'],
            'category' => 'System',
            'title' => 'Lehrer-Bereich'
        ],
        [
            'keywords' => ['Eltern', 'Parents'],
            'route' => '/settings',
            'params' => ['section' => 'eltern'],
            'category' => 'System',
            'title' => 'Eltern-Bereich'
        ]
    ]
];
