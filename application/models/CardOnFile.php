<?php
class CardOnFile {
	public $COFId;
	public $CardType;
    public $_stdClass;

	public function __construct(stdClass $cof) {
		if (!isset($cof->COFId)) {
			throw new Exception('COFId not set');
		}
		if (!isset($cof->COFId)) {
			throw new Exception('CardType not set');
		}
        $this->_stdClass = $cof;

		$this->COFId = $cof->COFId;
		$this->CardType = $cof->CardType;
	}
}