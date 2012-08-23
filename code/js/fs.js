var dumpObj = function(o){
    var str = "";
    for(var i in o) {
    str = str + o[i];
    }
    alert(str);
} 
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

function jumpMenu(selObj){
    if(selObj.options[selObj.selectedIndex].value == "") return false;
    location.href = selObj.options[selObj.selectedIndex].value;
}

function findObj(n, d) {
    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
    for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=findObj(n,d.layers[i].document);
    if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function selectFriendlist(selName){
    form_handle('index',true);
    var selObj = findObj(selName);
    if (selObj) jumpMenu(selObj);
}

function form_handle(page,bl){
    document.getElementById('friendlist_form').friendlist.disabled = bl;
    document.getElementById('add').disabled = bl;
    if(page == 'view'){
        document.getElementById('sex_form').sex[0].disabled = bl;
        document.getElementById('sex_form').sex[1].disabled = bl;
        document.getElementById('sex_form').sex[2].disabled = bl;
        document.getElementById('relationship_form').relationship[0].disabled = bl;
        document.getElementById('relationship_form').relationship[1].disabled = bl;
        document.getElementById('relationship_form').relationship[2].disabled = bl;
        document.getElementById('relationship_form').relationship[3].disabled = bl;
        document.getElementById('relationship_form').relationship[4].disabled = bl;
        document.getElementById('relationship_form').relationship[5].disabled = bl;
        document.getElementById('relationship_form').relationship[6].disabled = bl;
        document.getElementById('relationship_form').relationship[7].disabled = bl;
        document.getElementById('relationship_form').relationship[8].disabled = bl;
        document.getElementById('relationship_form').relationship[9].disabled = bl;
        if(document.getElementById('flid_form') != null) document.getElementById('flid_form').flid.disabled = bl;
        if(document.getElementById('gid_form') != null) document.getElementById('gid_form').gid.disabled = bl;
        document.getElementById('in').disabled = bl;
        document.getElementById('out').disabled = bl;
        flid_handle('a',bl);
        flid_handle('input',bl);
    }
}

function flid_handle(tgname,bl) { 
    var tag = document.getElementsByTagName(tgname);
         for (var i=0; i<tag.length; i++){
            var clname_ie = tag[i].getAttribute('className');
            var clname = tag[i].getAttribute('class');
            
            if( clname == 'grid-page-input' || clname == 'grid-page-start' || clname == 'grid-page-prev' || clname == 'grid-page-info' || clname == 'grid-page-next' || clname == 'grid-page-end' || clname_ie == 'grid-page-input' ||  clname_ie == 'grid-page-start' || clname_ie == 'grid-page-prev' || clname_ie == 'grid-page-info' || clname_ie == 'grid-page-next' || clname_ie == 'grid-page-end' ){
                tag[i].disabled = bl;
            }
         }
    }