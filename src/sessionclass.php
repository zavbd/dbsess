<?php
/*
Free for non-comercial use covered by GPL
If you want to use this in a commercial package
please contact me on justin@okulyillari.com

This class allows you to store PHP session data
in your database, it is modified to run with the
PEAR::DB class

UPDATED: 10/06/2004 - intermittent update bug fixed
*/


//set sessions handler on php

class session{ 

	var $session_limit = 300; //life of sessions in seconds
    var $db = null;
	function __construct($db){
	//set db handler
	var_dump($db);
	$this->db = $db;
    session_set_save_handler (array(&$this, 'open'), 
                          array(&$this, 'close'), 
                          array(&$this, 'read'), 
                          array(&$this, 'write'), 
                          array(&$this, 'destroy'), 
                          array(&$this, 'gc'));
	}
	/* Open session, if you have your own db connection 
       code, put it in here! */ 
    function open($path, $name) { 
        if (isset($this->db)) return TRUE; 
		else return FALSE;
    } 

    /* Close session */ 
    function close() { 
        /* This is used for a manual call of the 
           session gc function */ 
        $this->gc(0); 
        return TRUE; 
    } 

    /* Read session data from database */ 
    function read($ses_id) {
        $session_sql = "SELECT * FROM sessions WHERE ses_id = '$ses_id'"; 
		//var_dump($this->db);
		$res = $this->db->query($session_sql);
		$session_res =& $this->db->getRow($res); 
        if (!$session_res) { 
            return ''; 
        } 

       if ($session_res) { 
            $ses_data = $session_res["ses_value"];
		return $ses_data; 
        } else { 
            return ''; 
        } 
    } 

    /* Write new data to database */ 
    function write($ses_id, $data) { 
        $session_sql = "UPDATE sessions SET ses_time='" . time() 
                     . "', ses_value='$data' WHERE ses_id='$ses_id'"; 
        $session_res = $this->db->query($session_sql); 
		if (!$session_res) { 
            return FALSE; 
        } 
        if ($this->db->affectedRows() > 0) { 
            return TRUE; 
        } 

        $session_sql = "INSERT INTO sessions (ses_id, ses_time, ses_start, ses_value)" 
                     . " VALUES ('$ses_id', '" . time() 
                     . "', '" . time() . "', '$data')"; 
        $session_res = $this->db->query($session_sql); 
        if (!$session_res) {     
            return FALSE; 
        }         else { 
            return TRUE; 
        } 
    } 

    /* Destroy session record in database */ 
    function destroy($ses_id) { 
        $session_sql = "DELETE FROM sessions WHERE ses_id = '$ses_id'"; 
        $session_res = $this->db->query($session_sql); 
        if (!$session_res) { 
            return FALSE; 
        }         else { 
            return TRUE; 
        } 
    } 

    // Garbage collection removes old sessions 
    function gc($life) { 
        $ses_life = time() - $this->session_limit; 
        $session_sql = "DELETE FROM sessions WHERE ses_time < $ses_life"; 
        $session_res = $this->db->query($session_sql); 


        if (!$session_res) { 
            return FALSE; 
        }         else { 
            return TRUE; 
        } 
    } 
	    // Users online 
    function users() { 
        $users_sql = "SELECT COUNT(ses_id) FROM sessions"; 
        $users_res = $this->db->getOne($users_sql); 

        if (!$users_res) { 
            return NULL; 
        }         else { 
            return $users_res; 
        } 
    } 
} 
?>
