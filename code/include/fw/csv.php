<?php
class csv
{
    static public function csvWriter($data,$infinity = FALSE){//無限にある場合は1行目のカラムを書出さない
        $temp_filename = tempnam( "", 'ca' );
        $fp = fopen( $temp_filename, "w" );
        
        /* ヘッダの作成例と出力 */
        $contents="";
        fputs($fp, $contents);
        
        foreach($data as $key => $val){
            $contents="";//初期化
            $ary_count = count($val);
            
            //カラム書き出しデバッグ用
            if($infinity === FALSE){
                $i=0;
                foreach($val as $key2 => $val2){
                    $i++;
                    if($key == "0"){
                        if($ary_count == $i){
                            $contents .= "\"".$key2."\"\n";
                        }else{
                            $contents .= "\"".$key2."\",";
                        }
                    }else{
                        break;
                    }
                }
                prev($data);
            }

            $i=0;
            foreach($val as $key2 => $val2){
                $i++;
                if($ary_count == $i){
                    $contents .= "\"".$val2."\"\n";
                }else{
                    $contents .= "\"".$val2."\",";
                }
            }
            /* ファイルに出力 */
            fputs($fp,mb_convert_encoding($contents,'SJIS','UTF-8'));
        }
        fclose( $fp );
        return $temp_filename;
    }
}
?>