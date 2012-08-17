<?php
class download
{
    static public function prepare_download( $filename, $mimetype, $allowInline = TRUE )
    {
        // Disposition determination
        $disposition = 'attachment';
        if ( $allowInline )
        {
            // "text"s are always acceptable
            if ( strncmp('text/', $mimetype, 5) == 0 ) {
                if ( ereg("\\.csv$", $filename) ) {
                    $disposition = 'attachment';
                } else {
                    $disposition = 'inline';
                }
            } else {
    
                // standard images
                $acceptables = array( 'image/jpeg',
                                      'image/pjpeg',
                                      'image/png',
                                      'image/gif' );
                // browser provided acceptable MIME types.
                if ( array_key_exists('HTTP_ACCEPT', $_SERVER) )
                {
                    $acceptables = array();
                    $a = explode(',', $_SERVER['HTTP_ACCEPT']);
                    foreach( $a as $type )
                    {
                        $acceptables[] = trim( $type );
                    }
                }
    
                if ( in_array( $mimetype, $acceptables ) )
                {
                    $disposition = 'inline';
                }
            }
        }
    
        //mb_http_output( 'pass' );
        //ini_set('default_charset', '');
        //while ( ob_get_level() > 0 )
        //{
            //ob_end_clean();
        //}
    
        // issue HTTP headers
        header("Content-Disposition: ${disposition} ;filename=".$filename);
        //header("Content-Disposition: ${disposition}");
        header('Content-Type: ' . $mimetype);
    }
}
?>