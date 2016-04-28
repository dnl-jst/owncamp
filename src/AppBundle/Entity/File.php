<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="files")
 */
class File
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $filename;

    /**
     * @ORM\Column(type="blob")
     */
    protected $valueBlob;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $valueBlobType;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $valueBlobExt;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="files")
     * @ORM\JoinColumn(name="taskId", referencedColumnName="id")
     */
    protected $task;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set valueBlob
     *
     * @param string $valueBlob
     *
     * @return File
     */
    public function setValueBlob($valueBlob)
    {
        $this->valueBlob = $valueBlob;

        return $this;
    }

    /**
     * Get valueBlob
     *
     * @return string
     */
    public function getValueBlob()
    {
        return $this->valueBlob;
    }

    /**
     * Set valueBlobType
     *
     * @param string $valueBlobType
     *
     * @return File
     */
    public function setValueBlobType($valueBlobType)
    {
        $this->valueBlobType = $valueBlobType;

        return $this;
    }

    /**
     * Get valueBlobType
     *
     * @return string
     */
    public function getValueBlobType()
    {
        return $this->valueBlobType;
    }

    /**
     * Set valueBlobExt
     *
     * @param string $valueBlobExt
     *
     * @return File
     */
    public function setValueBlobExt($valueBlobExt)
    {
        $this->valueBlobExt = $valueBlobExt;

        return $this;
    }

    /**
     * Get valueBlobExt
     *
     * @return string
     */
    public function getValueBlobExt()
    {
        return $this->valueBlobExt;
    }

    /**
     * Set task
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return File
     */
    public function setTask(\AppBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \AppBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }
}
