function Folder(folderDescription, hreference) 

{ 

this.desc = folderDescription ;

this.hreference = hreference ;

this.id = -1 ;

this.navObj = 0; 

this.iconImg = 0; 

this.nodeImg = 0; 

this.isLastNode = 0; 



this.isOpen = true; 

this.iconSrc = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/16.gif" ; 

this.children = new Array; 

this.nChildren = 0 ;



this.initialize = initializeFolder ;

this.setState = setStateFolder ;

this.addChild = addChild ;

this.createIndex = createEntryIndex ;

this.hide = hideFolder ;

this.display = display ;

this.renderOb = drawFolder ;

this.totalHeight = totalHeight ;

this.subEntries = folderSubEntries ;

this.outputLink = outputFolderLink ;

} 



function setStateFolder(isOpen) 

{ 

var subentries; 

var totalHeight; 

var fIt = 0 ;

var i=0 ;



if (isOpen == this.isOpen) return ;



if (browserVersion == 2) 

{ 

totalHeight = 0 ;

for (i=0; i < this.nChildren; i++) 

totalHeight = totalHeight + this.children[i].navObj.clip.height ;

subEntries = this.subEntries() ;

if (this.isOpen) 

totalHeight = 0 - totalHeight ;

for (fIt = this.id + subEntries + 1; fIt < nEntries; fIt++) 

indexOfEntries[fIt].navObj.moveBy(0, totalHeight) ;

} 

this.isOpen = isOpen ;

propagateChangesInState(this) ;

} 



function propagateChangesInState(folder) 

{ 

var i=0 ;



if (folder.isOpen) 

{ 

if (folder.nodeImg) {

if (folder.isLastNode) 

folder.nodeImg.src = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/14.gif" ;

else 

folder.nodeImg.src = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/13.gif" ;

folder.iconImg.src = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/10.gif" ;

}

for (i=0; i<folder.nChildren; i++) 

folder.children[i].display() ;

} 

else 

{ 

if (folder.nodeImg) {

if (folder.isLastNode) 

folder.nodeImg.src = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/12.gif" ;

else 

folder.nodeImg.src = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/11.gif" ;

folder.iconImg.src = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/9.gif" ;

}

for (i=0; i<folder.nChildren; i++) 

folder.children[i].hide() ;

} 

} 



function hideFolder() 

{ 

if (browserVersion == 1) { 

if (this.navObj.style.display == "none") 

return ;

this.navObj.style.display = "none" ;

} else { 

if (this.navObj.visibility == "hidden") 

return ;

this.navObj.visibility = "hidden" ;

} 

this.setState(0) ;

} 



function initializeFolder(level, lastNode, leftSide) 

{ 

var j=0 ;

var i=0 ;

var numberOfFolders ;

var numberOfDocs ;

var nc ;



nc = this.nChildren ;

this.createIndex() ;

var auxEv = "" ;



if (browserVersion > 0) 

auxEv = "<a href='javascript:clickOnNode("+this.id+")'>" ;

else 

auxEv = "<a>" ;



if (level>0) 

if (lastNode) 

{ 

this.renderOb(leftSide + auxEv + "<img id='nodeIcon" + this.id + "' src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/14.gif' width=16 height=22 border=0></a>") ;

leftSide = leftSide + "<img src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/17.gif' width=16 height=22>" ;

this.isLastNode = 1 ;

} 

else 

{ 

this.renderOb(leftSide + auxEv + "<img id='nodeIcon" + this.id + "' src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/13.gif' width=16 height=22 border=0></a>") ;

leftSide = leftSide + "<img src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/18.gif' width=16 height=22>" ;

this.isLastNode = 0 ;

} 

else 

this.renderOb("") ;



if (nc > 0) 

{ 

level = level + 1 ;

for (i=0 ; i < this.nChildren; i++) 

{ 

if (i == this.nChildren-1) 

this.children[i].initialize(level, 1, leftSide) ;

else 

this.children[i].initialize(level, 0, leftSide) ;

} 

} 

} 



function drawFolder(leftSide) 

{ 

if (browserVersion == 2) { 

if (!doc.yPos) 

doc.yPos=8 ;

doc.write("<layer id='folder" + this.id + "' top=" + doc.yPos + " visibility=hidden>") ;

} 



doc.write("<table ") ;

if (browserVersion == 1) 

doc.write(" id='folder" + this.id + "' style='position:block;' ") ;

doc.write(" border=0 cellspacing=0 cellpadding=0>") ;

doc.write("<tr><td>") ;

doc.write(leftSide) ;

this.outputLink() ;

doc.write("<img id='folderIcon" + this.id + "' ") ;

doc.write("src='" + this.iconSrc+"' border=0></a>") ;

doc.write("</td><td valign=middle nowrap>") ;

if (USETEXTLINKS) 

{ 

this.outputLink() ;

doc.write(this.desc + "</a>") ;

} 

else 

doc.write(this.desc) ;

doc.write("</td>") ;

doc.write("</table>") ;



if (browserVersion == 2) { 

doc.write("</layer>") ;

} 



if (browserVersion == 1) { 

this.navObj = doc.getElementById("folder"+this.id) ;

this.iconImg = doc.getElementById("folderIcon"+this.id) ;

this.nodeImg = doc.getElementById("nodeIcon"+this.id) ;

} else if (browserVersion == 2) { 

this.navObj = doc.layers["folder"+this.id] ;

this.iconImg = this.navObj.document.images["folderIcon"+this.id] ;

this.nodeImg = this.navObj.document.images["nodeIcon"+this.id] ;

doc.yPos=doc.yPos+this.navObj.clip.height ;

} 

} 



function outputFolderLink() 

{ 

if (this.hreference) 

{ 

doc.write("<a href='" + this.hreference + "'target='right'") ;

if (browserVersion > 0) 

doc.write("onClick='javascript:clickOnFolder("+this.id+")'") ;

doc.write(">") ;

} 

else 

doc.write("<a>") ;

} 



function addChild(childNode) 

{ 

this.children[this.nChildren] = childNode ;

this.nChildren++ ;

return childNode ;

} 



function folderSubEntries() 

{ 

var i = 0 ;

var se = this.nChildren ;



for (i=0; i < this.nChildren; i++){ 

if (this.children[i].children) 

se = se + this.children[i].subEntries() ;

} 



return se ;

} 




function Item(itemDescription, itemLink) 

{ 

this.desc = itemDescription ;

this.link = itemLink ;

this.id = -1 ;

this.navObj = 0 ;

this.iconImg = 0 ;

this.iconSrc = "http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/10.gif" ;




this.initialize = initializeItem ;

this.createIndex = createEntryIndex ;

this.hide = hideItem ;

this.display = display ;

this.renderOb = drawItem ;

this.totalHeight = totalHeight ;

} 



function hideItem() 

{ 

if (browserVersion == 1) { 

if (this.navObj.style.display == "none") 

return ;

this.navObj.style.display = "none" ;

} else { 

if (this.navObj.visibility == "hidden") 

return ;

this.navObj.visibility = "hidden" ;

} 

} 



function initializeItem(level, lastNode, leftSide) 

{ 

this.createIndex() ;



if (level>0) 

if (lastNode) 

{ 

this.renderOb(leftSide + "<img src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/20.gif' width=16 height=22>") ;

leftSide = leftSide + "<img src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/17.gif' width=16 height=22>" ;

} 

else 

{ 

this.renderOb(leftSide + "<img src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/19.gif' width=16 height=22>") ;

leftSide = leftSide + "<img src='http://c.blog.xuite.net/cf/7b/11732000/blog_698/txt/4902396/18.gif' width=16 height=22>" ;

} 

else 

this.renderOb("") ;

} 



function drawItem(leftSide) 

{ 

if (browserVersion == 2) 

doc.write("<layer id='item" + this.id + "' top=" + doc.yPos + " visibility=hidden>") ;



doc.write("<table ") ;

if (browserVersion == 1) 

doc.write(" id='item" + this.id + "' style='position:block;' ") ;

doc.write(" border=0 cellspacing=0 cellpadding=0>") ;

doc.write("<tr><td>") ;

doc.write(leftSide) ;

doc.write("<a href=" + this.link + ">") ;

doc.write("<img id='itemIcon"+this.id+"' ") ;

doc.write("src='"+this.iconSrc+"' border=0>") ;

doc.write("</a>") ;

doc.write("</td><td valign=middle nowrap>") ;

if (USETEXTLINKS) 

doc.write("<a href=" + this.link + ">" + this.desc + "</a>") ;

else 

doc.write(this.desc) ;

doc.write("</table>") ;



if (browserVersion == 2) 

doc.write("</layer>") ;



if (browserVersion == 1) { 

this.navObj = doc.getElementById("item"+this.id) ;

this.iconImg = doc.getElementById("itemIcon"+this.id) ;

} else if (browserVersion == 2) { 

this.navObj = doc.layers["item"+this.id] ;

this.iconImg = this.navObj.document.images["itemIcon"+this.id] ;

doc.yPos=doc.yPos+this.navObj.clip.height ;

} 

} 





function display() 

{ 

if (browserVersion == 1) 

this.navObj.style.display = "block" ;

else 

this.navObj.visibility = "show" ;

} 



function createEntryIndex() 

{ 

this.id = nEntries ;

indexOfEntries[nEntries] = this ;

nEntries++ ;

} 



function totalHeight() 

{ 

var h = this.navObj.clip.height ;

var i = 0 ;



if (this.isOpen) 

for (i=0 ; i < this.nChildren; i++) 

h = h + this.children[i].totalHeight() ;



return h ;

} 



function clickOnFolder(folderId) 

{ 

var clicked = indexOfEntries[folderId] ;



if (!clicked.isOpen) 

clickOnNode(folderId) ;



return ;



if (clicked.isSelected) 

return ;

} 



function clickOnNode(folderId) 

{ 

var clickedFolder = 0 ;

var state = 0 ;



clickedFolder = indexOfEntries[folderId] ;

state = clickedFolder.isOpen ;



clickedFolder.setState(!state) ;

} 



function initializeDocument() 

{ 

if (doc.getElementById) 

browserVersion = 1 ;

else 

if (doc.layers) 

browserVersion = 2 ;

else 

browserVersion = 0 ;



foldersTree.initialize(0, 1, "") ;

foldersTree.display();



if (browserVersion > 0) 

{ 

doc.write("<layer top="+indexOfEntries[nEntries-1].navObj.top+"> </layer>") ;



clickOnNode(0) ;

clickOnNode(0) ;

} 

} 






function gFld(description, hreference) 

{ 

description="<font style=font-size:9pt; font-color: #00ff00;background-color:#ccff99>" + description;

folder = new Folder(description, hreference) ;

return folder ;

} 



function gLnk(target, description, linkData) 

{ 

description="<font style=font-size:9pt; font-color: #0000ff;background-color:#ccff99>" + description;

fullLink = "" ;



if (target==0) 

{ 

fullLink = "'"+linkData+"' target=right" ;
			
} 

else 

{ 

if (target==1) 

fullLink = "'http://"+linkData+"' target=right" ;

else 

fullLink = "'http://"+linkData+"' target=right" ;

} 



linkItem = new Item(description, fullLink) ;

return linkItem ;

} 



function insFld(parentFolder, childFolder) 

{ 

return parentFolder.addChild(childFolder) 

} 



function insDoc(parentFolder, document) 

{ 

parentFolder.addChild(document) 

} 



USETEXTLINKS = 1 ;

indexOfEntries = new Array ;

nEntries = 0 ;

doc = document ;

browserVersion = 0 ;

selectedFolder=0;