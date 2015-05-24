<?php
namespace Entity;

class UrlSlugIsUsedException extends \Exception {}
require_once(__DIR__.'/../../libraries/sphinxapi.php');
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Export\ExportException;


/**
 * Charity
 *
 * @Table(name="Charity")
 * @Entity @HasLifecycleCallbacks
 */
class Charity extends BaseEntity implements \JsonSerializable {

    public function jsonSerialize() {
        $a = [
            'id' => $this->id,
            'type' => 'charity',
            'name' => $this->getName(),
            'tagline' => $this->getMissionSummary(),
            'score' => $this->getOverallScore(),
            'hasScore' => $this->getOverallScore() !== null,
            'url' => $this->getUrl(),
            'imageUrl' => "/assets/ico/128x128.png",
        ];

        return $a;
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
     * @var string
     *
     * @Column(name="orgId", type="string", length=50, nullable=false)
     */
    private $orgId;

    /**
     * @var string
     *
     * @Column(name="ein", type="string", length=50, nullable=false)
     */
    private $ein;

    /**
     * @var float
     *
     * @Column(name="overallRtg", type="float", nullable=false)
     */
    private $overallRtg;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="url_slug", type="string", length=255, nullable=false)
     */
    private $url_slug;

    /**
     * @var string
     *
     * @Column(name="freatured_text", type="string", length=255, nullable=false)
     */
    
    private $freatured_text;
    
    /**
     * @var float
     *
     * @Column(name="overallScore", type="float", nullable=false)
     */
    private $overallScore;

    /**
     * @var integer
     *
     * @Column(name="rank", type="integer", nullable=true)
     */
    private $rank;

    /**
     * @var string
     *
     * @Column(name="tagLine", type="string", length=255, nullable=true)
     */
    private $tagLine;


	/**
	 * @var integer
	 *
	 * @Column(name="cityId", type="integer", nullable=true)
	 */
    private $cityId;

	/**
	 * @var integer
	 *
	 * @Column(name="stateId", type="integer", nullable=false)
	 */
    private $stateId;

	/**
	 * @var string
	 *
	 * @Column(name="email", type="string")
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @Column(name="phoneNumber", type="string")
	 */
	private $phoneNumber;

	/**
	 * @var string
	 *
	 * @Column(name="webSite", type="string")
	 */
	private $webSite;

	/**
	 * @var string
	 *
	 * @Column(name="address", type="string")
	 */
	private $address;

	/**
	 * @var string
	 *
	 * @Column(name="address2", type="string")
	 */
	private $address2;

	/**
	 * @var string
	 *
	 * @Column(name="zipCode", type="string")
	 */
	private $zipCode;

	/**
	 * @var string
	 *
	 * @Column(name="description", type="string")
	 */
	private $description;

	/**
	 * @var integer
	 *
	 * @Column(name="userId", type="integer")
	 */
	private $userId;

	/**
	 * @var string
	 *
	 * @Column(name="fileName", type="string")
	 */
	private $fileName;

	/**
	 * @var string
	 *
	 * @Column(name="createdAt", type="string")
	 */
	private $createdAt;

    /**
     * @var integer
     *
     * @Column(name="highestPaidOfficerCompensation", type="integer")
     */
    private $highestPaidOfficerCompensation;

    /**
     * @var integer
     *
     * @Column(name="programServicesAmount", type="integer")
     */
    private $programServicesAmount;

    /**
     * @var integer
     *
     * @Column(name="fundRaisingCostsAmount", type="integer")
     */
    private $fundRaisingCostsAmount;

    /**
     * @var integer
     *
     * @Column(name="adminExpensesAmount", type="integer")
     */
    private $adminExpensesAmount;

    /**
     * @var integer
     *
     * @Column(name="revenueAmount", type="integer")
     */
    private $revenueAmount;

    /**
     * @var integer
     *
     * @Column(name="highestPaidOfficerCompensationPercentile", type="integer")
     */
    private $highestPaidOfficerCompensationPercentile;

    /**
     * @var integer
     *
     * @Column(name="programServicesPercentile", type="integer")
     */
    private $programServicesPercentile;

    /**
     * @var integer
     *
     * @Column(name="fundRaisingCostsPercentile", type="integer")
     */
    private $fundRaisingCostsPercentile;

    /**
     * @var integer
     *
     * @Column(name="adminExpensesPercentile", type="integer")
     */
    private $adminExpensesPercentile;

    /**
     * @var integer
     *
     * @Column(name="revenuePercentile", type="integer")
     */
    private $revenuePercentile;

    /**
     * @var integer
     *
     * @Column(name="executivePay", type="integer")
     */
    private $executivePay;

    /**
     * @var integer
     *
     * @Column(name="overallScorePercentile", type="integer")
     */
    private $overallScorePercentile;

    /**
     * @var float
     *
     * @Column(name="overallEfficiency", type="float")
     */
    private $overallEfficiency;

    /**
     * @var integer
     *
     * @Column(name="overallEfficiencyPercentile", type="integer")
     */
    private $overallEfficiencyPercentile;

    /**
     * @var float
     *
     * @Column(name="fundraisingEffectiveness", type="float")
     */
    private $fundraisingEffectiveness;

    /**
     * @var integer
     *
     * @Column(name="fundraisingEffectivenessPercentile", type="integer")
     */
    private $fundraisingEffectivenessPercentile;

    /**
     * @var float
     *
     * @Column(name="executiveProductivity", type="float")
     */
    private $executiveProductivity;

    /**
     * @var integer
     *
     * @Column(name="executiveProductivityPercentile", type="integer")
     */
    private $executiveProductivityPercentile;

    /**
     * @var integer
     *
     * @Column(name="chiefExecSalary", type="integer")
     */
    private $chiefExecSalary;

    /**
     * @var integer
     *
     * @Column(name="chiefExecSalaryPercentile", type="integer")
     */
    private $chiefExecSalaryPercentile;

    /**
     * @var integer
     *
     * @Column(name="isFeatured", type="integer")
     */
    private $isFeatured = 0;

    /**
     * @var string
     *
     * @Column(name="updatedAt", type="string")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @Column(name="mission_summary", type="string")
     */
    private $mission_summary;

    /**
     * @var integer
     *
     * @Column(name="mission_summary_user_id", type="integer")
     */
    private $mission_summary_user_id;

	private $imageFile;

	private $errors = array();

    public function getLdJsonSchema() {
        $data = [];

        $data["@context"] = "http://schema.org";
        $data["@type"] = "NGO";


        if ($this->email) {
            $data['email'] = $this->email;
        }
        if ($this->getPhoneNumber()) {
            $data['telephone'] = $this->getPhoneNumber();
        }

        $address = ["@type" => "PostalAddress"];

        if ($this->getIrsStreetAddress() != 'N/A') {
            $address['streetAddress'] = $this->getIrsStreetAddress();
        }
        if ($this->getIrsZipcode() != 'N/A') {
            $address['postalCode'] = $this->getIrsZipcode();
        }
        $address['addressCountry'] = 'USA';
        if ($this->getStateName()) {
            $address['addressRegion'] = $this->getStateName();
        }

        if ($this->getIrsCity() != 'N/A') {
            $address["addressLocality"] = $this->getIrsCity();
        } elseif ($this->getCityName()) {
            $address["addressLocality"] = $this->getCityName();
        }

        $data['address'] = $address;

        $data['name'] = $this->getName();

        if (!$this->hasFakeEin()) {
            $data['duns'] = $this->getEin(true);
        }

        return json_encode($data, JSON_PRETTY_PRINT);
        /*
        {
            "@context": "http://schema.org",
  "@type": "NGO",
  "address": {
            "@type": "PostalAddress",
    "addressLocality": "Paris, France",
    "postalCode": "F-75002",
    "streetAddress": "38 avenue de l'Opera"
  },
  "email": "secretariat(at)google.org",
  "faxNumber": "( 33 1) 42 68 53 01",
  "member": [
    {
        "@type": "Organization"
    },
    {
        "@type": "Organization"
    }
  ],
  "name": "Google.org (GOOG)",
  "telephone": "( 33 1) 42 68 53 00"
}
        */
    }

	public function getId() {
		return $this->id;
	}

    /**
     * @param int $convert_case
     *
     * @return string
     */
    public function getName($convert_case = MB_CASE_TITLE) {
        if ($convert_case === false || $convert_case === null) {
            return $this->name;
        }
		return mb_convert_case($this->name, MB_CASE_TITLE);
	}

    public function getcharityText() {
		return $this->freatured_text;
	}
        
	public function getOverallRtg() {
		return $this->overallRtg;
	}

    public function setRank($rank) {
        $this->rank = $rank;
    }

	public function getTagLine() {
		return $this->tagLine;
	}

    public static function findSphinx($search_text, $search_zip, $tab = false, $offset = 0, $limit = 20) {
        $em = \Base_Controller::$em;

        $data = array();
        $data['current_text'] = $search_text ? $search_text : '';
        $data['current_zip'] = $search_zip ? $search_zip : '';

        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits((int)$offset, (int)$limit, 1000);
        $cl->SetRankingMode(SPH_RANK_PROXIMITY_BM25);
        $cl->SetFieldWeights([
                'fieldName' => 40,
                'tagLine' => 10,
                'city' => 1,
                'state' => 1
            ]);

        $cityId = null;
        if ($search_zip) {
            $zcRepo = $em->getRepository('Entity\ZipCode');
            /** @var ZipCode $zipCode  */
            $zipCode = $zcRepo->findOneBy(array('zip' => $search_zip));
            if ($zipCode)
                $cityId = $zipCode->getCityId();
            else
                $cityId = 999999;
        }

        if ($cityId) {
            $cl->SetFilter('cityId', array($cityId));
        }

        $search = function($tab, $tabName) use($search_text, &$cl, &$em) {
            $sort = $tab['sort'];
            $cl->SetSortMode($sort['mode'], (string)$sort['by']);

            $query_string = \Common::getQueryString($search_text);
            $GLOBALS['super_timers'][$query_string.'pre'] = microtime(true) - $GLOBALS['super_start'];
            $res = $cl->Query($query_string, 'charity:charity_delta');
            $GLOBALS['super_timers'][$query_string.'post'] = microtime(true) - $GLOBALS['super_start'];;

            if ($res === false) {
                return array('count' => 0, 'charities' => array());
            }

            /** @var \Entity\Charity[] $charities */
            $charities = [];

            if (@$res['matches']) {
                foreach ($res['matches'] as $match) {
                    $charities[$match['id']] = $match['id'];
                }

                $qb = $em->createQueryBuilder();
                $qb->select('ccc');
                $qb->from('Entity\Charity', 'ccc');
                $qb->where('ccc.id IN ('.join(',',$charities).')');

                /** @var \Entity\Charity[] $results */
                $results = $qb->getQuery()->getResult();
                foreach($results as $r) {
                    $charities[$r->getId()] = $r;
                }
            }

            return array(
                'charities' => $charities,
                'count' => $res['total']
            );
        };


        $tabs = array(
            'relevance' => array(
                'sort' => array(
                    'by' => "(@weight + (overallScore / 4)) * ((orgId / 10) + 1) * ((has_data / 10) + 1)",
                    'mode' => SPH_SORT_EXPR,
                ),
            ),
            'charity_name' => array(
                'sort' => array(
                    'by' => 'orgId DESC, name ASC',
                    'mode' => SPH_SORT_EXTENDED,
                ),
            ),
            'popular' => array(
                'sort' => array(
                    'by' => 'orgId DESC, followers DESC',
                    'mode' => SPH_SORT_EXTENDED,
                ),
            ),
            'reviews' => array(
                'sort' => array(
                    'by' => 'orgId DESC, reviews DESC',
                    'mode' => SPH_SORT_EXTENDED,
                ),
            ),
            'score' => array(
                'sort' => array(
                    'by' => 'orgId DESC, overallScore DESC',
                    'mode' => SPH_SORT_EXTENDED,
                ),
            ),
        );

        if (!$tab) {
            foreach($tabs as $tabName => $tab) {
                $res = $search($tab, $tabName);
                $data['content_'.$tabName] = $res['charities'];
                $data['result_count'] = $res['count'];
            }
        } else {
            $res = $search($tabs[$tab], $tab);
            $data['content_'.$tab] = $res['charities'];
            $data['result_count'] = $res['count'];
        }

        return $data;
    }

    public static function findSphinxQuery($query, $offset = 0, $limit = 10, $max_matches = 1000) {
        $em = \Base_Controller::$em;

        $cl = new \SphinxClient();

        $cl->SetServer(GIVERHUB_LIVE ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits($offset, $limit, $max_matches);

        $cl->SetSortMode(SPH_SORT_ATTR_ASC, 'name');

        $res = $cl->Query($query, 'charity:charity_delta');
        if ($res === false) {
            return ['count' => 0, 'charities' => []];
        }

        /** @var Charity[] $charities */
        $charities = array();

        if (@$res['matches']) {
            foreach ($res['matches'] as $match){
                $charities[$match['id']] = $match['id'];
            }

            $qb = $em->createQueryBuilder();
            $qb->select('c');
            $qb->from('Entity\Charity', 'c');
            $qb->where('c.id IN ('.join(',',$charities).')');


            /** @var \Entity\Charity[] $results */
            $results = $qb->getQuery()->getResult();
            foreach($results as $r) {
                $charities[$r->getId()] = $r;
            }
        }

        return array(
            'charities' => $charities,
            'count' => $res['total_found']
        );
    }

    public function similar($limit = 3) {
        $cl = new \SphinxClient();

        $cl->SetServer($_SERVER['SERVER_NAME'] == 'giverhub.com' ? 'sphinx' : '127.0.0.1');
        $cl->SetConnectTimeout(5);
        $cl->SetArrayResult(true);

        $cl->SetLimits(0, $limit, 1000);
        $cl->SetSortMode(SPH_SORT_EXTENDED, 'orgId DESC, overallScore DESC');

        /**
         * @param \SphinxClient $cl
         *
         * @return Charity[]
         */
        $getSphinxCharities = function(\SphinxClient $cl) {
            $res = $cl->Query('', 'charity:charity_delta');
            if ($res === false) {
                return array();
            }

            /** @var \Entity\Charity[] $charities */
            $charities = array();

            if (@$res['matches']) {
                foreach ($res['matches'] as $match){
                    $charities[$match['id']] = $match['id'];
                }

                $qb = \Base_Controller::$em->createQueryBuilder();
                $qb->select('ccc');
                $qb->from('Entity\Charity', 'ccc');
                $qb->where('ccc.id IN ('.join(',',$charities).')');


                /** @var \Entity\Charity[] $results */
                $results = $qb->getQuery()->getResult();
                foreach($results as $r) {
                    $charities[$r->getId()] = $r;
                }
            }

            return $charities;
        };

        $setFilters = function(\SphinxClient $cl) {
            $causeIds = $this->getCauseIds();
            if ($causeIds) {
                $cl->SetFilter('causes', $causeIds);
            }
            $cl->SetFilter('charityId', array($this->id), true); // exclude this charity
        };

        $setFilters($cl);

        /** @var Charity[] $charitiesWithCityFilter */
        $charitiesWithCityFilter = array();
        $user = \Base_Controller::$staticUser;
        if ($user) {
            if ($user->hasAddress()) {
                $city = $user->getDefaultAddress()->getCity();
            }

            if (isset($city)) {
                $cl->SetFilter('cityId', array($city->getId()));

                $charitiesWithCityFilter = $getSphinxCharities($cl);
            }
        }

        if (count($charitiesWithCityFilter) < 3) {
            $cl->ResetFilters();
            $setFilters($cl);

            $charityIds = array();
            foreach($charitiesWithCityFilter as $charity) {
                $charityIds[] = $charity->getId();
            }
            if ($charityIds) {
                $cl->SetFilter('charityId', $charityIds, true);
            } // exclude existing

            $cl->SetLimits(0, 3 - count($charitiesWithCityFilter), 1000);

            $charities = $getSphinxCharities($cl);

            $charities = array_merge($charitiesWithCityFilter, $charities);
            return $charities;
        } else {
            return $charitiesWithCityFilter;
        }

    }

    /**
     * @return array
     */
    public function getCauseIds() {
        $causeIds = array();

        foreach($this->getCauses() as $cause) {
            $causeIds[] = $cause->getId();
        }

        return $causeIds;
    }

    public function getCategories() {
        $charityCharityCauses = CharityCharityCause::findBy(array('charityId' => $this->id));
        /** @var CharityCategory[] $categories */
        $categories = array();
        foreach($charityCharityCauses as $charityCharityCause) {
            $category = $charityCharityCause->getCause()->getCategory();
            $categories[$category->getId()] = $category;
        }

        return $categories;
    }

	public function setEin($ein) {
		$this->ein = $ein;
	}

	public function setCityId($cityId) {
		$this->cityId = $cityId;
	}

	public function setOverallRtg($overallRtg) {
		$this->overallRtg = $overallRtg;
	}

    public function setOrgId($orgId) {
        $this->orgId = $orgId;
    }

	public function setName($name) {
		$this->name = $name;
	}

	public function setStateId($stateId) {
		$this->stateId = $stateId;
	}

	public function setTagLine($tagLine) {
		$this->tagLine = $tagLine;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setPhoneNumber($phoneNumber) {
		$this->phoneNumber = $phoneNumber;
	}

	public function setWebSite($webSite) {
		$this->webSite = $webSite;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function setAddress2($address2) {
		$this->address2 = $address2;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}

	public function setImageFile($imageFile) {
		$this->imageFile = $imageFile;
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

    private function formatToDollars($n) {
        if (is_string($n)) {
            if (is_numeric($n)) {
                $n = (float)$n;
            } else {
                $n = (int)preg_replace('#[^0-9]#', '', $n);
            }
        }
        /* money_format is not for windows platform so this condition will take care of that */
        if(!function_exists("money_format")){
            return '$' . number_format($n, 2);
        }

        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%.0n', $n);
    }

    public function getHighestPaidOfficerCompensationPercentile() {
        return $this->highestPaidOfficerCompensationPercentile;
    }

    public function getProgramServicesPercentile() {
        return $this->programServicesPercentile;
    }

    public function getFundRaisingCostsPercentile() {
        return $this->fundRaisingCostsPercentile;
    }

    public function getAdminExpensesPercentile() {
        return $this->adminExpensesPercentile;
    }

    public function getRevenuePercentile() {
        return $this->revenuePercentile;
    }

    public function getHighestPaidOfficerCompensation() {
        return $this->highestPaidOfficerCompensation;
    }

    public function getProgramServicesAmount() {
        return $this->programServicesAmount;
    }

    public function getFundRaisingCostsAmount() {
        return $this->fundRaisingCostsAmount;
    }

    public function getAdminExpensesAmount() {
        return $this->adminExpensesAmount;
    }

    public function getRevenueAmount() {
        return $this->revenueAmount;
    }

    public function getHighestPaidOfficerCompensationStr() {
        $this->prepareHighestPaidOfficerCompensation();
        return $this->highestPaidOfficerCompensation !== null ? $this->formatToDollars($this->highestPaidOfficerCompensation) : 'N/A';
    }

    public function getProgramServicesAmountStr() {
        $this->prepareProgramServicesAmount();
        return $this->programServicesAmount !== null ? $this->formatToDollars($this->programServicesAmount) : 'N/A';
    }

    public function getFundRaisingCostsAmountStr() {
        $this->prepareFundRaisingCostsAmount();
        return $this->fundRaisingCostsAmount !== null ? $this->formatToDollars($this->fundRaisingCostsAmount) : 'N/A';
    }

    public function getAdminExpensesAmountStr() {
        $this->prepareAdminExpensesAmount();
        return $this->adminExpensesAmount !== null ? $this->formatToDollars($this->adminExpensesAmount) : 'N/A';
    }

    public function getRevenueAmountStr() {
        $this->prepareRevenueAmount();
        return $this->revenueAmount !== null ? $this->formatToDollars($this->revenueAmount) : 'N/A';
    }

    public function setHighestPaidOfficerCompensation($v) {
        $this->highestPaidOfficerCompensation = $v;
    }

    public function setProgramServicesAmount($v) {
        $this->programServicesAmount = $v;
    }

    public function setFundRaisingCostsAmount($v) {
        $this->fundRaisingCostsAmount = $v;
    }

    public function setAdminExpensesAmount($v) {
        $this->adminExpensesAmount = $v;
    }

    public function setRevenueAmount($v) {
        $this->revenueAmount = $v;
    }

	public function getFileName() {
		return $this->fileName;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getPhoneNumber() {
		return $this->phoneNumber;
	}

	public function getStateId() {
		return $this->stateId;
	}

	public function getCityId() {
		return $this->cityId;
	}

	public function getEin($formatted = false) {
        if ($formatted) {
            if (preg_match('#^admin#', $this->ein)) {
                return '';
            } else {
                return \Common::formatEin( $this->ein );
            }
        }
		return $this->ein;
	}

    public function hasFakeEin() {
        return preg_match('#^admin#', $this->ein);
    }

	public function getWebSite() {
		return $this->webSite;
	}

	public function getAddress() {
		return $this->address;
	}

	public function getAddress2() {
		return $this->address2;
	}

	public function getZipCode() {
		return $this->zipCode;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function setCreatedAt($createdAt) {
		$this->createdAt = $createdAt;
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

    /**
     * @param null $limit
     * @param null $offset
     *
     * @return User[]
     * @throws \Exception
     */
    public function getFollowers($limit = null, $offset = null) {
		$em = \Base_Controller::$em;

        $cfRepo = $em->getRepository('Entity\CharityFollower');

        /** @var \Entity\CharityFollower[] $charityFollowers */
        $charityFollowers = $cfRepo->findBy(array('charity_id' => $this->id), null, $limit, $offset);

        /** @var \Entity\User[] $followers */
        $followers = array();
        foreach($charityFollowers as $charityFollower) {
            $user = $charityFollower->getUserEntity();
            if (!$user) {
                //throw new \Exception('User could not be loaded. user id: ' . $charityFollower->getUserId());
            }
            $followers[] = $user;
        }
        return $followers;
	}

	public function getOverallScore() {
		return $this->overallScore;
	}

    public function getOverallScorePercentage() {
        return $this->overallScore;
    }

    static public $overallScoreText = "This is the nonprofits Overall Score. It's based on program services financial data. it's the program services percentage of total functional expenses. A score of 100 means that the nonprofit spends all their money on program services.";

	public function setOverallScore($overallScore) {
		$this->overallScore = $overallScore;
	}

	public function getOrgId() {
		return $this->orgId;
	}

	public function isReadyForDonation() {
		return $this->ein ? true : false;
	}

    private $metaCache = array();

    public function getMetaValue($key) {
        $em = \Base_Controller::$em;
        $mRepo = $em->getRepository('Entity\CharityMeta');
        /** @var CharityMeta $meta */
        $meta = $mRepo->findOneBy(array('key' => $key, 'charityId' => $this->getId()));
        return $meta ? $meta->getValue() : false;
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $flush
     *
     * @return CharityMeta
     */
    public function setMeta($key, $value, $flush = false) {
        echo '.';
        $em = \Base_Controller::$em;
        $mRepo = $em->getRepository('Entity\CharityMeta');

        if (!$this->metaCache) {
            /** @var CharityMeta[] $metas */
            $metas = $mRepo->findBy(array('charityId' => $this->id));
            foreach($metas as $meta) {
                $this->metaCache[$meta->getKey()] = array('id' => $meta->getId(), 'value' => $meta->getValue());
            }
        }

        if (!isset($this->metaCache[$key])) {
            $charityMeta = new CharityMeta();
            $charityMeta->setKey($key);
            $charityMeta->setCharity($this);
            $charityMeta->setValue($value);
            $em->persist($charityMeta);
            if ($flush) $em->flush($charityMeta);
            $this->metaCache[$key] = array('value' => $value);
            echo '|';
            return $charityMeta;
        } elseif ($this->metaCache[$key]['value'] != $value) {
            if (!isset($this->metaCache[$key]['id'])) {
                echo $key . '-' . $value . ' ein: ' . $this->ein . PHP_EOL;
            }
            /** @var CharityMeta $charityMeta */
            $charityMeta = $mRepo->find($this->metaCache[$key]['id']);
            $charityMeta->setValue($value);
            $em->persist($charityMeta);
            if ($flush) $em->flush($charityMeta);
            $this->metaCache[$key]['value'] = $value;
            echo '*';
            return $charityMeta;
        }
        echo '-';
        return null;
    }

    private $_admin_data = null;

    /**
     * @param $field
     *
     * @return string|null
     */
    public function getAdminDataValue($field) {
        if ($this->_admin_data === null) {
            /** @var CharityAdminData[] $datas */
            $datas = CharityAdminData::findBy(['charity' => $this]);
            foreach($datas as $data) {
                $this->_admin_data[$data->getField()] = $data->getValue();
            }
        }
        return isset($this->_admin_data[$field]) ? $this->_admin_data[$field] : null;
    }

    /**
     * @return string
     */
    public function getMissionSummary() {
        if ($this->getAdminDataValue('tagline')) {
            return $this->getAdminDataValue('tagline');
        }

        if ($this->tagLine) {
            return $this->tagLine;
        }
        return $this->mission_summary;
    }

    private $_mission = null;

    /**
     * @return Mission|string|null
     */
    public function getMission() {
        if ($this->getAdminDataValue('mission')) {
            return $this->getAdminDataValue('mission');
        }

        $missions = $this->getMissions(1);

        if ($missions) {
            return $missions[0];
        }
        return null;
    }

    /** @var \Entity\Mission[] */
    private $_missions = null;

    /**
     * @param null|integer $limit
     *
     * @return Mission[]
     */
    public function getMissions($limit = null) {
        if ($this->_missions === null) {
            /** @var Mission[] $missions */
            $this->_missions = [];

            $CI =& get_instance();

            $sql =
            'select
                m.id as id
            from
                mission m
            left join
                mission_vote mv on m.id = mv.mission_id
            where
                charity_id = '.$this->id.'
            group by
                m.id
            order by
                sum(mv.vote) DESC,
                m.created_time ASC';
            if ($limit) {
                $sql .= ' limit '.$limit;
            }

            $q = $CI->db->query($sql);
            $rows = $q->result_array();
            foreach($rows as $mission) {
                $this->_missions[] = Mission::find($mission['id']);
            }
        }

        return $this->_missions;
    }


    /**
     * @param $mission_summary
     */
    public function setMissionSummary($mission_summary) {
        $this->mission_summary = $mission_summary;
    }

    private function loadMetaCache() {
        if (!$this->metaCache) {
            $CI =& get_instance();
            $CI->db->select('key, value');
            $query = $CI->db->get_where('CharityMeta', array('charityId' => $this->id));
            foreach ($query->result_array() as $row) {
                $this->metaCache[$row['key']] = $row['value'];
            }
        }
    }

    private function dollarToInt($dollarStr) {
        if (preg_match_all('#[\$|,](\d+)#', $dollarStr, $matches)) {
            return join('',$matches[1]);
        }
        return null;
    }

    private function prepareHighestPaidOfficerCompensation() {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return 'N/A';
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#^people_(\d+)_year_(\d+)_(name|title|compensation)$#', $key, $matches)) {
                $nr = $matches[1];
                $year = $matches[2];
                $nameTitleOrCompensation = $matches[3];

                $years[$year][$nr][$nameTitleOrCompensation] = $value;
            }
        }
        if (!$years) {
            return 'N/A';
        }

        ksort($years);
        $peopleLastYear = array_pop($years);

        $compensation = array();
        foreach($peopleLastYear as $person) {
            if (preg_match_all('#[\$|,](\d+)#', $person['compensation'], $matches)) {
                $compensation[join('',$matches[1])] = $person['compensation'];
            }
        }
        if (!$compensation) {
            return 'N/A';
        }
        ksort($compensation);

        $compensation =  $this->dollarToInt(array_pop($compensation));
        $this->setHighestPaidOfficerCompensation($compensation);
    }

    public function prepareChiefExecSalary($execName) {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return 'N/A';
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#^people_(\d+)_year_(\d+)_(name|title|compensation)$#', $key, $matches)) {
                $nr = $matches[1];
                $year = $matches[2];
                $nameTitleOrCompensation = $matches[3];

                $years[$year][$nr][$nameTitleOrCompensation] = $value;
            }
        }
        if (!$years) {
            return 'N/A';
        }

        ksort($years);
        $peopleLastYear = array_pop($years);

        foreach($peopleLastYear as $person) {
            if (strtoupper($person['name']) == strtoupper($execName)) {
                if (preg_match_all('#[\$|,](\d+)#', $person['compensation'], $matches)) {
                    $this->chiefExecSalary = join('',$matches[1]);
                }
                break;
            }
        }

        return $this->chiefExecSalary;
    }

    public function getChiefExecSalary() {
        return $this->chiefExecSalary;
    }

    public function getChiefExecSalaryPercentile() {
        return $this->chiefExecSalaryPercentile;
    }

    public function getChiefExecSalaryStr() {
        return $this->formatToDollars($this->chiefExecSalary);
    }

    private function prepareProgramServicesAmount() {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return 'N/A';
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#Expenses_Program Services_(\d+)#', $key, $matches)) {
                $years[$matches[1]] = $value;
            }
        }
        if (!$years) {
            return 'N/A';
        }

        ksort($years);
        $this->setProgramServicesAmount($this->dollarToInt(array_pop($years)));
    }

    private function prepareFundRaisingCostsAmount() {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return 'N/A';
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#Expenses_Fundraising_(\d+)#', $key, $matches)) {
                $years[$matches[1]] = $value;
            }
        }
        if (!$years) {
            return 'N/A';
        }

        ksort($years);
        $this->setFundRaisingCostsAmount($this->dollarToInt(array_pop($years)));
    }

    private function prepareAdminExpensesAmount() {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return 'N/A';
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#Expenses_Administration_(\d+)#', $key, $matches)) {
                $years[$matches[1]] = $value;
            }
        }
        if (!$years) {
            return 'N/A';
        }

        ksort($years);
        $this->setAdminExpensesAmount($this->dollarToInt(array_pop($years)));
    }

    private function prepareRevenueAmount() {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return 'N/A';
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#Revenue_Total Revenue_(\d+)#', $key, $matches)) {
                $years[$matches[1]] = $value;
            }
        }
        if (!$years) {
            return 'N/A';
        }

        ksort($years);
        $this->setRevenueAmount($this->dollarToInt(array_pop($years)));
    }

    public function percentilePrepare() {
        $this->prepareAdminExpensesAmount();
        $this->prepareFundRaisingCostsAmount();
        $this->prepareHighestPaidOfficerCompensation();
        $this->prepareProgramServicesAmount();
        $this->prepareRevenueAmount();
    }

    public function calculateExecutivePay() {
        $this->loadMetaCache();

        if (!$this->metaCache) {
            return;
        }

        $years = array();
        foreach($this->metaCache as $key => $value) {
            if (preg_match('#^people_(\d+)_year_(\d+)_(name|title|compensation)$#', $key, $matches)) {
                $nr = $matches[1];
                $year = $matches[2];
                $nameTitleOrCompensation = $matches[3];

                $years[$year][$nr][$nameTitleOrCompensation] = $value;
            }
        }
        if (!$years) {
            return;
        }

        ksort($years);
        $peopleLastYear = array_pop($years);

        $compensation = false;
        foreach($peopleLastYear as $person) {
            if (preg_match_all('#[\$|,](\d+)#', $person['compensation'], $matches)) {
                if ($compensation === false) {
                    $compensation = (int)join('',$matches[1]);
                } else {
                    $compensation += (int)join('',$matches[1]);
                }
                echo '+';
            }
        }
        if ($compensation === false) {
            return;
        }

        echo $compensation . PHP_EOL;
        $this->executivePay = $compensation;
    }

    public function calculateOverallEfficiencyAndFundraisingEffectivenessAndExecutiveProductivity() {
        $this->calculateOverallEfficiency();
        $this->calculateFundraisingEffectiveness();
        $this->calculateExecutiveProductivity();
    }

    public function calculateOverallScore() {
        if ($this->programServicesPercentile === null || $this->overallEfficiencyPercentile === null) {
            $this->overallScore = null;
        } else {
            if ($this->programServicesAmount && $this->executivePay === 0) {
                $execProd = 50;
            } else {
                $execProd = $this->executiveProductivityPercentile;
            }
            if ($execProd === null) {
                $this->overallScore = null;
            } else {
                $this->overallScore = ($this->programServicesPercentile + $execProd + $this->overallEfficiencyPercentile) / 3;
            }
        }
        return $this->overallScore;
    }

    public function calculateOverallEfficiency() {
        if ($this->programServicesAmount === null || $this->revenueAmount === null) {
            $this->overallEfficiency = null;
        } else {
            $this->overallEfficiency = $this->revenueAmount ? $this->programServicesAmount / $this->revenueAmount : $this->programServicesAmount;
        }
    }

    public function calculateFundraisingEffectiveness() {
        if (!$this->fundRaisingCostsAmount || $this->revenueAmount === null) {
            $this->fundraisingEffectiveness = null;
        } else {
            $this->fundraisingEffectiveness = $this->fundRaisingCostsAmount ? $this->revenueAmount / $this->fundRaisingCostsAmount : $this->revenueAmount;
        }
    }

    public function calculateExecutiveProductivity() {
        if (!$this->executivePay || $this->programServicesAmount === null) {
            $this->executiveProductivity = null;
        } else {
            $this->executiveProductivity = $this->executivePay ? $this->programServicesAmount / $this->executivePay : $this->programServicesAmount;
        }
    }

    function custom_number_format($n, $precision = 3) {
        if ($n < 1000000) {
            // Anything less than a million
            $n_format = number_format($n);
        } else if ($n < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else {
            // At least a billion
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        }

        return $n_format;
    }

    public function getOverallEfficiency() {
        return $this->overallEfficiency;
    }

    public function getOverallEfficiencyStr() {
        return $this->overallEfficiency === null ? 'N/A' : $this->custom_number_format($this->overallEfficiency);
    }

    public function getOverallEfficiencyPercentile() {
        return $this->overallEfficiencyPercentile;
    }

    public function getFundraisingEffectiveness() {
        return $this->fundraisingEffectiveness;
    }

    public function getFundraisingEffectivenessStr() {
        return $this->fundraisingEffectiveness === null ? 'N/A' : $this->custom_number_format($this->fundraisingEffectiveness);
    }

    public function getFundraisingEffectivenessPercentile() {
        return $this->fundraisingEffectivenessPercentile;
    }

    public function getExecutiveProductivity() {
        return $this->executiveProductivity;
    }

    public function getExecutiveProductivityStr() {
        return $this->executiveProductivity === null ? 'N/A' : $this->custom_number_format($this->executiveProductivity);
    }

    public function getExecutiveProductivityPercentile() {
        return $this->executiveProductivityPercentile;
    }

    public function getOverallScorePercentile() {
        return $this->overallScorePercentile;
    }

    public function getUrlSlugOrId() {
        return $this->url_slug ? $this->url_slug : $this->id;
    }

    public function getUrl($full = false) {
        if ($full) {
            return base_url('/nonprofits/'.$this->getUrlSlugOrId());
        }
        return '/nonprofits/'.$this->getUrlSlugOrId();
    }

    public function getLink() {
        return '<a title="'.htmlspecialchars($this->getName()).'" href="'.$this->getUrl().'">'.htmlspecialchars($this->getName()).'</a>';
    }

    public function getFeaturedText() {
        return $this->freatured_text;
    }

    public function getIsFeatured() {
        return $this->isFeatured;
    }

    public function setIsFeatured($isFeatured) {
        $this->isFeatured = $isFeatured;
    }

    public function setFeaturedText($text) {
        $this->freatured_text = $text;
    }

    /**
     * @return CharityKeyword[]
     */
    public function getKeywords() {
        $em = \Base_Controller::$em;
        $kwRepo = $em->getRepository('\Entity\CharityKeyword');
        return $kwRepo->findBy(array('charity_id' => $this->id), array('keyword_name' => 'asc'));
    }

    /**
     * @return \Entity\CharityCity
     */
    public function getCity() {
        $em = \Base_Controller::$em;
        $cityRepo = $em->getRepository('Entity\CharityCity');
        return $cityRepo->findOneBy(array('id' => $this->getCityId()));

    }

    /**
     * @return \Entity\CharityState
     */
    public function getState() {
        $em = \Base_Controller::$em;
        $stateRepo = $em->getRepository('Entity\CharityState');
        return $stateRepo->findOneBy(array('id' => $this->getStateId()));
    }


    /**
     * @return CharityImage[]
     */
    public function getImages() {
        $em = \Base_Controller::$em;
        $ciRepo = $em->getRepository('\Entity\CharityImage');
        /** @var CharityImage[] $charityImages */
        $charityImages = $ciRepo->findBy(array('charity_id' => $this->id));
        return $charityImages;
    }

    /**
     * @return CharityCause[]
     */
    public function getCauses() {
        /** @var \Entity\CharityCharityCause[] $charityCharityCauses */
        $charityCharityCauses = \Base_Controller::$em->getRepository('\Entity\CharityCharityCause')->findBy(array(
                                                                                        'charityId' => $this->id
                                                                                   ));
        /** @var \Entity\CharityCause[] $causes */
        $causes = array();
        foreach($charityCharityCauses as $ccc) {
            $causes[] = $ccc->getCause();
        }
        return $causes;
    }

    public function getCauseNames() {
        $names = array();
        foreach($this->getCauses() as $cause) {
            $names[] = $cause->getName();
        }
        return join(', ', $names);
    }

    private $reviews = array();

    /**
     * @return bool
     */
    public function hasReviews() {
        return !empty($this->getReviews());
    }


    public function getReviews() {
        if ($this->reviews) {
            return $this->reviews;
        }
        return $this->reviews = \Base_Controller::$em->getRepository('\Entity\CharityReview')->findBy(array(
                                                                                                           'charity_id' => $this->id,
                                                                                                      ));
    }

    private $averageReview;

    public function getAverageReview() {
        if ($this->averageReview) {
            return $this->averageReview;
        }

        $query = \Base_Controller::$em->createQuery('SELECT AVG(cr.rating) FROM \Entity\CharityReview cr WHERE cr.charity_id = ?1');
        $query->setParameter(1, $this->id);

        $this->averageReview = $query->getSingleScalarResult();

        return $this->averageReview;
    }

    public function followersCount() {
        $query = \Base_Controller::$em->createQuery('SELECT COUNT(cf.id) FROM \Entity\CharityFollower cf WHERE cf.charity_id = ?1');
        $query->setParameter(1, $this->id);

        return $query->getSingleScalarResult();
    }

    /** @var Charity[] */
    static private $featuredCharities;

    /**
     * @return Charity|null
     */
    static public function getFeaturedCharity() {
        if (self::$featuredCharities === null) {
            self::$featuredCharities = self::findBy(array('isFeatured' => 1));
        }

        if (self::$featuredCharities) {
            return self::$featuredCharities[rand(0,count(self::$featuredCharities)-1)];
        } else {
            return null;
        }
    }

    /**
     * @param int $limit
     *
     * @return Charity[]
     */
    static public function getFeaturedCharities($limit = 4) {
        if (self::$featuredCharities === null) {
            self::$featuredCharities = self::findBy(array('isFeatured' => 1), null, 20);
        }

        if (self::$featuredCharities) {
            shuffle(self::$featuredCharities);
            return array_slice(self::$featuredCharities, 0, $limit);
        } else {
            return array();
        }
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAtDt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAtDt()
    {
        return new \DateTime($this->updatedAt);
    }

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    /** @PreUpdate */
    public function onPreUpdate()
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function getCityName() {
        if ($this->cityId) {
            return $this->getCity()->getName();
        }
        return null;
    }

    public function getStateFullName() {
        if ($this->stateId) {
            return $this->getState()->getFullName();
        }
        return null;
    }

    public function getStateName() {
        if ($this->stateId) {
            return $this->getState()->getName();
        }
        return null;
    }

    /** @PreRemove */
    public function onPreRemove() {
        $CI =& get_instance();
        $CI->db->query('delete from CharityMeta where charityId = ' . $this->id);

        $keywords = CharityKeyword::findBy(['charity_id' => $this->id]);
        foreach($keywords as $keyword) {
            \Base_Controller::$em->remove($keyword);
            \Base_Controller::$em->flush();
        }
    }

    /**
     * @return bool
     */
    public function showOverallScore() {
        return $this->isFromCharityNavigator() || $this->getIrsProgramServicesAsPercentageOfTotalFunctionalExpenses(true);
    }

    /**
     * @return bool
     */
    public function isFromCharityNavigator() {
        return (bool)$this->orgId;
    }

    public function getImageUrl() {
        return '/assets/ico/128x128.png';
    }

    public function changeUrlSlug($new_url_slug) {
        if ($new_url_slug != \Common::slug($new_url_slug)) {
            throw new \Exception('Make sure to pass a valid slug (lowercase etc)');
        }

        if ($this->url_slug == $new_url_slug) {
            return;
        }

        $old_slug = $this->url_slug;


        // check if new slug exists elsewhere
        /** @var Charity|null $existing_charity */
        $existing_charity = Charity::findOneBy(['url_slug' => $new_url_slug]);
        if ($existing_charity) {
            throw new UrlSlugIsUsedException;
        }
        /** @var \Entity\CharityUrlHistory $existing_history */
        $existing_history = CharityUrlHistory::findOneBy(['url_slug' => $new_url_slug]);
        if ($existing_history) {
            // check if it's an old url for this nonprofit.. if it is .. switch!
            if ($existing_history->getCharity() == $this) {
                $this->url_slug = $new_url_slug;
                $existing_history->setUrlSlug($old_slug);
                $this->setUpdatedAtDt(new \DateTime());
                \Base_Controller::$em->persist($this);
                \Base_Controller::$em->persist($existing_history);
                \Base_Controller::$em->flush();
                return;
            } else {
                throw new UrlSlugIsUsedException;
            }
        }

        // add the old url to the history
        $history = new CharityUrlHistory();
        $history->setCharity($this);
        $history->setUrlSlug($old_slug);
        \Base_Controller::$em->persist($history);
        \Base_Controller::$em->flush($history);
        // and finally change to the new slug
        $this->setUpdatedAtDt(new \DateTime());
        $this->url_slug = $new_url_slug;
        \Base_Controller::$em->persist($this);
        \Base_Controller::$em->flush($this);
    }

    /**
     * @param mixed $url_slug
     */
    public function setUrlSlug($url_slug)
    {
        $this->url_slug = $url_slug;
    }

    /**
     * @return mixed
     */
    public function getUrlSlug()
    {
        return $this->url_slug;
    }

    public function __toString() {
        return $this->getUrl();
    }

    /**
     * @param int $mission_summary_user_id
     */
    public function setMissionSummaryUserId($mission_summary_user_id)
    {
        $this->mission_summary_user_id = $mission_summary_user_id;
    }

    /**
     * @return int
     */
    public function getMissionSummaryUserId()
    {
        return $this->mission_summary_user_id;
    }


    /**
     * @param User $user
     */
    public function setMissionSummaryUser(User $user)
    {
        $this->mission_summary_user_id = $user->getId();
    }

    /**
     * @return User|null
     */
    public function getMissionSummaryUser()
    {
        if ($this->getAdminDataValue('tagline')) {
            return null;
        }
        if (!$this->mission_summary_user_id) {
            return null;
        }
        return User::find($this->mission_summary_user_id);
    }

    public function getCitizenData() {
        $CI =& get_instance();
        $db = $CI->db;



        $q = $db->query('select * from irs_fieldnames');
        $rows = $q->result_array();

        $field_names = [];
        foreach($rows as $row) {
            $field_names[$row['fieldname']] = $row['descrip'] . ' (' . $row['fieldname'] . ')';
        }
        $data = [];

        $q = $db->query('select * from irs_eins where ein = ?', [$this->ein]);

        $rows = $q->result_array();
        foreach($rows as $row) {
            $group = ['label' => 'Basic - ' . $row['year'], 'fields' => []];

            foreach($row as $field => $value) {
                if (isset($field_names[$field])) {
                    $field_name = $field_names[$field];
                } else {
                    $field_name = $field;
                }
                $group['fields'][$field_name] = $value;
            }
            $data[] = $group;
        }

        foreach(['irs_2012_990', 'irs_2012_990ez', 'irs_2012_990pf', 'irs_990', 'irs_990ez', 'irs_990pf'] as $table) {
            $q = $db->query('select * from '.$table.' where ein = ?', [$this->ein]);

            $rows = $q->result_array();

            foreach($rows as $nr => $row) {
                $group = [];
                $group['label'] = $table . ' row nr ' . ($nr+1);
                $group['fields'] = [];
                foreach($row as $field => $value) {
                    if (isset($field_names[$field])) {
                        $field_name = $field_names[$field];
                    } else {
                        $field_name = $field;
                    }
                    $group['fields'][$field_name] = $value;
                }
                $data[] = $group;
            }
        }

        return $data;
    }

    /** @var IrsEins[] */
    private $_irs_eins;

    /** @var Irs990[] */
    private $_irs_990;
    /** @var Irs990ez[] */
    private $_irs_990ez;
    /** @var Irs990pf[] */
    private $_irs_990pf;

    /** @var Irs2012990[] */
    private $_irs_2012990;
    /** @var Irs2012990ez[] */
    private $_irs_2012990ez;
    /** @var Irs2012990pf[] */
    private $_irs_2012990pf;


    /**
     * @return IrsEins[]
     */
    public function getIrsEins() {
        if ($this->_irs_eins === null) {
            $this->_irs_eins = IrsEins::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_eins;
    }

    /**
     * @return Irs2012990[]
     */
    public function getIrs2012990() {
        if ($this->_irs_2012990 === null) {
            $this->_irs_2012990 = Irs2012990::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_2012990;
    }

    /**
     * @return Irs2012990ez[]
     */
    public function getIrs2012990ez() {
        if ($this->_irs_2012990ez === null) {
            $this->_irs_2012990ez = Irs2012990ez::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_2012990ez;
    }

    /**
     * @return Irs2012990pf[]
     */
    public function getIrs2012990pf() {
        if ($this->_irs_2012990pf === null) {
            $this->_irs_2012990pf = Irs2012990pf::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_2012990pf;
    }

    /**
     * @return Irs990[]
     */
    public function getIrs990() {
        if ($this->_irs_990 === null) {
            $this->_irs_990 = Irs990::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_990;
    }

    /**
     * @return Irs990ez[]
     */
    public function getIrs990ez() {
        if ($this->_irs_990ez === null) {
            $this->_irs_990ez = Irs990ez::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_990ez;
    }

    /**
     * @return Irs990pf[]
     */
    public function getIrs990pf() {
        if ($this->_irs_990pf === null) {
            $this->_irs_990pf = Irs990pf::findBy(['ein' => $this->ein]);
        }

        return $this->_irs_990pf;
    }

    /**
     * @param $field_name
     *
     * @return null|string
     * @throws \Exception
     */
    public function getLatestIrsEinsField($field_name) {
        $rows = $this->getIrsEins();
        if (!$rows) {
            return null;
        }
        $tmp_rows = [];
        foreach($rows as $row) {
            $tmp_rows[$row->getYear()] = $row;
        }
        ksort($tmp_rows);
        /** @var IrsEins $row */
        $row = array_pop($tmp_rows);

        switch($field_name) {
            case 'irs_eins_assets':
                return $row->getAssetAmount();
            case 'irs_eins_income':
                return $row->getIncomeAmount();
            case 'irs_eins_revenue':
                return $row->getForm990RevenueAmount();
            case 'irs_eins_street_address':
                return $row->getStreetAddress();
            case 'irs_eins_city':
                return $row->getCity();
            case 'irs_eins_state':
                return $row->getState();
            case 'irs_eins_zipcode':
                return $row->getZipCode();
            case 'irs_eins_ruling_date':
                return $row->getRulingDate();
            default:
                throw new \Exception('field does not exist. field_name: '.$field_name);
        }
    }

    /**
     * @param $field_name
     *
     * @return null|string
     * @throws \Exception
     */
    public function getLatestIrs9902012990Field($field_name) {
        $tmp_rows = [];

        if (!in_array($field_name, [
                'compnsatnandothr',
                'pensionplancontrb',
                'othremplyeebenef',
                'feesforsrvcmgmt',
                'legalfees',
                'accntingfees',
                'feesforsrvclobby',
                'feesforsrvcinvstmgmt',
                'feesforsrvcothr',
                'advrtpromo',
                'officexpns',
                'infotech',
                'royaltsexpns',
                'occupancy',
                'travel',
                'travelofpublicoffcl',
                'converconventmtng',
                'interestamt',
                'pymtoaffiliates',
                'deprcatndepletn',
                'insurance',
                'othrexpnsa',
                'othrexpnsb',
                'othrexpnsc'
            ])) {
            $rows = $this->getIrs2012990();
            foreach($rows as $row) {
                $tmp_rows[$row->getTaxPrd()] = $row;
            }
        }

        $rows = $this->getIrs990();
        foreach($rows as $row) {
            $tmp_rows[$row->getTaxPd()] = $row;
        }

        if (!$tmp_rows) {
            return null;
        }

        ksort($tmp_rows);
        /** @var Irs990|Irs2012990 $row */
        $row = array_pop($tmp_rows);

        switch($field_name) {
            case 'totcntrbgfts':
                return $row->getTotcntrbgfts();
            case 'totprgmrevnue':
                return $row->getTotprgmrevnue();
            case 'invstmntinc':
                return $row->getInvstmntinc();
            case 'grsincfndrsng':
                return $row->getGrsincfndrsng();
            case 'lessdirfndrsng':
                return $row->getLessdirfndrsng();
            case 'totfuncexpns':
                return $row->getTotfuncexpns();
            case 'profndraising':
                return $row->getProfndraising();
            case 'totrevenue':
                return $row->getTotrevenue();
            case 'compnsatncurrofcr':
                return $row->getCompnsatncurrofcr();
            default:
                $method = 'get'.ucfirst($field_name);
                if (method_exists($row, $method)) {
                    return $row->$method();
                }
                throw new \Exception('field does not exist. field_name: '.$field_name);
        }
    }

    public function getLatestIrsField($field_name, $format_to_dollars = true) {
        $ret = null;
        switch($field_name) {
            case 'irs_eins_assets':
            case 'irs_eins_income':
            case 'irs_eins_revenue':
            case 'irs_eins_street_address':
            case 'irs_eins_city':
            case 'irs_eins_state':
            case 'irs_eins_zipcode':
            case 'irs_eins_ruling_date':
                $ret = $this->getLatestIrsEinsField($field_name);
                break;
            case 'totcntrbgfts':
            case 'totprgmrevnue':
            case 'invstmntinc':
            case 'grsincfndrsng':
            case 'lessdirfndrsng':
            case 'totfuncexpns':
            case 'profndraising':
            case 'totrevenue':
            case 'compnsatncurrofcr':
            case 'compnsatnandothr':
            case 'othrsalwages':
            case 'pensionplancontrb':
            case 'othremplyeebenef':
            case 'payrolltx':
            case 'feesforsrvcmgmt':
            case 'legalfees':
            case 'accntingfees':
            case 'feesforsrvclobby':
            case 'feesforsrvcinvstmgmt':
            case 'feesforsrvcothr':
            case 'advrtpromo':
            case 'officexpns':
            case 'infotech':
            case 'royaltsexpns':
            case 'occupancy':
            case 'travel':
            case 'travelofpublicoffcl':
            case 'converconventmtng':
            case 'interestamt':
            case 'pymtoaffiliates':
            case 'deprcatndepletn':
            case 'insurance':
            case 'othrexpnsa':
            case 'othrexpnsb':
            case 'othrexpnsc':
                $ret = $this->getLatestIrs9902012990Field($field_name);
                break;
            default:
                throw new \Exception('Unknown field_name: ' . $field_name);
        }
        if ($ret === null) {
            return 'N/A';
        }
        if ($format_to_dollars) {
            $ret = $this->formatToDollars($ret);
        }
        return $ret;
    }

    private $_revenue;
    public function getPercentageOfRevenue($value, $return_string = true, $return_rounded = true) {
        if (!$this->_revenue) {
            $this->_revenue = $this->getLatestIrsField('totrevenue', false);
        }

        if (is_numeric($this->_revenue) && $this->_revenue && is_numeric($value)) {
            $value = ($value / $this->_revenue) * 100;
            if ($return_rounded) {
                $value = round($value, 1);
            }
            if ($return_string) {
                return $value . '%';
            } else {
                return $value;
            }
        } else {
            return '-';
        }
    }

    private $_program_services;
    public function getIrsProgramServicesAsPercentageOfTotalFunctionalExpenses($raw_result = false) {
        if ($this->_program_services === null) {
            $left = $this->getLatestIrsField('totfuncexpns',false); // Total functional expenses
            $right = (
                //$this->getLatestIrsField('lessdirfndrsng', false) +
                $this->getLatestIrsField('compnsatncurrofcr', false) +  // Compensation of current officers, directors, etc
                //$this->getLatestIrsField('compnsatnandothr', false) +
                //$this->getLatestIrsField('pensionplancontrb', false) +
                //$this->getLatestIrsField('othremplyeebenef', false) +
                //$this->getLatestIrsField('payrolltx', false) +
                //$this->getLatestIrsField('feesforsrvcmgmt', false) +
                //$this->getLatestIrsField('accntingfees', false) +
                //$this->getLatestIrsField('feesforsrvclobby', false) +
                //$this->getLatestIrsField('profndraising', false) +  // Professional fundraising fees
                $this->getLatestIrsField('lessdirfndrsng', false) +  // Fundraising expenses
                //$this->getLatestIrsField('feesforsrvcinvstmgmt', false) +
                //$this->getLatestIrsField('feesforsrvcothr', false) +
                $this->getLatestIrsField('advrtpromo', false) + // Advertising and promotion
                $this->getLatestIrsField('officexpns', false) + // Office expenses
                $this->getLatestIrsField('infotech', false) + // Information technology
                $this->getLatestIrsField('royaltsexpns', false) + // Royalties
                $this->getLatestIrsField('occupancy', false) + // Occupancy
                $this->getLatestIrsField('travel', false) // Travel
                //$this->getLatestIrsField('travelofpublicoffcl', false) +
                //$this->getLatestIrsField('converconventmtng', false) +
                //$this->getLatestIrsField('interestamt', false) +
                //$this->getLatestIrsField('pymtoaffiliates', false) +
                //$this->getLatestIrsField('deprcatndepletn', false) +
                //$this->getLatestIrsField('insurance', false) +
                //$this->getLatestIrsField('othrexpnsa', false) +
                //$this->getLatestIrsField('othrexpnsb', false) +
                //$this->getLatestIrsField('othrexpnsc', false)
            );

            if (!is_numeric($left) || !$left) {
                $this->_program_services = false;
            } else {
                $this->_program_services = (($left - $right) / $left) * 100;
                if ($right > $left) {
                    $this->_program_services = 0;
                }
            }
        }
        if ($this->_program_services === false) {
            if ($raw_result) {
                return null;
            } else {
                return '-';
            }
        }
        return $raw_result ? $this->_program_services : round($this->_program_services) . '%';
    }

    /**
     * @return null|string
     */
    public function getIrsAssets() {
        return $this->getLatestIrsField('irs_eins_assets');
    }

    public function getIrsIncome() {
        return $this->getLatestIrsField('irs_eins_income');
    }

    public function getIrsRevenue() {
        return $this->getLatestIrsField('irs_eins_revenue');
    }

    public function getIrsRulingDate($format_to_dollars = false) {
        return $this->getLatestIrsField('irs_eins_ruling_date', $format_to_dollars);
    }

    public function getIrsTotalContributions() {
        return $this->getLatestIrsField('totcntrbgfts');
    }
    public function getIrsTotalContributionsPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('totcntrbgfts', false), $return_string, $return_rounded);
    }

    public function getIrsProgramServiceRevenue() {
        return $this->getLatestIrsField('totprgmrevnue');
    }

    public function getIrsInvestmentIncome() {
        return $this->getLatestIrsField('invstmntinc');
    }

    public function getIrsGrossFundraising() {
        return $this->getLatestIrsField('grsincfndrsng');
    }

    public function getIrsFundraisingExpenses() {
        return $this->getLatestIrsField('lessdirfndrsng');
    }

    public function getIrsFundraisingExpensesPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('lessdirfndrsng', false), $return_string, $return_rounded);
    }

    public function getIrsTotalFunctionalExpenses() {
        return $this->getLatestIrsField('totfuncexpns');
    }

    public function getIrsTotalFunctionalExpensesPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('totfuncexpns', false), $return_string, $return_rounded);
    }

    public function getIrsProFundraisingFees() {
        return $this->getLatestIrsField('profndraising');
    }

    public function getIrsProFundraisingFeesPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('profndraising', false), $return_string, $return_rounded);
    }

    public function getIrsTotalRevenue($format_to_dollars = true) {
        return $this->getLatestIrsField('totrevenue', $format_to_dollars);
    }

    public function getIrsCompensationOfCurrentOfficers() {
        return $this->getLatestIrsField('compnsatncurrofcr');
    }

    public function getIrsCompensationOfCurrentOfficersPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('compnsatncurrofcr', false), $return_string, $return_rounded);
    }

    public function getIrsAdvertisingAndPromotionsPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('advrtpromo', false), $return_string, $return_rounded);
    }

    public function getIrsOfficeExpensesPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('officexpns', false), $return_string, $return_rounded);
    }

    public function getIrsInformationTechnologyPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('infotech', false), $return_string, $return_rounded);
    }

    public function getIrsTravelPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('travel', false), $return_string, $return_rounded);
    }

    public function getIrsConferencesConventionsMeetingsPercentageOfRevenue($return_string = true, $return_rounded = true) {
        return $this->getPercentageOfRevenue($this->getLatestIrsField('converconventmtng', false), $return_string, $return_rounded);
    }

    public function getIrsStreetAddress() {
        return $this->getLatestIrsField('irs_eins_street_address', false);
    }

    public function getIrsCity() {
        return $this->getLatestIrsField('irs_eins_city', false);
    }

    public function getIrsState() {
        return $this->getLatestIrsField('irs_eins_state', false);
    }

    public function getIrsZipcode() {
        return $this->getLatestIrsField('irs_eins_zipcode', false);
    }

    public function getIrsStateFullName() {
        $short = $this->getIrsState();
        if (!$short) {
            return null;
        }
        /** @var CharityState $state */
        $state = CharityState::findOneBy(['name' => $short]);
        if (!$state) {
            return $short;
        }
        return $state->getFullName();
    }

    public function getSearchDesc() {
        $words = ['Nonprofit'];
        if ($this->getIrsCity()) {
            $words[] = $this->getIrsCity();
        } elseif ($this->getCityName()) {
            $words[] = $this->getCityName();
        }
        if ($this->getOverallScore() !== null) {
            $score = round($this->getOverallScore());
            $grade = '';
            if ($score >= 90) {
                $grade = ' (Very Good)';
            } elseif ($score >= 70) {
                $grade = ' (Good)';
            } elseif ($score >= 50) {
                $grade = ' (OK)';
            }
            $words[] = 'GiverScore: ' . $score . $grade;
        }
        return join(' * ', $words);
    }

    public function getIrsInfoFromDesc() {
        $tmp_rows = [];

        $rows = $this->getIrs990();

        foreach($rows as $row) {
            $tmp_rows[$row->getTaxPd()] = $row;
        }

        $rows = $this->getIrs2012990();

        foreach($rows as $row) {
            $tmp_rows[$row->getTaxPrd()] = $row;
        }

        if (!$tmp_rows) {
            return null;
        } else {
            ksort($tmp_rows);
            /** @var Irs990|Irs2012990 $row */
            $row = array_pop($tmp_rows);
            $tax_period = $row instanceof Irs990 ? $row->getTaxPd() : $row->getTaxPrd();
            return ' * ' . substr($tax_period,0,4) . ' form 990 data';
        }
    }

    static public $high_low_markers = [
        'program_services' => [
            'Very Low' => 17.963116348365,
            'Low' => 63.51355131038,
            'Normal' => 79.827108224484,
            'High' => 96.654019417921,
        ],
        'fundraising_expenses' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 0,
            'High' => 0.87,
        ],
        'Professional Fundraising Fees' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 0,
            'High' => 0,
        ],
        'Executive Compensation' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 2,
            'High' => 10,
        ],
        'Advertising and Promotion' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 0.081409141303971,
            'High' => 0.80482308525271,
        ],
        'Office Expenses' => [
            'Very Low' => 0,
            'Low' => 0.57575317269165,
            'Normal' => 1.5295324716662,
            'High' => 3.3542600896861,
        ],
        'Information Technology' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 0,
            'High' => 0.22207813017103,
        ],
        'Travel' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 0.34310188513856,
            'High' => 1.8132571125151,
        ],
        'Conferences, conventions, meetings' => [
            'Very Low' => 0,
            'Low' => 0,
            'Normal' => 0.035832321597194,
            'High' => 0.68789352214928,
        ],
    ];

    public function getHighLow($field,$value) {
        if ($value === '-' || $value === null) {
            return '-';
        }
        $markers = self::$high_low_markers;
        if (!isset($markers[$field])) {
            throw new \Exception('Invalid marker field: ' . $field);
        }
        $markers = $markers[$field];
        foreach($markers as $high_low => $mark) {
            if ($value <= $mark) {
                return $high_low;
            }
        }
        return 'Very High';
    }

    /**
     * @return CharityVolunteeringOpportunity[]
     */
    public function getVolunteeringOpportunities() {
        return CharityVolunteeringOpportunity::findBy(['charity' => $this]);
    }

    /**
     * @return integer
     */
    public function getVolunteeringOpportunitiesReviewsCount() {
        $em = \Base_Controller::$em;
        $query = $em->createQuery('SELECT COUNT(r.id) FROM \Entity\CharityVolunteeringOpportunitiesReview r WHERE r.charity = :charity');
        $query->setParameter('charity', $this);
        return $query->getSingleScalarResult();
    }

    /**
     * @param User $user
     *
     * @return CharityVolunteeringOpportunitiesReview
     */
    public function getUserReviewedVolunteeringOpportunities(User $user) {
        /** @var \Entity\CharityVolunteeringOpportunitiesReview $exists */
        $exists = CharityVolunteeringOpportunitiesReview::findOneBy(['user' => $user, 'charity' => $this]);
        return $exists;
    }

    /**
     * @param \DateTime $dt
     *
     * @return string the number of volunteering events the nonprofit has the month of $dt
     */
    public function getNrOfEventsForMonth(\DateTime $dt) {
        $dt2 = clone $dt;
        $dt->modify('first day of this month');
        $dt2->modify('last day of this month');

        $em = \Base_Controller::$em;

        $qb = $em->createQueryBuilder('events');

        $or = $qb->expr()->orx();
        $or->add($qb->expr()->between('events.start_date', ':start_from', ':start_to'));
        $or->add($qb->expr()->between('events.end_date', ':end_from', ':end_to'));

        $qb->select('count(events.id)')->from('\Entity\CharityVolunteeringOpportunity', 'events')
            ->where('events.charity = :charity')
            ->andWhere($or);

        $qb->setParameters(['charity' => $this, 'start_from' => $dt, 'start_to' => $dt2, 'end_from' => $dt, 'end_to' => $dt2]);

        $q = $qb->getQuery();

        $sql = $q->getSQL();

        $nr = $q->getSingleScalarResult();
        return !$nr ? 'no events' : ($nr == 1 ? '1 event' : $nr . ' events');
    }

    public function hasCover() {
        return is_file(__DIR__.'/../../../images/charity_header_pics/'.$this->id);
    }

    public function getCover() {
        return '/images/charity_header_pics/'.$this->id;
    }

    public function deleteCover() {
        if (!@unlink(__DIR__.'/../../../images/charity_header_pics/'.$this->id)) {
            throw new \Exception('failed deleting charity cover: '.__DIR__.'/../../../images/charity_header_pics/'.$this->id);
        }
    }

    public function hasLogo() {
        return is_file(__DIR__.'/../../../images/charity_logo/'.$this->id);
    }

    public function getLogo() {
        if ($this->hasLogo()) {
            return '/images/charity_logo/' . $this->id;
        }
        return '';
    }

    public function deleteLogo() {
        if (!@unlink(__DIR__.'/../../../images/charity_logo/'.$this->id)) {
            throw new \Exception('failed deleting charity logo: '.__DIR__.'/../../../images/charity_logo/'.$this->id);
        }
    }
}
