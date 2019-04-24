/*document.oncontextmenu = cmenu; function cmenu() { return false; }*/
function preventSelection(element){
 var preventSelection = false;
 
 function addHandler(element, event, handler){
 if (element.attachEvent) 
 element.attachEvent('on' + event, handler);
 else 
 if (element.addEventListener) 
 element.addEventListener(event, handler, false);
 }
 function removeSelection(){
 if (window.getSelection) { window.getSelection().removeAllRanges(); }
 else if (document.selection && document.selection.clear)
 document.selection.clear();
 }
 function killCtrlA(event){
 var event = event || window.event;
 var sender = event.target || event.srcElement;
 
 if (sender.tagName.match(/INPUT|TEXTAREA/i))
 return;
 
 var key = event.keyCode || event.which;
 if (event.ctrlKey && key == 'A'.charCodeAt(0))
 {
 removeSelection();
 
 if (event.preventDefault) 
 event.preventDefault();
 else
 event.returnValue = false;
 }
 }
 
  function killCtrlC(event){
 var event = event || window.event;
 var sender = event.target || event.srcElement;
 
 if (sender.tagName.match(/INPUT|TEXTAREA/i))
 return;
 
 var key = event.keyCode || event.which;
 if (event.ctrlKey && key == 'C'.charCodeAt(0))
 {
 removeSelection();
 
 if (event.preventDefault) 
 event.preventDefault();
 else
 event.returnValue = false;
 }
 }
 
 
 
 addHandler(element, 'mousemove', function(){
 if(preventSelection)
 removeSelection();
 });
 addHandler(element, 'mousedown', function(event){
 var event = event || window.event;
 var sender = event.target || event.srcElement;
 preventSelection = !sender.tagName.match(/INPUT|TEXTAREA/i);
 });
 
 addHandler(element, 'mouseup', function(){
 if (preventSelection)
 removeSelection();
 preventSelection = false;
 });
 
 addHandler(element, 'keydown', killCtrlA);
 addHandler(element, 'keyup', killCtrlA);
 addHandler(element, 'keydown', killCtrlC);
 addHandler(element, 'keyup', killCtrlC);
}
preventSelection(document);