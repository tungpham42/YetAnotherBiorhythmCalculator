<?php if (!defined('BASE_PATH')) die("No direct access allowed"); ?>
<legend>Game Options</legend>
<div class="control-group">
    <label class="control-label" for="alphabet">Alphabet</label>
    <div class="controls">
        <input type="text" id="alphabet" name="option[alphabet]" value="<?php echo $options['alphabet']; ?>"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="grid-size">Grid Size</label>
    <div class="controls">
        <select id="grid-size" name="option[size]">
            <?php 
                create_select_options(array(
                    "10" => "10x10",
                    "15" => "15x15",
                    "20" => "20x20"

                ), $options['size']);
            ?>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="showSolveButton">Show solve button</label>
    <div class="controls">
        <select id="showSolveButton" name="option[showSolveButton]">
            <?php 
                create_select_options(array(
                    "1" => "yes",
                    "0" => "no",
                ), $options['showSolveButton']);
            ?>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="show-description">Show descriptions</label>
    <div class="controls">
        <select id="show-description" name="option[showDescriptions]">
            <?php 
                create_select_options(array(
                    "1" => "yes",
                    "0" => "no",
                ), $options['showDescriptions']);
            ?>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="total-words">Total Words</label>
    <div class="controls">
        <input type="text" id="total-words" name="option[totalWords]" value="<?php echo $options['totalWords']; ?>"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="initial-score">initial Score</label>
    <div class="controls">
        <input type="text" id="initial-score" name="option[initialScore]" value="<?php echo $options['initialScore']; ?>"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="seconds">Every</label>
    <div class="controls">
        <div class="input-append">
            <input class="input-mini" type="text" id="seconds" name="option[every]" value="<?php echo $options['every']; ?>"/>
            <span class="add-on">seconds</span>
        </div>
        
        <div class="input-prepend input-append">
             <span class="add-on">deduct</span>
            <input class="input-mini" name="option[deduct]" type="text" value="<?php echo $options['deduct']; ?>"/>
            <span class="add-on">points</span>
        </div>
    </div>
</div>
