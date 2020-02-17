defmode = "normalmode"; // default mode. either normalmode, advmode, or helpmode


if (defmode == "advmode") {
        helpmode = false;
        normalmode = false;
        advmode = true;
} else if (defmode == "helpmode") {
        helpmode = true;
        normalmode = false;
        advmode = false;
} else {
        helpmode = false;
        normalmode = true;
        advmode = false;
}

function chmode(swtch){
        if (swtch == 1){
                advmode = false;
                normalmode = false;
                helpmode = true;
        } else if (swtch == 0) {
                helpmode = false;
                normalmode = false;
                advmode = true;
        } else if (swtch == 2) {
                helpmode = false;
                advmode = false;
                normalmode = true;
        }
}


function AddText(text) {
	text = ' ' + text + ' ';
	if (document.input.message.createTextRange && document.input.message.caretPos) {
	var caretPos = document.input.message.caretPos;
	caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	setfocus();
	} else {
	document.input.message.value  += text;
	setfocus();
	}
}

function AddSmilie(text) {
	text = ' ' + text + ' ';
	if (document.input.message.createTextRange && document.input.message.caretPos) {
	var caretPos = document.input.message.caretPos;
	caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
                myfocus();
	} else {
	document.input.message.value  += text;
	myfocus();
	}
}

function icon(text) { 
	AddText(text);
}

function storeCaret (textEl){
        if(textEl.createTextRange){
                textEl.caretPos = document.selection.createRange().duplicate();
        }
}


function insertAtCaret (textEl, text){
        if (textEl.createTextRange && textEl.caretPos){
                var caretPos = textEl.caretPos;
                caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
        }else{
                textEl.value  = text;
        }
}

function email() {
        if (helpmode) {
                alert("Email TagnTurns an email address into a mailto hyperlink.nUsage: [email]someone@anywhere.com[/email]nUsage: [email=someone@anywhere.com]link text[/email]");
        } else if (advmode) {
                AddTxt="[email] [/email]";
                AddText(AddTxt);
        } else {
                txt2=prompt("What name should be shownnIf this field is empty the email address will be visible","");
                if (txt2!=null) {
                        txt=prompt("Please enter an email address.","name@domain.com");
                        if (txt!=null) {
                                if (txt2=="") {
                                        AddTxt="[email]"+txt+"[/email]";

                                } else {
                                        AddTxt="[email="+txt+"]"+txt2+"[/email]";
                                }
                                AddText(AddTxt);
                        }
                }
        }
}



function chsize(size) {
        if (helpmode) {
                alert("0");
        } else if (advmode) {
                AddTxt="[size="+size+"] [/size]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text to be size  "+size,"Text");
                if (txt!=null) {
                        AddTxt="[size="+size+"]"+txt+"[/size]";
                        AddText(AddTxt);
                }
        }
}


function chfont(font) {
        if (helpmode){
                alert("Font TagnSets the font face for the enclosed text.nUsage: [font="+font+"]The font of this text is");
        } else if (advmode) {
                AddTxt="[font="+font+"] [/font]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text to be in  "+font,"Text");
                if (txt!=null) {
                        AddTxt="[font="+font+"]"+txt+"[/font]";
                        AddText(AddTxt);
                }
        }
}

function bold() {
        if (helpmode) {
                alert("Bold TagnMakes the enlosed text bold.nUsage: [b]This is some bold text[/b]");
        } else if (advmode) {
                AddTxt="[b] [/b]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text that should be bolded.","Text");
                if (txt!=null) {
                        AddTxt="[b]"+txt+"[/b]";
                        AddText(AddTxt);
                }
        }
}

function italicize() {
        if (helpmode) {
                alert("Italicize TagnMakes the enlosed text italicized.nUsage: [i]This is some italicized text[/i]");
        } else if (advmode) {
                AddTxt="[i] [/i]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text that should be italicized.","Text");
                if (txt!=null) {
                        AddTxt="[i]"+txt+"[/i]";
                        AddText(AddTxt);
                }
        }
}

function quote() {
        if (helpmode){
                alert("Quote tagnQuotes the enclosed text to reference something specific that someone has posted.nUsage: [quote]This is a quote[/quote]");
        } else if (advmode) {
                AddTxt="r[quote]r[/quote]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text you want quoted.","Text");
                if(txt!=null) {
                        AddTxt="r[quote]r"+txt+"r[/quote]";
                        AddText(AddTxt);
                }
        }
}

function chcolor(color) {
        if (helpmode) {
                alert("Color TagnSets the text color.  Any named color can be used.nUsage: [color="+color+"]This is some "+color+" text[/color]");
        } else if (advmode) {
                AddTxt="[color="+color+"] [/color]";
                AddText(AddTxt);
        } else {
        txt=prompt("Please enter the text that should be "+color,"Text "+color,"Text");
                if(txt!=null) {
                        AddTxt="[color="+color+"]"+txt+"[/color]";
                        AddText(AddTxt);
                }
        }
}

function center() {
        if (helpmode) {
                alert("Centered tagnCenters the enclosed text.nUsage: [align=center]This text is centered[/align]");
        } else if (advmode) {
                AddTxt="[align=center] [/align]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text that should be centered.","Text");
                if (txt!=null) {
                        AddTxt="[align=center]"+txt+"[/align]";
                        AddText(AddTxt);
                }
        }
}

function hyperlink() {
        if (helpmode) {
                alert("Hyperlink TagnTurns an url into a hyperlink.nUsage: [url]http://www.anywhere.com[/url]nUsage: [url=http://www.anywhere.com]link text[/url]");
        } else if (advmode) {
                AddTxt="[url] [/url]";
                AddText(AddTxt);
        } else {
                txt2=prompt("What name should be shown?nIf this Field is blank the URL would be visible","");
                if (txt2!=null) {
                        txt=prompt("Please enter the url for the hyperlink.","http://");
                        if (txt!=null) {
                                if (txt2=="") {
                                        AddTxt="[url]"+txt+"[/url]";
                                        AddText(AddTxt);
                                } else {
                                        AddTxt="[url="+txt+"]"+txt2+"[/url]";
                                        AddText(AddTxt);
                                }
                        }
                }
        }
}

function image() {
        if (helpmode){
                alert("Image TagnInserts an image into the post.nUsage: [img]http:www.anywhere.comimage.gif[/img]");
        } else if (advmode) {
                AddTxt="[img] [/img]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the URL to the image you wish to insert.","http://");
                if(txt!=null) {
                        AddTxt="r[img]"+txt+"[/img]";
                        AddText(AddTxt);
                }
        }
}

function code() {
        if (helpmode) {
                alert("Code TagnBlockquotes the text you reference and preserves the formatting.nUsefull for posting code.nUsage: [code]This is formated text[/code]");
        } else if (advmode) {
                AddTxt="r[code]r[/code]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text you wanted blockquoted.","");
                if (txt!=null) {
                        AddTxt="r[code]"+txt+"[/code]";
                        AddText(AddTxt);
                }
        }
}

function list() {
        if (helpmode) {
                alert("List TagnBuilds a bulleted, numbered, or alphabetical list.nUsage: [list]n[*]item1n[*]item2n[*]item3n[/list]");
        } else if (advmode) {
                AddTxt="r[list]r[*]r[*]r[*]r[/list]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please select your list typen ('A' for aplhabetic, '1' for nummeric) You can leave this field blank","");
                while ((txt!="") && (txt!="A") && (txt!="a") && (txt!="1") && (txt!=null)) {
                        txt=prompt("ERROR!nThe only possible values for type of list are blank 'A' and '1'.","");
                }
                if (txt!=null) {
                        if (txt=="") {
                                AddTxt="r[list]rn";
                        } else {
                                AddTxt="r[list="+txt+"]r";
                        }
                        txt="1";
                        while ((txt!="") && (txt!=null)) {
                                txt=prompt("List Item:nLeave it blank to End the List","");
                                if (txt!="") {
                                        AddTxt+="[*]"+txt+"r";
                                }
                        }
                        AddTxt+="[/list]rn";
                        AddText(AddTxt);
                }
        }
}

function underline() {
        if (helpmode) {
                alert("Underline TagnUnderlines the enclosed text.nUsage: [u]This text is underlined[/u]");
        } else if (advmode) {
                AddTxt="[u] [/u]";
                AddText(AddTxt);
        } else {
                txt=prompt("Please enter the text that should be underlined.","Text");
                if (txt!=null) {
                        AddTxt="[u]"+txt+"[/u]";
                        AddText(AddTxt);
                }
        }
}

function setfocus() {
        document.input.message.focus();
}

bold_tip = "Bold Text: [b]Text[/b]";
italic_tip = "Italicized Text: [i]Text[/i]";
underline_tip = "Underlined Text: [u]Text[/u]";
center_tip = "Centered Text: [align=center]Text[/align]";
quote_tip = "Quoted Text: [quote]Text[/quote]";
code_tip = "Code Formatted Text: [code]Text[/code]";
list_tip = "List Text: [list][*]Text[*]Text[/list]";
image_tip = "Insert Image: [img]http://url[/img]";
url_tip = "Insert URL: [url]http://url[/url] or [url=http://url]Text[/url]";
email_tip = "Insert Email: [email]name@domain.com[/email] or [email=name@domain.com]Text[/url]";
font_tip = "Font: [font=arial]Text[/font]";
color_tip = "Font Color: [color=red]Text[/color]";
size_tip = "Font Size: [size=1]Text[/size]";
helpmode_tip = "Help Mode: Click on any of the formatting buttons for a description and instructions.";
advmode_tip = "Advanced Mode: The BB Code will be inserted without options as soon as you hit the button.";
normode_tip = "Normal Mode: Popups will bring you step by step through the process of inserting BB Code.";

function tipline(tip) {
     document.input.tipbox.value = eval(tip + "_tip");
}

var NewWindow = null;

function Popup(url, window_name, window_width, window_height, left , top) {
if (left!=null) {
LeftPosition = left;
} else {
     LeftPosition = (screen.width) ? (screen.width-window_width)/2 : 0;}
if (top!=null) {
TopPosition = top;
} else {
     TopPosition = (screen.height) ? (screen.height-window_height)/2 : 0;}
settings= 'height='+window_height+',width='+window_width+',top='+TopPosition+',left='+LeftPosition+',toolbar=no'+',location=no,directories=no,status=no,menubar=no'+',scrollbars=yes,resizable=yes';

NewWindow = window.open(url,window_name,settings);
}


function myreg() { 
window.location = "member.php?action=reg"; }

function mypoll() { 
window.location = "viewthread.php?tid=$tid&viewres=1"; }

