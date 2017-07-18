<?php if (!defined('BASE_PATH')) die("No direct access allowed"); ?>
<table class="table" id="words-table">
<thead>
    <tr>
       <th>Word</th>
       <th>Description</th>
    </tr>

    <thead>
        <tbody>

        <?php if ($_POST): ?>
            <?php $descriptions = $_POST["description"]; ?>
            <?php foreach ($_POST["word"] as $i => $word): ?>
                <tr class="<?php echo $fields_with_errors[$i]; ?>">
                    <?php create_word_and_description_inputs($word, $descriptions[$i]); ?>
                </tr>
            <?php endforeach; ?>

        <?php elseif (isset($data)): ?>
            <?php foreach ($data as $word => $description): ?>
                <tr>
                    <?php create_word_and_description_inputs($word, $description); ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr>
            <?php create_word_and_description_inputs(); ?>
        </tr>
    </tbody>
</table>