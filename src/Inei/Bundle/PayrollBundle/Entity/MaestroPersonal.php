<?php

namespace Inei\Bundle\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MaestroPersonal
 *
 * @ORM\Table(name="MAESTRO_PERSONAL")
 * @ORM\Entity(repositoryClass="Inei\Bundle\PayrollBundle\Repository\MaestroPersonalRepository")
 */
class MaestroPersonal
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODI_EMPL_PER", type="string", length=8, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="MAESTRO_PERSONAL_CODI_EMPL_PER_seq", allocationSize=1, initialValue=1)
     */
    private $codiEmplPer;

    /**
     * @var string
     *
     * @ORM\Column(name="APE_PAT_PER", type="string", length=35, nullable=false)
     */
    private $apePatPer;

    /**
     * @var string
     *
     * @ORM\Column(name="APE_MAT_PER", type="string", length=35, nullable=false)
     */
    private $apeMatPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_EMP_PER", type="string", length=35, nullable=false)
     */
    private $nomEmpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_CORT_PER", type="string", length=70, nullable=false)
     */
    private $nombCortPer;

    /**
     * @var string
     *
     * @ORM\Column(name="DIR_EMP_PER", type="string", length=150, nullable=false)
     */
    private $dirEmpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_DEPA_DPT", type="string", length=2, nullable=false)
     */
    private $codiDepaDpt;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_PROV_TPR", type="string", length=2, nullable=false)
     */
    private $codiProvTpr;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_DIST_TDI", type="string", length=2, nullable=false)
     */
    private $codiDistTdi;

    /**
     * @var string
     *
     * @ORM\Column(name="NUM_TEL_PER", type="string", length=10, nullable=false)
     */
    private $numTelPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FEC_ING_PER", type="date", nullable=false)
     */
    private $fecIngPer;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_PLAN_TPL", type="string", length=2, nullable=false)
     */
    private $tipoPlanTpl;

    /**
     * @var string
     *
     * @ORM\Column(name="EST_CIV_PER", type="string", length=1, nullable=false)
     */
    private $estCivPer;

    /**
     * @var string
     *
     * @ORM\Column(name="SEX_EMP_PER", type="string", length=1, nullable=false)
     */
    private $sexEmpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="GRA_INS_PER", type="string", length=1, nullable=false)
     */
    private $graInsPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FEC_NAC_PER", type="date", nullable=false)
     */
    private $fecNacPer;

    /**
     * @var string
     *
     * @ORM\Column(name="PAIS_NACI_TPA", type="string", length=4, nullable=false)
     */
    private $paisNaciTpa;

    /**
     * @var string
     *
     * @ORM\Column(name="DEPA_NACI_DPT", type="string", length=2, nullable=false)
     */
    private $depaNaciDpt;

    /**
     * @var string
     *
     * @ORM\Column(name="PROV_NACI_TPR", type="string", length=2, nullable=false)
     */
    private $provNaciTpr;

    /**
     * @var string
     *
     * @ORM\Column(name="DIST_NACI_TDI", type="string", length=2, nullable=false)
     */
    private $distNaciTdi;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_DEPE_TDE", type="string", length=4, nullable=false)
     */
    private $codiDepeTde;

    /**
     * @var string
     *
     * @ORM\Column(name="UBIC_FISI_TDE", type="string", length=4, nullable=false)
     */
    private $ubicFisiTde;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_NIVE_TNI", type="string", length=3, nullable=false)
     */
    private $codiNiveTni;

    /**
     * @var string
     *
     * @ORM\Column(name="NIVE_ENC_TNI", type="string", length=3, nullable=false)
     */
    private $niveEncTni;

    /**
     * @var string
     *
     * @ORM\Column(name="ESTA_TRAB_PER", type="string", length=1, nullable=false)
     */
    private $estaTrabPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CON_TRA_PER", type="string", length=1, nullable=false)
     */
    private $conTraPer;

    /**
     * @var string
     *
     * @ORM\Column(name="REG_LAB_PER", type="string", length=8, nullable=false)
     */
    private $regLabPer;

    /**
     * @var string
     *
     * @ORM\Column(name="REG_PEN_PER", type="string", length=8, nullable=false)
     */
    private $regPenPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_CARG_TCA", type="string", length=8, nullable=false)
     */
    private $codiCargTca;

    /**
     * @var string
     *
     * @ORM\Column(name="CARG_ENC_TCA", type="string", length=8, nullable=false)
     */
    private $cargEncTca;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_AFP_PER", type="string", length=1, nullable=false)
     */
    private $flagAfpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_AFP", type="string", length=2, nullable=false)
     */
    private $codiAfp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECH_AFP_PER", type="date", nullable=false)
     */
    private $fechAfpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_AFP_PER", type="string", length=15, nullable=false)
     */
    private $codiAfpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBR_ELEC_PER", type="string", length=8, nullable=false)
     */
    private $librElecPer;

    /**
     * @var string
     *
     * @ORM\Column(name="LIBR_MILI_PER", type="string", length=10, nullable=false)
     */
    private $librMiliPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_IPSS_PER", type="string", length=16, nullable=false)
     */
    private $codiIpssPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NUME_BREV_PER", type="string", length=15, nullable=false)
     */
    private $numeBrevPer;

    /**
     * @var string
     *
     * @ORM\Column(name="GRU_SANG_PER", type="string", length=4, nullable=false)
     */
    private $gruSangPer;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_MON_SUEL_PER", type="string", length=2, nullable=false)
     */
    private $codMonSuelPer;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_SUEL_PER", type="string", length=1, nullable=false)
     */
    private $flagSuelPer;

    /**
     * @var string
     *
     * @ORM\Column(name="BANC_SUEL_TBC", type="string", length=2, nullable=false)
     */
    private $bancSuelTbc;

    /**
     * @var string
     *
     * @ORM\Column(name="SUEL_CTA_PER", type="string", length=18, nullable=false)
     */
    private $suelCtaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="BANC_CTS_TBC", type="string", length=2, nullable=false)
     */
    private $bancCtsTbc;

    /**
     * @var string
     *
     * @ORM\Column(name="CTS_CTA_PER", type="string", length=18, nullable=false)
     */
    private $ctsCtaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_MON_CTS_PER", type="string", length=2, nullable=false)
     */
    private $codMonCtsPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NUME_PLAZ_PER", type="string", length=8, nullable=false)
     */
    private $numePlazPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECH_RNOM_PER", type="date", nullable=false)
     */
    private $fechRnomPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NUME_RNOM_PER", type="string", length=10, nullable=false)
     */
    private $numeRnomPer;

    /**
     * @var string
     *
     * @ORM\Column(name="SEDE_ACTU_PER", type="string", length=3, nullable=false)
     */
    private $sedeActuPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FING_ADM_PER", type="date", nullable=false)
     */
    private $fingAdmPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FING_CARRP_PER", type="date", nullable=false)
     */
    private $fingCarrpPer;

    /**
     * @var string
     *
     * @ORM\Column(name="OTRO_DOCU_PER", type="string", length=15, nullable=false)
     */
    private $otroDocuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="OBSERVA_PER", type="string", length=100, nullable=false)
     */
    private $observaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="CARGO_REMU_PER", type="string", length=8, nullable=false)
     */
    private $cargoRemuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NIVEL_REMU_PER", type="string", length=3, nullable=false)
     */
    private $nivelRemuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="PLAZA_REMU_PER", type="string", length=8, nullable=false)
     */
    private $plazaRemuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="DEPE_REMU_PER", type="string", length=4, nullable=false)
     */
    private $depeRemuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="OBSER_REMU_PER", type="string", length=100, nullable=false)
     */
    private $obserRemuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_CUEN_PER", type="string", length=1, nullable=false)
     */
    private $tipoCuenPer;

    /**
     * @var string
     *
     * @ORM\Column(name="SEGU_MEDI_PER", type="string", length=1, nullable=false)
     */
    private $seguMediPer;

    /**
     * @var string
     *
     * @ORM\Column(name="SEDE_REMU_PER", type="string", length=3, nullable=false)
     */
    private $sedeRemuPer;

    /**
     * @var string
     *
     * @ORM\Column(name="APEP_SOLT_PER", type="string", length=20, nullable=false)
     */
    private $apepSoltPer;

    /**
     * @var string
     *
     * @ORM\Column(name="APEM_SOLT_PER", type="string", length=20, nullable=false)
     */
    private $apemSoltPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_SOLT_ER", type="string", length=30, nullable=false)
     */
    private $nombSoltEr;

    /**
     * @var string
     *
     * @ORM\Column(name="CESA_SOBR_PER", type="string", length=1, nullable=false)
     */
    private $cesaSobrPer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FEC_CESE_PER", type="date", nullable=false)
     */
    private $fecCesePer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_TITU_CES", type="string", length=70, nullable=false)
     */
    private $nombTituCes;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_COBR_CES", type="string", length=70, nullable=false)
     */
    private $nombCobrCes;

    /**
     * @var string
     *
     * @ORM\Column(name="ENCA_PLAZ_PER", type="string", length=8, nullable=false)
     */
    private $encaPlazPer;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_PROPUESTA", type="string", length=1, nullable=false)
     */
    private $flagPropuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="META_PROP", type="string", length=4, nullable=false)
     */
    private $metaProp;

    /**
     * @var string
     *
     * @ORM\Column(name="FTEFTO", type="string", length=4, nullable=false)
     */
    private $ftefto;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_PROY_PIN", type="string", length=4, nullable=false)
     */
    private $codiProyPin;

    /**
     * @var string
     *
     * @ORM\Column(name="CODRIE", type="string", length=2, nullable=false)
     */
    private $codrie;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_ALMACEN", type="string", length=1, nullable=false)
     */
    private $flagAlmacen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECH_INI_RECU", type="date", nullable=false)
     */
    private $fechIniRecu;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_RECURRENTE", type="string", length=1, nullable=false)
     */
    private $flagRecurrente;

    /**
     * @var string
     *
     * @ORM\Column(name="OBS_RECU", type="string", length=200, nullable=false)
     */
    private $obsRecu;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_FOTOC_PER", type="string", length=1, nullable=false)
     */
    private $flagFotocPer;

    /**
     * @var string
     *
     * @ORM\Column(name="IND_VALIDO", type="string", length=1, nullable=false)
     */
    private $indValido;

    /**
     * @var string
     *
     * @ORM\Column(name="BIO_SUSERID", type="string", length=8, nullable=false)
     */
    private $bioSuserid;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_VIA_PER", type="string", length=2, nullable=false)
     */
    private $tipoViaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_VIA_PER", type="string", length=50, nullable=false)
     */
    private $nombViaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NUME_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $numeDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="KM_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $kmDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="MZ_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $mzDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="INTE_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $inteDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="DPTO_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $dptoDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="LOTE_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $loteDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="PISO_DIRE_PER", type="string", length=6, nullable=false)
     */
    private $pisoDirePer;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_ZONA_PER", type="string", length=2, nullable=false)
     */
    private $tipoZonaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_ZONA_PER", type="string", length=50, nullable=false)
     */
    private $nombZonaPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_REFDOM_PER", type="string", length=150, nullable=false)
     */
    private $nombRefdomPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NUM_RUC_PER", type="string", length=12, nullable=false)
     */
    private $numRucPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NUM_CEL_PER", type="string", length=15, nullable=false)
     */
    private $numCelPer;

    /**
     * @var string
     *
     * @ORM\Column(name="EMAIL_PER", type="string", length=50, nullable=false)
     */
    private $emailPer;

    /**
     * @var string
     *
     * @ORM\Column(name="EMAIL_INSTI", type="string", length=50, nullable=false)
     */
    private $emailInsti;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_FUNC_TCA", type="string", length=8, nullable=false)
     */
    private $codiFuncTca;

    /**
     * @var string
     *
     * @ORM\Column(name="CARG_ENC_PER", type="string", length=3, nullable=false)
     */
    private $cargEncPer;

    /**
     * @var string
     *
     * @ORM\Column(name="MARCA", type="string", length=1, nullable=false)
     */
    private $marca;

    /**
     * @var string
     *
     * @ORM\Column(name="FLAG_CUENTA", type="string", length=1, nullable=false)
     */
    private $flagCuenta;

    /**
     * @var string
     *
     * @ORM\Column(name="CODI_UBIC_OFIC", type="string", length=3, nullable=false)
     */
    private $codiUbicOfic;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_PRIM_PER", type="string", length=25, nullable=false)
     */
    private $nombPrimPer;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMB_SEGU_PER", type="string", length=25, nullable=false)
     */
    private $nombSeguPer;

    /**
     * @var string
     *
     * @ORM\Column(name="TIPO_COMISION_AFP", type="string", length=2, nullable=false)
     */
    private $tipoComisionAfp;



    /**
     * Get codiEmplPer
     *
     * @return string 
     */
    public function getCodiEmplPer()
    {
        return $this->codiEmplPer;
    }

    /**
     * Set apePatPer
     *
     * @param string $apePatPer
     * @return MaestroPersonal
     */
    public function setApePatPer($apePatPer)
    {
        $this->apePatPer = $apePatPer;
    
        return $this;
    }

    /**
     * Get apePatPer
     *
     * @return string 
     */
    public function getApePatPer()
    {
        return $this->apePatPer;
    }

    /**
     * Set apeMatPer
     *
     * @param string $apeMatPer
     * @return MaestroPersonal
     */
    public function setApeMatPer($apeMatPer)
    {
        $this->apeMatPer = $apeMatPer;
    
        return $this;
    }

    /**
     * Get apeMatPer
     *
     * @return string 
     */
    public function getApeMatPer()
    {
        return $this->apeMatPer;
    }

    /**
     * Set nomEmpPer
     *
     * @param string $nomEmpPer
     * @return MaestroPersonal
     */
    public function setNomEmpPer($nomEmpPer)
    {
        $this->nomEmpPer = $nomEmpPer;
    
        return $this;
    }

    /**
     * Get nomEmpPer
     *
     * @return string 
     */
    public function getNomEmpPer()
    {
        return $this->nomEmpPer;
    }

    /**
     * Set nombCortPer
     *
     * @param string $nombCortPer
     * @return MaestroPersonal
     */
    public function setNombCortPer($nombCortPer)
    {
        $this->nombCortPer = $nombCortPer;
    
        return $this;
    }

    /**
     * Get nombCortPer
     *
     * @return string 
     */
    public function getNombCortPer()
    {
        return $this->nombCortPer;
    }

    /**
     * Set dirEmpPer
     *
     * @param string $dirEmpPer
     * @return MaestroPersonal
     */
    public function setDirEmpPer($dirEmpPer)
    {
        $this->dirEmpPer = $dirEmpPer;
    
        return $this;
    }

    /**
     * Get dirEmpPer
     *
     * @return string 
     */
    public function getDirEmpPer()
    {
        return $this->dirEmpPer;
    }

    /**
     * Set codiDepaDpt
     *
     * @param string $codiDepaDpt
     * @return MaestroPersonal
     */
    public function setCodiDepaDpt($codiDepaDpt)
    {
        $this->codiDepaDpt = $codiDepaDpt;
    
        return $this;
    }

    /**
     * Get codiDepaDpt
     *
     * @return string 
     */
    public function getCodiDepaDpt()
    {
        return $this->codiDepaDpt;
    }

    /**
     * Set codiProvTpr
     *
     * @param string $codiProvTpr
     * @return MaestroPersonal
     */
    public function setCodiProvTpr($codiProvTpr)
    {
        $this->codiProvTpr = $codiProvTpr;
    
        return $this;
    }

    /**
     * Get codiProvTpr
     *
     * @return string 
     */
    public function getCodiProvTpr()
    {
        return $this->codiProvTpr;
    }

    /**
     * Set codiDistTdi
     *
     * @param string $codiDistTdi
     * @return MaestroPersonal
     */
    public function setCodiDistTdi($codiDistTdi)
    {
        $this->codiDistTdi = $codiDistTdi;
    
        return $this;
    }

    /**
     * Get codiDistTdi
     *
     * @return string 
     */
    public function getCodiDistTdi()
    {
        return $this->codiDistTdi;
    }

    /**
     * Set numTelPer
     *
     * @param string $numTelPer
     * @return MaestroPersonal
     */
    public function setNumTelPer($numTelPer)
    {
        $this->numTelPer = $numTelPer;
    
        return $this;
    }

    /**
     * Get numTelPer
     *
     * @return string 
     */
    public function getNumTelPer()
    {
        return $this->numTelPer;
    }

    /**
     * Set fecIngPer
     *
     * @param \DateTime $fecIngPer
     * @return MaestroPersonal
     */
    public function setFecIngPer($fecIngPer)
    {
        $this->fecIngPer = $fecIngPer;
    
        return $this;
    }

    /**
     * Get fecIngPer
     *
     * @return \DateTime 
     */
    public function getFecIngPer()
    {
        return $this->fecIngPer;
    }

    /**
     * Set tipoPlanTpl
     *
     * @param string $tipoPlanTpl
     * @return MaestroPersonal
     */
    public function setTipoPlanTpl($tipoPlanTpl)
    {
        $this->tipoPlanTpl = $tipoPlanTpl;
    
        return $this;
    }

    /**
     * Get tipoPlanTpl
     *
     * @return string 
     */
    public function getTipoPlanTpl()
    {
        return $this->tipoPlanTpl;
    }

    /**
     * Set estCivPer
     *
     * @param string $estCivPer
     * @return MaestroPersonal
     */
    public function setEstCivPer($estCivPer)
    {
        $this->estCivPer = $estCivPer;
    
        return $this;
    }

    /**
     * Get estCivPer
     *
     * @return string 
     */
    public function getEstCivPer()
    {
        return $this->estCivPer;
    }

    /**
     * Set sexEmpPer
     *
     * @param string $sexEmpPer
     * @return MaestroPersonal
     */
    public function setSexEmpPer($sexEmpPer)
    {
        $this->sexEmpPer = $sexEmpPer;
    
        return $this;
    }

    /**
     * Get sexEmpPer
     *
     * @return string 
     */
    public function getSexEmpPer()
    {
        return $this->sexEmpPer;
    }

    /**
     * Set graInsPer
     *
     * @param string $graInsPer
     * @return MaestroPersonal
     */
    public function setGraInsPer($graInsPer)
    {
        $this->graInsPer = $graInsPer;
    
        return $this;
    }

    /**
     * Get graInsPer
     *
     * @return string 
     */
    public function getGraInsPer()
    {
        return $this->graInsPer;
    }

    /**
     * Set fecNacPer
     *
     * @param \DateTime $fecNacPer
     * @return MaestroPersonal
     */
    public function setFecNacPer($fecNacPer)
    {
        $this->fecNacPer = $fecNacPer;
    
        return $this;
    }

    /**
     * Get fecNacPer
     *
     * @return \DateTime 
     */
    public function getFecNacPer()
    {
        return $this->fecNacPer;
    }

    /**
     * Set paisNaciTpa
     *
     * @param string $paisNaciTpa
     * @return MaestroPersonal
     */
    public function setPaisNaciTpa($paisNaciTpa)
    {
        $this->paisNaciTpa = $paisNaciTpa;
    
        return $this;
    }

    /**
     * Get paisNaciTpa
     *
     * @return string 
     */
    public function getPaisNaciTpa()
    {
        return $this->paisNaciTpa;
    }

    /**
     * Set depaNaciDpt
     *
     * @param string $depaNaciDpt
     * @return MaestroPersonal
     */
    public function setDepaNaciDpt($depaNaciDpt)
    {
        $this->depaNaciDpt = $depaNaciDpt;
    
        return $this;
    }

    /**
     * Get depaNaciDpt
     *
     * @return string 
     */
    public function getDepaNaciDpt()
    {
        return $this->depaNaciDpt;
    }

    /**
     * Set provNaciTpr
     *
     * @param string $provNaciTpr
     * @return MaestroPersonal
     */
    public function setProvNaciTpr($provNaciTpr)
    {
        $this->provNaciTpr = $provNaciTpr;
    
        return $this;
    }

    /**
     * Get provNaciTpr
     *
     * @return string 
     */
    public function getProvNaciTpr()
    {
        return $this->provNaciTpr;
    }

    /**
     * Set distNaciTdi
     *
     * @param string $distNaciTdi
     * @return MaestroPersonal
     */
    public function setDistNaciTdi($distNaciTdi)
    {
        $this->distNaciTdi = $distNaciTdi;
    
        return $this;
    }

    /**
     * Get distNaciTdi
     *
     * @return string 
     */
    public function getDistNaciTdi()
    {
        return $this->distNaciTdi;
    }

    /**
     * Set codiDepeTde
     *
     * @param string $codiDepeTde
     * @return MaestroPersonal
     */
    public function setCodiDepeTde($codiDepeTde)
    {
        $this->codiDepeTde = $codiDepeTde;
    
        return $this;
    }

    /**
     * Get codiDepeTde
     *
     * @return string 
     */
    public function getCodiDepeTde()
    {
        return $this->codiDepeTde;
    }

    /**
     * Set ubicFisiTde
     *
     * @param string $ubicFisiTde
     * @return MaestroPersonal
     */
    public function setUbicFisiTde($ubicFisiTde)
    {
        $this->ubicFisiTde = $ubicFisiTde;
    
        return $this;
    }

    /**
     * Get ubicFisiTde
     *
     * @return string 
     */
    public function getUbicFisiTde()
    {
        return $this->ubicFisiTde;
    }

    /**
     * Set codiNiveTni
     *
     * @param string $codiNiveTni
     * @return MaestroPersonal
     */
    public function setCodiNiveTni($codiNiveTni)
    {
        $this->codiNiveTni = $codiNiveTni;
    
        return $this;
    }

    /**
     * Get codiNiveTni
     *
     * @return string 
     */
    public function getCodiNiveTni()
    {
        return $this->codiNiveTni;
    }

    /**
     * Set niveEncTni
     *
     * @param string $niveEncTni
     * @return MaestroPersonal
     */
    public function setNiveEncTni($niveEncTni)
    {
        $this->niveEncTni = $niveEncTni;
    
        return $this;
    }

    /**
     * Get niveEncTni
     *
     * @return string 
     */
    public function getNiveEncTni()
    {
        return $this->niveEncTni;
    }

    /**
     * Set estaTrabPer
     *
     * @param string $estaTrabPer
     * @return MaestroPersonal
     */
    public function setEstaTrabPer($estaTrabPer)
    {
        $this->estaTrabPer = $estaTrabPer;
    
        return $this;
    }

    /**
     * Get estaTrabPer
     *
     * @return string 
     */
    public function getEstaTrabPer()
    {
        return $this->estaTrabPer;
    }

    /**
     * Set conTraPer
     *
     * @param string $conTraPer
     * @return MaestroPersonal
     */
    public function setConTraPer($conTraPer)
    {
        $this->conTraPer = $conTraPer;
    
        return $this;
    }

    /**
     * Get conTraPer
     *
     * @return string 
     */
    public function getConTraPer()
    {
        return $this->conTraPer;
    }

    /**
     * Set regLabPer
     *
     * @param string $regLabPer
     * @return MaestroPersonal
     */
    public function setRegLabPer($regLabPer)
    {
        $this->regLabPer = $regLabPer;
    
        return $this;
    }

    /**
     * Get regLabPer
     *
     * @return string 
     */
    public function getRegLabPer()
    {
        return $this->regLabPer;
    }

    /**
     * Set regPenPer
     *
     * @param string $regPenPer
     * @return MaestroPersonal
     */
    public function setRegPenPer($regPenPer)
    {
        $this->regPenPer = $regPenPer;
    
        return $this;
    }

    /**
     * Get regPenPer
     *
     * @return string 
     */
    public function getRegPenPer()
    {
        return $this->regPenPer;
    }

    /**
     * Set codiCargTca
     *
     * @param string $codiCargTca
     * @return MaestroPersonal
     */
    public function setCodiCargTca($codiCargTca)
    {
        $this->codiCargTca = $codiCargTca;
    
        return $this;
    }

    /**
     * Get codiCargTca
     *
     * @return string 
     */
    public function getCodiCargTca()
    {
        return $this->codiCargTca;
    }

    /**
     * Set cargEncTca
     *
     * @param string $cargEncTca
     * @return MaestroPersonal
     */
    public function setCargEncTca($cargEncTca)
    {
        $this->cargEncTca = $cargEncTca;
    
        return $this;
    }

    /**
     * Get cargEncTca
     *
     * @return string 
     */
    public function getCargEncTca()
    {
        return $this->cargEncTca;
    }

    /**
     * Set flagAfpPer
     *
     * @param string $flagAfpPer
     * @return MaestroPersonal
     */
    public function setFlagAfpPer($flagAfpPer)
    {
        $this->flagAfpPer = $flagAfpPer;
    
        return $this;
    }

    /**
     * Get flagAfpPer
     *
     * @return string 
     */
    public function getFlagAfpPer()
    {
        return $this->flagAfpPer;
    }

    /**
     * Set codiAfp
     *
     * @param string $codiAfp
     * @return MaestroPersonal
     */
    public function setCodiAfp($codiAfp)
    {
        $this->codiAfp = $codiAfp;
    
        return $this;
    }

    /**
     * Get codiAfp
     *
     * @return string 
     */
    public function getCodiAfp()
    {
        return $this->codiAfp;
    }

    /**
     * Set fechAfpPer
     *
     * @param \DateTime $fechAfpPer
     * @return MaestroPersonal
     */
    public function setFechAfpPer($fechAfpPer)
    {
        $this->fechAfpPer = $fechAfpPer;
    
        return $this;
    }

    /**
     * Get fechAfpPer
     *
     * @return \DateTime 
     */
    public function getFechAfpPer()
    {
        return $this->fechAfpPer;
    }

    /**
     * Set codiAfpPer
     *
     * @param string $codiAfpPer
     * @return MaestroPersonal
     */
    public function setCodiAfpPer($codiAfpPer)
    {
        $this->codiAfpPer = $codiAfpPer;
    
        return $this;
    }

    /**
     * Get codiAfpPer
     *
     * @return string 
     */
    public function getCodiAfpPer()
    {
        return $this->codiAfpPer;
    }

    /**
     * Set librElecPer
     *
     * @param string $librElecPer
     * @return MaestroPersonal
     */
    public function setLibrElecPer($librElecPer)
    {
        $this->librElecPer = $librElecPer;
    
        return $this;
    }

    /**
     * Get librElecPer
     *
     * @return string 
     */
    public function getLibrElecPer()
    {
        return $this->librElecPer;
    }

    /**
     * Set librMiliPer
     *
     * @param string $librMiliPer
     * @return MaestroPersonal
     */
    public function setLibrMiliPer($librMiliPer)
    {
        $this->librMiliPer = $librMiliPer;
    
        return $this;
    }

    /**
     * Get librMiliPer
     *
     * @return string 
     */
    public function getLibrMiliPer()
    {
        return $this->librMiliPer;
    }

    /**
     * Set codiIpssPer
     *
     * @param string $codiIpssPer
     * @return MaestroPersonal
     */
    public function setCodiIpssPer($codiIpssPer)
    {
        $this->codiIpssPer = $codiIpssPer;
    
        return $this;
    }

    /**
     * Get codiIpssPer
     *
     * @return string 
     */
    public function getCodiIpssPer()
    {
        return $this->codiIpssPer;
    }

    /**
     * Set numeBrevPer
     *
     * @param string $numeBrevPer
     * @return MaestroPersonal
     */
    public function setNumeBrevPer($numeBrevPer)
    {
        $this->numeBrevPer = $numeBrevPer;
    
        return $this;
    }

    /**
     * Get numeBrevPer
     *
     * @return string 
     */
    public function getNumeBrevPer()
    {
        return $this->numeBrevPer;
    }

    /**
     * Set gruSangPer
     *
     * @param string $gruSangPer
     * @return MaestroPersonal
     */
    public function setGruSangPer($gruSangPer)
    {
        $this->gruSangPer = $gruSangPer;
    
        return $this;
    }

    /**
     * Get gruSangPer
     *
     * @return string 
     */
    public function getGruSangPer()
    {
        return $this->gruSangPer;
    }

    /**
     * Set codMonSuelPer
     *
     * @param string $codMonSuelPer
     * @return MaestroPersonal
     */
    public function setCodMonSuelPer($codMonSuelPer)
    {
        $this->codMonSuelPer = $codMonSuelPer;
    
        return $this;
    }

    /**
     * Get codMonSuelPer
     *
     * @return string 
     */
    public function getCodMonSuelPer()
    {
        return $this->codMonSuelPer;
    }

    /**
     * Set flagSuelPer
     *
     * @param string $flagSuelPer
     * @return MaestroPersonal
     */
    public function setFlagSuelPer($flagSuelPer)
    {
        $this->flagSuelPer = $flagSuelPer;
    
        return $this;
    }

    /**
     * Get flagSuelPer
     *
     * @return string 
     */
    public function getFlagSuelPer()
    {
        return $this->flagSuelPer;
    }

    /**
     * Set bancSuelTbc
     *
     * @param string $bancSuelTbc
     * @return MaestroPersonal
     */
    public function setBancSuelTbc($bancSuelTbc)
    {
        $this->bancSuelTbc = $bancSuelTbc;
    
        return $this;
    }

    /**
     * Get bancSuelTbc
     *
     * @return string 
     */
    public function getBancSuelTbc()
    {
        return $this->bancSuelTbc;
    }

    /**
     * Set suelCtaPer
     *
     * @param string $suelCtaPer
     * @return MaestroPersonal
     */
    public function setSuelCtaPer($suelCtaPer)
    {
        $this->suelCtaPer = $suelCtaPer;
    
        return $this;
    }

    /**
     * Get suelCtaPer
     *
     * @return string 
     */
    public function getSuelCtaPer()
    {
        return $this->suelCtaPer;
    }

    /**
     * Set bancCtsTbc
     *
     * @param string $bancCtsTbc
     * @return MaestroPersonal
     */
    public function setBancCtsTbc($bancCtsTbc)
    {
        $this->bancCtsTbc = $bancCtsTbc;
    
        return $this;
    }

    /**
     * Get bancCtsTbc
     *
     * @return string 
     */
    public function getBancCtsTbc()
    {
        return $this->bancCtsTbc;
    }

    /**
     * Set ctsCtaPer
     *
     * @param string $ctsCtaPer
     * @return MaestroPersonal
     */
    public function setCtsCtaPer($ctsCtaPer)
    {
        $this->ctsCtaPer = $ctsCtaPer;
    
        return $this;
    }

    /**
     * Get ctsCtaPer
     *
     * @return string 
     */
    public function getCtsCtaPer()
    {
        return $this->ctsCtaPer;
    }

    /**
     * Set codMonCtsPer
     *
     * @param string $codMonCtsPer
     * @return MaestroPersonal
     */
    public function setCodMonCtsPer($codMonCtsPer)
    {
        $this->codMonCtsPer = $codMonCtsPer;
    
        return $this;
    }

    /**
     * Get codMonCtsPer
     *
     * @return string 
     */
    public function getCodMonCtsPer()
    {
        return $this->codMonCtsPer;
    }

    /**
     * Set numePlazPer
     *
     * @param string $numePlazPer
     * @return MaestroPersonal
     */
    public function setNumePlazPer($numePlazPer)
    {
        $this->numePlazPer = $numePlazPer;
    
        return $this;
    }

    /**
     * Get numePlazPer
     *
     * @return string 
     */
    public function getNumePlazPer()
    {
        return $this->numePlazPer;
    }

    /**
     * Set fechRnomPer
     *
     * @param \DateTime $fechRnomPer
     * @return MaestroPersonal
     */
    public function setFechRnomPer($fechRnomPer)
    {
        $this->fechRnomPer = $fechRnomPer;
    
        return $this;
    }

    /**
     * Get fechRnomPer
     *
     * @return \DateTime 
     */
    public function getFechRnomPer()
    {
        return $this->fechRnomPer;
    }

    /**
     * Set numeRnomPer
     *
     * @param string $numeRnomPer
     * @return MaestroPersonal
     */
    public function setNumeRnomPer($numeRnomPer)
    {
        $this->numeRnomPer = $numeRnomPer;
    
        return $this;
    }

    /**
     * Get numeRnomPer
     *
     * @return string 
     */
    public function getNumeRnomPer()
    {
        return $this->numeRnomPer;
    }

    /**
     * Set sedeActuPer
     *
     * @param string $sedeActuPer
     * @return MaestroPersonal
     */
    public function setSedeActuPer($sedeActuPer)
    {
        $this->sedeActuPer = $sedeActuPer;
    
        return $this;
    }

    /**
     * Get sedeActuPer
     *
     * @return string 
     */
    public function getSedeActuPer()
    {
        return $this->sedeActuPer;
    }

    /**
     * Set fingAdmPer
     *
     * @param \DateTime $fingAdmPer
     * @return MaestroPersonal
     */
    public function setFingAdmPer($fingAdmPer)
    {
        $this->fingAdmPer = $fingAdmPer;
    
        return $this;
    }

    /**
     * Get fingAdmPer
     *
     * @return \DateTime 
     */
    public function getFingAdmPer()
    {
        return $this->fingAdmPer;
    }

    /**
     * Set fingCarrpPer
     *
     * @param \DateTime $fingCarrpPer
     * @return MaestroPersonal
     */
    public function setFingCarrpPer($fingCarrpPer)
    {
        $this->fingCarrpPer = $fingCarrpPer;
    
        return $this;
    }

    /**
     * Get fingCarrpPer
     *
     * @return \DateTime 
     */
    public function getFingCarrpPer()
    {
        return $this->fingCarrpPer;
    }

    /**
     * Set otroDocuPer
     *
     * @param string $otroDocuPer
     * @return MaestroPersonal
     */
    public function setOtroDocuPer($otroDocuPer)
    {
        $this->otroDocuPer = $otroDocuPer;
    
        return $this;
    }

    /**
     * Get otroDocuPer
     *
     * @return string 
     */
    public function getOtroDocuPer()
    {
        return $this->otroDocuPer;
    }

    /**
     * Set observaPer
     *
     * @param string $observaPer
     * @return MaestroPersonal
     */
    public function setObservaPer($observaPer)
    {
        $this->observaPer = $observaPer;
    
        return $this;
    }

    /**
     * Get observaPer
     *
     * @return string 
     */
    public function getObservaPer()
    {
        return $this->observaPer;
    }

    /**
     * Set cargoRemuPer
     *
     * @param string $cargoRemuPer
     * @return MaestroPersonal
     */
    public function setCargoRemuPer($cargoRemuPer)
    {
        $this->cargoRemuPer = $cargoRemuPer;
    
        return $this;
    }

    /**
     * Get cargoRemuPer
     *
     * @return string 
     */
    public function getCargoRemuPer()
    {
        return $this->cargoRemuPer;
    }

    /**
     * Set nivelRemuPer
     *
     * @param string $nivelRemuPer
     * @return MaestroPersonal
     */
    public function setNivelRemuPer($nivelRemuPer)
    {
        $this->nivelRemuPer = $nivelRemuPer;
    
        return $this;
    }

    /**
     * Get nivelRemuPer
     *
     * @return string 
     */
    public function getNivelRemuPer()
    {
        return $this->nivelRemuPer;
    }

    /**
     * Set plazaRemuPer
     *
     * @param string $plazaRemuPer
     * @return MaestroPersonal
     */
    public function setPlazaRemuPer($plazaRemuPer)
    {
        $this->plazaRemuPer = $plazaRemuPer;
    
        return $this;
    }

    /**
     * Get plazaRemuPer
     *
     * @return string 
     */
    public function getPlazaRemuPer()
    {
        return $this->plazaRemuPer;
    }

    /**
     * Set depeRemuPer
     *
     * @param string $depeRemuPer
     * @return MaestroPersonal
     */
    public function setDepeRemuPer($depeRemuPer)
    {
        $this->depeRemuPer = $depeRemuPer;
    
        return $this;
    }

    /**
     * Get depeRemuPer
     *
     * @return string 
     */
    public function getDepeRemuPer()
    {
        return $this->depeRemuPer;
    }

    /**
     * Set obserRemuPer
     *
     * @param string $obserRemuPer
     * @return MaestroPersonal
     */
    public function setObserRemuPer($obserRemuPer)
    {
        $this->obserRemuPer = $obserRemuPer;
    
        return $this;
    }

    /**
     * Get obserRemuPer
     *
     * @return string 
     */
    public function getObserRemuPer()
    {
        return $this->obserRemuPer;
    }

    /**
     * Set tipoCuenPer
     *
     * @param string $tipoCuenPer
     * @return MaestroPersonal
     */
    public function setTipoCuenPer($tipoCuenPer)
    {
        $this->tipoCuenPer = $tipoCuenPer;
    
        return $this;
    }

    /**
     * Get tipoCuenPer
     *
     * @return string 
     */
    public function getTipoCuenPer()
    {
        return $this->tipoCuenPer;
    }

    /**
     * Set seguMediPer
     *
     * @param string $seguMediPer
     * @return MaestroPersonal
     */
    public function setSeguMediPer($seguMediPer)
    {
        $this->seguMediPer = $seguMediPer;
    
        return $this;
    }

    /**
     * Get seguMediPer
     *
     * @return string 
     */
    public function getSeguMediPer()
    {
        return $this->seguMediPer;
    }

    /**
     * Set sedeRemuPer
     *
     * @param string $sedeRemuPer
     * @return MaestroPersonal
     */
    public function setSedeRemuPer($sedeRemuPer)
    {
        $this->sedeRemuPer = $sedeRemuPer;
    
        return $this;
    }

    /**
     * Get sedeRemuPer
     *
     * @return string 
     */
    public function getSedeRemuPer()
    {
        return $this->sedeRemuPer;
    }

    /**
     * Set apepSoltPer
     *
     * @param string $apepSoltPer
     * @return MaestroPersonal
     */
    public function setApepSoltPer($apepSoltPer)
    {
        $this->apepSoltPer = $apepSoltPer;
    
        return $this;
    }

    /**
     * Get apepSoltPer
     *
     * @return string 
     */
    public function getApepSoltPer()
    {
        return $this->apepSoltPer;
    }

    /**
     * Set apemSoltPer
     *
     * @param string $apemSoltPer
     * @return MaestroPersonal
     */
    public function setApemSoltPer($apemSoltPer)
    {
        $this->apemSoltPer = $apemSoltPer;
    
        return $this;
    }

    /**
     * Get apemSoltPer
     *
     * @return string 
     */
    public function getApemSoltPer()
    {
        return $this->apemSoltPer;
    }

    /**
     * Set nombSoltEr
     *
     * @param string $nombSoltEr
     * @return MaestroPersonal
     */
    public function setNombSoltEr($nombSoltEr)
    {
        $this->nombSoltEr = $nombSoltEr;
    
        return $this;
    }

    /**
     * Get nombSoltEr
     *
     * @return string 
     */
    public function getNombSoltEr()
    {
        return $this->nombSoltEr;
    }

    /**
     * Set cesaSobrPer
     *
     * @param string $cesaSobrPer
     * @return MaestroPersonal
     */
    public function setCesaSobrPer($cesaSobrPer)
    {
        $this->cesaSobrPer = $cesaSobrPer;
    
        return $this;
    }

    /**
     * Get cesaSobrPer
     *
     * @return string 
     */
    public function getCesaSobrPer()
    {
        return $this->cesaSobrPer;
    }

    /**
     * Set fecCesePer
     *
     * @param \DateTime $fecCesePer
     * @return MaestroPersonal
     */
    public function setFecCesePer($fecCesePer)
    {
        $this->fecCesePer = $fecCesePer;
    
        return $this;
    }

    /**
     * Get fecCesePer
     *
     * @return \DateTime 
     */
    public function getFecCesePer()
    {
        return $this->fecCesePer;
    }

    /**
     * Set nombTituCes
     *
     * @param string $nombTituCes
     * @return MaestroPersonal
     */
    public function setNombTituCes($nombTituCes)
    {
        $this->nombTituCes = $nombTituCes;
    
        return $this;
    }

    /**
     * Get nombTituCes
     *
     * @return string 
     */
    public function getNombTituCes()
    {
        return $this->nombTituCes;
    }

    /**
     * Set nombCobrCes
     *
     * @param string $nombCobrCes
     * @return MaestroPersonal
     */
    public function setNombCobrCes($nombCobrCes)
    {
        $this->nombCobrCes = $nombCobrCes;
    
        return $this;
    }

    /**
     * Get nombCobrCes
     *
     * @return string 
     */
    public function getNombCobrCes()
    {
        return $this->nombCobrCes;
    }

    /**
     * Set encaPlazPer
     *
     * @param string $encaPlazPer
     * @return MaestroPersonal
     */
    public function setEncaPlazPer($encaPlazPer)
    {
        $this->encaPlazPer = $encaPlazPer;
    
        return $this;
    }

    /**
     * Get encaPlazPer
     *
     * @return string 
     */
    public function getEncaPlazPer()
    {
        return $this->encaPlazPer;
    }

    /**
     * Set flagPropuesta
     *
     * @param string $flagPropuesta
     * @return MaestroPersonal
     */
    public function setFlagPropuesta($flagPropuesta)
    {
        $this->flagPropuesta = $flagPropuesta;
    
        return $this;
    }

    /**
     * Get flagPropuesta
     *
     * @return string 
     */
    public function getFlagPropuesta()
    {
        return $this->flagPropuesta;
    }

    /**
     * Set metaProp
     *
     * @param string $metaProp
     * @return MaestroPersonal
     */
    public function setMetaProp($metaProp)
    {
        $this->metaProp = $metaProp;
    
        return $this;
    }

    /**
     * Get metaProp
     *
     * @return string 
     */
    public function getMetaProp()
    {
        return $this->metaProp;
    }

    /**
     * Set ftefto
     *
     * @param string $ftefto
     * @return MaestroPersonal
     */
    public function setFtefto($ftefto)
    {
        $this->ftefto = $ftefto;
    
        return $this;
    }

    /**
     * Get ftefto
     *
     * @return string 
     */
    public function getFtefto()
    {
        return $this->ftefto;
    }

    /**
     * Set codiProyPin
     *
     * @param string $codiProyPin
     * @return MaestroPersonal
     */
    public function setCodiProyPin($codiProyPin)
    {
        $this->codiProyPin = $codiProyPin;
    
        return $this;
    }

    /**
     * Get codiProyPin
     *
     * @return string 
     */
    public function getCodiProyPin()
    {
        return $this->codiProyPin;
    }

    /**
     * Set codrie
     *
     * @param string $codrie
     * @return MaestroPersonal
     */
    public function setCodrie($codrie)
    {
        $this->codrie = $codrie;
    
        return $this;
    }

    /**
     * Get codrie
     *
     * @return string 
     */
    public function getCodrie()
    {
        return $this->codrie;
    }

    /**
     * Set flagAlmacen
     *
     * @param string $flagAlmacen
     * @return MaestroPersonal
     */
    public function setFlagAlmacen($flagAlmacen)
    {
        $this->flagAlmacen = $flagAlmacen;
    
        return $this;
    }

    /**
     * Get flagAlmacen
     *
     * @return string 
     */
    public function getFlagAlmacen()
    {
        return $this->flagAlmacen;
    }

    /**
     * Set fechIniRecu
     *
     * @param \DateTime $fechIniRecu
     * @return MaestroPersonal
     */
    public function setFechIniRecu($fechIniRecu)
    {
        $this->fechIniRecu = $fechIniRecu;
    
        return $this;
    }

    /**
     * Get fechIniRecu
     *
     * @return \DateTime 
     */
    public function getFechIniRecu()
    {
        return $this->fechIniRecu;
    }

    /**
     * Set flagRecurrente
     *
     * @param string $flagRecurrente
     * @return MaestroPersonal
     */
    public function setFlagRecurrente($flagRecurrente)
    {
        $this->flagRecurrente = $flagRecurrente;
    
        return $this;
    }

    /**
     * Get flagRecurrente
     *
     * @return string 
     */
    public function getFlagRecurrente()
    {
        return $this->flagRecurrente;
    }

    /**
     * Set obsRecu
     *
     * @param string $obsRecu
     * @return MaestroPersonal
     */
    public function setObsRecu($obsRecu)
    {
        $this->obsRecu = $obsRecu;
    
        return $this;
    }

    /**
     * Get obsRecu
     *
     * @return string 
     */
    public function getObsRecu()
    {
        return $this->obsRecu;
    }

    /**
     * Set flagFotocPer
     *
     * @param string $flagFotocPer
     * @return MaestroPersonal
     */
    public function setFlagFotocPer($flagFotocPer)
    {
        $this->flagFotocPer = $flagFotocPer;
    
        return $this;
    }

    /**
     * Get flagFotocPer
     *
     * @return string 
     */
    public function getFlagFotocPer()
    {
        return $this->flagFotocPer;
    }

    /**
     * Set indValido
     *
     * @param string $indValido
     * @return MaestroPersonal
     */
    public function setIndValido($indValido)
    {
        $this->indValido = $indValido;
    
        return $this;
    }

    /**
     * Get indValido
     *
     * @return string 
     */
    public function getIndValido()
    {
        return $this->indValido;
    }

    /**
     * Set bioSuserid
     *
     * @param string $bioSuserid
     * @return MaestroPersonal
     */
    public function setBioSuserid($bioSuserid)
    {
        $this->bioSuserid = $bioSuserid;
    
        return $this;
    }

    /**
     * Get bioSuserid
     *
     * @return string 
     */
    public function getBioSuserid()
    {
        return $this->bioSuserid;
    }

    /**
     * Set tipoViaPer
     *
     * @param string $tipoViaPer
     * @return MaestroPersonal
     */
    public function setTipoViaPer($tipoViaPer)
    {
        $this->tipoViaPer = $tipoViaPer;
    
        return $this;
    }

    /**
     * Get tipoViaPer
     *
     * @return string 
     */
    public function getTipoViaPer()
    {
        return $this->tipoViaPer;
    }

    /**
     * Set nombViaPer
     *
     * @param string $nombViaPer
     * @return MaestroPersonal
     */
    public function setNombViaPer($nombViaPer)
    {
        $this->nombViaPer = $nombViaPer;
    
        return $this;
    }

    /**
     * Get nombViaPer
     *
     * @return string 
     */
    public function getNombViaPer()
    {
        return $this->nombViaPer;
    }

    /**
     * Set numeDirePer
     *
     * @param string $numeDirePer
     * @return MaestroPersonal
     */
    public function setNumeDirePer($numeDirePer)
    {
        $this->numeDirePer = $numeDirePer;
    
        return $this;
    }

    /**
     * Get numeDirePer
     *
     * @return string 
     */
    public function getNumeDirePer()
    {
        return $this->numeDirePer;
    }

    /**
     * Set kmDirePer
     *
     * @param string $kmDirePer
     * @return MaestroPersonal
     */
    public function setKmDirePer($kmDirePer)
    {
        $this->kmDirePer = $kmDirePer;
    
        return $this;
    }

    /**
     * Get kmDirePer
     *
     * @return string 
     */
    public function getKmDirePer()
    {
        return $this->kmDirePer;
    }

    /**
     * Set mzDirePer
     *
     * @param string $mzDirePer
     * @return MaestroPersonal
     */
    public function setMzDirePer($mzDirePer)
    {
        $this->mzDirePer = $mzDirePer;
    
        return $this;
    }

    /**
     * Get mzDirePer
     *
     * @return string 
     */
    public function getMzDirePer()
    {
        return $this->mzDirePer;
    }

    /**
     * Set inteDirePer
     *
     * @param string $inteDirePer
     * @return MaestroPersonal
     */
    public function setInteDirePer($inteDirePer)
    {
        $this->inteDirePer = $inteDirePer;
    
        return $this;
    }

    /**
     * Get inteDirePer
     *
     * @return string 
     */
    public function getInteDirePer()
    {
        return $this->inteDirePer;
    }

    /**
     * Set dptoDirePer
     *
     * @param string $dptoDirePer
     * @return MaestroPersonal
     */
    public function setDptoDirePer($dptoDirePer)
    {
        $this->dptoDirePer = $dptoDirePer;
    
        return $this;
    }

    /**
     * Get dptoDirePer
     *
     * @return string 
     */
    public function getDptoDirePer()
    {
        return $this->dptoDirePer;
    }

    /**
     * Set loteDirePer
     *
     * @param string $loteDirePer
     * @return MaestroPersonal
     */
    public function setLoteDirePer($loteDirePer)
    {
        $this->loteDirePer = $loteDirePer;
    
        return $this;
    }

    /**
     * Get loteDirePer
     *
     * @return string 
     */
    public function getLoteDirePer()
    {
        return $this->loteDirePer;
    }

    /**
     * Set pisoDirePer
     *
     * @param string $pisoDirePer
     * @return MaestroPersonal
     */
    public function setPisoDirePer($pisoDirePer)
    {
        $this->pisoDirePer = $pisoDirePer;
    
        return $this;
    }

    /**
     * Get pisoDirePer
     *
     * @return string 
     */
    public function getPisoDirePer()
    {
        return $this->pisoDirePer;
    }

    /**
     * Set tipoZonaPer
     *
     * @param string $tipoZonaPer
     * @return MaestroPersonal
     */
    public function setTipoZonaPer($tipoZonaPer)
    {
        $this->tipoZonaPer = $tipoZonaPer;
    
        return $this;
    }

    /**
     * Get tipoZonaPer
     *
     * @return string 
     */
    public function getTipoZonaPer()
    {
        return $this->tipoZonaPer;
    }

    /**
     * Set nombZonaPer
     *
     * @param string $nombZonaPer
     * @return MaestroPersonal
     */
    public function setNombZonaPer($nombZonaPer)
    {
        $this->nombZonaPer = $nombZonaPer;
    
        return $this;
    }

    /**
     * Get nombZonaPer
     *
     * @return string 
     */
    public function getNombZonaPer()
    {
        return $this->nombZonaPer;
    }

    /**
     * Set nombRefdomPer
     *
     * @param string $nombRefdomPer
     * @return MaestroPersonal
     */
    public function setNombRefdomPer($nombRefdomPer)
    {
        $this->nombRefdomPer = $nombRefdomPer;
    
        return $this;
    }

    /**
     * Get nombRefdomPer
     *
     * @return string 
     */
    public function getNombRefdomPer()
    {
        return $this->nombRefdomPer;
    }

    /**
     * Set numRucPer
     *
     * @param string $numRucPer
     * @return MaestroPersonal
     */
    public function setNumRucPer($numRucPer)
    {
        $this->numRucPer = $numRucPer;
    
        return $this;
    }

    /**
     * Get numRucPer
     *
     * @return string 
     */
    public function getNumRucPer()
    {
        return $this->numRucPer;
    }

    /**
     * Set numCelPer
     *
     * @param string $numCelPer
     * @return MaestroPersonal
     */
    public function setNumCelPer($numCelPer)
    {
        $this->numCelPer = $numCelPer;
    
        return $this;
    }

    /**
     * Get numCelPer
     *
     * @return string 
     */
    public function getNumCelPer()
    {
        return $this->numCelPer;
    }

    /**
     * Set emailPer
     *
     * @param string $emailPer
     * @return MaestroPersonal
     */
    public function setEmailPer($emailPer)
    {
        $this->emailPer = $emailPer;
    
        return $this;
    }

    /**
     * Get emailPer
     *
     * @return string 
     */
    public function getEmailPer()
    {
        return $this->emailPer;
    }

    /**
     * Set emailInsti
     *
     * @param string $emailInsti
     * @return MaestroPersonal
     */
    public function setEmailInsti($emailInsti)
    {
        $this->emailInsti = $emailInsti;
    
        return $this;
    }

    /**
     * Get emailInsti
     *
     * @return string 
     */
    public function getEmailInsti()
    {
        return $this->emailInsti;
    }

    /**
     * Set codiFuncTca
     *
     * @param string $codiFuncTca
     * @return MaestroPersonal
     */
    public function setCodiFuncTca($codiFuncTca)
    {
        $this->codiFuncTca = $codiFuncTca;
    
        return $this;
    }

    /**
     * Get codiFuncTca
     *
     * @return string 
     */
    public function getCodiFuncTca()
    {
        return $this->codiFuncTca;
    }

    /**
     * Set cargEncPer
     *
     * @param string $cargEncPer
     * @return MaestroPersonal
     */
    public function setCargEncPer($cargEncPer)
    {
        $this->cargEncPer = $cargEncPer;
    
        return $this;
    }

    /**
     * Get cargEncPer
     *
     * @return string 
     */
    public function getCargEncPer()
    {
        return $this->cargEncPer;
    }

    /**
     * Set marca
     *
     * @param string $marca
     * @return MaestroPersonal
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;
    
        return $this;
    }

    /**
     * Get marca
     *
     * @return string 
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set flagCuenta
     *
     * @param string $flagCuenta
     * @return MaestroPersonal
     */
    public function setFlagCuenta($flagCuenta)
    {
        $this->flagCuenta = $flagCuenta;
    
        return $this;
    }

    /**
     * Get flagCuenta
     *
     * @return string 
     */
    public function getFlagCuenta()
    {
        return $this->flagCuenta;
    }

    /**
     * Set codiUbicOfic
     *
     * @param string $codiUbicOfic
     * @return MaestroPersonal
     */
    public function setCodiUbicOfic($codiUbicOfic)
    {
        $this->codiUbicOfic = $codiUbicOfic;
    
        return $this;
    }

    /**
     * Get codiUbicOfic
     *
     * @return string 
     */
    public function getCodiUbicOfic()
    {
        return $this->codiUbicOfic;
    }

    /**
     * Set nombPrimPer
     *
     * @param string $nombPrimPer
     * @return MaestroPersonal
     */
    public function setNombPrimPer($nombPrimPer)
    {
        $this->nombPrimPer = $nombPrimPer;
    
        return $this;
    }

    /**
     * Get nombPrimPer
     *
     * @return string 
     */
    public function getNombPrimPer()
    {
        return $this->nombPrimPer;
    }

    /**
     * Set nombSeguPer
     *
     * @param string $nombSeguPer
     * @return MaestroPersonal
     */
    public function setNombSeguPer($nombSeguPer)
    {
        $this->nombSeguPer = $nombSeguPer;
    
        return $this;
    }

    /**
     * Get nombSeguPer
     *
     * @return string 
     */
    public function getNombSeguPer()
    {
        return $this->nombSeguPer;
    }

    /**
     * Set tipoComisionAfp
     *
     * @param string $tipoComisionAfp
     * @return MaestroPersonal
     */
    public function setTipoComisionAfp($tipoComisionAfp)
    {
        $this->tipoComisionAfp = $tipoComisionAfp;
    
        return $this;
    }

    /**
     * Get tipoComisionAfp
     *
     * @return string 
     */
    public function getTipoComisionAfp()
    {
        return $this->tipoComisionAfp;
    }
}