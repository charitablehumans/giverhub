<?php
require_once(__DIR__ . '/Base_Controller.php');


class Post extends Base_Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index($id) {
        $GLOBALS['super_timers']['pppp1'] = microtime(true) - $GLOBALS['super_start'];

        /** @var \Entity\ActivityFeedPost $post */
        $post = \Entity\ActivityFeedPost::findOneBy(['scrambled_id' => $id]);
        if (!$post) {
            $this->giverhub_404('nonprofits/charity-404', 'Post not found');
            return;
        }

        $data['post'] = $post;
        $data['main_content'] = 'post/index';

        $this->headerPrefix = 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# giverhub: http://ogp.me/ns/fb/giverhub#';
        $this->htmlTitle = $this->ogTitle = 'Posted on GiverHub';
        $this->ogType = 'giverhub:story';
        $this->ogDesc = '';
        $desc = $post->getText(['remove_ending_dot' => true]);
        if ($desc) {
            $this->ogDesc = $desc . ".\n\n";
        }


        $images = $post->getImages();
        foreach($images as $image) {
            array_unshift($this->ogImage, $image->getUrl(true));
        }

        $videos = $post->getYoutubeVideos();
        if ($videos) {
            $video = $videos[0];

            array_unshift($this->ogImage, $video->getThumbnailUrl());

            if ($video->getTitle()) {
                $this->ogTitle = $video->getTitle();
            }
            if ($video->getDescription()) {
                $desc = $video->getDescription(['remove_ending_dot' => true]);
                if ($desc) {
                    $this->ogDesc = $desc . ".\n\n";
                }
            }
        }

        $external_url = $post->getExternalUrl();

        if ($external_url) {
            if ($external_url->getImageUrl()) {
                array_unshift($this->ogImage, $external_url->getImageUrl(true));
            }
            if ($external_url->getTitle()) {
                $title = $external_url->getTitle(['remove_ending_dot' => true]);
                if ($title) {
                    $this->htmlTitle = $this->ogTitle = $title;
                }
            }
            if ($external_url->getDescription()) {
                $desc = $external_url->getDescription(['remove_ending_dot' => true]);
                if ($desc) {
                    $this->ogDesc .= $desc . ".\n\n";
                }
            }
        }

        $this->ogDesc .= "GiverHub: Donate instantly, itemize automatically.";

        //$this->extra_headers[] = '<meta property="og:amount" content="$'.$bet->getAmount().'">';

        $GLOBALS['super_timers']['pppp2'] = microtime(true) - $GLOBALS['super_start'];
        $this->load->view('includes/user/template', $data);
        $GLOBALS['super_timers']['pppp3'] = microtime(true) - $GLOBALS['super_start'];
    }

    public function external_url() {
        if (!$this->user) {
            throw new Exception('user not signed in.');
        }
        if (!isset($_POST['url'])) {
            throw new Exception('invalid request, missing url parameter.');
        }
        if (!$_POST['url']) {
            throw new Exception('invalid request, url parameter is empty');
        }

        $json = [
            'success' => false,
            'url' => $_POST['url'],
        ];
        try {
            /** @var \Entity\ExternalUrl $external_url */
            $external_url = \Entity\ExternalUrl::fetch($_POST['url']);
            if ($external_url->getContentType() == 'invalid') {
                $json['invalid_url'] = true;
                $json['success'] = false;
            } else {
                $json['content_type'] = $external_url->getContentType();
                $json['image_url'] = $external_url->getImageUrl();
                $json['image_size'] = null;
                if ($external_url->getImage() == 1) {
                    $json['image_size'] = 'small';
                } elseif ($external_url->getImage() == 3) {
                    $json['image_size'] = 'large';
                }
                $json['title'] = $external_url->getTitle();
                $json['description'] = $external_url->getDescription();
                $json['url_id'] = $external_url->getId();
                $json['success'] = true;
            }
        } catch(Exception $e) {
            $json['success'] = false;
            $json['invalid_url'] = true;
        }
        echo json_encode($json);
    }
}
