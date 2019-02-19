window.$ = jQuery;
jQuery( window ).on( 'elementor:init', function () {
    var Controlfile_uploader = elementor.modules.controls.BaseData.extend( {
        ui: function ui() {
            var ui = elementor.modules.controls.BaseData.prototype.ui.apply( this, arguments );

            ui.upload_button = '.pmw-upload-button';
            ui.remove_button = '.pmw-remove-button';
            ui.file_preview = '.pmw-file-preview';

            return ui;
        },

        events: function events() {
            return _.extend( elementor.modules.controls.BaseData.prototype.events.apply( this, arguments ), {
                'click @ui.upload_button': 'openFrame',
                'click @ui.remove_button': 'deleteImage'
            } );
        },

        applySavedValue: function applySavedValue() {
            var value = this.getControlValue();
            if ( value.id )
            {
                var filename_parts = value.name.split('.');
                var image_types = [ 'jpg', 'jpeg', 'png', 'gif', 'svg' ];
                if ( image_types.indexOf(filename_parts[filename_parts.length-1]) >= 0 )
                {
                    this.ui.file_preview.html( '<p><img src="' + value.url + '" style="max-width: 70%;height: auto;"></p>' );

                }else
                {
                    this.ui.file_preview.html( '<p><strong>' + this.ui.file_preview.data('text-filename') + '</strong>: ' + value.name + '<br><strong>' + this.ui.file_preview.data('text-size') + '</strong>: ' + value.size + '</p>' );
                }
                this.ui.remove_button.css('display', 'inline-block');
            }else
            {
                this.ui.file_preview.html( '' );
                this.ui.remove_button.css('display', 'none');
            }
        },

        openFrame: function openFrame() {
            if ( !this.frame )
            {
                this.initFrame();
            }

            this.frame.open();
        },

        deleteImage: function deleteImage( event ) {
            event.stopPropagation();

            this.setValue( {
                url: '',
                id: '',
                name: '',
                size: ''
            } );

            this.applySavedValue();
        },

        /**
         * Create a media modal select frame, and store it so the instance can be reused when needed.
         */
        initFrame: function initFrame() {
            // Set current doc id to attach uploaded images.
            wp.media.view.settings.post.id = elementor.config.document.id;
            this.frame = wp.media( {
                frame: "post",
                state: "insert",
                multiple: false
            } );

            // When a file is selected, run a callback.
            this.frame.on( 'insert', this.select.bind( this ) );
        },

        /**
         * Callback handler for when an attachment is selected in the media modal.
         * Gets the selected image information, and sets it within the control.
         */
        select: function select() {
            this.trigger( 'before:select' );

            // Get the attachment from the modal frame.
            var attachment = this.frame.state().get( 'selection' ).first().toJSON();

            if ( attachment.url )
            {
                this.setValue( {
                    url: attachment.url,
                    id: attachment.id,
                    name: attachment.filename,
                    size: attachment.filesizeHumanReadable
                } );

                this.applySavedValue();
            }

            this.trigger( 'after:select' );
        },

        onBeforeDestroy: function onBeforeDestroy() {
            this.$el.remove();
        }
    } );
    elementor.addControlView( 'file_uploader', Controlfile_uploader );
} );
function pmw_media_uploader()
{
    media_uploader = wp.media( {
        frame: "post",
        state: "insert",
        multiple: false
    } );

    media_uploader.on( "insert", function () {
        var json = media_uploader.state().get( "selection" ).first().toJSON();
        var image_url = json.url;
        if ( formfield )
        {
            if ( formtype === "image" )
            {
                $( '#' + formfield + " .file-value" ).val( json.id );
                $( '#' + formfield + " .pmw-image-preview" ).attr( 'src', image_url );
            }
            if ( formtype === "file" )
            {
                $( '#' + formfield + " .file-value" ).val( json.id );
                $( '#' + formfield + " .pmw-file-preview" ).html( '<p>' + 'نام  فایل : ' + json.filename + '<br> حجم : ' + json.filesizeHumanReadable + '</p>' );
            }
            if ( !$( '#' + formfield ).find( ".pmw-remove-upload-button" ).length )
            {
                var big_image_upload = $( '#' + formfield );
                var remove_text = (big_image_upload.attr( "upload-type" ) === "image") ? "حذف تصویر" : "حذف فایل";
                $( '<input type="button" class="basic ui button pmw-remove-upload-button" upload-type="' + big_image_upload.attr( "upload-type" ) + '" value="' + remove_text + '">' ).insertAfter( big_image_upload.find( ".pmw-upload-button" ) );
            }
        }
    } );
    media_uploader.open();
}
