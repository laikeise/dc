//Author: Younes Bouab
//
//Date Released:   05-31-02 @ 2:20PM
//
//Version 1.25: Supports Frames & solved the MAC OS 9.X and 10 BUG in IE 
//
//Title:  Menu Configuration
//
//Copyright: Younes Bouab 2002 
//
//Technical Support: bouaby@SUPEReDITION.com
//
//Support: SUPEReDITION.com
//
/////////////////////////////////////////////////////////////////////////////////////
//Copyright (c) 2002 Younes Bouab (www.SUPEReDITION.com) Version 1.25
//
//Experience the DHTML Menu - Get it at www.SUPEReDITION.com
//
//***********************************************************************************
//      If you decide to use our menu,  please support us by sending 
//      whatever financial contribution you can afford to: 
//
//
//      SUPEReDITION.com
//      717, 23rd Street #2
//      Sacramento CA 95816 - USA
//
//
//      PayPal Account: Billing@SUPEReDITION.com
//
//  
//      Users that have sent a financial contribution, will be entitled to 
//      1 year of technical support by sending an email to HMenu_Support@SUPEReDITION.com
//      Please, specify the version of the menu used when requesting support.         
//
// 
//***********************************************************************************
//
//All copyright messages MUST STAY intact
//
//Menu HomePage: http://www.superedition.com/Main.asp?Page=Tutorials&query=Javascript
/////////////////////////////////////////////////////////////////////////////////////
//
//
//
/////////////////////////////////////////////////
//Menu Configuration File
/////////////////////////////////////////////////
/////You can change the value of a variable 
/////below or turn it off by making it equal "" 
/////to suit your needs, but you should not
/////delete any variable.
///////////////////////////////////////////////


/**********************************************/
//Menu Type: Do Not Change! 
/**********************************************/
MENU_TYPE=1; //1: Horizental
             //2: Vertical

Sort=0;   //Sort: When set to 1, the 
          //menu items are sorted according 
          //to the index value. This feature
          //can be used when a server side
          //language (asp. jsp. php,...)
          //is used to generate the menu items 
          //from a database and they are not always 
          //in order.  



/**********************************************/
//Menu Starting point
/**********************************************/
TOP=0;
LEFT=0;
//LEFT=+(screen.width/2)-190;


/**********************************************/
//Menu item Dimension
/**********************************************/
WIDTH=132;
HEIGHT=31;

/**********************************************/
//Global Menu Settings for all: Required
/**********************************************/
//Main Menu Items
HALIGN="LEFT";
LayerColor="beige";
LayerRollColor="#999999";
FONT="arial";
FONTSIZE="2";
FONTSTYLE=""; // "": Normal, "B": Bold, "I": Italic
FONTCOLOR="#000000";
START_CHAR="&nbsp;";


/**********************************************/
//Main Parent Settings: Optional 
// leave empty "", if you would like to use 
// the Global Menu Settings above
/**********************************************/
Main_Parent_LayerColor="#666699";
Main_Parent_LayerRollColor=""; 
Main_Parent_FONT="arial";
Main_Parent_FONTSTYLE="";
Main_Parent_FONTSIZE="";
Main_Parent_FONTCOLOR="#FFFFFF";
Main_Parent_START_CHAR="";


/**********************************************/
//Layer Border Properties
/**********************************************/
LayerBorderSize="1";
LayerBorderStyle="solid";
LayerBorderColor="#ffffff";


/**********************************************/
//Menu Children Offsets
/**********************************************/
TOP_OFFSET=5;
LEFT_OFFSET=10;