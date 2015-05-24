<?php
require_once(__DIR__.'/upload.class.php');

class GiverhubUploadHandler extends UploadHandler {

    function __construct($options=null) {
        parent::__construct($options);
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);

        if (empty($file->error)) {
            if (is_callable($this->options['on_upload_success'])) {
                $file = $this->options['on_upload_success']($file, $this->options);
            }
        }
        return $file;
    }
}
