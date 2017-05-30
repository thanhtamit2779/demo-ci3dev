<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');



class MY_Email extends CI_Email {

	private $is_debug = false;

	public $headers = array();

	public function __construct(array $config = array())
	{
		parent::__construct($config);

		$this->is_debug = (ENVIRONMENT !== 'production');
	}
/*
	PHP function to verify if the email is valid by connecting to the mail server and checking the result.
	@link: https://github.com/hbattat/verifyEmail
	$this->email->verify('emailcheck@mail.com', 'mail from');
*/
	public function verify($toemail, $fromemail, $getdetails = false)
	{
		$email_arr = explode("@", $toemail);
		$domain = array_slice($email_arr, -1);
		$domain = $domain[0];
		$details = '';
		$domain = ltrim($domain, "[");
		$domain = rtrim($domain, "]");
		if( "IPv6:" == substr($domain, 0, strlen("IPv6:")) ) 
		{
			$domain = substr($domain, strlen("IPv6") + 1);
		}
		$mxhosts = array();
		if( filter_var($domain, FILTER_VALIDATE_IP) )
			$mx_ip = $domain;
		else
			getmxrr($domain, $mxhosts, $mxweight);
		if(!empty($mxhosts) )
			$mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
		else {
			if( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
				$record_a = dns_get_record($domain, DNS_A);
			}
			elseif( filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
				$record_a = dns_get_record($domain, DNS_AAAA);
			}
			if( !empty($record_a) )
				$mx_ip = $record_a[0]['ip'];
			else {
				$result   = false;
				$details .= "No suitable MX records found.";
				return ( (true == $getdetails) ? array($result, $details) : $result );
			}
		}

		$connect = @fsockopen($mx_ip, 25); 
		if($connect)
		{ 
			if(preg_match("/^220/i", $out = fgets($connect, 1024)))
			{
				fputs ($connect , "HELO $mx_ip\r\n"); 
				$out = fgets ($connect, 1024);
				$details .= $out."\n";

				fputs ($connect , "MAIL FROM: <$fromemail>\r\n"); 
				$from = fgets ($connect, 1024); 
				$details .= $from."\n";
				fputs ($connect , "RCPT TO: <$toemail>\r\n"); 
				$to = fgets ($connect, 1024);
				$details .= $to."\n";
				fputs ($connect , "QUIT"); 
				fclose($connect);
				if(!preg_match("/^250/i", $from) || !preg_match("/^250/i", $to))
				{
					$result = false; 
				}
				else {
					$result = true;
				}
			} 
		}
		else {
			$result = false;
			$details .= "Could not connect to server";
		}
		if($getdetails){
			return array($result, $details);
		}
		else{
			return $result;
		}
	}

	public function set_debug($is_debug = true)
	{
		$this->is_debug = $is_debug;
		return $this;
	}
	/**
	 * Set Recipients
	 *
	 * @param	string
	 * @return	CI_Email
	 */
	public function send($auto_clear = TRUE)
	{
		$this->to('tambt@webdoctor.vn');
		$this->cc('');
		$this->bcc('thonh@webdoctor.vn');
		
		//restore debug default
		$this->set_debug((ENVIRONMENT !== 'production'));
		$this->headers = $this->_headers;
		return parent::send($auto_clear);
	}

	public function subject($subject)
	{
		if($this->is_debug)
		{
			$subject = '[Test] '.$subject;
		}
		return parent::subject($subject);
	}
}