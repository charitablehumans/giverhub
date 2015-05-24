<?php
namespace Entity;

/**
 * Stat
 *
 * @Table(name="stat", indexes={@Index(name="stat_name_identifier", columns={"name", "identifier"}), @Index(name="stat_identifier_name", columns={"identifier", "name"})})
 * @Entity
 */
class Stat extends BaseEntity {
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
     * @Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="identifier", type="string", length=20, nullable=false)
     */
    private $identifier;

    /**
     * @var \DateTime
     *
     * @Column(name="timestamp", type="datetime", nullable=false)
     */
    private $timestamp;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier( $identifier )
    {
        $this->identifier = $identifier;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp( $timestamp )
    {
        $this->timestamp = $timestamp;
    }

    static public function register($name) {
        $stat = new static;
        $stat->setName($name);
        $stat->setTimestamp(new \DateTime());

        if (\Base_Controller::$staticUser) {
            if (\Base_Controller::$staticUser->isAdmin()) {
                return; // dont log admin actions.
            }
            $stat->setIdentifier(\Base_Controller::$staticUser->getId());
        } elseif (isset($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
            $stat->setIdentifier($_SERVER['REMOTE_ADDR']);
        } else {
            throw new \Exception('Unable to determine identifier when registering new stat.');
        }

        \Base_Controller::$em->persist($stat);
        \Base_Controller::$em->flush($stat);
    }

    static public function getUnique($name) {
        $data = apc_fetch('unique_stat_'.$name, $success);
        if ($success) {
            return $data;
        }
        $CI =& get_instance();
        $db = $CI->db;
        $q = $db->query('select count(*) as cnt from (SELECT identifier from stat where `name` = \''.mysql_real_escape_string($name).'\' group by identifier) t');
        apc_store('unique_stat_'.$name, $q->row()->cnt, 60*60*24);
        return $q->row()->cnt;
    }

    static public function getTotal($name) {
        $data = apc_fetch('total_stat_'.$name, $success);
        if ($success) {
            return $data;
        }
        $CI =& get_instance();
        $db = $CI->db;
        $q = $db->query('select count(*) as cnt from stat where `name` = \''.mysql_real_escape_string($name) . '\'');
        apc_store('total_stat_'.$name, $q->row()->cnt, 60*60*24);
        return $q->row()->cnt;
    }

    public function getExtra($what) {
        switch($what) {
            case 'user.link':
                return $this->getUser() ? $this->getUser()->getLink() : $this->identifier;
            case 'user.joined':
                return $this->getUser() ? $this->getUser()->getJoined() : '-';
        }
        return '-';
    }

    /**
     * @return User|null
     */
    public function getUser() {
        if (filter_var($this->identifier, FILTER_VALIDATE_INT)) {
            return \Entity\User::find($this->identifier);
        } else {
            return null;
        }
    }
}
