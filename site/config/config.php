<?php

return [
    "debug" => true,
    "tz" => "Asia/Jerusalem",
    "retreat.project_statuses" => [
        "normal", "vacation", "presentation"
    ],
    "students.irrelevant" => [
        "studies_status" => ["חילופים א", "פטור"],
    ],
    "auth" => [
        "trials" => 20,
        "methods" => ["password", "code"],
        "challenge" => [
            "email" => [
                'from' => 'pgmr@pgmr.club',
                'fromName' => 'מועדון.פגמ״ר',
                'subject' => 'קוד הכניסה שלך למועדון.פגמ״ר'
            ]
        ]
        // "debug" => true
    ],
    "email" => [],
    "routes" => [
        [
            "pattern" => "logout",
            "action" => function () {

                if ($user = kirby()->user()) {
                    $user->logout();
                }

                go("login");
            }
        ]
    ],
    "students.keys" => [
        "id_num" => "מספר זיהוי",
        "name" => "שם",
        "gender" => "מין",
        "email" => "אימייל",
        "tel" => "טלפון",
        "curriculum" => "תוכנית לימודים",
        "studies_status" => "סטטוס לימודים",
        "year" => "שנה",
        "classroom" => "כיתה",
        "average" => "ממוצע",
        "miluim" => "מילואים",
        "studio" => "סטודיו",
    ],
    "workshop.keys" => [
        "id_num" => "מספר זיהוי",
        "name" => "שם",
        "email" => "אימייל",
        "tel" => "טלפון",
        "n1" => "נוכחות 23.12",
        "n2" => "נוכחות 24.12",
        "n3" => "נוכחות 25.12",
    ],
    'panel.install' => true,
    "export.keys" => [
        "id_num" => "מספר זיהוי",
        "lname" => "שם משפחה",
        "fname" => "שם פרטי",
        "gender" => "מין",
        "email" => "אימייל",
        "tel" => "טלפון",
        "curriculum" => "תוכנית לימודים",
        "studies_status" => "סטטוס לימודים",
        "year" => "שנה",
        "classroom" => "כיתה",
        "average" => "ממוצע",
        "miluim" => "מילואים",
        "studio" => "סטודיו",
    ]
];
