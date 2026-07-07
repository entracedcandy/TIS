<!DOCTYPE html>
<html>
<head>
    <title>Form DOC Peternak Baru</title>
    <style>
        form {
            margin-left: 20px;
        }

        h2 {
        margin-left: 10px;
        }
    </style>
</head>
<body>
    <h2>Form DOC Untuk Peternak Baru</h2>

    <form method="post" action="">
        <?php if (!empty($questions)): ?>
            <?php foreach ($questions as $q): ?>
                <div style="margin-bottom: 20px;">
                    <label>
                        <?= $q['question_text'] ?>
                        <?php if (!empty($q['required'])): ?> <span style="color: red">*</span> <?php endif; ?>
                    </label>
                    <br>
                    <?php if ($q['type'] == 'radio' && !empty($q['options'])): ?>
                        <?php foreach ($q['options'] as $opt): ?>
                            <input type="radio" name="q<?= $q['questions_id'] ?>" value="<?= $opt['option_text'] ?>" <?= !empty($q['required']) ? 'required' : '' ?>> <?= $opt['option_text'] ?><br>
                        <?php endforeach; ?>
                    <?php elseif ($q['type'] == 'text'): ?>
                        <input type="text" name="q<?= $q['questions_id'] ?>" <?= !empty($q['required']) ? 'required' : '' ?>><br>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <input type="submit" value="Submit">
        <?php else: ?>
            <p>Tidak ada pertanyaan.</p>
        <?php endif; ?>
    </form>

</body>
</html> 