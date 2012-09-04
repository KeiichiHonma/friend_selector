function set_base_cehck(){
    var loopMax=document.user_update_form.elements["user_update[]"] .length; 
    for(cnt=0;cnt<loopMax;cnt++){
        if( base_checks.indexOf(document.user_update_form.elements["user_update[]"][cnt].value) != -1 ){
            document.user_update_form.elements["user_update[]"][cnt].checked = true;
        }else{
            document.user_update_form.elements["user_update[]"][cnt].checked = false;
        }
    }
}
set_base_cehck();