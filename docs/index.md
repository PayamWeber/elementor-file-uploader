# elementor-file-uploader
Elementor File Uploader Plugin

Hello and welcome to this useful plugin. If you are a wordpress developer, might had the issue that elementor does not support file uploader.
At this moment problem has been solved and you can use this file uploader and enjoy your life.

## Requirements
###### PHP Version: 5.6 or later

##### Tested on:
`Wordpress 5.0.3`,
`Elementor 2.4.3`

## How to use
For using this amazing control you just need to call it in controls method in your widget class
```php
$this->add_control(
    'example_name',
    [
        'label' => __( 'File Uploader' ),
        'type' => 'file_uploader',
    ]
);
```

## Hooks
```
efu_mime_types
```
`type = filter`,
`args = $types`

### About me
I'm a PHP / Laravel / Wordpress Developer that like to make everything easier in this huge world of programming

resume: [payamjafari.ir](http://payamjafari.ir)
