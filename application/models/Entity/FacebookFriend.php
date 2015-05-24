<?php
namespace Entity;

use Doctrine\DBAL\DBALException;

/**
 * FacebookFriend
 *
 * @Table(name="facebook_friend")
 * @Entity
 */
class FacebookFriend extends BaseEntity {

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
     * @Column(name="`name`", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="fb_id", type="string", nullable=false)
     */
    private $fb_id;

    /**
     * @param int $fb_id
     */
    public function setFbId($fb_id)
    {
        $this->fb_id = $fb_id;
    }

    /**
     * @return int
     */
    public function getFbId()
    {
        return $this->fb_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $data
     *
     * @return BaseEntity|FacebookFriend
     * @throws \Exception
     * @throws \PDOException
     */
    static public function createOrUpdate($data) {
        $fb_friend = self::findOneBy(['fb_id' => $data['id']]);
        try {
            if (!$fb_friend) {
                $fb_friend = new self();
                $fb_friend->setFbId($data['id']);
            }
            $fb_friend->setName($data['name']);
            \Base_Controller::$em->persist($fb_friend);
            \Base_Controller::$em->flush($fb_friend);
        } catch(\PDOException $e) {
            if($e->getCode() != '23000') {
                throw $e;
            } else {
                $fb_friend = self::findOneBy(['fb_id' => $data['id']]);
                if (!$fb_friend) {
                    throw new \Exception($e->getMessage());
                }
            }
        } catch(DBALException $e) {
            $prev = $e->getPrevious();
            if ($prev instanceof \PDOException && $prev->getCode() === "23000") {
                $fb_friend = self::findOneBy(['fb_id' => $data['id']]);
                if (!$fb_friend) {
                    throw new \Exception($e->getMessage());
                }
            } else {
                throw $e;
            }
        }

        return $fb_friend;
    }

    public function getImageUrl() {
        return 'https://graph.facebook.com/' . $this->fb_id . '/picture';
    }

    public function getLink() {
        return $this->name;
    }

    public function getFname() {
        $parts = explode(' ', $this->name);
        return $parts[0];
    }

    public function getFnameLink() {
        return $this->getFname();
    }

    public function getLname() {
        $parts = explode(' ', $this->name);

        array_shift($parts);

        return join(' ', $parts);
    }

    public function getUrl() {
        return '#';
    }

    /**
     * @return BetFriend[]
     */
    public function getBetFriends() {
        /** @var BetFriend[] $bets */
        $bets = BetFriend::findBy(['facebookFriend' => $this]);
        return $bets;
    }

    /**
     * @return ChallengeUser[]
     */
    public function getChallengeUsers() {
        /** @var ChallengeUser[] $challenge_users */
        $challenge_users = ChallengeUser::findBy(['fbUser' => $this]);
        return $challenge_users;
    }

	/**
     * @return GivercardTransactions[]
     */
    public function getGivercardTransactions() {
        /** @var GivercardTransactions[] $givercards */
        $givercards = GivercardTransactions::findBy(['to_user_fb_id' => $this->id]);
        return $givercards;
    }
}
