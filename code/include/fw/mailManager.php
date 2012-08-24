<?php

class mailManager
{
    private $mail;
    private $mail_template;
    
    private $halt_real = array
    (
        array( 'halt@813.co.jp' , 'halt' )
    );

    private $halt_debug = array
    (
        array( 'honma@zeus.corp.813.co.jp' , 'halt' )
    );

    private $dev_real = array
    (
        array( 'keiichi-honma@813.co.jp' , 'dev' )
    );

    private $dev_debug = array
    (
        array( 'honma@zeus.corp.813.co.jp' , 'halt' )
    );

    //81に送る
    private $hachione_real = array
    (
        array( 'info@813.co.jp' , '[81]' )
    );

    private $hachione_debug = array
    (
        array( 'honma@zeus.corp.813.co.jp' , '[81]' )
    );

    function __construct(){
        require_once('fw/qdmail.php');
        mb_language('uni');
        $this->mail =  new Qdmail();
        $this->mail  -> charsetHeader( 'utf-8' ) ;
        //$this->mail  -> charsetBody( 'utf-8','Base64') ;
    }

    private function callTemplate(){
        $this->mail->resetHeader();
        $this->mail_template = new mailTemplate();
    }

    private function append(){
        //戻し
        $this->mail_template->ca_url = null;
        $this->mail_template->ca_url_ssl = null;
        $this->clearAll();
    }

    public function clearAll(){
        $this->mail->resetHeader();
        $this->mail->resetBody();
        $this->mail->resetHeaderBody();
    }

    //宛先/////////////////////////////////////////////////////////////////////////////////////////////////
    public function sendHalt($body,$shutdown_error = null){
        global $con;
        if($con->isDebug){
            $this->mail-> to( $this->halt_debug );
        }else{
            $this->mail-> to( $this->halt_real );
        }

        $body .= "\n\nhalt----------------------------------------------------\n\n";
        $body .= 'URL : '.$_SERVER['SCRIPT_NAME']."\n";
        if(isset($_SERVER['HTTP_REFERER'])) $body .= 'REFERER : '.$_SERVER['HTTP_REFERER']."\n";
        $body .= 'USER_AGENT : '.$_SERVER['HTTP_USER_AGENT']."\n";
        $body .= 'ADDR : '.$_SERVER['REMOTE_ADDR']."\n";
        
        if(!is_null($shutdown_error)){
            foreach ($shutdown_error as $key => $value){
                $body .= $key.$value."\n";
            }
        }
        $this->mail-> subject(LOCALE.':FS:Halt');
        $this->mail->text($body);
        $this->setFrom();
        $this->mail->send();
    }

    private function setRegistTo($mail){
        global $con;
        if($con->isDebug){
            $this->mail -> to( array($mail) );
            $this->mail -> bcc( $this->hachione_debug );
        }else{
            $this->mail -> to( array($mail) );
            $this->mail -> bcc( $this->hachione_real );
        }
    }

    private function setInquiryTo($mails){
        $to = array();
        $array_mail = explode(',',$mails);
        if($array_mail === FALSE){
            $this->mail -> to( array($mails) );
        }else{
            foreach ($array_mail as $key => $mail){
                $to[] = array($mail);
            }
            $this->mail -> to( $to );
        }
        global $con;
        if($con->isDebug){
            $this->mail -> bcc( $this->hachione_debug );
        }else{
            $this->mail -> bcc( $this->hachione_real );
        }
    }

    private function setHachioneTo(){
        global $con;
        if($con->isDebug){
            $this->mail -> to( $this->hachione_debug );
        }else{
            $this->mail -> to( $this->hachione_real );
        }
    }

    private function setUserTo(){
        $this->mail -> to( $_POST['mail'] );
    }

    private function setFrom(){
        if($con->isDebug){
            $this->mail->from( $this->hachione_debug );
        }else{
            $this->mail->from( $this->hachione_real );
        }
    }

    //基本処理///////////////////////////////////////////////////////////////////////////////////////


    public function sendInquiry($manage,$isUser = FALSE){
        require_once('inquiry/form.php');
        $form = new inquiryForm();

        if($isUser){
            $this->setUserTo();
        }else{
            $this->setInquiryTo($manage['col_mail']);
        }
        
        $subject = $isUser ? 'Thank you for your request:Tokyo Apartment rent' : 'Tokyo Apartment rent';
        $this->mail->subject($subject);

        $message = '';
        
        if($isUser){
            $message .= 'Hello Dear '.$_POST['name']."\n\n";
            $message .= 'Your request has been successfully recorded. '."\n";
            $message .= 'The owner or agents will contact you back shortly in order to precise the request procedures to you.'."\n\n";
        }else{
            $message .= 'Hello Dear '.$manage['manage_name']."\n\n";
            $message .= 'We would like to inform you that you have request from our customer through our web-site (Tokyo Apartments Rent.com).'."\n";
            $message .= 'When you receive this email, please confirm the request from our customer as below.'."\n";
            $message .= 'We would be grateful if you could resonse to our customer.'."\n\n";
            $message .= 'We thank you in advance for your response to our customer.'."\n\n";
        }

        $message .= "properties----------------------------------------------------\n";
        global $room_logic;
        
        foreach ($room_logic->save_room as $key => $room){
            if($isUser){
                $message .= '・'.$room['property_name'].' - '.$room['col_number']."\n";
            }elseif(!$isUser && strcasecmp($room['col_mid'],$manage['manage_id']) == 0){
                $message .= '・'.$room['property_name'].' - '.$room['col_number']."\n";
            }
        }

        $message .= "\ndetail----------------------------------------------------\n";


        $message .= '*Name'."\n";
        $message .= $_POST['name']."\n\n";

        $message .= '*E-mail'."\n";
        $message .= $_POST['mail']."\n\n";

        $message .= '*Telephone'."\n";
        $message .= $_POST['telephone']."\n\n";
        
        
        $currently_in_japan = $form->getYesNo();
        $message .= '*Currently in Japan'."\n";
        $message .= $currently_in_japan[$_POST['currently_in_japan']]."\n\n";
        
        $individual_company = $form->getIndividualCompany();
        $message .= '*Individual / Company'."\n";
        $message .= $individual_company[$_POST['individual_company']]."\n\n";

        $message .= '*Company name'."\n";
        $message .= $_POST['company_name']."\n\n";
        
        $individual_company = $form->getNumberOfPersons();
        $message .= '*Number of persons'."\n";
        $message .= $individual_company[$_POST['number_of_persons']];
        if(isset($_POST['children']) && in_array(1,$_POST['children'])) $message .= ' (Children)';
        $message .= "\n\n";
        
        $message .= '*Approximate move-in date'."\n";

        if(isset($_POST['move_in_date_timing']) && $_POST['move_in_date_timing'] > 0){
            if(strcasecmp($_POST['move_in_date_timing'],1) == 0){
                $message .= 'As soon as possible'."\n\n";
            }elseif (strcasecmp($_POST['move_in_date_timing'],2) == 0){
                $message .= 'Not decided'."\n\n";
            }
        }elseif(isset($_POST['move_in_date'])){
            $message .= date('Y/n/j',$_POST['move_in_date'])."\n\n";
        }

        $message .= '*Requests | Questions'."\n";
        $message .= $_POST['requests_questions']."\n\n";
        
        if(!$isUser){
            $message .= "\n".'----------------------------------------------------'."\n";
            $message .= 'Tokyo Apartment Rent'."\n";
            $message .= 'http://www.tokyoapt-rent.com/'."\n";
            $message .= '----------------------------------------------------';
            
            //パートナへの送信はreply
            $this->mail->replyto( $_POST['mail'] );
        }else{
            $message .= "\n".'Thanks for your trust.'."\n";
            $message .= "\n".'----------------------------------------------------'."\n";
            $message .= 'Tokyo Apartment Rent'."\n";
            $message .= 'http://www.tokyoapt-rent.com/'."\n";
            $message .= '----------------------------------------------------';
        }

        $this->mail->text($message);
        
        $from = array
        (
            array( 'info@tokyoapt-rent.com' , 'Tokyo Apartment Rent' )
        );
        $this->mail->from( $from );
        
        $this->mail->send();
        $this->append();
    }

    //デバッグ
    public function sendDebug($string){
        global $con;
        if($con->isDebug){
            $this->mail-> to( $this->dev_debug );
        }else{
            $this->mail-> to( $this->dev_real );
        }
        
        $this->mail-> subject('デバッグ');
        $this->mail->text($string);
        $this->setFrom();
        $this->mail->send();
    }
}
?>
