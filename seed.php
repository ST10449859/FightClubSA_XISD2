<?php
declare(strict_types=1);


require_once __DIR__ . '/../api/config.php';

$count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

if ($count > 0) {
    exit('Demo data was not inserted because users already exist.');
}

$pdo->beginTransaction();

try {
    $passwordHash = password_hash('DemoPass123!', PASSWORD_DEFAULT);

    $users = [
        ['Demo Member 1', 'member1@example.com'],
        ['Demo Member 2', 'member2@example.com'],
        ['Demo Member 3', 'member3@example.com'],
        ['Demo Member 4', 'member4@example.com'],
        ['Demo Member 5', 'member5@example.com'],
        ['Demo Member 6', 'member6@example.com'],
        ['Demo Member 7', 'member7@example.com'],
        ['Demo Member 8', 'member8@example.com'],
        ['Demo Member 9', 'member9@example.com'],
        ['Demo Member 10', 'member10@example.com']
    ];

    $insertUser = $pdo->prepare(
        'INSERT INTO users (full_name, email, password_hash, phone)
         VALUES (?, ?, ?, ?)'
    );

    $insertSetting = $pdo->prepare(
        'INSERT INTO user_settings (user_id) VALUES (?)'
    );

    $userIds = [];

    foreach ($users as $i => $user) {
        $insertUser->execute([
            $user[0],
            $user[1],
            $passwordHash,
            '07100000' . str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)
        ]);

        $userId = (int)$pdo->lastInsertId();
        $userIds[] = $userId;
        $insertSetting->execute([$userId]);
    }

    $trainers = [
        ['Thabo Mokoena', 'Boxing'],
        ['Lerato Dlamini', 'Kickboxing'],
        ['Sipho Nkosi', 'Strength training'],
        ['Ayanda Ndlovu', 'Fitness conditioning'],
        ['Naledi Khumalo', 'Boxing'],
        ['Bongani Zulu', 'Self-defence'],
        ['Zanele Maseko', 'Kickboxing'],
        ['Lwazi Mthembu', 'Strength training'],
        ['Kagiso Molefe', 'Fitness conditioning'],
        ['Amahle Jacobs', 'Boxing']
    ];

    $insertTrainer = $pdo->prepare(
        'INSERT INTO trainers (full_name, specialty, bio)
         VALUES (?, ?, ?)'
    );

    $trainerIds = [];

    foreach ($trainers as $trainer) {
        $insertTrainer->execute([
            $trainer[0],
            $trainer[1],
            'Experienced instructor providing guided training and fitness support.'
        ]);
        $trainerIds[] = (int)$pdo->lastInsertId();
    }

    $plans = [
        ['Basic', 'Access to standard gym sessions.', 199.00],
        ['Standard', 'Gym access and group classes.', 299.00],
        ['Premium', 'Gym access and premium group classes.', 399.00],
        ['Boxing', 'Boxing training membership.', 349.00],
        ['Kickboxing', 'Kickboxing training membership.', 349.00],
        ['Student', 'Discounted student membership.', 179.00],
        ['Off-Peak', 'Access during designated off-peak hours.', 149.00],
        ['Family', 'Family membership plan.', 599.00],
        ['Annual', 'Annual membership plan.', 1999.00],
        ['Personal Training', 'Membership with personal training sessions.', 799.00]
    ];

    $insertPlan = $pdo->prepare(
        'INSERT INTO membership_plans
            (name, description, price, duration_days)
         VALUES (?, ?, ?, ?)'
    );

    $planIds = [];

    foreach ($plans as $plan) {
        $insertPlan->execute([
            $plan[0], $plan[1], $plan[2],
            $plan[0] === 'Annual' ? 365 : 30
        ]);
        $planIds[] = (int)$pdo->lastInsertId();
    }

    $insertClass = $pdo->prepare(
        'INSERT INTO classes
            (trainer_id, title, description, class_date,
             start_time, end_time, capacity)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );

    $classIds = [];
    $classNames = [
        'Beginner Boxing', 'Kickboxing Basics',
        'Strength Circuit', 'Fitness Conditioning',
        'Boxing Skills', 'Self-Defence',
        'Kickboxing Drills', 'Strength Fundamentals',
        'Cardio Boxing', 'Advanced Boxing'
    ];

    foreach ($classNames as $i => $title) {
        $date = date('Y-m-d', strtotime('+' . ($i + 1) . ' days'));

        $insertClass->execute([
            $trainerIds[$i],
            $title,
            'A coached session suitable for members at the advertised level.',
            $date,
            '18:00:00',
            '19:00:00',
            20
        ]);

        $classIds[] = (int)$pdo->lastInsertId();
    }

    $insertMembership = $pdo->prepare(
        'INSERT INTO memberships
            (user_id, plan_id, start_date, end_date, status)
         VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), ?)'
    );

    $insertPayment = $pdo->prepare(
        'INSERT INTO payments
            (user_id, membership_id, amount, payment_method, status, reference_code)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    $insertBooking = $pdo->prepare(
        "INSERT INTO bookings (user_id, class_id, status)
         VALUES (?, ?, 'booked')"
    );

    $insertNotification = $pdo->prepare(
        'INSERT INTO notifications (user_id, title, message)
         VALUES (?, ?, ?)'
    );

    for ($i = 0; $i < 10; $i++) {
        $insertMembership->execute([
            $userIds[$i],
            $planIds[$i],
            'active'
        ]);

        $membershipId = (int)$pdo->lastInsertId();

        $insertPayment->execute([
            $userIds[$i],
            $membershipId,
            $plans[$i][2],
            'demo',
            'pending',
            'DEMO-' . ($i + 1)
        ]);

        $insertBooking->execute([
            $userIds[$i],
            $classIds[$i]
        ]);

        $insertNotification->execute([
            $userIds[$i],
            'Welcome to FightClubSA',
            'Your demo account is ready. Explore classes and membership options.'
        ]);
    }

    $pdo->commit();

    echo 'Demo data inserted successfully.<br>';
    echo 'Demo login: member1@example.com<br>';
    echo 'Demo password: DemoPass123!<br>';
    echo 'Delete or disable seed.php after use.';
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());
    http_response_code(500);
    echo 'Demo data insertion failed. Check the PHP error log.';
}
