<?php
require_once(__DIR__ . '/Base_Controller.php');

use \Entity\Charity;
use \Entity\SitemapLetters;
use \Entity\SitemapPages;

class Sitemap extends Base_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');

        $this->load->config('general_website_conf');
    }

    public function az($type = null, $letter = null, $page = 1) {

        $GLOBALS['super_timers']['az1'] = microtime(true) - $GLOBALS['super_start'];
        $data['letter'] = $letter;



        if ($type === null) {

            $this->htmlTitle = 'A-Z Sitemap';
            $this->metaDesc = 'A-Z Sitemap, Listing nonprofits / charities and petitions that you can donate to and sign. Listed in A-Z order.';

            $data['main_content'] = 'sitemap/az-index';

        } else {

            if (!in_array($type, ['nonprofits', 'petitions'])) {
                throw new Exception('bad type: ' . $type);
            }
            $data['url_prefix'] = '/a-z/'.$type.'/';
            $data['type'] = $type;

            if ($letter === null) {
                $this->htmlTitle = 'A-Z Sitemap - ' . ucfirst($type);
                $this->metaDesc = 'A-Z Sitemap, Listing '.$type.' that you can donate to and sign. Listed in A-Z order.';

                $data['main_content'] = 'sitemap/az-type-index';

                if ($type == 'nonprofits') {
                    $data['letters'] = SitemapLetters::findBy(['type' => 'nonprofit'], ['letter' => 'asc']);
                } else {
                    $data['letters'] = SitemapLetters::findBy(['type' => 'petition'], ['letter' => 'asc']);
                }
            } else {

                $GLOBALS['super_timers']['azlll1'] = microtime(true) - $GLOBALS['super_start'];
                $this->htmlTitle = 'A-Z Sitemap - ' . ucfirst($type) . ' - ' . $letter . ' - page ' . $page;
                $this->metaDesc = 'A-Z Sitemap, Listing '.$type.' starting with letter '.$letter.' that you can donate to and sign. Listed in A-Z order. Page ' . $page;

                $data['main_content'] = 'sitemap/az-letter';

                $GLOBALS['super_timers']['azlll2444'] = microtime(true) - $GLOBALS['super_start'];
                /** @var SitemapPages[] $sitemap_pages */
                $sitemap_pages = SitemapPages::findBy([
                    'letter' => $letter,
                    'type' => $type == 'nonprofits' ? 'nonprofit' : 'petition',
                    'page' => $page
                ], null, 80);

                $ids = [];
                foreach($sitemap_pages as $sitemap_page) {
                    $ids[] = $sitemap_page->getEntityId();
                }

                if ($ids) {
                    $qb = self::$em->createQueryBuilder();
                    $qb->select( 'e' );
                    $qb->from( $type == 'nonprofits' ? '\Entity\Charity' : '\Entity\ChangeOrgPetition', 'e' );
                    $qb->where( $qb->expr()->in( 'e.id', $ids ) );

                    $GLOBALS['super_timers']['azlll222'] = microtime( true ) - $GLOBALS['super_start'];
                    $data['entities']                    = $qb->getQuery()->getResult();
                    $GLOBALS['super_timers']['azlll2']   = microtime( true ) - $GLOBALS['super_start'];
                } else {
                    $data['entities'] = [];
                }
                $data['page'] = $page;

                /** @var SitemapLetters $sitemap_letter */
                $sitemap_letter = SitemapLetters::findOneBy(['letter' => $letter, 'type' => $type == 'nonprofits' ? 'nonprofit' : 'petition']);

                $data['pages'] = ceil($sitemap_letter->getCount() / 80);
                $GLOBALS['super_timers']['azlll3'] = microtime(true) - $GLOBALS['super_start'];
            }
        }

        $this->load->view('includes/user/template', $data);
    }

    /**
     *
     * Run it like this from bash shell:
     * rm jugge.txt # (IF you want to start from beginning)
     * php index.php sitemap generate_cache 0 2 $(cat jugge.txt);
     * while [ $? -ne 0 ];
     * do php index.php sitemap generate_cache 0 2 $(cat jugge.txt);
     * done
     *
     * @param int $do_letters
     * @param int $limit
     * @param int $skip
     *
     * @throws Exception
     */
    public function generate_cache($do_letters = 1, $limit = 50, $skip = 0) {
        if (!$this->input->is_cli_request()) {
            echo "You have no permission to access this page";exit;
        }

        if ($do_letters) {
            $letters = array_merge(range(0, 9), range('A', 'Z'));

            foreach($letters as $letter) {
                echo $letter . '.';
                $nonprofit = Charity::findSphinxQuery( '@fieldName ^' . $letter . '*', 0, 1, 1000000 );
                echo '.';
                $petition = \Entity\ChangeOrgPetition::findSphinxQuery( '@titleField ^' . $letter . '*', 0, 1, 1000000 );
                echo '.';
                SitemapLetters::update( $letter, 'nonprofit', $nonprofit['count'] );
                echo '.';
                SitemapLetters::update( $letter, 'petition', $petition['count'] );
                echo PHP_EOL;

            }
        } else {
            $done = 0;
            $checked = 0;
            /** @var SitemapLetters[] $sitemap_letters */
            $sitemap_letters = SitemapLetters::findBy([], ['type' => 'asc', 'letter' => 'asc']);
            foreach($sitemap_letters as $sitemap_letter) {
                $pages = ceil($sitemap_letter->getCount()/80);
                echo 'checking ' . $sitemap_letter->getType() . ' letter ' . $sitemap_letter->getLetter() . ' pages: ' . $pages . PHP_EOL;
                for($page = 1; $page <= $pages; $page++) {
                    if ($checked < $skip) {
                        $checked++;
                        continue;
                    }
                    $sitemap_pages = SitemapPages::findBy(['letter' => $sitemap_letter->getLetter(),'type' => $sitemap_letter->getType(),'page' => $page]);
                    $expected_on_page = $page == $pages ? $sitemap_letter->getCount() % 80 : 80;
                    echo 'letter: ' . $sitemap_letter->getLetter() . ' page: ' . $page . ' expecting: ' . $expected_on_page . ' found: ' . count($sitemap_pages) . PHP_EOL;

                    if (count($sitemap_pages) != $expected_on_page) {
                        echo 'we got work!' . PHP_EOL;
                        $offset = ($page - 1) * 80;
                        if ($sitemap_letter->getType() == 'nonprofit') {
                            $res = Charity::findSphinxQuery('@fieldName ^'.$sitemap_letter->getLetter().'*', $offset, 80, 1000000);
                            $entities = $res['charities'];
                            echo ':';
                        } elseif ($sitemap_letter->getType() == 'petition') {
                            $res = \Entity\ChangeOrgPetition::findSphinxQuery('@titleField ^'.$sitemap_letter->getLetter().'*', $offset, 80, 1000000);
                            $entities = $res['petitions'];
                            echo ':';
                        } else {
                            throw new Exception('invalid type: ' . $sitemap_letter->getType());
                        }
                        foreach($entities as $entity) {
                            echo '.';
                            SitemapPages::update($sitemap_letter->getLetter(), $sitemap_letter->getType(), $page, $entity, false);
                        }
                        self::$em->flush();
                        echo PHP_EOL;
                        $done++;
                        if ($done > $limit) {
                            file_put_contents('jugge.txt', $checked);
                            exit(1);
                        }
                    } else {
                        $checked++;
                        echo 'checked: '. $checked . ' found it..' . PHP_EOL;
                    }
                }
            }
            exit(0);
        }


    }
}