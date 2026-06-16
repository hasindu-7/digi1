<?php
session_start();
include '../includes/db_connect.php';

// Fetch all FAQs from the database
$result = $conn->query("SELECT * FROM faqs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .faq-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 40px;
            background: var(--bg-surface);
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .faq-container h2 {
            text-align: center;
            margin-bottom: 40px;
            color: var(--accent-color);
        }

        .faq-item {
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 20px;
        }

        .faq-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .faq-question {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--text-primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .faq-answer {
            color: var(--text-secondary);
            line-height: 1.6;
            padding-left: 34px; /* Aligns text with the question, pushing past the emoji */
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <span>❓</span> <?php echo htmlspecialchars($row['question']); ?>
                    </div>
                    <div class="faq-answer">
                        <?php echo nl2br(htmlspecialchars($row['answer'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-secondary);">No FAQs available at the moment.</p>
        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
