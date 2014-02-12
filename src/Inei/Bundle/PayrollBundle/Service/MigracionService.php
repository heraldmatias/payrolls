<?php
namespace Inei\Bundle\PayrollBundle\Service;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MigracionService
 *
 * @author holivares
 */
class MigracionService {
    
    public function is_proc_running($pid) {
        exec("ps -p {$pid} > /dev/null 2>&1", $out, $rc);
        if (!$rc)
          return true;
        return false;
    }

    public function start_proc($prog_name, &$pid, $errmsg) {
        exec("bash -c '{$prog_name} >> /tmp/log_messages 2>&1 & echo $!'", $out, $rc);
        if (!$rc) {
           $pid = intval(trim($out[0]));
           $errmsg = "El proceso ya ha sido iniciado {$pid}";
        } else {
           foreach($out as $o)
              $errmsg .= $o;
        }
        return $rc;
    }
    
    public function get_log_messages(){
        if (file_exists("/tmp/log_messages"))
            return sprintf("<div class='bar' style='width: %s%%;'></div>", (substr_count(file_get_contents("/tmp/log_messages"), "\n" )/426)*100);nl2br(file_get_contents("/tmp/log_messages"));//
    }
    
    public function get_proc_status(){        
        $bgpid = $_GET['bgpid'];

        if ($this->is_proc_running($bgpid)) {
          $result['Status'] = 0;
          $result['Running'] = 1;
          $result['Message'] = 'Procesando...';
        } else {
          $result['Status'] = 0;
          $result['Running'] = 0;
          $result['Message'] = "Proceso terminado. {$bgpid}";
        }
        return json_encode($result);
    }
    
    public function process(){
        $bgpid = $_GET['bgpid'];
        $errmsg = null;
        /* Launch background process */
        if ($bgpid) {
           /* Make sure the prev proc has been finished. If so, start a new one */
           if (!$this->is_proc_running($bgpid)) {
              $rc = $this->start_proc("/tmp/bgproc.sh", $bgpid, $errmsg);
              $result['bgpid'] = $bgpid;
           } else {
              $rc = 1;
              $errmsg = "Background job is already running";
           }
        } else {
           $rc = $this->start_proc("/tmp/bgproc.sh", $bgpid, $errmsg);
        }

        $result['Status'] = $rc;
        $result['bgpid'] = $bgpid;
        $result['Message'] = $errmsg;
        return json_encode($result);
    }
    

}

?>
