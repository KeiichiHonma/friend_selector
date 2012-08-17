<?php
class uri
{
/**
 * 自動リンクフィルターの実装
 *
 * @param  string $string   入力
 * @param  string $popup    外部リンクをポップアップ
 * @return string
 */
    public $url_pattern = '(https?://[a-zA-Z0-9/_.?#&;=$+:@%~,\\-]+)';
    public $email_pattern = '([a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*@[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*)';
    
    function autolink( $string, $popup = FALSE,$isLink = SMARTY_BOOL_ON)
    {
        if( 0 == strlen( $string ) )
        {
            return $string;
        }
        
    /*    require_once( 'grn/uum.csp' );
        global $G_container_base;
        $uum =& $G_container_base->getInstance( 'uum' );
        $login =& $uum->getLoginUser();
        
        $app_id = NULL;
        if ( $login ) {
            require_once( 'grn/ui.csp' );
            $manager =& GRN_UIConfigManager::static_getInstance();
            $config =& $manager->getUserConfig( $login );
            $app_id = $config->getMailToApplication();
        }
        
        $page_array = NULL;
        if ( $app_id ) {
            require_once( 'grn/application.csp' );
            $locator =& GRN_ApplicationLocator::static_getInstance();
            if ( $locator->isAvailable( $app_id ) ) {
                $app =& $locator->getInstance( $app_id );
                if ( is_a( $app, 'GRN_ApplicationBase' ) &&
                     in_array( 'getutility', get_class_methods( get_class( $app ) ) ) )
                {
                    $utility =& $app->getUtility();
                    $system =& $utility->getSystemConfig();
                    $system->getGeneralSetting( $general_settings );
                    if( ! $general_settings['disable_mail'] )
                    {
                        $page_array = $app->getMailPageInfo( 'send' );
                    }
                }
            }
        }*/
            
        //$suffix = (strncasecmp(PHP_OS, 'WIN', 3) === 0) ? 'exe' : 'cgi';
        //$exe_pattern = GRN_UI_EXENAME.'\\.'.$suffix.'(/(([a-zA-Z0-9_.%+\\-]+/)*[a-zA-Z0-9_.%+\\-]+)(/-/[a-zA-Z0-9/_.%+\\-]+)?(\\?([a-zA-Z0-9/_.&;=+%\\-]+((#[a-zA-Z0-9_.%+\\-]+)?))?)?)?';
        //$url_pattern = '(https?://[a-zA-Z0-9/_.?#&;=$+:@%~,\\-]+)';
        //$email_pattern = '([a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*@[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*)';
        $popup_string = $popup ? ' target="_blank"' : '';
        
        $matches = array();
    /*    mb_ereg_search_init( $string, $exe_pattern );
        while ( ( $ret = mb_ereg_search_pos() ) !== FALSE ) {
            $regs = mb_ereg_search_getregs();
            $matches[$ret[0]] = array( 'type'=>0, 'len'=>$ret[1], 'regs'=>$regs );
        }*/
        
        // UNIX系の場合
    /*    if( strncasecmp(PHP_OS, 'WIN', 3) !== 0 )
        {
            $cgi_pattern = GRN_UI_EXENAME.'\\.exe(/(([a-zA-Z0-9_.%+\\-]+/)*[a-zA-Z0-9_.%+\\-]+)(/-/[a-zA-Z0-9/_.%+\\-]+)?(\\?([a-zA-Z0-9/_.&;=+%\\-]+((#[a-zA-Z0-9_.%+\\-]+)?))?)?)?';
            mb_ereg_search_init( $string, $cgi_pattern );
            while ( ( $ret = mb_ereg_search_pos() ) !== FALSE ) {
                $regs = mb_ereg_search_getregs();
                $matches[$ret[0]] = array( 'type'=>0, 'len'=>$ret[1], 'regs'=>$regs );
            }
        }*/
        
        mb_ereg_search_init( $string, $this->url_pattern );
        while ( ( $ret = mb_ereg_search_pos() ) !== FALSE ) {
            $regs = mb_ereg_search_getregs();
            $matches[$ret[0]] = array( 'type'=>1, 'len'=>$ret[1], 'regs'=>$regs );
        }
        //mailをリンクにする
    /*    mb_ereg_search_init( $string, $email_pattern );
        while ( ( $ret = mb_ereg_search_pos() ) !== FALSE ) {
            $regs = mb_ereg_search_getregs();
            $matches[$ret[0]] = array( 'type'=>2, 'len'=>$ret[1], 'regs'=>$regs );
        }*/
        ksort($matches);
        $matches[strlen($string)] = array( 'type'=>3, 'len'=>0, 'regs'=>NULL );
        $ptr = 0;
        $ret_string = '';
        foreach ( $matches as $start => $info ) {
            if ( $start < $ptr ) continue;
            
            $type = $info['type'];
            $len = $info['len'];
            $regs = $info['regs'];
            $ret_string .= htmlspecialchars( substr( $string, $ptr, $start - $ptr ) );

            //文字の丸め
            if(strlen($regs[0]) > 70){
                $url_name = mb_strimwidth($regs[0],0,70,'...','UTF-8');
            }else{
                $url_name = $regs[0];
            }
            
            switch ( $info['type'] ) {
            case 0:
                //$link = cb_format_url( $regs[2] );
                if( $regs[4] || $regs[5] ) $link = substr( $link,0,  strlen( $link ) - 1 );
                if($isLink == SMARTY_BOOL_OFF){
                    $link = htmlspecialchars( $url_name );//丸めた文字だけ
                }else{
                    //$link = '<a href="' . $link . $regs[4] . $regs[5] . '">' .htmlspecialchars( $url_name ) . '</a>';
                    //$link = "\r\n".'<a href="' . $link . $regs[4] . $regs[5] . '">' .htmlspecialchars( $url_name ) . '</a>'."\r\n";
                    $link = "\t".'<a href="' . $link . $regs[4] . $regs[5] . '">' .htmlspecialchars( $url_name ) . '</a>'."\t";
                }
                $ret_string .= $link;
                break;
            case 1:
                if($isLink == SMARTY_BOOL_OFF){
                    $link = htmlspecialchars( $url_name );//丸めた文字だけ
                }else{
                    //$link = '<a href="' . $regs[0] . '"' . $popup_string . '>' .htmlspecialchars( $url_name ) . '</a>';
                    //$link = "\r\n".'<a href="' . $regs[0] . '"' . $popup_string . '>' .htmlspecialchars( $url_name ) . '</a>'."\r\n";
                    $link = "\t".'<a href="' . $regs[0] . '"' . $popup_string . '>' .htmlspecialchars( $url_name ) . '</a>'."\t";
                }
                $ret_string .= $link;
                break;
            case 2:
                    //mailto
    /*                $link = '<a href="mailto:' . $regs[0] . '">' .
                        htmlspecialchars( $regs[0] ) . '</a>';
                    $ret_string .= $link;*/
                    
                break;
    /*        case 2:
                if ( ! is_array( $page_array ) ||
                     ! array_key_exists( 'rcpt_param_name', $page_array ) ) {
                    $link = '<a href="mailto:' . $regs[0] . '">' .
                        htmlspecialchars( $regs[0] ) . '</a>';
                    $ret_string .= $link;
                } else {
                    if ( array_key_exists( 'params', $page_array ) ) {
                        $params = $page_array['params'];
                    } else {
                        $params = array();
                    }
                    $params[$page_array['rcpt_param_name']] = $regs[0];
                    $url = cb_format_url( $page_array['page'], $params );
                    $link = '<a href="' . $url . '">' .
                        htmlspecialchars( $regs[0] ) . '</a>';
                    $ret_string .= $link;
                }
                break;*/
            }
            
            $ptr = $start + $len;
        }
        
        return $ret_string;
    }

    function autobold( $string,$keyword )
    {
        if( 0 == strlen( $string ) || 0 == strlen( $keyword ) )
        {
            return $string;
        }
        $string_backup = $string;
        $string = htmlspecialchars($string);
        
        foreach($keyword as $key => $value) {
            /*マッチしている文字を取得する
            検索結果にHITしている時点でboldにすることは決まっている。
            そのため、何文字をboldにするかだけ調査*/
            $value_quote = preg_quote($value);//重要.正規表現用にエスケープ
            mb_regex_encoding('UTF-8');
            $result = mb_eregi("($value_quote)",$string_backup ,$regs);
            if($result !== FALSE){
                $list = array_unique($regs);
                foreach ($list as $key2 => $bold_word){
                    $string = str_replace(htmlspecialchars($bold_word),'<b>'.htmlspecialchars($bold_word).'</b>',$string);
                }
            }else{
                
            }
        }
        return $string;

        //$keyword_pattern = '(https?://[a-zA-Z0-9/_.?#&;=$+:@%~,\\-]+)';

/*        $keyword_pattern = '(';
        $toPutPipe = FALSE;
        foreach($keyword as $key => $value) {
            if ( $toPutPipe ) {
                $keyword_pattern .= '|';
            } else {
                $toPutPipe = TRUE;
            }
            $keyword_pattern .= $value;
        }
        $keyword_pattern .= ')';*/

        //$keyword_pattern = '('.$keyword.')';

        //$matches = array();
/*        mb_ereg_search_init( $string, $keyword_pattern );
        while ( ( $ret = mb_ereg_search_pos() ) !== FALSE ) {
            $regs = mb_ereg_search_getregs();
            $matches[$ret[0]] = array( 'type'=>1, 'len'=>$ret[1], 'regs'=>$regs );
        }
        ksort($matches);
        $matches[strlen($string)] = array( 'type'=>3, 'len'=>0, 'regs'=>NULL );
        $ptr = 0;
        $ret_string = '';
        foreach ( $matches as $start => $info ) {
            if ( $start < $ptr ) continue;
            
            $type = $info['type'];
            $len = $info['len'];
            $regs = $info['regs'];
            $ret_string .= htmlspecialchars( substr( $string, $ptr, $start - $ptr ) );
            
            switch ( $info['type'] ) {
            case 0:
                $ret_string .= '<b>' .htmlspecialchars( $regs[0] ) . '</b>';
                break;
            case 1:
                $ret_string .= '<b>' .htmlspecialchars( $regs[0] ) . '</b>';
                break;
            case 2:
            }
            
            $ptr = $start + $len;
        }
        
        return $ret_string;*/
    }
}
?>