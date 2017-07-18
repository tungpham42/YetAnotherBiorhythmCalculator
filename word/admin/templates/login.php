<?php if (!defined('BASE_PATH')) die("No direct access allowed"); ?>

<div class="span4 offset4">
    <form method="post" action="">
        <h1 class="text-center">Login</h1>

        <div class="control-group">
            <label for="username">Username:</label>
            <div class="controls">
                <input class="input-xlarge" type="text" id="username" name="username" placeholder="Username"/>
            </div>
        </div>
        
        <div class="control-group">
            <label for="password">password:</label>
            <div class="controls">
                <input class="input-xlarge" type="password" id="password" name="password" placeholder="Password" />
            </div>
        </div>

        <input class="btn" type="submit" value="Login"/>
    </form>
</div>
