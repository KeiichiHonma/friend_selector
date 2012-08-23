//var sw = window.screen.width;
var sh = window.screen.height;
var grid_height = 350;
if(sh >= 1080){
    document.getElementById("center_grid").style.paddingTop = "300px";
    document.getElementById("spin").style.top = "230px";
    grid_height = 500;
}