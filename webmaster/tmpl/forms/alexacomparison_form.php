<div class="form-area thumbnail">
    <div class="error alert alert-error"></div>

    <form method="post" id="main">
        <div class="domain-wrapper">
            <div class="control-group alexa-domain">
                <label class="control-label" for="domain1">Enter Domain</label>
                <div class="input-append">
                    <input id="domain1" name="domain[]" type="text" class="domain_input">
                    <button class="btn remove_domain" type="button" style="display: none;">-</button>
                    <button class="btn add_domain" type="button">+</button>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="graph_type">Graph Type</label>
            <select id="graph_type" name="graph_type">
                <option value="r">Daily Reach</option>
                <option value="n">Rank</option>
            </select>
        </div>
        <div class="control-group">
            <label class="control-label" for="time_span">Time Span</label>
            <select id="time_span" name="time_span">
                <option value="3m">3 Months</option>
                <option value="6m">6 Months</option>
                <option value="1y">1 Year</option>
                <option value="3y">3 Year</option>
            </select>
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

        <button type="submit" id="go" class="btn btn-large btn-primary">Check</button>
    </form>
</div>