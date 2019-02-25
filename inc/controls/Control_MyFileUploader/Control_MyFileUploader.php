<?php

class Control_MyFileUploader extends \Elementor\Base_Data_Control
{

    public static $_id = 'file_uploader';

    /**
     * Get emoji one area control type.
     *
     * Retrieve the control type, in this case `emojionearea`.
     *
     * @since  1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function get_type()
    {
        return self::$_id;
    }

    /**
     * Enqueue emoji one area control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the emoji one
     * area control.
     *
     * @since  1.0.0
     * @access public
     */
    public function enqueue()
    {
        wp_enqueue_media();

        // Styles

        // Scripts
        $class_name = get_class( $this );
        wp_enqueue_script( 'elementor-file-uploader', EFU_DIR_URL . "/inc/controls/$class_name/$class_name.js", [ 'jquery' ], '1.0' );
    }

    /**
     * Render text control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since  1.0.0
     * @access public
     */
    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        $value       = $this->get_value( [ 'name' => self::$_id ], [] );
        ?>
        <div class="elementor-control-field" style="display: block;">
            <label class="elementor-control-title" style="width: 100%;">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper" style="width: 100%;">
                <div class="pmw-file-upload">
                    <div class="pmw-file-preview" data-text-filename="<?php _e( 'File name', 'efu' ) ?>" data-text-size="<?php _e( 'Size', 'efu' ) ?>" style="margin-top:20px;margin-bottom:20px;"></div>
                    <button class="elementor-button elementor-button-default elementor-button-success pmw-upload-button">
                        <?php _e( 'Upload', 'efu' ) ?>
                    </button>
                    <button class="elementor-button elementor-button-default elementor-button-warning pmw-remove-button" style="background-color: rgb(176, 27, 27);">
                        <?php _e( 'Delete', 'efu' ) ?>
                    </button>
                    <input type="hidden" class="file-value" data-setting="{{ data.name }}">
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get text control default settings.
     *
     * Retrieve the default settings of the text control. Used to return the
     * default settings while initializing the text control.
     *
     * @since  1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function get_default_settings()
    {
        return [];
    }

    /**
     * this method will return the default value of this control
     * @return array|string
     */
    public function get_default_value()
    {
        return [
            'id' => '',
            'url' => '',
            'size' => '',
            'name' => '',
        ];
    }

}