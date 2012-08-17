<?php
function smarty_modifier_make_qa_list($qa,$isLarge = SMARTY_BOOL_OFF,$isSystem = SMARTY_BOOL_OFF,$words = null)
{
    if(!$qa) return '表示するべき質問がありません';
    global $con;

    require_once('fw/uri.php');
    $uri = new uri();

    //設定
    $isExtends = FALSE;
    $class_name = 'common';
    $size_string = '';
    $url_head = CAURL;

    //判定
    $isAnswer = isset($qa[0]['col_answer']) && $con->pagename == 'answer' ? TRUE : FALSE;//最近の回答だけ
    $isReply = isset($qa[0]['col_reply']) && $con->pagename == 'reply' ? TRUE : FALSE;
    $isSearch = $con->pagename == 'search' ? TRUE : FALSE;

    //exrends
    if($isAnswer || $isReply || $isSearch){
        $isExtends = TRUE;
        $class_name = 'extends';//先頭アイコン処理
    }
    //large
    if($isLarge == SMARTY_BOOL_ON) $size_string = '_l';
    //system
    if($isSystem == SMARTY_BOOL_ON){
        $url_head = CAURLSSL.'/system';
    }

    $title_trim_len = 80;
    $member_trim_len = 30;
    
    if($isLarge == SMARTY_BOOL_ON){
        if($isAnswer){
            $text_trim_len = 192;
            $br_len = 96;
        }else{
            //system用のreplyとか
            $text_trim_len = 200;
            $br_len = 100;
        }
    }else{
        if($isAnswer){
            $text_trim_len = 140;
            /*$member_trim_len = 30;*/
            $br_len = 76;
        }elseif($isSearch){
            $title_trim_len = 60;//検索の時はboldされるため
            $text_trim_len = 150;//検索の時はboldされるため
            $member_trim_len = 34;
            $br_len = 50;
            //keyword
            $keyword = trim(mb_convert_kana($_GET['keyword'], "s",'UTF-8'));//全角スペースを半角にしてからtrim
            //$words = explode(' ',$keyword);
        }else{
            $text_trim_len = 140;
        }
    }

    $i = 1;
    //ul//////////////////////////////
    $html .= '<ul id="qalist'.$size_string.'">';
    foreach ($qa as $key => $value){
        $style_b = $i / 2;
        $date = isset($value['answer_ctime']) ? $value['answer_ctime'] : $value['question_ctime'];

        //time name
        if(isset($value['answer_ctime'])){
            $time = $value['answer_ctime'];
            
            $tmp_name = $isLarge == SMARTY_BOOL_OFF ? $con->base->mb_strimbyte($value['col_user'],$member_trim_len) : $value['col_user'];//large系は省略しない
            //$name = '回答者：'.'<a href="'.CAURL.'/ca/#ca'.$value['col_uid'].'" class="gray_link">'.$tmp_name.'</a>';
            $name = '回答者：'.htmlspecialchars($tmp_name);
        }else{
            
            if(isset($value['reply_ctime'])){
                $time = $value['reply_ctime'];
                $name = '<img src="/img/common/member_on.png" alt="" width="20" height="20"  class="alphafilter" />';
            }else{
                $time = $value['question_ctime'];
                $name = '<img src="/img/common/member_on.png" alt="" width="20" height="20"  class="alphafilter" />';
            }
            $tmp_name = $isLarge == SMARTY_BOOL_OFF ? $con->base->mb_strimbyte($value['col_member'],$member_trim_len) : $value['col_member'];//large系は省略しない
            $name .= '<a href="'.CAURL.'/member/view/mid/'.$value['col_mid'].'" class="gray_link">'.htmlspecialchars($tmp_name).'</a>';
        }

        //li//////////////////////////////
        $html .= is_int($style_b) ? '<li class="list_'.$class_name.'_b clearfix">' : '<li class="list_'.$class_name.' clearfix">';
        //dl//////////////////////////////
        if($value['col_status'] == STATUS_FINISH){
            $html .= '<img src="/img/question/finish_s.gif" alt="" width="31" height="30" class="'.$class_name.'" />';
            $html .= '<dl>';
        }else{
            $html .= '<dl class="common">';
        }
        //dt//////////////////////////////
        if($isLarge == SMARTY_BOOL_OFF){
            $title = $con->base->mb_strimbyte($value['col_title'],$title_trim_len);
        }else{
            $title = $value['col_title'];//large系は省略しない
        }
        //bold
        if($isSearch){
            //$title = $uri->autobold(htmlspecialchars($title),$words);
            $title = $uri->autobold($title,$words);
        }else{
            $title = htmlspecialchars($title);
        }
        if(strcasecmp($value['col_status'],STATUS_FINISH) == 0){
            $html .= '<dt class="question_finish">';
        }else{
            $html .= '<dt class="question">';
        }
        $html .= '<a href="'.$url_head.'/question/view/qid/'.$value['question_id'].'">'.$title.'</a></dt>';
        //dd//////////////////////////////
        //category
        $html .= '<dd class="category"><a href="'.$url_head.'/category/view/cid/'.$value['col_cid'].'" class="gray_link">'.$con->category->category_info[$value['col_cid']]['col_name'].'</a></dd>';
        //date name
        $html .= '<dd class="txt">'.'&nbsp;<img src="/img/common/date_edit.gif" alt="" width="20" height="20" />'.date("y/m/d H:i",$time).'&nbsp;'.$name.'</dd>';
        //回答数
        if(!isset($value['answerscore'])){//search
            $html .= '<dd class="answer_blank">&nbsp;</dd>';
            
        }elseif($value['answerscore'] > 0){
            $html .= '<dd class="answer">'.$value['answerscore'].'件</dd>';
        }else{
            $html .= '<dd class="answer_no">'.$value['answerscore'].'件</dd>';
        }
        //内容
        if($isExtends){
            if($isSearch){
                //$data = $con->base->mb_strim_square($value['col_question'],$text_trim_len,$br_len,'…',SMARTY_BOOL_OFF);
                $data = $con->base->make_text($value['col_question'],SMARTY_BOOL_OFF,$text_trim_len);
                $r = $uri->autobold($data,$words);
                $html .= '<dd class="extends_txt'.$size_string.'">'.$r.'</dd>';
            }elseif($isAnswer){
                //$data = $con->base->mb_strim_square($value['col_answer'],$text_trim_len,$br_len,'…',SMARTY_BOOL_OFF);
                $data = $con->base->make_text($value['col_answer'],SMARTY_BOOL_OFF,$text_trim_len);
                $html .= '<dd class="teacher"><img src="/img/teacher/'.$value['col_uid'].'_s.gif" alt="CA" width="40" height="40" /></dd>';
                $html .= '<dd class="extends_answer_txt'.$size_string.'">'.$data.'&nbsp;<a href="'.$url_head.'/question/view/qid/'.$value['question_id'].'#answer'.$value['answer_id'].'"><img src="/img/common/b_more.gif" alt="もっと見る" width="76" height="13" border="0" class="btn" /></a></dd>';
            }elseif($isReply){
                //$data = $con->base->mb_strim_square($value['col_reply'],$text_trim_len,$br_len,'…',SMARTY_BOOL_OFF);
                $data = $con->base->make_text($value['col_reply'],SMARTY_BOOL_OFF,$text_trim_len);
                $html .= '<dd class="extends_txt'.$size_string.'">'.$data.'&nbsp;<a href="'.$url_head.'/question/view/qid/'.$value['question_id'].'#reply'.$value['reply_id'].'"><img src="/img/common/b_more.gif" alt="もっと見る" width="76" height="13" border="0" class="btn" /></a></dd>';
            }
        }
        $html .= '</dl>';
        $html .= '</li>';
        $i++;
    }
    $html .= '</ul>';
    $html .= '
    
    <p class="question_help">
    <br /><span class="pink">アイコンの見方</span><br />
    <img src="/img/question/question.gif" alt="" width="16" height="16" border="0" />&nbsp;現在進行中の質問<br />
    <img src="/img/question/question_finish.gif" alt="" width="16" height="16" border="0" />&nbsp;解決済みの質問<br />
    </p>';
    return $html;
}
?>
