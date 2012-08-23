
function doAllCheck(number){
    $('.allCheck' + number).attr('checked', true);
}

function resetAllCheck(number){
    $('.allCheck' + number).attr('checked', false);
}

$(function () {
    $('.allCheck1').click(function () {
        var isSave = true;
        var tr = $('#' + table1_id + ' tbody tr');//全行を取得
            
        //クッキー配列
        var row_ids = [];
        var new_row_ids = [];
        if (jQuery.cookie) {
            var str_ids = jQuery.cookie( table1_cookie_name );
            if (str_ids) row_ids = str_ids.split(',');
            if(isSearch){
                var str_new_ids = jQuery.cookie( table1_new_cookie_name );
                if (str_new_ids) new_row_ids = str_new_ids.split(',');
            }
        }

        if(this.checked){
            //全選択 ar1.concat(ar2)
            for( var i=0,l=tr.length;i<l;i++ ){
                isAlreadySelected = tr.eq(i).attr('id') != undefined && jQuery.inArray( tr.eq(i).attr('id'), row_ids ) != -1;
                
                if( !isAlreadySelected ){
                    row_ids.push( tr.eq(i).attr('id') );
                    if(isSearch) new_row_ids.push( tr.eq(i).attr('id') );
                }
            }
            if(is_max_select && max_select < row_ids.length){
                isSave = false;
                //チェック状態を戻す
                $('.allCheck1').attr('checked', false);
                alert(max_select_message);
            }else{
                $('#' + table1_id + ' input:checkbox[name^=chk1]').attr('checked', this.checked);
                //css
                $('tr', '#' + table1_id).attr('_selected', 'true').addClass('grid-row-sel');
            }

            
        }else{
            //全解除
            for( var i=0,l=tr.length;i<l;i++ ){
                isAlreadySelected = tr.eq(i).attr('id') != undefined && jQuery.inArray( tr.eq(i).attr('id'), row_ids ) != -1;
                
                if( isAlreadySelected ){
                    row_ids.splice(jQuery.inArray(tr.eq(i).attr('id'), row_ids),1);
                    if(isSearch) new_row_ids.splice(jQuery.inArray(tr.eq(i).attr('id'), new_row_ids),1);
                }
            }
            $('#' + table1_id + ' input:checkbox[name^=chk1]').attr('checked', this.checked);
            //css
            $('tr', '#' + table1_id).attr('_selected', 'false').removeClass('grid-row-sel').removeClass('grid-row-hover');
        }
        
        //save
        if (jQuery.cookie && isSave) {
            jQuery.cookie( table1_cookie_name, row_ids.join(','), {path: '/'});
            if(isSearch) jQuery.cookie( table1_new_cookie_name, new_row_ids.join(','), {path: '/'});
        }
    });

    $('.allCheck2').click(function () {
        var isSave = true;
        var tr = $('#' + table2_id + ' tbody tr');//全行を取得
            
        //クッキー配列
        var row_ids = [];
        var new_row_ids = [];
        if (jQuery.cookie) {
            var str_ids = jQuery.cookie( table2_cookie_name );
            if (str_ids) row_ids = str_ids.split(',');
            if(isSearch){
                var str_new_ids = jQuery.cookie( table2_new_cookie_name );
                if (str_new_ids) new_row_ids = str_new_ids.split(',');
            }
        }

        if(this.checked){
            //全選択
            for( var i=0,l=tr.length;i<l;i++ ){
                isAlreadySelected = tr.eq(i).attr('id') != undefined && jQuery.inArray( tr.eq(i).attr('id'), row_ids ) != -1;
                
                if( !isAlreadySelected ){
                    row_ids.push( tr.eq(i).attr('id') );
                    if(isSearch) new_row_ids.push( tr.eq(i).attr('id') );
                }
            }
            if(is_max_select && max_select < row_ids.length){
                isSave = false;
                //チェック状態を戻す
                $('.allCheck2').attr('checked', false);
                alert(max_select_message);
            }else{
                $('#' + table2_id + ' input:checkbox[name^=chk2]').attr('checked', this.checked);
                //css
                $('tr', '#' + table2_id).attr('_selected', 'true').addClass('grid-row-sel');
            }

            
        }else{
            //全解除
            for( var i=0,l=tr.length;i<l;i++ ){
                isAlreadySelected = tr.eq(i).attr('id') != undefined && jQuery.inArray( tr.eq(i).attr('id'), row_ids ) != -1;
                
                if( isAlreadySelected ){
                    row_ids.splice(jQuery.inArray(tr.eq(i).attr('id'), row_ids),1);
                    if(isSearch) new_row_ids.splice(jQuery.inArray(tr.eq(i).attr('id'), new_row_ids),1);
                }
            }
            $('#' + table2_id + ' input:checkbox[name^=chk2]').attr('checked', this.checked);
            //css
            $('tr', '#' + table2_id).attr('_selected', 'false').removeClass('grid-row-sel').removeClass('grid-row-hover');
        }
        
        //save
        if (jQuery.cookie && isSave) {
            jQuery.cookie( table2_cookie_name, row_ids.join(','), {path: '/'});
            if(isSearch) jQuery.cookie( table2_new_cookie_name, new_row_ids.join(','), {path: '/'});
        }
    });
});
