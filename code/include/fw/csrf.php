<?php
class csrf
{
    public static function getToken()
    {
        global $con;
/*        $csrf_ticket = md5(uniqid( rand(), true ) );
        $con->session->set( 'csrf_ticket', $csrf_ticket );*/
        $csrf_ticket = $con->session->get( 'csrf_ticket' );
        if ( ! $csrf_ticket )
        {
            $csrf_ticket = md5(uniqid( rand(), true ) );
            $con->session->set( 'csrf_ticket', $csrf_ticket );
        }
/*var_dump($csrf_ticket);
var_dump($_SESSION['csrf_ticket']);*/
        global $con;
        $con->t->assign('csrf_ticket',$csrf_ticket);
        return $csrf_ticket;
    }

    public static function validateToken( $token, $throw_error = TRUE )
    {

        global $con;
        $csrf_ticket = $con->session->get( 'csrf_ticket' );
/*var_dump($token);
var_dump($csrf_ticket);*/
        if ( $csrf_ticket )
        {
            if ( strcmp( trim( $csrf_ticket ), trim( $token ) ) === 0 )
            {
                return TRUE;
            }
        }
        if ($throw_error)
        {
            require_once('fw/errorManager.php');
            errorManager::throwError(E_CMMN_TOKEN_WRONG);
        }
        return FALSE;
    }
}
?>