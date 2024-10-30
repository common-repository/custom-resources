<?php namespace Org\Fineswap\OpenSource\CustomResources; ?>

<div class="wrap" id="custom_resources_wrap">
  <div class="sidebar-wrap">
    <div class="sidebar-container sidebar-author">
      <h3>An Open Source product from:</h3>
      <a href="http://fineswap.com/open-source/?utm_source=wordpress&utm_medium=plugin&utm_term=sidebar&utm_campaign=custom-resources" target="_blank"><img src="http://cdn.fineswap.com/+sun2feb1537/logo.png" border="0" alt="Fineswap's logo"/></a>
    </div>
    <div class="sidebar-container sidebar-about">
      <h3>About Us</h3>
      <div class="sidebar-content">
        <p>Fineswap is on a journey to broaden your horizons about sustainability and green living:</p>
        <ul>
          <li>Our <a href="http://fineswap.com/?utm_source=wordpress&utm_medium=plugin&utm_term=sidebar&utm_campaign=custom-resources" target="_blank">website</a> is a blog dedicated to bringing you news, updates, views, reviews, ideas and thoughts of worldwide authors writing about sustainability and green living.</li>
          <li>Our <a href="http://fineswap.com/app/?utm_source=wordpress&utm_medium=plugin&utm_term=sidebar&utm_campaign=custom-resources" target="_blank">mobile app</a> enables users to publish their possessions for the purpose of swapping with others. It's a modern take on fashion and sustainable living; we call it &quot;green consumerism&quot;.</li>
        </ul>
      </div>
    </div>
    <div class="sidebar-container sidebar-social">
      <h3>Follow Us:</h3>
      <div class="sidebar-content">
        <ul>
          <li class="twitter-share"><a href="https://twitter.com/fineswap" target="_blank">Twitter</a></li>
          <li class="facebook-share"><a href="https://www.facebook.com/fineswap.page" target="_blank">Facebook</a></li>
          <li class="google-share"><a href="https://plus.google.com/110648714643818672919" target="_blank">Google</a></li>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
  </div>
  <div class="body-wrap">
    <h2><?php echo htmlspecialchars(TITLE); ?></h2>

    <form method="post" action="options.php">

      <?php settings_fields(CFG); ?>
      <?php do_settings_sections(CFG); ?>

      <p>
        By default, custom styles and scripts are loaded for both frontend website and backend system.
        Go to the end of the page to change this behavior.
      </p>

      <table class="form-table">

        <tr class="absolute-links">
          <th scope="row">Absolute links</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Turn all relative styles' links to absolute ones</span></legend>
              <label for="<?php echo CFG_STYLES_ABSOLUTE; ?>">
                <input name="<?php echo CFG_STYLES_ABSOLUTE; ?>" type="checkbox" value="1" id="<?php echo CFG_STYLES_ABSOLUTE; ?>" <?php echo checked('1', get_option(CFG_STYLES_ABSOLUTE), FALSE); ?>/>
                Turn all relative styles' links to absolute ones
              </label>
            </fieldset>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">External styles</th>
          <td>
            <textarea name="<?php echo CFG_STYLES_EX; ?>" spellcheck="false"><?php echo get_option(CFG_STYLES_EX); ?></textarea>
            <p class="description">Loaded just before &lt;/head&gt;; enter one URL per line including prefix (http://, https://, //)</p>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Inline styles</th>
          <td>
            <textarea name="<?php echo CFG_STYLES_IN; ?>" spellcheck="false"><?php echo get_option(CFG_STYLES_IN); ?></textarea>
            <p class="description">Embedded immediately after <strong>External styles</strong>; do not include &lt;style&gt;...&lt;/style&gt;</p>
          </td>
        </tr>

        <tr class="absolute-links">
          <th scope="row">Absolute links</th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span>Turn all relative scripts' links to absolute ones</span></legend>
              <label for="<?php echo CFG_SCRIPT_ABSOLUTE; ?>">
                <input name="<?php echo CFG_SCRIPT_ABSOLUTE; ?>" type="checkbox" value="1" id="<?php echo CFG_SCRIPT_ABSOLUTE; ?>" <?php echo checked('1', get_option(CFG_SCRIPT_ABSOLUTE), FALSE); ?>/>
                Turn all relative scripts' links to absolute ones
              </label>
            </fieldset>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">External scripts</th>
          <td>
            <textarea name="<?php echo CFG_SCRIPT_EX; ?>" spellcheck="false"><?php echo get_option(CFG_SCRIPT_EX); ?></textarea>
            <p class="description">Loaded just before &lt;/head&gt;; enter one URL per line including prefix (http://, https://, //)</p>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">Inline script</th>
          <td>
            <textarea name="<?php echo CFG_SCRIPT_IN; ?>" spellcheck="false"><?php echo get_option(CFG_SCRIPT_IN); ?></textarea>
            <p class="description">Embedded immediately after <strong>External scripts</strong>; do not include &lt;script&gt;...&lt;/script&gt;</p>
          </td>
        </tr>

      </table>

      <h3>Load custom styles in:</h3>

      <div class="form-table">

        <fieldset>
          <legend class="screen-reader-text"><span>Frontend website</span></legend>
          <label for="<?php echo CFG_STYLES_LOAD_FRT; ?>">
            <input name="<?php echo CFG_STYLES_LOAD_FRT; ?>" type="checkbox" value="1" id="<?php echo CFG_STYLES_LOAD_FRT; ?>" <?php echo checked('1', get_option(CFG_STYLES_LOAD_FRT), FALSE); ?>/>
            Frontend website
          </label>
        </fieldset>

        <fieldset>
          <legend class="screen-reader-text"><span>Backend console</span></legend>
          <label for="<?php echo CFG_STYLES_LOAD_BCK; ?>">
            <input name="<?php echo CFG_STYLES_LOAD_BCK; ?>" type="checkbox" value="1" id="<?php echo CFG_STYLES_LOAD_BCK; ?>" <?php echo checked('1', get_option(CFG_STYLES_LOAD_BCK), FALSE); ?>/>
            Backend console
          </label>
        </fieldset>

      </div>

      <h3>Load custom scripts in:</h3>

      <div class="form-table">

        <fieldset>
          <legend class="screen-reader-text"><span>Frontend website</span></legend>
          <label for="<?php echo CFG_SCRIPT_LOAD_FRT; ?>">
            <input name="<?php echo CFG_SCRIPT_LOAD_FRT; ?>" type="checkbox" value="1" id="<?php echo CFG_SCRIPT_LOAD_FRT; ?>" <?php echo checked('1', get_option(CFG_SCRIPT_LOAD_FRT), FALSE); ?>/>
            Frontend website
          </label>
        </fieldset>

        <fieldset>
          <legend class="screen-reader-text"><span>Backend console</span></legend>
          <label for="<?php echo CFG_SCRIPT_LOAD_BCK; ?>">
            <input name="<?php echo CFG_SCRIPT_LOAD_BCK; ?>" type="checkbox" value="1" id="<?php echo CFG_SCRIPT_LOAD_BCK; ?>" <?php echo checked('1', get_option(CFG_SCRIPT_LOAD_BCK), FALSE); ?>/>
            Backend console
          </label>
        </fieldset>

      </div>

      <?php submit_button(); ?>

    </form>
  </div>
  <div class="clear"></div>
</div>
