<?php
	namespace Entity;

	/**
	 * @Entity
	 * @Table(name="UrlCache")
	 */
	class UrlCache extends BaseEntity {
		/**
		 * @Id
		 * @GeneratedValue(strategy="AUTO")
		 * @Column(type="integer")
		 */
		protected $id;

		/** @Column(type="string") **/
		protected $url;

		/** @Column(type="text") **/
		protected $data;

        /** @Column(type="integer") **/
        protected $version = 0;


		public function __construct($url, $data) {
			$this->url = $url;
			$this->data = $data;
		}

		static public function load(\Doctrine\ORM\EntityManager $em, $url) {
			$repo = $em->getRepository('Entity\UrlCache');
			$urlCache = $repo->findOneBy(array('url' => $url));
			if ($urlCache === null) {
				$data = file_get_contents($url);
				$urlCache = new static($url, $data);
				$em->persist($urlCache);
				$em->flush($urlCache);
			}

			return $urlCache;

		}

		static public function loadJSON(\Doctrine\ORM\EntityManager $em, $url) {
			$urlCache = static::load($em, $url);
			return json_decode($urlCache->data, true);
		}


        public function getData() {
            return $this->data;
        }
	}
