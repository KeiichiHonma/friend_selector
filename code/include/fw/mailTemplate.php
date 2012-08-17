<?php

class mailTemplate
{
    public $ca_url = 'http://www.oshiete-ca.com';
    public $ca_url_ssl = 'https://www.oshiete-ca.com';
    private $syomei = "「教えてCA！」運営事務局\n〒150-0044\n東京都渋谷区円山町25-4 加藤ビル3F\n\nお問い合わせ先：info@oshiete-ca.com\n土日・祝日・夏季・年末年始はお休みになります。";
    public $message = '';

    public function makeSubject($subject){
        return '【教えてCA!】'.$subject;
    }

    //ﾒﾝﾊﾞｰ登録
    public function makeRegistMemberMail($param){
        require_once('airline/logic.php');
        $airline = new airlineLogic();

        require_once('fw/formManager.php');
        $form = new formManager();

        $this->message .= $param['name']." 様\n\n";
        $this->message .= 'この度は教えてCA！メンバー登録誠にありがとうございます。'."\n\n";
        $this->message .= "ご登録内容----------------------------------------------------\n\n";
        
        $this->message .= '●メールアドレス'."\n";
        $this->message .= 'パソコン:'.$param['mail']."\n";
        $this->message .= '携帯:'.$param['mail_m']."\n";
        $this->message .= '●ログインメールアドレス選択'."\n";
        $this->message .= $form->getLoginType($param['login_type'])."\n";
        $this->message .= '●メンバーネーム'."\n";
        $this->message .= $param['name']."\n";
        
        //航空会社
        $air_text = '';
        if(strlen($param['airline1']) > 0){
            $air_text .= $airline->airline_info[$param['airline1']]['col_name'];
        }
        if(strlen($param['airline2']) > 0){
            $air_text .= ','.$airline->airline_info[$param['airline2']]['col_name'];
        }
        if(strlen($param['airline3']) > 0){
            $air_text .= ','.$airline->airline_info[$param['airline3']]['col_name'];
        }

        $this->message .= '●希望している航空会社'."\n";
        $this->message .= $air_text."\n";
        //$notice_mail = strcasecmp($param['notification_mail'],0) == 0 ? '受け取る' : '受け取らない';
        if(strlen($param['pr']) > 0){
            $this->message .= '●自己紹介'."\n";
            $this->message .= $param['pr']."\n";
        }
        $this->message .= '●採用関連等のお知らせ'."\n";
        $this->message .= $form->getNotificationMail($param['magazine_mail'])."\n";
        
        $this->message .= '●回答通知メール'."\n";
        $this->message .= $form->getNotificationMail($param['notification_mail'])."\n";

        $this->message .= '●お名前'."\n";
        $this->message .= $param['given_name']."\n";
        $this->message .= '●お名前（ふりがな）'."\n";
        $this->message .= $param['kana']."\n";
        $this->message .= '●郵便番号'."\n";
        $this->message .= $param['postcode1'].'－'.$param['postcode2']."\n";
        $this->message .= '●生年月日'."\n";
        $this->message .= $param['birthdate_year'].'年'.$param['birthdate_month'].'月'.$param['birthdate_day']."日\n\n";
        
        $this->message .= '教えてCA！'."\n";
        $this->message .= $this->ca_url."/\n\n";
        
        $this->message .= '質問する！'."\n";
        $this->message .= $this->ca_url.'/question/input'."\n\n";
        
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeResetMemberMail($member,$rand){
        $this->message .= $member[0]['col_name']." 様\n\n";
        $this->message .= '教えてCA！運営事務局よりパスワード再発行手続きのお知らせです。'."\n";
        $this->message .= '以下のページよりパスワード再発行の手続きを行い、そのパスワードで'."\n";
        $this->message .= '今後はログインするようにしてください。'."\n\n";

        $this->message .= $this->ca_url_ssl.'/reminder/reset/input/code/'.$rand."\n\n";
        $this->message .= '※メール環境によっては上記URLが2行で表示されている（改行されている）'."\n";
        $this->message .= '場合がありますが、実際には1行のURLです。'."\n";
        $this->message .= 'クリックしてもページが表示されない場合、URLを1行に修正した上で'."\n";
        $this->message .= 'アクセスしてください。'."\n\n";

        $this->message .= '※上記URLの有効期間は1時間です。'."\n";
        $this->message .= '1時間以内にアクセスしていただけなかった場合は、再度以下のURLよりお手続きをお願い致します。'."\n";

        $this->message .= $this->ca_url_ssl.'/reminder/do/input'."\n\n";

        //念のためログイン方法を記載
        $this->message .= "------------------------------------------------------------------------\n";
        $this->message .= $member[0]['col_name']." 様のログインメールアドレスは\n";
        if(strcasecmp($member[0]['col_login_type'],LOGIN_MOBILE) == 0){
            $this->message .= '携帯のメールアドレスでログインする設定になっております。'."\n";
            $this->message .= 'ログインする際のメールアドレスの欄には、携帯のメールアドレスをご入力くださいませ。'."\n";
        }else{
            $this->message .= 'パソコンのメールアドレスでログインする設定になっております。'."\n";
            $this->message .= 'ログインする際のメールアドレスの欄には、パソコンのメールアドレスをご入力くださいませ。'."\n";
        }
        $this->message .= "------------------------------------------------------------------------\n\n";
        
        $this->message .= 'なお、このメールに覚えがない場合、他の方がメールアドレスを間違えて'."\n";
        $this->message .= '入力された可能性があります。パスワードを変更されることはございません'."\n";
        $this->message .= 'のでご安心ください。'."\n\n";

        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeLeaveAdminMail($member){
        $this->message .= $member[0]['col_name']." 様が退会しました。\n\n";
        $this->message .= 'メンバーID：'.$member[0]['_id']."\n";
    }

    public function makeAnswerMail($member_name,$qa){
        global $con;
        $this->message .= $member_name." 様\n\n";
        $this->message .= '教えてCA！公認CAから新しい回答が投稿されました'."\n";
        $this->message .= '下記のページにて回答を確認することができます。'."\n\n";

        $this->message .= $this->ca_url.'/question/view/qid/'.$qa->question[0]['question_id']."\n\n";

        $this->message .= '※メール環境によっては上記ＵＲＬが２行で表示されている（改行されている）'."\n";
        $this->message .= '場合がありますが、実際には１行のＵＲＬです。'."\n";
        $this->message .= 'クリックしてもページが表示されない場合、ＵＲＬを１行に修正した上で'."\n";
        $this->message .= 'アクセスしてください。'."\n\n";

        $this->message .= "ご質問内容----------------------------------------------------\n";
        $this->message .= '●質問日時'."\n";
        $this->message .= date("Y年m月d日 H:i",$qa->question[0]['question_ctime'])."\n";
        $this->message .= '●質問カテゴリ'."\n";
        $this->message .= $con->category->category_info[$qa->question[0]['col_cid']]['col_name']."\n";
        $this->message .= '●質問タイトル'."\n";
        $this->message .= $qa->question[0]['col_title']."\n\n";

        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    }

    public function makeApplyMail($param,$present){
        $this->message .= $param['given_name']." 様\n\n";
        $this->message .= 'この度は'."\n";
        $this->message .= '教えてCA！'.$present[0]['col_name'].'キャンペーンにご応募いただきまして'."\n";
        $this->message .= '誠にありがとうございます。'."\n\n";

        $this->message .= '当選発表は'.$present[0]['col_lucky_date'].'を予定しております。'."\n\n";

        $this->message .= "ご応募内容----------------------------------------------------\n";
        $this->message .= '●お名前'."\n";
        $this->message .= $param['given_name']."\n";
        $this->message .= '●お名前（ふりがな）'."\n";
        $this->message .= $param['kana']."\n";
        $this->message .= '●郵便番号'."\n";
        $this->message .= $param['postcode1'].'－'.$param['postcode2']."\n";
        $this->message .= '●住所'."\n";
        $this->message .= $param['address']."\n";
        $this->message .= '●生年月日'."\n";
        $this->message .= $param['birthdate_year'].'年'.$param['birthdate_month'].'月'.$param['birthdate_day']."日\n";
        $this->message .= '●電話番号'."\n";
        $this->message .= $param['telephone1'].'－'.$param['telephone2'].'－'.$param['telephone3']."\n";
        
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeSchoolApplyMailForMember($param,$school_name,$hope_hub){
        require_once('job/logic.php');
        $job_logic = new jobLogic();
        
        $this->message .= $param['given_name']." 様\n\n";
        $this->message .= 'この度は'."\n";
        $this->message .= $school_name.'への資料請求'."\n";
        $this->message .= '誠にありがとうございます。'."\n\n";

        $this->message .= "申込内容----------------------------------------------------\n";
        $this->message .= '●資料請求校'."\n";
        $this->message .= $school_name."\n";

        $this->message .= '●通学希望校'."\n";
        $this->message .= $hope_hub."\n";

        $this->message .= '●メールアドレス'."\n";
        $this->message .= $param['mail']."\n";
        $this->message .= '●お名前'."\n";
        $this->message .= $param['given_name']."\n";
        $this->message .= '●お名前（ふりがな）'."\n";
        $this->message .= $param['kana']."\n";
        $this->message .= '●郵便番号'."\n";
        $this->message .= $param['postcode1'].'－'.$param['postcode2']."\n";
        $this->message .= '●住所'."\n";
        $this->message .= $param['address']."\n";
        $this->message .= '●生年月日'."\n";
        $this->message .= $param['birthdate_year'].'年'.$param['birthdate_month'].'月'.$param['birthdate_day']."日\n";
        $this->message .= '●電話番号'."\n";
        $this->message .= $param['telephone1'].'－'.$param['telephone2'].'－'.$param['telephone3']."\n";
        $this->message .= '●学年/ご職業'."\n";
        $this->message .= $job_logic->job_info[$param['jid']]['col_name']."\n";
        $this->message .= '●学校名'."\n";
        $this->message .= $param['your_school']."\n";
        $this->message .= '●ご意見・ご質問など'."\n";
        $this->message .= $param['remarks']."\n";

        $this->message .= "\n".'近日中にご依頼のスクールより資料送付させて頂きますので、しばらくお待ち下さい。'."\n";
        $this->message .= '何卒宜しくお願いいたします。'."\n";

        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeSchoolApplyMailForSchool($param,$school_name,$hope_hub){
        require_once('job/logic.php');
        $job_logic = new jobLogic();
        
        $this->message .= $school_name." 様\n\n";

        $this->message .= '下記の通り資料請求がありました。'."\n";
        $this->message .= 'ご対応の程よろしくお願いいたします。'."\n";
        $this->message .= '（このメールは、内容をご確認いただくための自動送信メールです。）'."\n";
        $this->message .= '（このメールへ返信されても、資料請求した方へメールは送信されません。）'."\n\n";

        $this->message .= "申込情報----------------------------------------------------\n";
        $this->message .= '●通学希望校'."\n";
        $this->message .= $hope_hub."\n";

        $this->message .= '●メールアドレス'."\n";
        $this->message .= $param['mail']."\n";
        $this->message .= '●お名前'."\n";
        $this->message .= $param['given_name']."\n";
        $this->message .= '●お名前（ふりがな）'."\n";
        $this->message .= $param['kana']."\n";
        $this->message .= '●郵便番号'."\n";
        $this->message .= $param['postcode1'].'－'.$param['postcode2']."\n";
        $this->message .= '●住所'."\n";
        $this->message .= $param['address']."\n";
        $this->message .= '●生年月日'."\n";
        $this->message .= $param['birthdate_year'].'年'.$param['birthdate_month'].'月'.$param['birthdate_day']."日\n";
        $this->message .= '●電話番号'."\n";
        $this->message .= $param['telephone1'].'－'.$param['telephone2'].'－'.$param['telephone3']."\n";
        $this->message .= '●学年/ご職業'."\n";
        $this->message .= $job_logic->job_info[$param['jid']]['col_name']."\n";
        $this->message .= '●学校名'."\n";
        $this->message .= $param['your_school']."\n";
        $this->message .= '●ご意見・ご質問など'."\n";
        $this->message .= $param['remarks']."\n";

        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeSeminarApplyMailForMember($param,$school_name,$location_name,$seminar,$date_string){
        require_once('job/logic.php');
        $job_logic = new jobLogic();
        
        $this->message .= $param['given_name']." 様\n\n";
        $this->message .= 'この度は'."\n";
        $this->message .= $seminar[0]['col_title'].'への申込'."\n";
        $this->message .= '誠にありがとうございます。'."\n\n";

        $this->message .= "申込内容----------------------------------------------------\n";
        $this->message .= '●セミナー・イベント名'."\n";
        $this->message .= $seminar[0]['col_title']."\n";
        
        $this->message .= '●開催校'."\n";
        $this->message .= $school_name."\n";

        $this->message .= '●開催場所'."\n";
        $this->message .= $location_name."\n";

        $this->message .= '●開催日時'."\n";
        $this->message .= $date_string."\n";

        $this->message .= '●メールアドレス'."\n";
        $this->message .= $param['mail']."\n";
        $this->message .= '●お名前'."\n";
        $this->message .= $param['given_name']."\n";
        $this->message .= '●お名前（ふりがな）'."\n";
        $this->message .= $param['kana']."\n";
        $this->message .= '●郵便番号'."\n";
        $this->message .= $param['postcode1'].'－'.$param['postcode2']."\n";
        $this->message .= '●住所'."\n";
        $this->message .= $param['address']."\n";
        $this->message .= '●生年月日'."\n";
        $this->message .= $param['birthdate_year'].'年'.$param['birthdate_month'].'月'.$param['birthdate_day']."日\n";
        $this->message .= '●電話番号'."\n";
        $this->message .= $param['telephone1'].'－'.$param['telephone2'].'－'.$param['telephone3']."\n";
        $this->message .= '●学年/ご職業'."\n";
        $this->message .= $job_logic->job_info[$param['jid']]['col_name']."\n";
        $this->message .= '●学校名'."\n";
        $this->message .= $param['your_school']."\n";
        $this->message .= '●ご意見・ご質問など'."\n";
        $this->message .= $param['remarks']."\n";

        $this->message .= "\n".'お申し込み頂いたセミナー・イベントに関して、ご質問や不明点がある場合は開催スクールまで直接お問い合わせ下さい。'."\n";
        $this->message .= '何卒宜しくお願いいたします。'."\n";

        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeSeminarApplyMailForSchool($param,$school_name,$location_name,$seminar,$date_string){
        require_once('job/logic.php');
        $job_logic = new jobLogic();
        
        $this->message .= $school_name." 様\n\n";

        $this->message .= '下記の通り'.$seminar[0]['col_title'].'への申込がありました。'."\n";
        $this->message .= 'ご対応の程よろしくお願いいたします。'."\n";
        $this->message .= '（このメールは、内容をご確認いただくための自動送信メールです。）'."\n";
        $this->message .= '（このメールへ返信されても、申込した方へメールは送信されません。）'."\n\n";

        $this->message .= "申込情報----------------------------------------------------\n";
        $this->message .= '●セミナー・イベント名'."\n";
        $this->message .= $seminar[0]['col_title']."\n";

        $this->message .= '●開催場所'."\n";
        $this->message .= $location_name."\n";

        $this->message .= '●開催日時'."\n";
        $this->message .= $date_string."\n";

        $this->message .= '●メールアドレス'."\n";
        $this->message .= $param['mail']."\n";
        $this->message .= '●お名前'."\n";
        $this->message .= $param['given_name']."\n";
        $this->message .= '●お名前（ふりがな）'."\n";
        $this->message .= $param['kana']."\n";
        $this->message .= '●郵便番号'."\n";
        $this->message .= $param['postcode1'].'－'.$param['postcode2']."\n";
        $this->message .= '●住所'."\n";
        $this->message .= $param['address']."\n";
        $this->message .= '●生年月日'."\n";
        $this->message .= $param['birthdate_year'].'年'.$param['birthdate_month'].'月'.$param['birthdate_day']."日\n";
        $this->message .= '●電話番号'."\n";
        $this->message .= $param['telephone1'].'－'.$param['telephone2'].'－'.$param['telephone3']."\n";
        $this->message .= '●学年/ご職業'."\n";
        $this->message .= $job_logic->job_info[$param['jid']]['col_name']."\n";
        $this->message .= '●学校名'."\n";
        $this->message .= $param['your_school']."\n";
        $this->message .= '●ご意見・ご質問など'."\n";
        $this->message .= $param['remarks']."\n";

        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $this->syomei;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }

    public function makeNewJudgmentMail($question_count,$j_question,$not_thank_count,$j_not_thank,$thank_count,$j_thank){
        global $con;
        $this->message .= "以下の内容を確認して、\n";
        $this->message .= "公開/非公開の判断をお願いいたします。\n";
        
        if($question_count > 0){
            $this->message .= "\n".'[質問]'."\n";
            $this->message .= '■未判断が'.$question_count.'件あります。'."\n";
            foreach ($j_question as $key => $value){
                $this->message .= '・'.$con->base->mb_strimbyte($value['col_title']).'/'.$value['col_member']."\n";
            }
        }

        if($not_thank_count > 0){
            $this->message .= "\n".'[補足]'."\n";
            $this->message .= '■未判断が'.$not_thank_count.'件あります。'."\n";
            foreach ($j_not_thank as $key => $value){
                $this->message .= '・'.$con->base->mb_strimbyte($value['col_reply']).'/'.$value['col_member']."\n";
            }
        }

        if($not_thank_count > 0){
            $this->message .= "\n".'[お礼]'."\n";
            $this->message .= '■未判断が'.$thank_count.'件あります。'."\n";
            foreach ($j_thank as $key => $value){
                $this->message .= '・'.$con->base->mb_strimbyte($value['col_reply']).'/'.$value['col_member']."\n";
            }
        }

        $this->message .= "\n".$this->syomei;
    }

    public function makeNewQuestionMail($question_count,$n_question,$not_thank_count,$n_not_thank,$thank_count,$n_thank){
        $before = time() - 10800;//3時間前
        $from = date("H",$before);
        $to = $from+3;
        global $con;

        $this->message .= $from.'時から'.$to.'時の間に'."\n";
        $this->message .= '質問、補足、お礼が公開されています。'."\n";

        if($question_count > 0){
            $this->message .= "\n".'[質問]'."\n";
            $this->message .= '■'.$question_count.'件公開されました。'."\n";
            foreach ($n_question as $key => $value){
                $this->message .= '・'.$con->base->mb_strimbyte($value['col_title']).'/'.$value['col_member']."\n";
            }
        }

        if($not_thank_count > 0){
            $this->message .= "\n".'[補足]'."\n";
            $this->message .= '■'.$not_thank_count.'件公開されました。'."\n";
            foreach ($n_not_thank as $key => $value){
                $this->message .= '・'.$con->base->mb_strimbyte($value['col_reply']).'/'.$value['col_member']."\n";
            }
        }

        if($thank_count > 0){
            $this->message .= "\n".'[お礼]'."\n";
            $this->message .= '■'.$thank_count.'件公開されました。'."\n";
            foreach ($n_thank as $key => $value){
                $this->message .= '・'.$con->base->mb_strimbyte($value['col_reply']).'/'.$value['col_member']."\n";
            }
        }
        
        $this->message .= "\n".'質問、補足に関してはご回答お願いいたします。'."\n";
        $this->message .= '※閲覧時に、質問が解決になっている場合もあります。'."\n";
        $this->message .= 'お礼は内容の確認をお願いいたします。'."\n";

        $this->message .= "\n".$this->syomei;
    }

    public function makeReportMail($name,$report){
        //header///////////
        $this->message .= '━━ http://www.oshiete-ca.com/ ━━━━━━━━━━━━━━━━━━━━'."\n";
        $this->message .= '                 ■□■　『教えてCA!』ニュース　■□■'."\n";
        $this->message .= '━━━━━━━━━━━━━━━━━━━━━━━━━━━ '.date("Y/n/j",time()).' ━━━'."\n\n";
        $this->message .= $name." 様こんにちわ！\n\n";
        $this->message .= '『教えてCA!』運営事務局 からキャビンアテンダントニュースをお送りいたします。'."\n\n\n";
        
        $this->message .= $report['col_report'];

        $this->message .= "\n".'────────────────────────────────────┐'."\n";
        $this->message .= '発行：『教えてCA!』運営事務局'."\n";
        $this->message .= 'URL：http://www.oshiete-ca.com/'."\n";
        $this->message .= 'E-Mail：info@oshiete-ca.com'."\n";
        $this->message .= '運営会社：株式会社ハチワン'."\n\n";
        $this->message .= 'このメールに書かれた内容の無断掲載、無断複製を禁じます。'."\n";
        $this->message .= '────────────────────────────────────┘'."\n";
    }

    public function makeGoikenMail($goiken,$member = null,$mobile){
        if($mobile){
            $this->message .= "携帯から";
        }else{
            $this->message .= "パソコンから";
        }
        $this->message .= "ご意見が寄せられました。\n";
        $this->message .= "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        $this->message .= $goiken;
        $this->message .= "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        if(!is_null($member)){
            require_once('airline/logic.php');
            $airline = new airlineLogic();

            require_once('fw/formManager.php');
            $form = new formManager();
            
            $this->message .= '●メンバーID：'.$member[0]['_id']."\n";
            
            $this->message .= '●メールアドレス'."\n";
            $this->message .= 'パソコン:'.$member[0]['col_mail']."\n";
            $this->message .= '携帯:'.$member[0]['col_mail_m']."\n";
            $this->message .= '●ログインメールアドレス選択'."\n";
            $this->message .= $form->getLoginType($member[0]['col_login_type'])."\n";
            $this->message .= '●メンバーネーム'."\n";
            $this->message .= $member[0]['col_name']."\n";
            
            //航空会社
            $air_text = '';
            if(strlen($member[0]['col_airline1']) > 0){
                $air_text .= $airline->airline_info[$member[0]['col_airline1']]['col_name'];
            }
            if(strlen($member[0]['col_airline2']) > 0){
                $air_text .= ','.$airline->airline_info[$member[0]['col_airline2']]['col_name'];
            }
            if(strlen($member[0]['col_airline3']) > 0){
                $air_text .= ','.$airline->airline_info[$member[0]['col_airline3']]['col_name'];
            }

            $this->message .= '●希望している航空会社'."\n";
            $this->message .= $air_text."\n";

            if(strlen($member[0]['col_pr']) > 0){
                $this->message .= '●自己紹介'."\n";
                $this->message .= $member[0]['col_pr']."\n";
            }
            $this->message .= '●採用関連等のお知らせ'."\n";
            $this->message .= $form->getNotificationMail($member[0]['col_magazine_mail'])."\n";
            
            $this->message .= '●回答通知メール'."\n";
            $this->message .= $form->getNotificationMail($member[0]['col_notification_mail'])."\n";

            $this->message .= '●お名前'."\n";
            $this->message .= $member[0]['col_given_name']."\n";
            $this->message .= '●お名前（ふりがな）'."\n";
            $this->message .= $member[0]['col_kana']."\n";
            $this->message .= '●郵便番号'."\n";
            $this->message .= $member[0]['col_postcode1'].'－'.$member[0]['col_postcode2']."\n";
            $this->message .= '●生年月日'."\n";
            $this->message .= $member[0]['col_birthdate_year'].'年'.$member[0]['col_birthdate_month'].'月'.$member[0]['col_birthdate_day']."日\n\n";
            
            $this->message .= '質問、掲示板の履歴'."\n";
            $this->message .= $this->ca_url.'/member/view/mid/'.$member[0]['_id']."\n\n";
        }else{
            $this->message .= 'FROM:匿名の方'."\n";
        }
    }

    public function makeMobileUrlMail(){
        $this->message .= '以下のURLから教えてCA！モバイルにアクセスしてください。'."\n";
        $this->message .= "-----------------------\n";
        $this->message .= "***『教えてCA!モバイル』***\n";
        $this->message .= $this->ca_url.'/'."\n";
        $this->message .= "-----------------------\n";
        
        $this->message .= $this->syomei;
    }

}
?>
