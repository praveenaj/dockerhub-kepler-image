<?php
/**
 * Template for Settings tab of Admin panel
 *
 * @since 1.0
 */
$productCode = get_option('envato_purchase_code_kepler');
$isActivated = $productCode != '';

$kepler_theme = wp_get_theme();
$version = $kepler_theme->get( 'Version' );
$diagnostic = get_option('kepler_diagnostic', 'true') == 'true';

add_thickbox();

?>

<?php 
    require_once plugin_dir_path( __FILE__ ) . 'tabs.php';
?>

<div class="kepler_theme_plugins an-inside-container theme-browser rendered kepler_theme_settings">
<div class="themes wp-clearfix">
  <div class="kepler_theme-portlet">
      <div class="kepler_theme-portlet-col f-g-7">
              <h2><?php esc_html_e('Status','kepler_theme')?></h2>
              <p class="kepler_theme-desc"><?php esc_html_e('Product ID: ','kepler_theme')?><span class="an-keycode" id="kepler_theme-product-id"><?php echo esc_html($productCode); ?></span><br/><?php esc_html_e('Kepler version ','kepler_theme')?><?php echo esc_html('1.0.8');?><br/><?php esc_html_e('Theme ','kepler_theme')?><?php echo esc_html($version);?></p>
      </div>
      <div class="kepler_theme-portlet-col f-g-3 an-align-end an-jus-center">
            <p class="dashicons-before dashicons-yes an-label <?php if($isActivated) { echo esc_html('an-active'); } ?>"> <?php esc_html_e('Kepler Builder is ','kepler_theme')?> <?php echo esc_html($isActivated ? 'activated' : 'not activated') ?></p>
      </div>  
  </div>

  <div class="kepler_theme-portlet">
      <div class="kepler_theme-portlet-col f-g-7">
              <h2><?php esc_html_e('Update Product Key','kepler_theme')?></h2>
              <p class="kepler_theme-desc"><?php esc_html_e('To use a different product key on this WordPress instance, select change product key','kepler_theme')?></p>
      </div>
      <div class="kepler_theme-portlet-col f-g-3 an-align-end an-jus-center">
        <a href="#TB_inline?&width=370&height=155&inlineId=kepler_theme-modal-product-key" class="thickbox button button-default open-my-dialog"><?php esc_html_e('Change product key','kepler_theme')?></a>	
      </div>  
  </div>
  <div class="kepler_theme-portlet">
      <div class="kepler_theme-portlet-col f-g-7">
              <h2><?php esc_html_e('Diagnostics & Usage','kepler_theme')?></h2>
              <p class="kepler_theme-desc"><?php esc_html_e('Help Kepler Theme improve its products & services by automatically sending diagnostics and usage data. Diagnostics data include crash reports.','kepler_theme')?></p>
      </div>
      <div class="kepler_theme-portlet-col f-g-3 an-align-end an-jus-center">
              <input id="kepler_theme__diagnostic-option"  class="kepler_theme_apple-switch" type="checkbox" name="kepler_theme__diagnostic-option" <?php if($diagnostic){ echo esc_html('checked'); } ?>>
      </div>
  </div>

  <div class="kepler_theme-portlet kepler_theme-portlet-col">
    <h2 class="an-portlet-title"><?php esc_html_e('API Integrations','kepler_theme')?></h2>
    <div class="kepler_theme-portlet-row an-portlet-item-inner">
      <div class="kepler_theme-portlet-col f-g-7">
                <h2><?php esc_html_e('Adobe Typekit','kepler_theme')?></h2>
                <p class="kepler_theme-desc"><?php esc_html_e('To use a different Adobe Typekit ID on installed Kepler Builder font Explorer, select change Kit ID','kepler_theme')?></p>
        </div>
        <div class="kepler_theme-portlet-col f-g-3 an-align-end an-jus-center">
                <a href="#TB_inline?&width=370&height=155&inlineId=kepler_theme-modal-typekit-key" class="thickbox button button-default open-my-dialog">Change Typekit ID</a>	

        </div>  
    </div>
    <div class="kepler_theme-portlet-row ">
      <div class="kepler_theme-portlet-col f-g-7">
        <h2>
                <?php esc_html_e('Google Maps','kepler_theme')?>
        </h2>
        <p class="kepler_theme-desc">
                <?php esc_html_e('To use a different Google Maps API Key on installed Kepler Builder, select change Map Key','kepler_theme')?>
        </p>
        </div>
        <div class="kepler_theme-portlet-col f-g-3 an-align-end an-jus-center">
                <a href="#TB_inline?&width=370&height=155&inlineId=kepler_theme-modal-google-key" class="thickbox button button-default open-my-dialog">Change map key</a>
        </div>  
    </div>
  </div>
  
    </div>

</div>

<div id="kepler_theme-modal-product-key" style="display:none;">
        <h3>
        <?php esc_html_e('Change Product Key.','kepler_theme')?> 
        </h3>
     <p>
     <?php esc_html_e('Please enter your new product key:','kepler_theme')?>  
     </p>
     <input type="hidden" id="kepler_theme_existing_key" value="<?php echo esc_html($productCode); ?>" hidden/>
     <input type="text" placeholder="xxxx-xxxx-xxxx-xxxx" id="kepler_theme__product-key__input" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" autofocus="true"/>
     <div id="kepler_theme__product-key-error" class="kepler_theme__product-key-error"></div>
        <br/>
     <a href="javascript:void(0)" id="kepler_theme__product-key__submit" class="button button-primary kepler_theme__product-key__submit">Submit</a>

</div>

<div id="kepler_theme-modal-typekit-key" style="display:none;">
        <h3>
        <?php esc_html_e('Change Typekit ID.','kepler_theme')?>
        </h3>
     <p>
     <?php esc_html_e('Please enter your ID','kepler_theme')?> 
     </p>
     <input type="text" placeholder="xxxx-xxxx-xxxx-xxxx" id="kepler_theme__typekit-key__input"/>
     <div id="kepler_theme__typekit-key-error" class="kepler_theme__typekit-key-error"></div>
        <br/>
     <a href="javascript:void(0)" id="kepler_theme__change_typkit_btn" class="button button-primary kepler_theme__change_typkit_btn">Submit</a>

</div>


<div id="kepler_theme-modal-google-key" style="display:none;">
        <h3>
        <?php esc_html_e('Change Google Maps Key. ','kepler_theme')?>
        </h3>
     <p>
        <?php esc_html_e('Please enter your key:','kepler_theme')?>    
     </p>
     <input type="text" placeholder="xxxx-xxxx-xxxx-xxxx" id="kepler_theme__google-key__input"/>
     <div id="kepler_theme__google-key-error" class="kepler_theme__google-key-error"></div>
        <br/>
     <a href="javascript:void(0)" id="kepler_theme__google-key__submit" class="button button-primary kepler_theme__google-key__submit"><?php esc_html_e('Submit','kepler_theme')?></a>

</div>


