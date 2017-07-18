<?php if (!defined('BASE_PATH')) die("No direct access allowed");
// Redirect to url
function redirect($url)
{
    header("Location: $url");
    die();
}

// is user logged
function is_logged()
{
    return isset($_SESSION["logged"]);
}

function delete_puzzle($id) {
    unlink(dirname(dirname(dirname(__FILE__))) . "/words/$id.txt");
    redirect('./?');
}

function create_word_and_description_inputs($word="", $description="") {
?>
    <td><input type="text" class="span3" name="word[]" value="<?php echo $word; ?>"/></td>
    <td><input type="text" class="span8" name="description[]" value="<?php echo $description; ?>"/></td>
<?php
}

function create_select_options($options, $default) {
    foreach ($options as $value => $option)
    {
        $selected = $value == $default ? "selected" : "";
        echo "<option value='$value' $selected>$option</option>";
    }
}