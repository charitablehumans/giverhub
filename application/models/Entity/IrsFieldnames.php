<?php

namespace Entity;

/**
 * IrsFieldnames
 *
 * @Table(name="irs_fieldnames", indexes={@Index(name="irs_fieldnames_extracttype_idx", columns={"extracttype"}), @Index(name="irs_fieldnames_fieldname_idx", columns={"fieldname"}), @Index(name="irs_fieldnames_year_idx", columns={"year"})})
 * @Entity
 */
class IrsFieldnames extends BaseEntity {
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
     * @Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @Column(name="extracttype", type="string", length=9, nullable=true)
     */
    private $extracttype;

    /**
     * @var string
     *
     * @Column(name="fieldname", type="string", length=60, nullable=true)
     */
    private $fieldname;

    /**
     * @var string
     *
     * @Column(name="descrip", type="string", length=255, nullable=true)
     */
    private $descrip;

    /**
     * @var string
     *
     * @Column(name="type", type="string", length=60, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @Column(name="form", type="string", length=60, nullable=true)
     */
    private $form;

    /**
     * @param string $descrip
     */
    public function setDescrip($descrip)
    {
        $this->descrip = $descrip;
    }

    /**
     * @return string
     */
    public function getDescrip()
    {
        return $this->descrip;
    }

    /**
     * @param string $extracttype
     */
    public function setExtracttype($extracttype)
    {
        $this->extracttype = $extracttype;
    }

    /**
     * @return string
     */
    public function getExtracttype()
    {
        return $this->extracttype;
    }

    /**
     * @param string $fieldname
     */
    public function setFieldname($fieldname)
    {
        $this->fieldname = $fieldname;
    }

    /**
     * @return string
     */
    public function getFieldname()
    {
        return $this->fieldname;
    }

    /**
     * @param string $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

}
