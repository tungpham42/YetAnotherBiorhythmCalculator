<?php if (!defined('BASE_PATH')) die("No direct access allowed"); ?>
<div class="span12">
    <form class="form-horizontal tab-content" method="post" action="?edit=<?php echo $id; ?>&utf8=✔">
        <?php if (isset($_GET["success"])): ?>
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                The puzzle was successfully saved.
            </div>
        <?php endif ?>

        <?php if (isset($puzzle_name_error)): ?>
            <div class="alert alert-error">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                Puzzle name is required
            </div>
        <?php endif ?>

        <div class="control-group">
            <label class="control-label" for="puzzle-name">Puzzle name</label>
            <div class="controls">
                <input type="text" id="puzzle-name" name="puzzle-name" value="<?php echo isset($name) ? $name : ''; ?>"/>
                <input type="submit" class="btn btn-success" value="Save">
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="puzzleDescription">Puzzle Description</label>
            <div class="controls">
                <textarea class="input-block-level" id="puzzleDescription" name="puzzleDescription"/><?php
                if (isset($options['puzzleDescription']) && !empty($options['puzzleDescription']))
                    echo urldecode($options['puzzleDescription']);
                ?></textarea>
            </div>
        </div>

        <!-- TABS -->
        <ul class="nav nav-tabs">
          <li class="active"><a href="#main" data-toggle="tab">Words</a></li>
          <li><a href="#game-options" data-toggle="tab">Game options</a></li>
        </ul>
        
        <fieldset class="tab-pane" id="game-options">
            <?php include_once('game-options.php'); ?>
        </fieldset>

        <fieldset class="tab-pane active" id="main">
            <legend>Words</legend>

            <?php if (isset($fields_with_errors)): ?>
                <div class="alert alert-error">
                    <a class="close" data-dismiss="alert" href="#">&times;</a>
                    Some fields have errors (word is required)
                </div>
            <?php endif ?>

            <?php include_once('word-table.php'); ?>

            <!-- Add inputs button -->
            <button class="btn btn-success pull-right" type="button" id="add-input"/>
                <i class="icon-plus"></i> Add word
            </button>
        </fieldset>

        <!-- Save Button -->
        <input class="btn" type="submit" value="Save"/>

        <!-- HELP -->
        <br><br>
        <div class="alert alert-info">Use <b>tab</b> key in the last input to add new words</span>
    </form>
</div>

<script src="js/admin.js" type="text/javascript"></script>
