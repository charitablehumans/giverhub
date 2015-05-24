<?php
namespace Entity;

/**
 * TaskQueue
 *
 * @Table(name="task_queue")
 * @Entity @HasLifecycleCallbacks
 */
class TaskQueue extends BaseEntity {

    /** @PrePersist */
    public function onPrePersist()
    {
        $this->created_at = date('Y-m-d H:i:s');
    }

    public static $statuses = ['waiting','running','completed','failed'];

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
     * @Column(name="`command`", type="string", nullable=false)
     */
    private $command;

    /**
     * @var string
     *
     * @Column(name="`created_at`", type="string", nullable=false)
     */
    private $created_at;

    /**
     * @var string
     *
     * @Column(name="`started_at`", type="string", nullable=true)
     */
    private $started_at;

    /**
     * @var string
     *
     * @Column(name="`stopped_at`", type="string", nullable=true)
     */
    private $stopped_at;

    /**
     * @var string
     *
     * @Column(name="`status`", type="string", nullable=false)
     */
    private $status = 'waiting';

    /**
     * @var string
     *
     * @Column(name="`output`", type="string", nullable=true)
     */
    private $output;

    /**
     * @var integer
     *
     * @Column(name="`pid`", type="integer", nullable=true)
     */
    private $pid;

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param int $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param string $started_at
     */
    public function setStartedAt($started_at)
    {
        $this->started_at = $started_at;
    }

    /**
     * @return string
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $stopped_at
     */
    public function setStoppedAt($stopped_at)
    {
        $this->stopped_at = $stopped_at;
    }

    /**
     * @return string
     */
    public function getStoppedAt()
    {
        return $this->stopped_at;
    }


}
