<?php

namespace Entity;

/**
 * FAQ
 *
 * @Table(name="faqs")
 * @Entity
 */
class FAQ extends BaseEntity {

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
     * @Column(name="fq_ques", type="string", nullable=false)
     */
    private $fq_ques;

    /**
     * @var string
     *
     * @Column(name="fq_ans", type="string", nullable=false)
     */
    private $fq_ans;

    /**
     * @var string
     *
     * @Column(name="fq_filter", type="string", nullable=true)
     */
    private $fq_filter;

    /**
     * @var integer
     *
     * @Column(name="fq_order", type="integer", nullable=true)
     */
    private $fq_order;

    /**
     * @var string
     *
     * @Column(name="create_date", type="string", nullable=false)
     */
    private $create_date;

    /**
     * @var integer
     *
     * @Column(name="is_active", type="integer", nullable=false)
     */
    private $is_active = 1;

    /**
     * @param int $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param string $create_date
     */
    public function setCreateDate($create_date)
    {
        $this->create_date = $create_date;
    }

    /**
     * @return string
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * @param \DateTime $create_date
     */
    public function setCreateDateDt(\DateTime $create_date)
    {
        $this->create_date = $create_date->format('Y-m-d');
    }

    /**
     * @return \DateTime
     */
    public function getCreateDateDt()
    {
        return new \DateTime($this->create_date);
    }

    /**
     * @param string $fq_ans
     */
    public function setFqAns($fq_ans)
    {
        $this->fq_ans = $fq_ans;
    }

    /**
     * @return string
     */
    public function getFqAns()
    {
        return $this->fq_ans;
    }

    /**
     * @param string $fq_filter
     */
    public function setFqFilter($fq_filter)
    {
        $this->fq_filter = $fq_filter;
    }

    /**
     * @return string
     */
    public function getFqFilter()
    {
        return $this->fq_filter;
    }

    /**
     * @param string $fq_ques
     */
    public function setFqQues($fq_ques)
    {
        $this->fq_ques = $fq_ques;
    }

    /**
     * @return string
     */
    public function getFqQues()
    {
        return $this->fq_ques;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setFqOrder($fq_order)
    {
        $this->fq_order = $fq_order;
    }

    public function getFqOrder()
    {
        return $this->fq_order;
    }

}