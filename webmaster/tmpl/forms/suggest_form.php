<div class="form-area thumbnail">
    <div class="error alert alert-error"></div>

    <form method="post" id="main">
        <div class="control-group">
            <label for="keyword" class="control-label">Google Suggest Tool - Enter Keyword</label>
            <div class="controls">
                <input type="text" id="keyword" name="keyword" placeholder="how * money" value="" class="input-block-level"/>
            </div>
        </div>
        <div class="control-group">
            <label for="lg" class="control-label">Any Google Language Code (you can see all codes <a href="https://sites.google.com/site/tomihasa/google-language-codes" rel="nofollow" target="_blank">here</a>)</label>
            <div class="controls">
                <input type="text" id="lg" name="lg" value="en" class="input-block-level"/>
            </div>
        </div>
        <? if(ConfigFactory::load("captcha")->$controller): ?>
            <div class="control-group">
                <div class="controls">
                    <label for="captcha" class="control-label">Captcha</label>
                    <input type="image" src="<?= base_url() ?>captcha/captcha.php" id="captcha_img" name="captcha_img" class="captcha-image"/>
                    <input type="text" id="captcha" name="captcha" class="input-block-level captcha" autocomplete="off"/>
                    <br/>
                    <span class="captcha-refresh">click to refresh</span>
                </div>
            </div>
        <? endif; ?>

        <button type="submit" id="go" class="btn btn-large btn-primary">Submit</button>
    </form>
</div>