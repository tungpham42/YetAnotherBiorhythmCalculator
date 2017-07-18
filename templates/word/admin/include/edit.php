<?php if (!defined('BASE_PATH')) die("No direct access allowed");

// Puzzle to edit
$id = $_GET["edit"];
$options = $config->defaultGameOptions;

if ($_POST)
{
    $name = $_POST["puzzle-name"];
    $options = $_POST["option"];
    $options["puzzleDescription"] = $_POST["puzzleDescription"];
    $data = array();
    $fields_with_errors = array();
    $puzzle_name_error = (!isset($name) || empty($name));
    $descriptions = $_POST["description"];
    
    // Validate form
    foreach ($_POST["word"] as $i => $word)
    {
        if ($word)
        {
            $data[$word] = $descriptions[$i];
        }
        else if ($descriptions[$i])
        {
            $fields_with_errors[$i] = "error";
        }
    }
    
    // No errors
    if ($name && !$fields_with_errors)
    {
        $puzzle = new Puzzle;

        $id = $puzzle->save($name, $data);
        redirect("?edit=$id&success");
    }
}
else if ($id)
{
    $puzzle = new Puzzle;
    
    if ($result = $puzzle->get($id))
    {
        $data = $result["data"];
        $name = $result["name"];
        $options = $result["options"];
    }
}
