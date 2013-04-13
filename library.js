function ReloadGo(val, name, str) {
	goURL = "?_PageLoaded_=1&amp;"+name+"="+val+str ;
	window.location.href=goURL;
}

function InArray(name, pStr) {
	var ValuesString = "--"+ValuesArray[name].join("--")+"--";
	var sstr = "--"+pStr+"--";
	if(ValuesString.indexOf(sstr)>=0)
		return true;
	else
		return false;
}

function CheckAddInfo(name, val, type) {
	var vis = "hidden";
	var displ = "none";
	var sstr = ":~:";
	
	if(InArray(name, val)){
		var vis = "visible";
		var displ = "block";							
	}
	GetObjectById("Hidden" + name).value = val;
	GetStyleById("Div" + name).display = displ;
	GetStyleById("Div" + name).visibility = vis;
	
	PValue[name] = val;

	if(InArray(name, val) && (type=="selectbox" || type=="textbox")){
		CopyValue(name, GetObjectById("Input" + name).value);
	}
}

function CopyValue(name, val, type, bcount) {
	if(bcount!=null)
		boxcount[name] = bcount;
	GetObjectById("Hidden" + name).value = PValue[name];
	if(type=="checkbox")
		for(i=0; i<boxcount[name]; i++){
			if(GetObjectById("Input" + name + i).checked==true){
				Delimiter = ":~:";
				if(GetObjectById("Hidden" + name).value.indexOf(":~:")>=0)
					Delimiter = ":=:";
				GetObjectById("Hidden" + name).value += Delimiter + GetObjectById("Input" + name + i).value;
			}
		}
	else
		if(val)
			GetObjectById("Hidden" + name).value += ":~:" + val;
}

//
// Cross Browsers JS Model
//
var ObjectsType;

if(document.getElementById)
	ObjectsType="DOM2";
else
	if(document.all)
		ObjectsType="IEDOM1"
	else
		if(document.layers)
			ObjectsType="NCDOM1";

function GetStyleById(IdName){
	switch(ObjectsType){
		case "DOM2":
			return document.getElementById(IdName).style;
		break;
		case "IEDOM1":
			return document.all[IdName].style;
		break;
		case "NCDOM1":
			return document.layers[IdName];
		break;
	}
}

function GetObjectById(IdName){
	switch(ObjectsType){
		case "DOM2":
			return document.getElementById(IdName);
		break;
		case "IEDOM1":
			return document.all[IdName];
		break;
		case "NCDOM1":
			return document.layers[IdName];
		break;
	}
}

//
// End Cross Browser JS Model
//
function MyListBox_move(Fm,to,TopIndex) {
    var list = Fm;
    var total = Fm.options.length-1;
    var index = Fm.selectedIndex
    
    if (index == -1) return false;
    if (to == +1 && index == total) return false;
    if (to == -1 && index == TopIndex) return false;
    
    var items = new Array;
    var values = new Array;
    for (i = total; i >= TopIndex; i--) {
        items[i] = list.options[i].text;
        values[i] = list.options[i].value;
    }
    
    for (i = total; i >= TopIndex; i--) {
        if (index == i) {
            list.options[i + to] = new Option(items[i],values[i], 0, 1);
            list.options[i] = new Option(items[i + to], values[i + to]);
            i--;
        }
        else {
            list.options[i] = new Option(items[i], values[i]);
        }
    }
    list.focus();
}

function MyListBox_selectAll(Fm, Target,TopIndex)
{
	var Field = 'Fm.' + Target
	var len = eval(Field + '.length')
	var tmpstr = ','
	for (i=TopIndex ; i < len; i++)
	{
		eval(Field + '.options[i].selected=true')
		tmpstr += eval(Field + '.options[i].value') + ','				
	}	
	eval('Fm.' + Target + '_result.value = \"' + tmpstr + '\"')			
}

function getMySelectionBox_PickFrom(From, To,TopIndex)
{
	// 1: There is first option
	// 0: There is no first option

	var len = From.length
	var j=0, ind;
	for (i=TopIndex ; i < len; i++)
	{
		if (From.options[i].selected==true)
		{
			To.options[To.options.length] = new Option(From.options[i].text, From.options[i].value)
			From.options[i].text = ''
			j++;
		}
	}

	ind = TopIndex;
	for (i=TopIndex ; i < len; i++)
	{
		ind++;
		if (From.options[i].text == '')
		{
			StopLoop=0
			while (ind < len && !StopLoop)
			{ 
				if (From.options[ind].text != '')
				{
					From.options[i].text = From.options[ind].text
					From.options[i].value = From.options[ind].value
					From.options[ind].text = ''
					StopLoop=1
				}
				else
					ind++;
			}
		}
	}
	From.options.length -= j
}

function currencyValidation(str) 
{
	var curchr, retval, fullstop;
	str.toString();
	retval=1;
	if (str) 
	{
	    fullstop=0;
		for (i=0; i < str.length; i++) {
			curchr = str.charAt(i) ;
			if (curchr!='.' && curchr!=',' && curchr!='0' && !parseInt(curchr)) {	
				retval=0; 
			} else if (curchr=='.') {
			    fullstop++;
			}
		}
		if (fullstop>1) {
		    retval=0; 
		}
	}
	return retval;
}

//
// Add Calculator calling JavaScript function
//
function callCalculator(pFormName,pFieldName,pParseTo)
{
	window.open(ACPath + "FormLayout/html/calc.phtml?formname=" + pFormName + "&fieldname=" + pFieldName + "&parseto=" + pParseTo, "Calculator", "width=300,height=320,status=no,toolbar=no,menubar=no");
}

function WindowsOpen (url, w, h)
{
	var win=window.open(url,"APPLET","left=0,top=0,width="+w+",height="+h+",channelmode=0,status=1,scrollbars=1,resizable=1", true); 		
	win.focus();
}

// Date functions
function _DoSetDateMinus (d, m, y) 
{
	if (d.selectedIndex==1) {
		d.selectedIndex=eval(d.length - 1) ;
		if (m.selectedIndex==1) {
			m.selectedIndex=eval(m.length - 1) ;
			if (y.selectedIndex > 0)
				y.selectedIndex=eval(y.selectedIndex - 1) ;
		} else if (m.selectedIndex>1) 
			m.selectedIndex=eval(m.selectedIndex - 1) ;
	} else if (d.selectedIndex>1) 
		d.selectedIndex=eval(d.selectedIndex - 1) ;
}

function _DoSetDate (d, m, y, StartYear) 
{
	today = new Date()
	d.selectedIndex=today.getDate();
	m.selectedIndex=today.getMonth() + 1;
	y.selectedIndex=eval(today.getFullYear() - StartYear + 1);
}

function _DoSetDateAdd (d, m, y) 
{
	if (d.selectedIndex==(d.length-1)) {
		d.selectedIndex=1 ;
		if (m.selectedIndex==(m.length-1)) {
			m.selectedIndex=1 ;
			y.selectedIndex=eval(y.selectedIndex + 1) ;
		} else
			m.selectedIndex=eval(m.selectedIndex + 1) ;
	} else
		d.selectedIndex=eval(d.selectedIndex + 1) ;
}

function _DoReSetDate (d, m, y) 
{
	d.selectedIndex=0;
	m.selectedIndex=0;
	y.selectedIndex=0;
}

function ShowCalendar(ControlName){
	//var urlik=ACPath + "FormLayout/index.phtml?currenttable=" + CurrentTable + "&amp;crmaction=showcalendar&amp;name="+ControlName+"&amp;selectedDay=" + eval('document.all.'+ControlName+'D.value') + "&amp;selectedMonth=" + eval('document.all.'+ControlName+'M.value') + "&amp;selectedYear=" + eval('document.all.'+ControlName+'Y.value');
	var urlik="index.php?s=showcalendar&amp;selectedDay=" + eval('document.all.'+ControlName+'D.value') + "&amp;selectedMonth=" + eval('document.all.'+ControlName+'M.value') + "&amp;selectedYear=" + eval('document.all.'+ControlName+'Y.value');
	view_popup_calendar=window.open(urlik,null,"left=0,top=0,resizable=yes,height=255,width=400,status=no,location=no,fullscreen=no,menubar=no,scrollbars=yes,toolbar=no,directories=no,channelmode=no");
	view_popup_calendar.focus();
}

// Time functions
function _DoSetTime(h, m, ampm)
{
	m.selectedIndex=today.getMinutes();

	today = new Date();
	if (ampm)
	{
		var Index=today.getHours();
		var PureIndex = Index;

		ampm.selectedIndex=PureIndex<12?0:1;
		Index = (Index<12)?Index:(Index-12);
		if (Index==0) Index=12

		h.selectedIndex = Index - 1;
	}
	else
		h.selectedIndex=today.getHours();
}

function _DoReSetTime(h, m, ampm)
{
	m.selectedIndex=0;
	if (ampm)
	{
		ampm.selectedIndex=0;
		h.selectedIndex=11;
	}
	else
		h.selectedIndex = 0;
}

function _DoVerifTime(h, m, ampm)
{
	if (h.selectedIndex==12 && m.selectedIndex>0)
	{
		h.selectedIndex = 0;
		ampm.selectedIndex = ampm.selectedIndex?0:1;
	}
}

function ShowHideDiv(name, BoxesId, FirstTime) {
	var vis = "hidden";
	var displ = "none";

	if(GetObjectById("Mcheckbox"+BoxesId).checked){
		var vis = "visible";
		var displ = "inline";

		ExtraValue="";
		if(boxcountmulti[name]==0){
			if(radiobox[name]==0)
				ExtraValue=GetObjectById("Extra"+BoxesId).value;
			else{
				for(i=0; i<radiobox[name]; i++)
					if(GetObjectById("Extra"+BoxesId+"_"+i).checked==true){
						ExtraValue+=GetObjectById("Extra"+BoxesId+"_"+i).value+":=:";
					}
				ExtraValue=ExtraValue.substr(0,ExtraValue.length-3);
			}
		} else {
			for(i=0; i<boxcountmulti[name]; i++)
				if(GetObjectById("Extra"+BoxesId+i).checked==true){
					ExtraValue+=GetObjectById("Extra"+BoxesId+i).value+":=:";
				}
			ExtraValue=ExtraValue.substr(0,ExtraValue.length-3);
		}
		
//	if(!FirstTime){
		if(!ExtraValue)
			CopyValueMulti(name, BoxesId, "!~!");
		else
			CopyValueMulti(name, BoxesId, ExtraValue);
//	}
	}
	else
		CopyValueMulti(name, BoxesId, "!~=~!");

	GetStyleById("Div"+BoxesId).display=displ;
	GetStyleById("Div"+BoxesId).visibility=vis;
}

function CopyValueMulti(name, BoxesId, val, type) {
	if(val=="")
		val="!~!";
	if(val=="!~=~!")
		val="";

//	if(GetObjectById("Mcheckbox"+BoxesId).checked) {
	var tmpVal=GetObjectById("id"+BoxesId).value;

	if(tmpVal.indexOf(":~:")>0)
		tmpVal=tmpVal.substring(0, tmpVal.indexOf(":~:"));

	GetObjectById("id"+BoxesId).value=tmpVal;

	if(type=="checkbox") {
		IsChecked=0
		for(i=0; i<boxcountmulti[name]; i++){
			if(GetObjectById("Extra"+BoxesId+i).checked==true){
				IsChecked++;
				Delimiter = ":~:";
				if(GetObjectById("id"+BoxesId).value.indexOf(":~:")>=0)
					Delimiter = ":=:";
				GetObjectById("id"+BoxesId).value += Delimiter + GetObjectById("Extra"+BoxesId+i).value;
			}
		}
		if(IsChecked==0)
			GetObjectById("id"+BoxesId).value += ":~:!~!";
	} else
		if(val){
			if(type=="textarea")
				val=val.replace(/\r\n/g, "<br>");
			GetObjectById("id"+BoxesId).value += ":~:" + val;
		}
//	}
}

//
// For multipricing
//
var browser;

if(document.layers)
	browser='NN4';
if(document.all)
	browser='IE'
if(!document.all && document.getElementById)
	browser='NN6';

function Layer(name,mode) {
	if (browser=='IE')
		document.all[name].style.display=(mode)?'':'none';
	if (browser=='NN4') {
		vis=(mode)?'visible':'hidden';
		eval('document.'+name+'.visibility=vis;');
		}
	if (browser=='NN6')
		document.getElementById(name).style.display=(mode)?'':'none';
}

function MP_UnHide(name, e)
{
	var vis = e?'':'none';
	var layers = ['MP_1_' + name, 'MP_2_' + name, 'MP_3_' + name, 'MP_4_' + name];
	for (var i=0; i<layers.length; i++)
	{
		var tags = document.all[layers[i]].all.tags('tr');
		for (var j=0; j<tags.length; j++)
		{
			var ins = tags[j].all.tags('input');
			if (!ins.length) continue;	
			if (e)
				tags[j].style.display = vis;
			else if (ins[0].value == '')
				tags[j].style.display = vis;
		}
	}
}

function MP_Show(name) {
	//alert('gr='+MP_Group[name]+' cur='+MP_Currency[name]);
	if ((MP_Group[name]==1) && (MP_Currency[name]==1)) {
		// Show only one price for all groups and all currencies
		Layer('MP_1_' + name,1);
		Layer('MP_2_' + name,0);
		Layer('MP_3_' + name,0);
		Layer('MP_4_' + name,0);
	} else if ((MP_Group[name]==2) && (MP_Currency[name]==1) ){
		Layer('MP_1_' + name,0);
		Layer('MP_2_' + name,1);
		Layer('MP_3_' + name,0);
		Layer('MP_4_' + name,0);
	} else if ((MP_Group[name]==1) && (MP_Currency[name]==2) ){
		Layer('MP_1_' + name,0);
		Layer('MP_2_' + name,0);
		Layer('MP_3_' + name,1);
		Layer('MP_4_' + name,0);
	} else {
		Layer('MP_1_' + name,0);
		Layer('MP_2_' + name,0);
		Layer('MP_3_' + name,0);
		Layer('MP_4_' + name,1);
	}
}
