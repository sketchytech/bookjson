<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>
<script>
// Currently shows all notes and references
// TODO: show one box that swipes to reveal notes, refs, user notes
// TODO: reveal only notes and references for single paragraph by using this.id

$(document).ready(function(){
$a=0;
$(".paragraph").click(function(){
    if ($a==0) { 
        $(".notehidden").slideDown(600,'linear');
        $(".refhidden").slideDown(600,'linear');
        $(".anchorhidden").slideDown(600,'linear');
    $a=1;}
  else {
      $(".notehidden").slideUp(600,'linear');
      $(".refhidden").slideUp(600,'linear');
      $(".anchorhidden").slideUp(600,'linear');
        $a=0;}  
  });
});

</script>


<div style="margin-top:100px">
<?php

// TODO: Add listening and storage side of user notes 

include_once('readjson.php');


global $itemNumber;
$itemNumber=1;
$linkedItemNumber=6;
global $para;
$para=new paragraph; // create new paragraph object from 



// Chapter
$chapter_json = file_get_contents("burn.json");
$chapter = json_decode($chapter_json);

processObject($chapter,$linkedItemNumber);
// After chapter has been processed create note list and print
global $notes; // makes notes globally available to all functions
$notes=$para->returnNotes(); // create the array of notes by importing the one created in readjson.php
printNotes();  // notes are printed


function processObject($chapter,$linkedItemNumber) {
global $itemNumber;

if(is_object($chapter)){

foreach($chapter as $key=>$value)
{
// if($itemNumber==$linkedItemNumber) echo "<a id='".$itemNumber."'></a>";
$title_tags = array("h1","h2","h3","h4","h5");
if(in_array($key, $title_tags)){ 
echo "<".$key.">".$value."</".$key.">";
echo "<div id='".$itemNumber."' style='display:none; background-color:lightyellow; color:red; padding:10px;' class='anchorhidden'><p>".$itemNumber." | <span style='color:black;' contenteditable='true'>Tap to write notes here.</span></p></div>";
}
if($key=="blockquote") {
echo "<".$key.">";
// make sure blockquotes handle italics and citations correctly
}
// Confirm that chapter is array
if (is_array($value)) {


global $itemNumber;
$numberOfItems=count($value);
$i=0;
while ($i<$numberOfItems){

//arrayProcess($value);
if(is_string($value[$i])) {

echo $value[$i];
}// Part of the error testing 
elseif(is_array($value[$i])) arrayProcess($value[$i],$linkedItemNumber);
elseif(is_object($value[$i])) bounceObject($value[$i],$linkedItemNumber);

$i++;
}
}
if($key=="blockquote") {echo "</".$key.">"; // this handles blockquotes that come between paragraphs but blockquotes within paragraphs must also be handled, because those are correct

// Add user notes
addUserNotes();
}
}
}
}
function arrayProcess($array,$linkedItemNumber){
global $itemNumber;
global $notes;
global $para;
$para->returnParagraph($array,$itemNumber);

//else processObject($array,$linkedItemNumber);
// Add user notes
addUserNotes();
$itemNumber++;
}

function bounceObject($object,$linkedItemNumber) {
global $itemNumber;
processObject($object,$linkedItemNumber);
$itemNumber++;
}
function printNotes(){
global $notes;
if (count($notes)>0){

if (count($notes)==1) echo "<h2>Note</h2>";
else echo "<h2>Notes</h2>";
echo "<ol>";
$i=0;
while($i<count($notes)){
$note_number=$i+1;
echo "<li id='note".$note_number."'>".$notes[$i]." <a href='#ref".$note_number."'>[^]</a></li>";
$i++;
}
echo "</ol>";
}
}

function addUserNotes() {
// Adds user notes
global $itemNumber;
echo "<div id='user".$itemNumber."' style='display:none; background-color:lightyellow; color:red; padding:10px;' class='anchorhidden'><p>".$itemNumber." | <span style='color:black;' contenteditable='true'>Tap to write notes here.</span></p></div>";
}

?>
</div>
<script type="text/javascript">
//window.location.hash="20";
</script>
