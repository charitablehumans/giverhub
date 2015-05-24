<?php

namespace Entity;

/**
 * SitemapLetters
 *
 * @Table(name="sitemap_letters", uniqueConstraints={@UniqueConstraint(name="uq_sitemap_letter", columns={"letter", "type"})})
 * @Entity
 */
class SitemapLetters extends BaseEntity {
    /**
     * @var integer
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="letter", type="string", length=1, nullable=false)
     */
    private $letter;

    static public $types = ['nonprofit', 'petition'];
    /**
     * @var string
     *
     * @Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount( $count )
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLetter()
    {
        return $this->letter;
    }

    /**
     * @param string $letter
     */
    public function setLetter( $letter )
    {
        $this->letter = $letter;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType( $type )
    {
        if (!in_array($type, self::$types)) {
            throw new \Exception('invalid type: ' . $type);
        }
        $this->type = $type;
    }

    static public function update($letter, $type, $count) {
        $sitemap_letter = self::findOneBy(['letter' => $letter, 'type' => $type]);
        if (!$sitemap_letter) {
            $sitemap_letter = new self;
            $sitemap_letter->setLetter($letter);
            $sitemap_letter->setType($type);
        }
        $sitemap_letter->setCount($count);

        \Base_Controller::$em->persist($sitemap_letter);
        \Base_Controller::$em->flush($sitemap_letter);
        return $sitemap_letter;
    }
}
