<?php

require_once(__DIR__ . '/Base_Controller.php');
require_once(__DIR__ . '/../libraries/jquery-file-upload/GiverhubUploadHandler.php');

class Upload extends \Base_Controller {

    public function __construct() {
        parent::__construct();

        if (isset($_FILES) && is_array($_FILES)) {
            $matches = false;
            foreach($_FILES as $info_arr) {
                if (is_array($info_arr) && isset($info_arr['name']) && preg_match('#undefined#i', $info_arr['name'])) {
                    $matches = true;
                    break;
                }
            }
            if ($matches) {
                $server  = print_r($_SERVER, true);
                $post    = print_r($_POST, true);
                $get     = print_r($_GET, true);
                $request = print_r($_REQUEST, true);
                $files   = print_r($_FILES, true);
                $user    = $this->user ? $this->user->getId() . ':' . $this->user->getName() : 'none';
                $msg     = "_FILES: {$files}" . PHP_EOL .
                    "user: {$user}" . PHP_EOL .
                    "_POST: {$post}" . PHP_EOL .
                    "_GET: {$get}" . PHP_EOL .
                    "_REQUEST: {$request}" . PHP_EOL .
                    "_SERVER: {$server}";
                mail('admin@giverhub.com', 'file upload problem @' . @$_SERVER['SERVER_NAME'], $msg);
            }
        }
    }

    private function set_headers() {
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');
    }

    public function profile_photo() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        $uploadHandler = new GiverhubUploadHandler(array(
                                                 'param_name' => 'profile-photo-input',
                                                 'upload_dir' => __DIR__.'/../tmp_uploads/',
                                                 'profile_dir' => __DIR__.'/../../images/profiles/',
                                                 'upload_url' => '/images/profiles/',
                                                 'image_versions' => array(),
                                                 'on_upload_success' => function($file, $options) {
                                                         $user = \Base_Controller::$staticUser;
                                                         $em = \Base_Controller::$em;

                                                         $path = $options['upload_dir'].$file->name;
                                                         if (!is_file($path)) {
                                                             throw new Exception('Uploaded file is not found.' . $path);
                                                         }
                                                         $name = $user->getId().'-'.$file->name;
                                                         $destination = $options['profile_dir'].$name;
                                                         if (!rename($path, $destination)) {
                                                             throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                                                         }

                                                         $file->url = $options['upload_url'] . rawurlencode($name);
                                                         $user->setImage($file->name);

                                                         $em->persist($user);
                                                         $em->flush($user);

                                                         return $file;
                                                 },
                                            ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function page_logo() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['page-id'])) {
            throw new Exception('missing page-id');
        }

        /** @var \Entity\Page $page */
        $page = \Entity\Page::find($_POST['page-id']);

        if (!$page) {
            throw new Exception('page not found: ' . $_POST['page-id']);
        }

        if (!$page->isAdmin()) {
            throw new Exception('current user is not page admin. user-id: ' . $this->user->getId() . ' page-id: '. $page->getId());
        }

        $GLOBALS['page_hack'] = $page;

        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'page-logo-input',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'profile_dir' => __DIR__.'/../../images/page-logos/',
            'upload_url' => '/images/page-logos/',
            'image_versions' => array(),
            'on_upload_success' => function($file, $options) {

                $user = \Base_Controller::$staticUser;
                /** @var \Entity\Page $page */
                $page = $GLOBALS['page_hack'];
                $em = \Base_Controller::$em;

                $path = $options['upload_dir'].$file->name;
                if (!is_file($path)) {
                    throw new Exception('Uploaded file is not found.' . $path);
                }
                $name = $page->getId().'-'.$file->name;
                $destination = $options['profile_dir'].$name;
                if (!rename($path, $destination)) {
                    throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                }

                $file->url = $options['upload_url'] . rawurlencode($name);
                $page->setLogo($file->name);

                $em->persist($page);
                $em->flush($page);

                return $file;
            },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function charity_photos() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        $uploadHandler = new GiverhubUploadHandler(array(
                                                        'param_name' => 'charity-photos-input',
                                                        'upload_dir' => __DIR__.'/../tmp_uploads/',
                                                        'charity_dir' => __DIR__.'/../../images/charity/',
                                                        'upload_url' => '/images/charity/',
                                                        'image_versions' => array(
                                                            'large' => array(
                                                                'upload_dir' => __DIR__.'/../tmp_uploads/',
                                                                'upload_url' => '/images/charity/',
                                                                'max_width' => 1920,
                                                                'max_height' => 1200,
                                                            ),
                                                            'thumbnail' => array(
                                                                'upload_dir' => __DIR__.'/../tmp_uploads/thumbs/',
                                                                'upload_url' => '/images/charity/',
                                                                'max_width' => 112,
                                                                'max_height' => 112
                                                            ),
                                                        ),
                                                        'on_upload_success' => function($file, $options) {
                                                                $user = \Base_Controller::$staticUser;
                                                                $em = \Base_Controller::$em;

                                                                $charity = \Entity\Charity::find($_REQUEST['charityId']);

                                                                if (!$charity) {
                                                                    throw new Exception('Failed to load charity. charity-id: ' . $_REQUEST['charityId']);
                                                                }

                                                                $path = $options['upload_dir'].$file->name;
                                                                $thumbPath = $options['upload_dir'].'thumbs/'.$file->name;

                                                                if (!is_file($path)) {
                                                                    throw new Exception('Uploaded file is not found.' . $path);
                                                                }
                                                                if (!is_file($thumbPath)) {
                                                                    throw new Exception('Uploaded file is not found. thumb.' . $thumbPath);
                                                                }

                                                                // we dont want to overwrite images..
                                                                $x = '';
                                                                do {
                                                                    $name = $charity->getId().'-'.$x.$file->name;
                                                                    $destination = $options['charity_dir'].$name;
                                                                    if (!$x) {
                                                                        $x = 1;
                                                                    } else {
                                                                        $x++;
                                                                    }
                                                                } while(is_file($destination));
                                                                if (!rename($path, $destination)) {
                                                                    throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                                                                }

                                                                // we dont want to overwrite images..
                                                                $x = '';
                                                                do {
                                                                    $thumbName = $charity->getId().'-'.$x.$file->name;
                                                                    $destination = $options['charity_dir'].'thumbs/'.$thumbName;
                                                                    if (!$x) {
                                                                        $x = 1;
                                                                    } else {
                                                                        $x++;
                                                                    }
                                                                } while(is_file($destination));
                                                                if (!rename($thumbPath, $destination)) {
                                                                    throw new Exception('Failed to move thumb file from ' . $path . ' to ' . $destination);
                                                                }

                                                                $charityImage = new \Entity\CharityImage();
                                                                $charityImage->setCharity($charity);
                                                                $charityImage->setUser($user);
                                                                $charityImage->setUploadDateTime(new \DateTime());
                                                                $charityImage->setImageName($name);
                                                                $charityImage->setImageThumb($thumbName);
                                                                $charityImage->setShowImage(1);

                                                                $em->persist($charityImage);
                                                                $em->flush($charityImage);

                                                                $file->url = $options['upload_url'] . rawurlencode($name);
                                                                $file->thumbnail_url = $options['upload_url'] . 'thumbs/' .rawurlencode($thumbName);

                                                                $user->addGiverHubScore('upload-charity-photo');
                                                                return $file;
                                                            },
                                                   ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function dashboard_image() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'dashboard-image',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'dashboard_dir' => __DIR__.'/../../images/dashboard/',
            'upload_url' => '/images/dashboard/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/dashboard/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
                'thumbnail' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/thumbs/',
                    'upload_url' => '/images/dashboard/',
                    'max_width' => 112,
                    'max_height' => 112
                ),
            ),
            'on_upload_success' => function($file, $options) {
                    $user = \Base_Controller::$staticUser;
                    $em = \Base_Controller::$em;

                    $path = $options['upload_dir'].$file->name;
                    $thumbPath = $options['upload_dir'].'thumbs/'.$file->name;

                    if (!is_file($path)) {
                        throw new Exception('Uploaded file is not found.' . $path);
                    }
                    if (!is_file($thumbPath)) {
                        throw new Exception('Uploaded file is not found. thumb.' . $thumbPath);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $name = $user->getId().'-'.$x.$file->name;
                        $destination = $options['dashboard_dir'].$name;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($path, $destination)) {
                        throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $thumbName = $user->getId().'-'.$x.$file->name;
                        $destination = $options['dashboard_dir'].'thumbs/'.$thumbName;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($thumbPath, $destination)) {
                        throw new Exception('Failed to move thumb file from ' . $path . ' to ' . $destination);
                    }

                    $this->user->setDashboardImage($name);
                    $this->user->setDashboardImageUploadDate(date('Y-m-d H:i:s'));
                    $em->persist($this->user);
                    $em->flush($this->user);

                    $file->url = $options['upload_url'] . rawurlencode($name);
                    $file->thumbnail_url = $options['upload_url'] . 'thumbs/' .rawurlencode($thumbName);

                    return $file;
                },
        ));
        $this->set_headers();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function activity_post_image() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'activity-post-image-input',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'post_dir' => __DIR__.'/../../images/activity_post_images/',
            'upload_url' => '/images/activity_post_images/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/activity_post_images/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
                'thumbnail' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/thumbs/',
                    'upload_url' => '/images/activity_post_images/',
                    'max_width' => 112,
                    'max_height' => 112
                ),
            ),
            'on_upload_success' => function($file, $options) {
                    $user = \Base_Controller::$staticUser;
                    $em = \Base_Controller::$em;


                    $path = $options['upload_dir'].$file->name;
                    $thumbPath = $options['upload_dir'].'thumbs/'.$file->name;

                    if (!is_file($path)) {
                        throw new Exception('Uploaded file is not found.' . $path);
                    }
                    if (!is_file($thumbPath)) {
                        throw new Exception('Uploaded file is not found. thumb.' . $thumbPath);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $name = $x.$file->name;
                        $destination = $options['post_dir'].$name;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($path, $destination)) {
                        throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $thumbName = $x.$file->name;
                        $destination = $options['post_dir'].'thumbs/'.$thumbName;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($thumbPath, $destination)) {
                        throw new Exception('Failed to move thumb file from ' . $path . ' to ' . $destination);
                    }

                    $post_image = new \Entity\ActivityFeedPostImage();
                    $post_image->setTempId($_REQUEST['tempId']);
                    $post_image->setUser($user);
                    $post_image->setUploadDateTime(new \DateTime());
                    $post_image->setImageName($name);
                    $post_image->setImageThumb($thumbName);

                    $em->persist($post_image);
                    $em->flush($post_image);

                    $file->url = $options['upload_url'] . rawurlencode($name);
                    $file->thumbnail_url = $options['upload_url'] . 'thumbs/' .rawurlencode($thumbName);
                    $file->activity_feed_post_image_id = $post_image->getId();
                    return $file;
                },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function delete_activity_feed_post_image() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['activity_feed_post_image_id'])) {
            throw new Exception('Invalid request, missing activity_feed_post_image_id');
        }

        /** @var \Entity\ActivityFeedPostImage $post_image */
        $post_image = \Entity\ActivityFeedPostImage::find($_POST['activity_feed_post_image_id']);

        if (!$post_image) {
            throw new Exception('Post image does not exist. ' . $_POST['activity_feed_post_image_id']);
        }
        if ($post_image->getUser()->getId() != $this->user->getId()) {
            throw new Exception('user tried to delete someone elses post image. id: ' . $_POST['activity_feed_post_image_id'] . ' user_id: ' . $this->user->getId() . ' owner-id: ' . $post_image->getUser()->getId());
        }

        if ($post_image->getActivityFeedPostId()) {
            throw new Exception('post image is already connected to a post. cannot delete. id: ' . $_POST['activity_feed_post_image_id'] . ' image-id: ' . $post_image->getId());
        }

        if (!unlink(__DIR__.'/../../images/activity_post_images/'.$post_image->getImageName())) {
            throw new Exception('Could not unlink: '.__DIR__.'/../../images/activity_post_images/'.$post_image->getImageName());
        }
        if (!unlink(__DIR__.'/../../images/activity_post_images/thumbs/'.$post_image->getImageThumb())) {
            throw new Exception('Could not unlink: '.__DIR__.'/../../images/activity_post_images/thumbs/'.$post_image->getImageName());
        }

        self::$em->remove($post_image);
        self::$em->flush();

        echo json_encode(['success' => true]);
    }

    public function request_charity_admin_image() {
        if (!$this->user) {
            throw new Exception('User not signed in.');
        }

        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'request-charity-admin-image-input',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'post_dir' => __DIR__.'/../../images/request_charity_admin/',
            'upload_url' => '/images/request_charity_admin/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/request_charity_admin/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
                'thumbnail' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/thumbs/',
                    'upload_url' => '/images/request_charity_admin/',
                    'max_width' => 112,
                    'max_height' => 112
                ),
            ),
            'on_upload_success' => function($file, $options) {
                    $em = \Base_Controller::$em;

                    $path = $options['upload_dir'].$file->name;
                    $thumbPath = $options['upload_dir'].'thumbs/'.$file->name;

                    if (!is_file($path)) {
                        throw new Exception('Uploaded file is not found.' . $path);
                    }
                    if (!is_file($thumbPath)) {
                        throw new Exception('Uploaded file is not found. thumb.' . $thumbPath);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $name = $x.$file->name;
                        $destination = $options['post_dir'].$name;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($path, $destination)) {
                        throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $thumbName = $x.$file->name;
                        $destination = $options['post_dir'].'thumbs/'.$thumbName;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($thumbPath, $destination)) {
                        throw new Exception('Failed to move thumb file from ' . $path . ' to ' . $destination);
                    }

                    $post_image = new \Entity\CharityAdminRequestPicture();
                    $post_image->setTempId($_REQUEST['tempId']);
                    $post_image->setFilename($name);
                    $post_image->setThumbFilename($thumbName);

                    $em->persist($post_image);
                    $em->flush($post_image);

                    $file->url = $options['upload_url'] . rawurlencode($name);
                    $file->thumbnail_url = $options['upload_url'] . 'thumbs/' .rawurlencode($thumbName);
                    $file->image_id = $post_image->getId();
                    $file->temp_id = $post_image->getTempId();
                    return $file;
                },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function delete_request_charity_admin_image() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['image_id'])) {
            throw new Exception('Invalid request, missing image_id');
        }

        if (!isset($_POST['temp_id'])) {
            throw new Exception('Invalid request, missing image_id');
        }

        /** @var \Entity\CharityAdminRequestPicture $post_image */
        $post_image = \Entity\CharityAdminRequestPicture::find($_POST['image_id']);

        if (!$post_image) {
            throw new Exception('Post image does not exist. ' . $_POST['image_id']);
        }

        if (!$post_image->getTempId() === $_POST['temp_id']) {
            throw new Exception('temp_id did not match for image with image_id: ' . $_POST['image_id'] . ' temp_id: ' . $_POST['temp_id']);
        }

        if ($post_image->getCharityAdminRequest()) {
            throw new Exception('post image is already connected to a post. cannot delete. id: ' . $_POST['image_id']);
        }

        if (!unlink(__DIR__.'/../../images/request_charity_admin/'.$post_image->getFilename())) {
            throw new Exception('Could not unlink: '.__DIR__.'/../../images/request_charity_admin/'.$post_image->getFilename());
        }
        if (!unlink(__DIR__.'/../../images/request_charity_admin/thumbs/'.$post_image->getThumbFilename())) {
            throw new Exception('Could not unlink: '.__DIR__.'/../../images/request_charity_admin/thumbs/'.$post_image->getThumbFilename());
        }

        self::$em->remove($post_image);
        self::$em->flush();

        echo json_encode(['success' => true]);
    }

    public function petition_create() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'petition-photo-input',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'post_dir' => __DIR__.'/../../images/giverhub_petition_images/',
            'upload_url' => '/images/giverhub_petition_images/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/giverhub_petition_images/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
                'thumbnail' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/thumbs/',
                    'upload_url' => '/images/giverhub_petition_images/',
                    'max_width' => 112,
                    'max_height' => 112
                ),
            ),
            'on_upload_success' => function($file, $options) {
                    $user = \Base_Controller::$staticUser;
                    $em = \Base_Controller::$em;


                    $path = $options['upload_dir'].$file->name;
                    $thumbPath = $options['upload_dir'].'thumbs/'.$file->name;

                    if (!is_file($path)) {
                        throw new Exception('Uploaded file is not found.' . $path);
                    }
                    if (!is_file($thumbPath)) {
                        throw new Exception('Uploaded file is not found. thumb.' . $thumbPath);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $name = $x.$file->name;
                        $destination = $options['post_dir'].$name;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($path, $destination)) {
                        throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                    }

                    // we dont want to overwrite images..
                    $x = '';
                    do {
                        $thumbName = $x.$file->name;
                        $destination = $options['post_dir'].'thumbs/'.$thumbName;
                        if (!$x) {
                            $x = 1;
                        } else {
                            $x++;
                        }
                    } while(is_file($destination));
                    if (!rename($thumbPath, $destination)) {
                        throw new Exception('Failed to move thumb file from ' . $path . ' to ' . $destination);
                    }

                    $photo = \Entity\PetitionPhoto::findOneBy(['tempId' => $_REQUEST['tempId']]);
                    if ($photo) {
                        self::$em->remove($photo);
                        self::$em->flush();
                    }

                    $photo = new \Entity\PetitionPhoto();
                    $photo->setTempId($_REQUEST['tempId']);
                    $photo->setUser($this->user);
                    $photo->setFull($name);
                    $photo->setThumb($thumbName);

                    $em->persist($photo);
                    $em->flush($photo);

                    $file->url = $options['upload_url'] . rawurlencode($name);
                    $file->thumbnail_url = $options['upload_url'] . 'thumbs/' .rawurlencode($thumbName);
                    $file->photo_id = $photo->getId();
                    return $file;
                },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function delete_petition_create_image() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['tempId'])) {
            throw new Exception('Invalid request, missing tempId');
        }

        /** @var \Entity\PetitionPhoto $photo */
        $photo = \Entity\PetitionPhoto::findOneBy(['tempId' => $_POST['tempId']]);

        if (!$photo) {
            throw new Exception('Petition image does not exist ' . $_POST['tempId']);
        }

        if ($photo->getUser() != $this->user) {
            throw new Exception('user does not own this tempId: ' . $_POST['tempId']);
        }

        self::$em->remove($photo);
        self::$em->flush();

        echo json_encode(['success' => true]);
    }

    public function charity_header_pic() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id');
        }

        $charity = \Entity\Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('could not load charity_id: ' . $_POST['charity_id']);
        }

        if (!$this->user->isCharityAdmin($charity)) {
            throw new Exception('user is not charity');
        }


        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'charity-header-pic',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'post_dir' => __DIR__.'/../../images/charity_header_pics/',
            'upload_url' => '/images/charity_header_pics/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/charity_header_pics/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
            ),
            'on_upload_success' => function($file, $options) {
                $path = $options['upload_dir'].$file->name;

                if (!is_file($path)) {
                    throw new Exception('Uploaded file is not found.' . $path);
                }

                $destination = $options['post_dir'].$_POST['charity_id'];

                if (!rename($path, $destination)) {
                    throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                }


                $file->url = $options['upload_url'] .$_POST['charity_id'].'?'.crc32(rand());

                return $file;
            },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function delete_charity_header_pic() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id');
        }

        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('could not load charity_id: ' . $_POST['charity_id']);
        }

        if (!$this->user->isCharityAdmin($charity)) {
            throw new Exception('user is not charity');
        }

        if ($charity->hasCover()) {
            $charity->deleteCover();
        }
        echo json_encode(['success' => true]);
    }


    public function charity_logo() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id');
        }

        $charity = \Entity\Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('could not load charity_id: ' . $_POST['charity_id']);
        }

        if (!$this->user->isCharityAdmin($charity)) {
            throw new Exception('user is not charity');
        }


        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'charity-logo',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'post_dir' => __DIR__.'/../../images/charity_logo/',
            'upload_url' => '/images/charity_logo/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/charity_logo/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
            ),
            'on_upload_success' => function($file, $options) {
                $path = $options['upload_dir'].$file->name;

                if (!is_file($path)) {
                    throw new Exception('Uploaded file is not found.' . $path);
                }

                $destination = $options['post_dir'].$_POST['charity_id'];

                if (!rename($path, $destination)) {
                    throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                }

                $file->url = $options['upload_url'] .$_POST['charity_id'].'?'.crc32(rand());

                return $file;
            },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function delete_charity_logo() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['charity_id'])) {
            throw new Exception('missing charity_id');
        }

        /** @var \Entity\Charity $charity */
        $charity = \Entity\Charity::find($_POST['charity_id']);
        if (!$charity) {
            throw new Exception('could not load charity_id: ' . $_POST['charity_id']);
        }

        if (!$this->user->isCharityAdmin($charity)) {
            throw new Exception('user is not charity');
        }

        if ($charity->hasLogo()) {
            $charity->deleteLogo();
        }
        echo json_encode(['success' => true]);
    }

    public function giving_pot_logo() {
        if (!$this->user) {
            throw new Exception('Need to be signed in.');
        }

        if (!isset($_POST['giving-pot-id'])) {
            throw new Exception('missing giving-pot-id');
        }

        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($_POST['giving-pot-id']);
        if (!$giving_pot) {
            throw new Exception('could not load giving_pot_id: ' . $_POST['giving-pot-id']);
        }

        if ($this->user != $giving_pot->getUser()) {
            throw new Exception('user is not onwer of giving pot.');
        }

        $GLOBALS['currentGivingPot'] = $giving_pot;

        $uploadHandler = new GiverhubUploadHandler(array(
            'param_name' => 'logo-input',
            'upload_dir' => __DIR__.'/../tmp_uploads/',
            'post_dir' => __DIR__.'/../../images/giving-pot-logo/',
            'upload_url' => '/images/giving-pot-logo/',
            'image_versions' => array(
                'large' => array(
                    'upload_dir' => __DIR__.'/../tmp_uploads/',
                    'upload_url' => '/images/giving-pot-logo/',
                    'max_width' => 1920,
                    'max_height' => 1200,
                ),
            ),
            'on_upload_success' => function($file, $options) {
                $path = $options['upload_dir'].$file->name;

                if (!is_file($path)) {
                    throw new Exception('Uploaded file is not found.' . $path);
                }

                $destination = $options['post_dir'].$_POST['giving-pot-id'];

                if (!rename($path, $destination)) {
                    throw new Exception('Failed to move file from ' . $path . ' to ' . $destination);
                }

                $file->url = $options['upload_url'] .$_POST['giving-pot-id'].'?'.crc32(rand());

                /** @var \Entity\GivingPot $giving_pot */
                $giving_pot = $GLOBALS['currentGivingPot'];
                $giving_pot->setCompanyLogo($options['upload_url'] .$_POST['giving-pot-id']);
                $giving_pot->setCompanyName(null);
                self::$em->persist($giving_pot);
                self::$em->flush($giving_pot);
                return $file;
            },
        ));

        $this->set_headers();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $uploadHandler->post();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
    }

    public function delete_giving_pot_logo() {
        if (!$this->user) {
            throw new Exception('User not signed in');
        }

        if (!isset($_POST['giving-pot-id'])) {
            throw new Exception('missing giving-pot-id');
        }

        /** @var \Entity\GivingPot $giving_pot */
        $giving_pot = \Entity\GivingPot::find($_POST['giving-pot-id']);
        if (!$giving_pot) {
            throw new Exception('could not load giving-pot-id: ' . $_POST['giving-pot-id']);
        }

        if ($this->user != $giving_pot->getUser()) {
            throw new Exception('user is not owner of giving pot');
        }

        if ($giving_pot->getCompanyLogo()) {
            $giving_pot->deleteCompanyLogo();
        }

        echo json_encode(['success' => true]);
    }
}
