<?php

/**
 * Kepler Builder UI
 *
 * @link       http://revox.io
 * @since      1.0.0
 *
 * @package    Kepler
 * @subpackage Kepler/builder/partials
 */

$isPlanMode = Kepler_Builder::isPlanMode();

?>
<div id="errorView" class=""></div>
<div id="preloader" class="preloader">
</div>
<a href="#" data-toggle="mode" data-view="standard" class="standard-mode-switch" id="switchPreviewStandard">
    <i class="ar-icon ar-preview"></i>
</a>
<div id="header" class="header"></div>
<div id="welcome"></div>
<div id="toolbox"></div>
<div class="page-container">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane <?= $isPlanMode ? 'active' : '' ?>" id="tab-plan">
            <div id="page-switcher"></div>
        </div>
        <div role="tabpanel" class="tab-pane <?= $isPlanMode ? '' : 'active' ?>" id="tab-design">
            <div class="page-preview" id="page-preview">
            </div>
            <div id="sidebar" class="sidebar right">
                <div class="right-inner full-height">
                    <div id="sidebar-panels" class="sidepanel-column">
                        <div class="panel panel-layers" id="layers-panel-wrapper" data-sortable="true" data-adjust="height" style="display:none">
                            <div class="panel-heading">
                                Layers
                                <div class="panel-controls">
                                    <ul>
                                        <li>
                                            <a href="#" class="builder-sorthandler"><i class="ar-icon ar-drag-handle"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="layersView" class="layers-tree">
                                </div>
                            </div>
                        </div>
                        <div id="emptyDesignPanel" class="empty-preset panel panel-presets full-height full-width">
                            <div class="panel-presets-inner">
                                <div class="element-details">
                                    <span class="element-name f-black f-11 light"><i class="ar-icon kp-wireframe"></i> <span class="muted">Select Something</span></span>
                                </div>
                                <ul class="nav nav-tabs preset-tabs settigs-tab-nav" role="tablist">
                                    <li class="cursor-default" role="presentation">
                                        <a aria-controls="appearance" class="tab-appearance settigs-tab disabled cursor-default" href="javascript:void(0)" role="tab" title="Appearance"><i class="ar-icon ar-appearance"></i></a>
                                    </li>
                                    <li class="cursor-default" role="presentation">
                                        <a aria-controls="settings" class="tab-settings settigs-tab disabled cursor-default" href="javascript:void(0)" role="tab" title="Settings"><i class="ar-icon ar-settings"></i></a>
                                    </li>
                                    <li class="cursor-default" role="presentation">
                                        <a aria-controls="animations" class="tab-settings parralax-tab disabled cursor-default" href="javascript:void(0)" role="tab" title="Parallax"><i class="ar-icon ar-interaction"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel-body no-padding d-flex justify-content-center align-items-center relative">
                                    <div class="text-align-center adjust-position">
                                    <svg version="1.1" width="70px" height="70px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 70 70" xml:space="preserve">
                                        <path opacity="0.82" fill="none" stroke="#CCCCCC" stroke-width="1.618" stroke-miterlimit="10" d="M23,53H10c-1.1,0-2-0.9-2-2V38c0-1.1,0.9-2,2-2h13c1.1,0,2,0.9,2,2v13C25,52.1,24.1,53,23,53z"/>
                                        <circle opacity="0.82" fill="none" stroke="#CCCCCC" stroke-width="1.618" stroke-miterlimit="10" cx="16.5" cy="13.5" r="10.5"/>
                                        <path opacity="0.82" fill="none" stroke="#B7B7B7" stroke-width="1.24" stroke-miterlimit="5" stroke-linejoin="round" stroke-linecap="round" d="M49.9,45.9c-0.6-1.4-2.3-5.2-2.5-5.6c-0.5-1.4,0.1-2.6,1.4-3.2c1.3-0.6,2.6-0.2,3.3,1.1
                                            c0.2,0.4,1.9,4.3,2.6,5.7c-0.4-0.8-0.8-1.8-1.2-2.6c-0.4-1-1.1-2.1-0.4-3.2c0.7-1.2,2.8-1.7,3.9-0.9c0.5,0.4,4.3,9,6,12.6
                                            c2.3,5.1,1.1,11.1-3.3,14.6c-3.1,2.5-7.3,2.6-11,1.3c-3.6-1.2-6.4-3.6-9.7-5.4c-1.3-0.7-7.1-3.6-3.9-5.6c1-0.6,2.2-0.5,3.3-0.3
                                            c1.5,0.2,3,0.6,4.5,1.1c-1.1-2.5-9.1-20.5-9.1-20.5c-0.9-2.4,3.7-4.1,4.7-2c0,0,7.3,16.3,6.5,14.5c-0.8-1.7-3.1-6.9-3.1-7.6
                                            c0-2.6,3.6-3.7,4.8-1.3L49.9,45.9z"/>
                                        <polygon opacity="0.82" fill="none" stroke="#CCCCCC" stroke-width="1.618" stroke-miterlimit="10" stroke-linejoin="round" stroke-linecap="round" points="47.5,4 38,21 57,21 "/>
                                    </svg>
                                    </div>
                                    <div class="pull-bottom bottom-left p-l-15 p-b-5">
                                    <p class="f-10 f-black light caps muted">HELP?</p>
                                        <p class="f-11 f-black light m-r-30 muted">Having trouble? Check our <a target="_blank" rel="noopener noreferrer" href="https://www.zendesk.com" class="link">Video Tutorials</a>  or <a target="_blank" rel="noopener noreferrer" href="https://www.zendesk.com" class="link">Community</a><i class="ar-icon kp-newpage"></i>
                                    </div>
                            </div>
                        </div>
                        <div id="props-panels" class="panel panel-presets" data-sortable="true" data-adjust="height">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="elementsMarketPlace" class="model-wrapper">
        
    </div>
</div>
<div id="modal-region"></div>
<div id="tour" class="kp-tour-container">
    <div class="kp-tour-step" data-order="1" data-step>
        <button class="kp-tour-dismiss-small" data-dismiss>
            <i class="ar-icon kp-tour-close"></i>
        </button>
        <div class="kp-tour-header">
            <p class="kp-tour-title f-15 f-black regular">
            Hi champ!. 👋<br/>
    You are one of the early Elites to try out Kepler.
            </p>
            <div class="kp-tour-logo">
            </div>
        </div>
        <div class="kp-tour-chat-bubble">
            <p class="kp-tour-message f-14a f-black light no-margin">I’m David from team Kepler, welcome to Kepler. 
    If you need help click  <span><i class="menu-icon ar-icon kp-hamburger"></i></span> on the top left corner.</p>
        </div>
    </div>

    <div class="kp-tour-step" data-order="2" data-step>
        <button class="kp-tour-dismiss-small" data-dismiss>
            <i class="ar-icon kp-tour-close"></i>
        </button>
        <p class="kp-tour-title f-10 f-black light caps muted">Did you know?</p>
        <p class="kp-tour-message f-13 f-black light">You can choose different website 
            filters from the explorer</p>
        <button class="kp-tour-dismiss-large btn btn-default btn-focus f-black f-12 medium" data-dismiss>Got it</button>
    </div>

    <div class="kp-tour-step" data-order="3" data-step>
        <button class="kp-tour-dismiss-small" data-dismiss>x</button>
        <p class="kp-tour-title f-10 f-black light caps muted">Did you know?</p>
        <p class="kp-tour-message f-13 f-black light ">You can enter edit styles to
make global style changes.</p>
        <button class="kp-tour-dismiss-large btn btn-default btn-focus f-black f-12 medium" data-dismiss>Got it</button>
    </div>
    <div class="kp-tour-step" data-order="4" data-step>
        <div>
            <video class="an-setup-video" preload="metadata" poster="<?php echo plugin_dir_url(__FILE__) . '../assets/video/tour-global-tip.jpg'?>" loop="" autoplay="" muted="" playsinline="" class="hero-img" width="490">
                <source src="<?php echo  plugin_dir_url(__FILE__) . '../assets/video/tour-global-tip.mp4' ?>" class="" type="video/mp4">
                <source src="<?php echo plugin_dir_url(__FILE__) . '../assets/video/tour-global-tip.ogg'?>" type="video/ogg">
                <source src="<?php echo plugin_dir_url(__FILE__) . '../assets/video/tour-global-tip.webm'?>" type="video/webm">
            </video>

            <p class="kp-tour-title f-black f-15 regular">In the Style Editor mode, you can make global 
    changes to all instances of this element, 
    across the website.</p>
            <p class="kp-tour-message f-12 f-black light muted">To make changes that are local to single instance of an element, you can go back to instance, and use the options in the Inspector on the right.</p>
            <div class="kp-tour-message-footer">
                <a href="https://help.kepler.app/knowledgebase/inspector/" target="_blank" class="k btn btn-default btn-focus f-black f-12 medium" >Learn more</a>
                <button class=" btn btn-default btn-focus f-black f-12 medium" data-dismiss>Got it</button> 
            </div>
        </div>
    </div>

    <div class="kp-tour-step" data-order="5" data-step>
    <button class="kp-tour-dismiss-small" data-dismiss>
        <i class="ar-icon kp-tour-close"></i>
    </button>
        <div>
            <i class="ar-icon kp-hand-point p-r-5 p-l-5"></i>
            <p class="kp-tour-message f-11 f-black light">Psst.. You can also switch 
presets of the selected elements
with just a click</p>
        </div>
        <button class="kp-tour-dismiss-large btn btn-default btn-focus f-black f-12 medium" data-dismiss>Got it</button>
        
       
        
    </div>
</div>