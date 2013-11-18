<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CrdCard
 *
 * @ORM\Table(name="excel_tomo")
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
     * @var string
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var string $file
     * @Assert\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid File")
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
    
    /**
     *
     * @var integer
     * @ORM\Column(name="tomo", type="integer", nullable=true)
     */
    private $tomo;
    
    
    public function getFullWebPath(){
        return null === $this->filename ? null : $this->getWebPath() . $this->filename;
    }
    
    public function getWebPath() {
         return __DIR__ . '/../../../../../../../../web/upload/tomos/';
    }

    public function getFullPath() {
        return null === $this->filename ? null : $this->getUploadRootDir() . $this->filename;
    }

    public function getFullFilePath() {
        return null === $this->file ? null : $this->getUploadRootDir() . $this->file;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir() . "tomos/"; #sthash.diiWiQjf.dpuf;
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
        //echo $this->file;
        if (null === $this->file) {
            return;
        }
        if (is_string($this->file)) {
            $this->setFile($this->file);
            $this->setFilename($this->getFile());
            return;
        }
        if (!$this->id) {
            $this->file->move($this->getTmpUploadRootDir(), $this->file->getClientOriginalName());
        } else {
            $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        }
        $this->setFile($this->file->getClientOriginalName());
        $this->setFilename($this->getFile());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function moveFile() {
        if (null === $this->file) {
            return;
        }
        if (!is_dir($this->getUploadRootDir())) {
            //echo $this->getUploadRootDir();
            mkdir($this->getUploadRootDir());
        }
        try {
            copy($this->getTmpUploadRootDir() . $this->file, $this->getFullFilePath());
            unlink($this->getTmpUploadRootDir() . $this->file);
        } catch (\Exception $e) {
            
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeFile() {
        if(is_dir($this->getFullPath())){
            unlink($this->getFullPath());
        }
        //rmdir($this->getUploadRootDir());
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ExcelTomo
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ExcelTomo
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ExcelTomo
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return ExcelTomo
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return ExcelTomo
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return ExcelTomo
     */
    public function setFilename($filename) {
        $this->filename = $filename;
//        $this->setFile($filename);
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename() {
        return $this->filename;
    }


    /**
     * Set tomo
     *
     * @param integer $tomo
     * @return ExcelTomo
     */
    public function setTomo($tomo)
    {
        $this->tomo = $tomo;

        return $this;
    }

    /**
     * Get tomo
     *
     * @return integer 
     */
    public function getTomo()
    {
        return $this->tomo;
    }
}
