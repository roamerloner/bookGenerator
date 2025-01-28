<?php
require_once 'config.php';
function generateBookData($seed, $offset, $language, $likes, $reviews, $batchSize) {
    $faker = Faker\Factory::create($language);
    $faker->seed($seed + $offset);

    $books = [];
    
    for ($i = 0; $i < $batchSize; $i++) {
        $reviewCount = round(generatePoissonRandom($reviews));
        $likesCount = round(generatePoissonRandom($likes));
        
        $books[] = [
            'isbn' => generateISBN($faker),
            'title' => generateBookTitle($faker, $language),
            'authors' => generateAuthors($faker),
            'publisher' => $faker->company . ', ' . $faker->year,
            'likes' => $likesCount,
            'reviews' => generateReviews($faker, $reviewCount, $language),
            'cover' => generateBookCover($faker)
        ];
    }
    
    return $books;
}


function generateISBN($faker) {
    return '978-' . $faker->numerify('#-##-######-#');
}

function generateBookTitle($faker, $language) {
    
    $patterns = [
        'en_US' => [
            "The #Adjective# #Noun#",
            "A #Noun# of #Noun#",
            "#Adjective# #Noun#",
            "The #Noun# of the #Adjective# #Noun#",
            "#Verb# the #Noun#",
            "#Noun#'s #Noun#",
            "The #Profession# and the #Noun#",
            "#Adjective# #Noun# in #Place#",
            "The #Number# #Noun.Plural#",
            "When #Noun.Plural# #Verb#"
        ],
        'de_DE' => [
            "Das #Adjective# #Noun#",
            "Die #Adjective# #Noun#",
            "Der #Adjective# #Noun#",
            "#Adjective# #Noun# und #Noun#",
            "Ein #Noun# für #Noun.Plural#",
            "Die #Noun.Plural# des #Noun#",
            "Das Geheimnis der #Noun.Plural#",
            "Im Schatten der #Noun.Plural#",
            "Die #Adjective# #Noun.Plural# von #Place#",
            "Der #Noun# von #Place#"
        ],
        'fr_FR' => [
            "Le #Noun# #Adjective#",
            "La #Adjective# #Noun# de #Noun#",
            "Un #Noun# à #Place#",
            "Le #Noun# du #Profession#",
            "#Verb# le #Noun#",
            "Les #Noun.Plural# et les #Noun.Plural#",
            "Le #Profession# et la #Noun#",
            "#Adjective# #Noun.Plural# à #Place#",
            "Les #Number# #Noun.Plural#",
            "Quand les #Noun.Plural# #Verb#"
        ]
    ];

    
    $wordLists = [
        'en_US' => [
            'Adjective' => ['great', 'small', 'old', 'new', 'dark', 'bright', 'strong', 'weak', 'quick', 'slow'],
            'Noun' => ['book', 'house', 'forest', 'star', 'river', 'mountain', 'person', 'child', 'city', 'land'],
            'Noun.Plural' => ['books', 'houses', 'forests', 'stars', 'rivers', 'mountains', 'people', 'children', 'cities', 'lands'],
            'Place' => ['New York', 'London', 'Paris', 'Berlin', 'Tokyo', 'Sydney', 'Rome', 'Moscow', 'Toronto', 'Dubai'],
            'Profession' => ['writer', 'teacher', 'doctor', 'artist', 'engineer', 'scientist', 'chef', 'pilot', 'lawyer', 'musician'],
            'Verb' => ['read', 'write', 'explore', 'discover', 'create', 'build', 'solve', 'travel', 'learn', 'teach'],
            'Number' => ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten']
        ],
        'de_DE' => [
            'Adjective' => ['groß', 'klein', 'alt', 'neu', 'dunkel', 'hell', 'stark', 'schwach', 'schnell', 'langsam'],
            'Noun' => ['Buch', 'Haus', 'Wald', 'Stern', 'Fluss', 'Berg', 'Mensch', 'Kind', 'Stadt', 'Land'],
            'Noun.Plural' => ['Bücher', 'Häuser', 'Wälder', 'Sterne', 'Flüsse', 'Berge', 'Menschen', 'Kinder', 'Städte', 'Länder'],
            'Place' => ['Berlin', 'München', 'Hamburg', 'Wien', 'Zürich', 'Frankfurt', 'Köln', 'Stuttgart', 'Dresden', 'Leipzig'],
            'Profession' => ['Schriftsteller', 'Lehrer', 'Arzt', 'Künstler', 'Ingenieur', 'Wissenschaftler', 'Koch', 'Pilot', 'Anwalt', 'Musiker'],
            'Verb' => ['lesen', 'schreiben', 'erkunden', 'entdecken', 'erschaffen', 'bauen', 'lösen', 'reisen', 'lernen', 'lehren'],
            'Number' => ['eins', 'zwei', 'drei', 'vier', 'fünf', 'sechs', 'sieben', 'acht', 'neun', 'zehn']
        ],
        'fr_FR' => [
            'Adjective' => ['grand', 'petit', 'vieux', 'nouveau', 'sombre', 'lumineux', 'fort', 'faible', 'rapide', 'lent'],
            'Noun' => ['livre', 'maison', 'forêt', 'étoile', 'rivière', 'montagne', 'personne', 'enfant', 'ville', 'pays'],
            'Noun.Plural' => ['livres', 'maisons', 'forêts', 'étoiles', 'rivières', 'montagnes', 'personnes', 'enfants', 'villes', 'pays'],
            'Place' => ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Lille', 'Rennes'],
            'Profession' => ['écrivain', 'professeur', 'médecin', 'artiste', 'ingénieur', 'scientifique', 'chef', 'pilote', 'avocat', 'musicien'],
            'Verb' => ['lire', 'écrire', 'explorer', 'découvrir', 'créer', 'construire', 'résoudre', 'voyager', 'apprendre', 'enseigner'],
            'Number' => ['un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix']
        ]
    ];

    
    $patterns = $patterns[$language] ?? $patterns['en_US'];
    $wordList = $wordLists[$language] ?? $wordLists['en_US'];

    
    $pattern = $faker->randomElement($patterns);

    
    $title = preg_replace_callback('/#(\w+(?:\.\w+)*)#/', function($matches) use ($wordList, $faker) {
        $key = $matches[1]; 
        $keys = explode('.', $key); 
        $currentList = $wordList;

        
        foreach ($keys as $k) {
            if (isset($currentList[$k])) {
                $currentList = $currentList[$k];
            } else {
                return ''; 
            }
        }

        
        return $faker->randomElement($currentList);
    }, $pattern);

    
    return ucfirst($title);
}

function generateAuthors($faker) {
    $numAuthors = rand(1, 3);
    $authors = [];
    
    for ($i = 0; $i < $numAuthors; $i++) {
        $authors[] = $faker->name;
    }
    
    return $authors;
}




function generateReviews($faker, $count, $language) {
    $reviews = [];

    
    $reviewPhrases = [
        'en_US' => [
            'positive' => [
                'A truly wonderful book! I couldn\'t put it down.',
                'Absolutely recommendable! The story was captivating from start to finish.',
                'This book is a masterpiece. The characters are so well-developed.',
                'Highly enjoyable! The plot twists kept me on the edge of my seat.',
                'A must-read! The writing is beautiful and the story is unforgettable.'
            ],
            'negative' => [
                'Unfortunately disappointing. The plot was predictable and the characters felt flat.',
                'Not my cup of tea. The pacing was too slow for my liking.',
                'I had high hopes, but this book didn\'t deliver. The ending was unsatisfying.',
                'The writing style didn\'t resonate with me. It felt forced and unnatural.',
                'I struggled to finish this book. The story lacked depth and originality.'
            ],
            'neutral' => [
                'A solid book, but nothing extraordinary. It was an okay read.',
                'Well-written, but the plot was a bit slow in places.',
                'Interesting concept, but the execution could have been better.',
                'An average book. It had its moments, but nothing truly memorable.',
                'Decent read, but I wouldn\'t recommend it to everyone.'
            ]
        ],
        'de_DE' => [
            'positive' => [
                'Ein wirklich gelungenes Buch! Ich konnte es nicht aus der Hand legen.',
                'Absolut empfehlenswert! Die Geschichte war von Anfang bis Ende fesselnd.',
                'Dieses Buch ist ein Meisterwerk. Die Charaktere sind so gut entwickelt.',
                'Sehr unterhaltsam! Die Plot-Twists haben mich bis zum Ende gefesselt.',
                'Ein Muss! Das Schreiben ist wunderschön und die Geschichte unvergesslich.'
            ],
            'negative' => [
                'Leider enttäuschend. Die Handlung war vorhersehbar und die Charaktere flach.',
                'Nicht mein Fall. Das Tempo war mir zu langsam.',
                'Ich hatte hohe Erwartungen, aber dieses Buch hat nicht überzeugt. Das Ende war unbefriedigend.',
                'Der Schreibstil hat mich nicht angesprochen. Er wirkte gezwungen und unnatürlich.',
                'Ich hatte Mühe, das Buch zu beenden. Die Geschichte fehlte es an Tiefe und Originalität.'
            ],
            'neutral' => [
                'Ein solides Buch, aber nichts Besonderes. Es war eine okaye Lektüre.',
                'Gut geschrieben, aber die Handlung war stellenweise etwas langsam.',
                'Interessantes Konzept, aber die Umsetzung hätte besser sein können.',
                'Ein durchschnittliches Buch. Es hatte seine Momente, aber nichts wirklich Bemerkenswertes.',
                'Eine anständige Lektüre, aber ich würde es nicht jedem empfehlen.'
            ]
        ],
        'fr_FR' => [
            'positive' => [
                'Un livre vraiment magnifique ! Je n\'ai pas pu le poser.',
                'Absolument recommandable ! L\'histoire était captivante du début à la fin.',
                'Ce livre est un chef-d\'œuvre. Les personnages sont si bien développés.',
                'Très divertissant ! Les rebondissements m\'ont tenu en haleine jusqu\'à la fin.',
                'Un incontournable ! L\'écriture est magnifique et l\'histoire inoubliable.'
            ],
            'negative' => [
                'Malheureusement décevant. L\'intrigue était prévisible et les personnages plats.',
                'Pas mon genre. Le rythme était trop lent pour moi.',
                'J\'avais de grands espoirs, mais ce livre n\'a pas tenu ses promesses. La fin était insatisfaisante.',
                'Le style d\'écriture ne m\'a pas convaincu. Il semblait forcé et artificiel.',
                'J\'ai eu du mal à finir ce livre. L\'histoire manquait de profondeur et d\'originalité.'
            ],
            'neutral' => [
                'Un livre solide, mais rien d\'extraordinaire. C\'était une lecture correcte.',
                'Bien écrit, mais l\'intrigue était un peu lente par moments.',
                'Concept intéressant, mais l\'exécution aurait pu être meilleure.',
                'Un livre moyen. Il avait ses moments, mais rien de vraiment mémorable.',
                'Une lecture décente, mais je ne le recommanderais pas à tout le monde.'
            ]
        ]
    ];

    
    $phrases = $reviewPhrases[$language] ?? $reviewPhrases['en_US'];

    for ($i = 0; $i < $count; $i++) {
        
        $tone = $faker->randomElement(['positive', 'negative', 'neutral']);

        
        $text = $faker->randomElement($phrases[$tone]);

        $reviews[] = [
            'text' => $text,
            'author' => $faker->name,
            'company' => $faker->company
        ];
    }

    return $reviews;
}

function generatePoissonRandom($lambda) {
    if ($lambda <= 0) return 0;
    
    $L = exp(-$lambda);
    $k = 0;
    $p = 1;
    
    do {
        $k++;
        $p *= mt_rand() / mt_getrandmax();
    } while ($p > $L);
    
    return $k - 1;
}

function generateBookCover($faker) {
    
    return "https://picsum.photos/seed/" . $faker->uuid . "/300/400";
}