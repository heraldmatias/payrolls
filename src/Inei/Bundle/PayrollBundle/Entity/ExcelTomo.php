<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CrdCard
 *
 * @ORM\Table(name="crd_card")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\ExcelTomoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ExcelTomo {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=90, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    /**
     * @var string $file
     * @Assert\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid File")
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getFullFilePath() {
        return null === $this->file ? null : $this->getUploadRootDir(). $this->file;
    }
 
    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir()."tomos/";#sthash.diiWiQjf.dpuf;
    }
 
    protected function getTmpUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../../web/upload/';
    }
 
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadFile() {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }
        if(!$this->id){
            $this->file->move($this->getTmpUploadRootDir(), $this->file->getClientOriginalName());
        }else{
            $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        }
        $this->setFile($this->file->getClientOriginalName());
    }
 
    /**
     * @ORM\PostPersist()
     */
    public function moveFile()
    {
        if (null === $this->file) {
            return;
        }
        if(!is_dir($this->getUploadRootDir())){
            //echo $this->getUploadRootDir();
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->file, $this->getFullFilePath());
        unlink($this->getTmpUploadRootDir().$this->file);
    }
 
    /**
     * @ORM\PreRemove()
     */
    public function removeFile()
    {
        unlink($this->getFullFilePath());
        rmdir($this->getUploadRootDir());
    }


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
     * Set title
     *
     * @param string $title
     * @return ExcelTomo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ExcelTomo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ExcelTomo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ExcelTomo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return ExcelTomo
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }
}
