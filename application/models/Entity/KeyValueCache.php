<?php
namespace Entity;

/**
 * KeyValueCache
 *
 * @Table(name="key_value_cache", uniqueConstraints={@UniqueConstraint(name="key_UNIQUE", columns={"key"})})
 * @Entity
 */
class KeyValueCache extends BaseEntity {
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
     * @Column(name="key", type="string", length=255, nullable=false)
     */
    private $key;

    /**
     * @var string
     *
     * @Column(name="value", type="text", nullable=false)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;

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
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey( $key )
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue( $value )
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated( $updated )
    {
        $this->updated = $updated;
    }


}
