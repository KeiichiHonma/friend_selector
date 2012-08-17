function search_d(){
    var d = document.getElementById("search").style.display
    if(d == 'none'){
        document.getElementById("search").style.display = "inline";
        document.getElementById("search_d_btn").value = "↓隠す↓";
        
    }else{
        document.getElementById("search").style.display = "none";
        document.getElementById("search_d_btn").value = "↓表示↓";
    }
}

function jumpMenu(selObj,restore){
    if(selObj.options[selObj.selectedIndex].value == "") return false;
    location.href = selObj.options[selObj.selectedIndex].value;
    if (restore) selObj.selectedIndex=0;
}

function findObj(n, d) {
    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
    if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function selectFriendlist(selName,restore){
    var selObj = findObj(selName);
    if (selObj) jumpMenu(selObj,restore);
}

function form_stop(){
    document.getElementById('sex_form').sex[0].disabled = true;
    document.getElementById('sex_form').sex[1].disabled = true;
    document.getElementById('sex_form').sex[2].disabled = true;
    document.getElementById('relationship_form').relationship[0].disabled = true;
    document.getElementById('relationship_form').relationship[1].disabled = true;
    document.getElementById('relationship_form').relationship[2].disabled = true;
    document.getElementById('relationship_form').relationship[3].disabled = true;
    document.getElementById('relationship_form').relationship[4].disabled = true;
    document.getElementById('relationship_form').relationship[5].disabled = true;
    document.getElementById('relationship_form').relationship[6].disabled = true;
    document.getElementById('relationship_form').relationship[7].disabled = true;
    document.getElementById('relationship_form').relationship[8].disabled = true;
    document.getElementById('relationship_form').relationship[9].disabled = true;
    document.getElementById('flid_form').flid.disabled = true;
    document.getElementById('add').disabled = true;
    document.getElementById('in').disabled = true;
    document.getElementById('out').disabled = true;
    stop_frid_class('a');
    stop_frid_class('input');
}

function stop_frid_class(tgname) { 
    var tag = document.getElementsByTagName(tgname);
         for (var i=0; i<tag.length; i++){
            var clname_ie = tag[i].getAttribute('className');
            var clname = tag[i].getAttribute('class');
            
            if( clname == 'grid-page-input' || clname == 'grid-page-start' || clname == 'grid-page-prev' || clname == 'grid-page-info' || clname == 'grid-page-next' || clname == 'grid-page-end' || clname_ie == 'grid-page-input' ||  clname_ie == 'grid-page-start' || clname_ie == 'grid-page-prev' || clname_ie == 'grid-page-info' || clname_ie == 'grid-page-next' || clname_ie == 'grid-page-end' ){
                tag[i].disabled = true;
            }
         }
    }