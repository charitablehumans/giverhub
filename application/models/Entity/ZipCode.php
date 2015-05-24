<?php
	namespace Entity;

	use Doctrine\ORM\EntityManager;

	/**
	 * ZipCode
	 *
	 * @Table(name="ZipCode")
	 * @Entity
	 */
	class ZipCode extends BaseEntity
	{
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
		 * @Column(name="zip", type="integer", nullable=false)
		 */
		private $zip;

		/**
		 * @var string
		 *
		 * @Column(name="city", type="string", length=50, nullable=false)
		 */
		private $city;

		/**
		 * @var string
		 *
		 * @Column(name="state", type="string", length=2, nullable=false)
		 */
		private $state;

		/**
		 * @var string
		 *
		 * @Column(name="full_state", type="string", length=50, nullable=false)
		 */
		private $fullState;

		/**
		 * @var integer
		 *
		 * @Column(name="cityId", type="integer", nullable=false)
		 */
		private $cityId;

		/**
		 * @var integer
		 *
		 * @Column(name="stateId", type="integer", nullable=false)
		 */
		private $stateId;

		public function __construct() {
		}


		public function getId() {
			return $this->id;
		}

		public function getCity() {
			return $this->city;
		}

		public function getState() {
			return $this->state;
		}

		public function getCityId() {
			return $this->cityId;
		}

		public function getStateId() {
			return $this->stateId;
		}

		public function setCityId($cityId) {
			$this->cityId = $cityId;
		}

		public function setStateId($stateId) {
			$this->stateId = $stateId;
		}

		public function getFullState() {
			return $this->fullState;
		}
	}
