<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Img_upload {

    protected $up_path;
    protected $up_config;

    function __construct($config) {
        $this->up_path = $config['path'];
        $this->_set_upload_config();
    }

    function _set_upload_config() {//set the configuration array for techers photo upload
        $ci = & get_instance();
        $this->up_config['upload_path'] = $this->up_path;
        $this->up_config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
        $this->up_config['max_size'] = '4000';
        $this->up_config['max_width'] = '4000';
        $this->up_config['max_height'] = '4000';
        //$ci->load->library ( 'upload', $this->upload_config );
        $ci->load->library('upload');
        $ci->upload->initialize($this->up_config);
    }

    function do_upload($data) {
        $ci = & get_instance();
        $this->_set_upload_config();
        return $ci->upload->do_upload($data);
    }

    function display_errors() {
        $ci = & get_instance();
        return $ci->upload->display_errors();
    }

    function data() {
        $ci = & get_instance();
        return $ci->upload->data();
    }

    function resize_img($source_img, $new_image = "", $width = 60, $height = 70, $aspect_ratio = FALSE) {
        $ci = & get_instance();

        if (empty($new_image))
            $new_image = $source_img;

        $ci->load->library('image_lib');
        $config['image_library'] = 'GD2';
        $config['source_image'] = $this->up_path . $source_img;
        $config['maintain_ratio'] = $aspect_ratio;

        $config['master_dim'] = 'width';
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = "$new_image";
        $config['create_thumb'] = FALSE;
        $ci->image_lib->initialize($config);
        $ci->image_lib->resize();
    }

    function del_img_file($img_filename = FALSE) {
        if (!$img_filename)
            return FALSE;
        $ci = &get_instance();
        $path[] = dirname($_SERVER['SCRIPT_FILENAME']);
        $path[] = "/";
        $path[] = $this->up_path;
        $path[] = $img_filename;
        $realpath = implode("", $path);
        if (file_exists($realpath)) {
            return unlink($realpath);
        }
        else
            return FALSE;
    }

}