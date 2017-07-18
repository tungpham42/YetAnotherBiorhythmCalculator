<?php
// Quick settings
set_time_limit(2);
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Include the Reversi class
include 'libs/Core/Reversi.class.php';

// How big (wide and tall) should the playing board be?
$gridSize = 8;

// Create the game!
$reversi = new Core_Reversi($gridSize);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Language" content="en-gb" />
    <title>Reversi board game in PHP and Javascript</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <link rel="stylesheet" type="text/css" href="./assets/css/reversi.css" media="screen" />
    
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/js/reversi.js"></script>
    <script type="text/javascript" src="./assets/js/str_split.js"></script>
    <script type="text/javascript">
    // Set variables
    var gridSize           = <?php echo $gridSize; ?>,
        boardContent       = new Object,
        boardContentString = "<?php echo $reversi->getBoardAfterTurn(); ?>",
        turnInPlay         = "<?php echo $reversi->getTurn(); ?>",
        turnNext           = turnInPlay == 'b' ? 'w' : 'b',
        coords             = null,
        x                  = false,
        y                  = false,
        xTemp              = false,
        yTemp              = false,
        next               = false,
        continueOn         = true,
        disksChanged       = new Array;
        
    // Setup the board
    setBoardContent();
    </script>
</head>
<body>
    <div style="float:left">
        <table id="reversi_board">
            <?php echo $reversi->getBoard(); ?>
        </table>
    </div>
    
    <div style="float:left;margin-left:30px">
        <h1>Thống kê ván Cờ</h1>
        
        <!-- Set the stats //-->
        <?php
        // Get the stats
        $reversiScore  = $reversi->getScore();
        $reversiStatus = $reversi->getGameStatus();
        ?>
        
        <!-- Is the game still ongoing? //-->
        <?php if ($reversiScore['empty'] <= 0) { ?>
            <!-- Game has finished //-->
            <p><strong>Trò chơi đã kết thúc!</strong></p>
            
            <p>
                <strong><?php echo $reversi->getFullColor($reversiStatus, true); ?></strong> thắng,
                điểm số là
                <strong><?php echo $reversiScore['white']; ?></strong>-<strong><?php echo $reversiScore['black']; ?></strong>
            </p>
            
            <p><a href="./">Chơi lại, sao không nhỉ?!</a></p>
        <?php } else { ?>
            <!-- Game is in progress //-->
            <!-- Is it a tie? //-->
            <?php if ($reversiStatus == 'tie') { ?>
                <!-- Tie //-->
                <p>
                    <strong>Tỉ số Hòa!</strong>
                    <strong><?php echo $reversiScore['white']; ?></strong>-<strong><?php echo $reversiScore['black']; ?></strong>
                    còn <strong><?php echo $reversiScore['empty']; ?></strong> ô nữa.
                </p>
            <?php } else { ?>
                <!-- Someone is winning //-->
                <p>
                    <strong><?php echo $reversi->getFullColor($reversiStatus, true); ?></strong> đang thắng,
                    <strong><?php echo $reversiScore['white']; ?></strong>-<strong><?php echo $reversiScore['black']; ?></strong>
                    còn <strong><?php echo $reversiScore['empty']; ?></strong> ô nữa.
                </p>
            <?php } ?>
            
            <!-- Which players turn is it? //-->
            <p><strong><?php echo $reversi->getFullColor($reversi->getTurn(), true); ?></strong>, đến lượt chơi của bạn!</p>
            
            <!-- How many disks were flipped? //-->
            <?php if ($reversi->getDisksFlipped() >= 1) { ?>
                <!-- Some were flipped //-->
                <p><?php echo $reversi->getDisksFlipped(); ?> ô đã lật!</p>
            <?php } else if (isset($_GET['x'])) { ?>
                <!-- No disks were flipped //-->
                <div class="error">
                    <p>Bạn chưa lật ô nào! Nếu muốn bỏ bước thì <a href="./?x=<?php echo (int)$_GET['x']; ?>&amp;y=<?php echo (int)$_GET['x']; ?>&amp;turn=<?php echo $_GET['turn'] == 'b' ? 'w' : 'b'; ?>&amp;board=<?php echo htmlentities($_GET['board']); ?>">nhấn vào đây</a>.</p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>