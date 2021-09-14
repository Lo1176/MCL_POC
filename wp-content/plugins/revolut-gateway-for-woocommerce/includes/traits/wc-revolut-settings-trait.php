<?php
defined('ABSPATH') || exit();

/**
 * Settings trait
 *
 * Provides shared logic for navigation tab settings
 *
 * @since 2.0
 */
trait WC_Revolut_Settings_Trait
{

    /**
     * Tab title
     *
     * @var string
     */
    protected $tab_title;

    /**
     * API settings
     *
     * @var WC_Revolut_Settings_API
     */
    protected $api_settings;

    public function admin_nav_tab($tabs)
    {
        $tabs[$this->id] = $this->tab_title;
        return $tabs;
    }

    public function output_settings_nav()
    {
        if (isset($_GET['page']) && isset($_GET['section'])) {
            $is_revolut_section = $_GET['page'] == 'wc-settings' && ($_GET['section'] == 'revolut' || $_GET['section'] == 'revolut_cc' || $_GET['section'] == 'revolut_pay');

            if ($is_revolut_section) {
                include REVOLUT_PATH . 'templates/html-settings-nav.php';
            }
        }
    }
}
