<?php
namespace Entity;

/**
 * ExternalUrl
 *
 * @Table(name="external_url", uniqueConstraints={@UniqueConstraint(name="url_UNIQUE", columns={"url"})})
 * @Entity @HasLifecycleCallbacks
 */
class ExternalUrl extends BaseEntity {

    /** @PrePersist */
    public function onPrePersist() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /** @PreUpdate */
    public function onPreUpdate() {
        $this->updated = new \DateTime();
    }

    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @Column(name="image", type="integer", nullable=false)
     */
    private $image = 0;

    /**
     * @var string
     *
     * @Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @Column(name="content_type", type="string", length=45, nullable=false)
     */
    private $content_type;

    /**
     * @var string
     *
     * @Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getDescription($options = [])
    {
        if (isset($options['remove_ending_dot']) && $options['remove_ending_dot']) {
            return rtrim($this->description, '. ');
        } else {
            return $this->description;
        }
    }

    /**
     * @return string
     */
    public function getImageUrl($include_full_hostname = false)
    {
        if ($this->image == 2) {
            if ($include_full_hostname) {
                return base_url('/images/external_url_images/'.$this->id.'.svg');
            } else {
                return '/images/external_url_images/'.$this->id.'.svg';
            }
        } elseif($this->image) {
            if ($include_full_hostname) {
                return base_url('/images/external_url_images/'.$this->id.'.jpg');
            } else {
                return '/images/external_url_images/'.$this->id.'.jpg';
            }
        } else {
            return '';
        }
    }

    /**
     * @return int
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param $image
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getTitle($options = [])
    {
        if (isset($options['remove_ending_dot']) && $options['remove_ending_dot']) {
            return rtrim($this->title, '. ');
        } else {
            return $this->title;
        }
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    static public function fetch($url) {
        $url = trim($url);
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('url is invalid.');
        }

        if (!preg_match('#^(http://|https://)#i', $url)) {
            throw new \Exception('url needs to start with http:// or https://');
        }

        /** @var ExternalUrl $entity */
        $entity = self::findOneBy(['url' => $url]);
        if (!$entity) {
            $entity = new self;
            $entity->setUrl($url);

            $entity->update();
        } elseif($entity->isOld()) {
            $entity->update();
        }

        return $entity;
    }

    public function isOld() {
        $two_hours_ago = new \DateTime('-2 hour');
        return $this->updated < $two_hours_ago;
    }

    public function update() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'giverhub_external_hit');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_BUFFERSIZE, 128); // more progress info
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($DownloadSize, $Downloaded, $UploadSize, $Uploaded) {
            // If $Downloaded exceeds 1KB, returning non-0 breaks the connection!
            return ($Downloaded > (100 * 1024)) ? 1 : 0;
        });
        $content = curl_exec ($ch);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close ($ch);

        preg_match('#^(.*)\;#', $content_type, $matches);
        if ($matches) {
            $content_type = $matches[1];
        }
        if ($content_type === 'text/html') {
            if (!$content) {
                $this->content_type = 'invalid';
            } else {
                $doc = new \DOMDocument();
                if (@!$doc->loadHTML($content)) {
                    $this->content_type = 'invalid';
                } else {
                    $title = $doc->getElementsByTagName('title');
                    if ($title->length) {
                        $title = $title->item(0);
                        $title = trim($title->textContent);
                        if (!$title) {
                            $this->content_type = 'invalid';
                        } else {
                            $this->title = $title;
                        }
                    } else {
                        $this->content_type = 'invalid';
                    }

                    $desc = false;
                    $image_url = false;
                    $meta_tags = $doc->getElementsByTagName('meta');
                    if ($meta_tags->length) {
                        foreach($meta_tags as $meta_tag) {
                            if (strtolower(trim($meta_tag->getAttribute('name'))) == 'description') {
                                $desc = trim($meta_tag->getAttribute('content'));
                            }
                            if (strtolower(trim($meta_tag->getAttribute('property'))) == 'og:image') {
                                $image_url = trim($meta_tag->getAttribute('content'));
                            }
                        }
                    }

                    if (!$image_url) {
                        $images = $doc->getElementsByTagName('img');
                        if ($images->length) {
                            $image_url = $images->item(0);
                            $image_url = trim($image_url->getAttribute('src'));
                            if ($image_url) {
                                if (substr($image_url,0,7) == 'http://' || substr($image_url,0,8) == 'https://') {
                                    // hackz
                                } else {
                                    $parsed = parse_url($this->url);
                                    $image_url = $parsed['scheme'] . '://'.$parsed['host'] . '/' . $image_url;
                                }
                            }
                        }
                    }

                    if (!$desc) {
                        $this->content_type = 'invalid';
                    } else {
                        $this->description = $desc;
                        $this->content_type = 'text/html';
                    }
                }
            }
        } elseif(preg_match('/^image/', $content_type)) {
            $this->title = $this->url;
            $this->description = $this->url;
            $this->content_type = 'image';
        } else {
            $this->title = '';
            $this->description = '';
            $this->content_type = 'invalid';
        }

        $em = \Base_Controller::$em;
        $em->persist($this);
        $em->flush($this);

        $filename = __DIR__.'/../../../images/external_url_images/'.$this->id.'.jpg';
        if ((isset($image_url) && $image_url) || $this->content_type == 'image') {

            if ($this->content_type == 'image') {
                $image_content = $content;
            } elseif (isset($image_url) && $image_url) {
                $image_content = file_get_contents($image_url);
            } else {
                throw new \Exception('Well this should never happen...');
            }

            if (false !== @file_put_contents($filename, $image_content)) {
                $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);

                if (!preg_match('#^image\/#', $mime)) {
                    @unlink($filename);
                    $this->image = 0;
                } else {
                    if ($mime === "image/svg+xml") {
                        @rename($filename, __DIR__.'/../../../images/external_url_images/'.$this->id.'.svg');
                        $this->image = 2;
                    } else {
                        $image_info = getimagesize($filename);
                        if ($image_info[0] >= 400) { // width: thank you php for returning arrays with numeric indexes..
                            $this->image = 3;
                        } else {
                            $this->image = 1;
                        }
                    }
                }
            } else {
                @unlink($filename);
                $this->image = 0;
            }
        } else {
            @unlink($filename);
            $this->image = 0;
        }

        $this->updated = new \DateTime();
        $em->persist($this);
        $em->flush($this);
    }

    /**
     * @param string $content_type
     */
    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }
}
