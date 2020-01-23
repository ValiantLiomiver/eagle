<?php
class Eagle_Mail{
	private $destTo = array();
	private $destCc = array();
	private $destCcn = array();
	private $charset = 'UTF-8';
	private $mailer = "smtp";
	private $replyto = "";
	private $replytoname = null;
	private $host = "localhost";
	private $smtp_auth = false;
	private $username = '';
	private $password = '';
	private $timeout = 30;
	
	private $from = '';
	private $fromname = '';
	private $subject = '';
	private $text_html = '';
	private $text_plain = '';
	
	function __construct($from='',$fromname=''){
		if("$from") $this->from = $from;
		if("$fromname") $this->fromname = $fromname;
	}
	
	function __destruct(){
		
	}
	
	function setSubject($subject){
		if("$subject") $this->subject = $subject;
	}
	
	function setHtml($text_html){
		if("$text_html") $this->text_html = $text_html;
	}
	
	function setText($text_plain){
		if("$text_plain") $this->text_plain = $text_plain;
	}
	
	function setCharser($charset){
		if("$charset") $this->charset = $charset;
	}
	
	function setMailer($mailer){
		if("$mailer") $this->mailer = $mailer;
	}
	
	function addTo($email){
		if("$email") $this->destTo[] = $email;
	}
	
	function addCc($email){
		if("$email") $this->destCc[] = $email;
	}
	
	function addCcn($email){
		if("$email") $this->destCcn[] = $email;
	}
	
	function setReplyTo($replyto,$replytoname=''){
		if("$replyto") $this->replyto = $replyto;
		if("$replytoname") $this->replytoname = $replytoname;
	}
	
	function setHost($host){
		if("$host") $this->host = $host;
	}
	
	function setSMTPAuth($bool,$username='',$password=''){
		if(is_bool($bool)){
			$this->smtp_auth = $bool;
			if($this->smtp_auth){
				$this->username = '';
				$this->password = '';
			}
		}
	}
	
	function send($replyto=null,$replytoname=null,$From=null,$FromName=null,$files=null){
		require "class.phpmailer5.1.php";
		$mail = new phpmailer();
		$mail->PluginDir = "";
		$mail->Mailer = $this->mailer;
		$mail->Host = $this->host;
		$mail->SMTPAuth = $this->smtp_auth;
		$mail->Username = $this->username;
		$mail->Password = $this->password;
		$mail->From = $this->from;
		$mail->FromName = $this->fromname;
		$mail->AddReplyTo($this->replyto,$this->replytoname);
		$mail->Timeout = $this->timeout;
		$mail->CharSet = $this->charset;
		
		//aggiunge i destinatari diretti
		$destTo_array = $this->destTo;
		for($i=0;$i<count($destTo_array);$i++){
			$regs=null;
			if(trim($destTo_array[$i])!=''){
				$destTo_array[$i]=trim($destTo_array[$i]);
				//devo assicurarmi che  l'indirizzo sia nella forma computer			 
				if(preg_match("/^(.*)<(.*)>/", $destTo_array[$i], $regs)){
					$mail->AddAddress($regs[2],$regs[1]);
				}
				else{
					$mail->AddAddress($destTo_array[$i]);
				} 
			}
		}
		//aggiunge i destinatari in copia 
		$destCc_array = $this->destCc;
		for ($i=0;$i<count($destCc_array);$i++){
			//if(trim($destCc_array[$i])!='') $mail->AddCC($destCc_array[$i]);
			$regs=null;
			if(trim($destCc_array[$i])!=''){
				$destCc_array[$i]=trim($destCc_array[$i]);
				//devo assicurarmi che  l'indirizzo sia nella forma computer
				if(preg_match("/^(.*)<(.*)>/", $destCc_array[$i], $regs)){
					$mail->AddCC($regs[2],$regs[1]);
				}
				else{
					$mail->AddCC($destCc_array[$i]);
				}
			}
		}
		
		//aggiunge i destinatari in copia nascosta
		$destCcn_array = $this->destCcn;
		for($i=0;$i<count($destCcn_array);$i++){
			//if(trim($destCcn_array[$i])!='') $mail->AddBCC($destCcn_array[$i]);
			$regs=null;
			if(trim($destCcn_array[$i])!='') {
				$destCcn_array[$i]=trim($destCcn_array[$i]);
				//devo assicurarmi che  l'indirizzo sia nella forma computer			 
				if(preg_match("/^(.*)<(.*)>/", $destCcn_array[$i], $regs)){
					$mail->AddBCC($regs[2],$regs[1]);	 
				}
				else{
					$mail->AddBCC($destCcn_array[$i]);	 
				} 
			}
		}
		
		//aggiunge eventuale allegato
		if(is_array($files)){
			foreach($files as $i=>$file){
				if (!$file['type']) $file['type'] = 'application/octet-stream';
				$a=$mail->AddAttachment($file['path'],$file['name'],"base64",$file['type']);
			}
		}
		
		$mail->Subject = $this->subject;
		//Definimos AltBody por si el destinatario del correo no admite email con formato html
		//conversione HTML->PLAIN
		/* tabella di conversione TEXT<=>HTML */

		if(!$this->text_plain){
			$text_plain = $this->text_html;
			$text_plain = str_replace("<br>","\n",$text_plain);
			$text_plain = str_replace("<br />","\n",$text_plain);
			$text_plain = strip_tags($text_plain);
			$text_plain = str_replace("&euro;","€",$text_plain);	   
			$text_plain = str_replace("&rsquo;","'",$text_plain);
			$text_plain = str_replace("&nbsp;"," ",$text_plain);
			$text_plain = str_replace("&quot;","\"",$text_plain);
			$text_plain = str_replace("&lt;","<",$text_plain);
			$text_plain = str_replace("&gt;",">",$text_plain);
			$text_plain = str_replace("&agrave;","à",$text_plain);
			$text_plain = str_replace("&egrave;","è",$text_plain);
			$text_plain = str_replace("&igrave;","ì",$text_plain);
			$text_plain = str_replace("&ograve;","ò",$text_plain);
			$text_plain = str_replace("&ugrave;","ù",$text_plain);
			$text_plain = str_replace("&Agrave;","À",$text_plain);
			$text_plain = str_replace("&Egrave;","È",$text_plain);
			$text_plain = str_replace("&Igrave;","Ì",$text_plain);
			$text_plain = str_replace("&Ograve;","Ò",$text_plain);
			$text_plain = str_replace("&Ugrave;","Ù",$text_plain);
			$text_plain = str_replace("&copy;","©",$text_plain);
			$text_plain = html_entity_decode($text_plain);
			$text_plain = utf8_encode($text_plain);
		}
		if(!$this->text_html){
			$text_html = $this->text_plain;
			$text_html = htmlentities($text_html);
			$text_html = str_replace(" ","&nbsp;",$text_html);
			$text_html = str_replace("\n","<br />",$text_html);
		}
		$mail->Body = $this->text_html; //testo in html
		$mail->AltBody = $this->text_plain;
		$exito = $mail->Send();
		$intentos=1;
		while((!$exito) && ($intentos < 5)){
			sleep(5);
			$exito = $mail->Send();
			$intentos=$intentos+1;
		}
		if(!$exito){
			echo "<br>Errore invio posta elettronica ".$valor;
			echo "<br>".$mail->ErrorInfo;
		}
		return ($exito?true:false);
	}
}
