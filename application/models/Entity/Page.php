<?php
namespace Entity;

/**
 * Page
 *
 * @Table(name="page")
 * @Entity
 */
class Page extends BaseEntity {
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
     * @Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Column(name="logo", type="text", nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="url_slug", type="string", length=45, nullable=false)
     */
    private $urlSlug;

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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription( $description )
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo( $logo )
    {
        $this->logo = $logo;
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
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * @param string $urlSlug
     */
    public function setUrlSlug( $urlSlug )
    {
        $this->urlSlug = $urlSlug;
    }

    /**
     * @return PageUser[]
     */
    public function getPageUsers() {
        return PageUser::findBy(['page' => $this]);
    }

    /**
     * @return User[]
     */
    public function getUsers() {
        /** @var User[] $users */
        $users = [];
        foreach(PageUser::findBy(['page' => $this]) as $page_user) {
            /** @var PageUser $page_user */
            $users[] = $page_user->getUser();
        }
        return $users;
    }

    public function isAdmin() {
        $user = \Base_Controller::$staticUser;
        if (!$user) {
            return false;
        }

        foreach($this->getUsers() as $u) {
            if ($u->getId() == $user->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function getUser() {
        $users = $this->getUsers();
        if (count($users) !== 1) {
            throw new \Exception('Currently we only support 1 user per page.. because the single user for the page shares their activity feed with the page.');
        }
        return $users[0];
    }

    public function getLogoUrl() {
        if (!$this->logo) {
            return '/img/placeholder-logo.png';
        }
        return '/images/page-logos/'.$this->id.'-'.$this->logo;
    }

    public function getUrl() {
        return '/page/'.$this->getUrlSlug();
    }

    public static function findSphinx($query) {
        $em = \Base_Controller::$em;

        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits(0, 10, 1000);

        $cl->SetSortMode(SPH_SORT_ATTR_ASC, 'name');

        $res = $cl->Query($query, 'page');
        if ($res === false) {
            return ['count' => 0, 'pages' => []];
        }


        /** @var Page[] $pages */
        $pages = array();

        if (@$res['matches']) {
            foreach ($res['matches'] as $match){
                $pages[$match['id']] = $match['id'];
            }

            $qb = $em->createQueryBuilder();
            $qb->select('p');
            $qb->from('Entity\Page', 'p');
            $qb->where('p.id IN ('.join(',',$pages).')');


            /** @var \Entity\Page[] $results */
            $results = $qb->getQuery()->getResult();
            foreach($results as $r) {
                $pages[$r->getId()] = $r;
            }
        }

        return array(
            'pages' => $pages,
            'count' => $res['total']
        );
    }
}
