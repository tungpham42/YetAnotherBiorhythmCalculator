<?php if (!defined('BASE_PATH')) die("No direct access allowed");

include_once("config.php");

define("PATH", dirname(dirname(dirname(__FILE__))) . "/words");

class Puzzle {
    
    # Save words to a file
    function save($name, $data)
    {
        $result = "";
        
        foreach ($_POST["option"] as $option => $value)
        {
            $result .= "# $option: $value\n";
        }

        if (isset($_POST["puzzleDescription"]))
            $result .= "# puzzleDescription: " . urlencode($_POST["puzzleDescription"]) . "\n";

        $result .= "# name: $name\n";
        
        foreach ($data as $word => $description)
        {
            $result .= "$word | $description\n";
        }
        
        if (file_put_contents($this->getFilePath($name), trim($result)))
            $this->deleteFileIfNeeded($name);
        
        return $this->getFileName($name);
    }

    private function deleteFileIfNeeded($name) {
        // filename is different
        if (! empty($_GET['edit']) && $_GET['edit'] !== $this->getFileName($name))
            unlink($this->getFilePath($_GET['edit']));
    }

    // Get Words from file
    function get($name)
    {
        global $config;

        $filename = $this->getFilePath($name);
        $name = $this->getPuzzleName($name);
        
        $data = array();
        $options = $config->defaultGameOptions;

        if ($content = file_get_contents($filename))
        {
            $content = preg_split("/\r\n|\n/", $content);

            foreach ($content as $line)
            {
                if (!trim($line) || substr(trim($line), 0, 1) == "#") {
                    $parts = explode(":", trim($line, "#"));

                    // both parts are required
                    if (trim($parts[0]) !== "" && trim($parts[1]) !== "")
                        $options[trim($parts[0])] = trim($parts[1]);

                } else {
                    $parts = explode("|", $line);
                    $data[trim($parts[0])] = trim(isset($parts[1]) ? $parts[1] : "");
                }
            }

           
        }

        return array("name" => $name, "data" => $data, "options" => $options);
    }
    
    // Get list of files
    function getAll($page=0)
    {
        global $config;

        $files = glob(PATH."/*.txt");

        $start = ($page - 1) * $config->per_page;
        $end   = $page * $config->per_page;
        $data  = array();

        for ($i = $start; $i < $end;  $i += 1)
        {

            if (!isset($files[$i])) { break; }

            # remove file extention
            $name = str_replace(".txt", "", str_replace(PATH."/", "", $files[$i]));

            $data[] =  (object)array(
                "name" => $this->getPuzzleName($name),
                "id"   => $name
            );
        }

        return $data;
    }

    // Count all files
    function countAll()
    {
        return count(glob(PATH."/*.txt"));
    }

    // remove dash from file name
    function getPuzzleName($name)
    {
        return str_replace("-", " ", ucwords($name));
    }
    
    // Get file location
    function getFilePath($name)
    {
        $filename = $this->getFileName($name);
        
        return PATH."/$filename.txt";
    }
    
    function getFileName($name)
    {
        return str_replace(" ", "-", strtolower($name));
    }
}
