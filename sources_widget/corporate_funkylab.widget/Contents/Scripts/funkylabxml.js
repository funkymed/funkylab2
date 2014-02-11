	function getXML(){
		
		for ( a in itemArray ){
			itemArray[a].removeFromSuperview();
		}	
		
		var login=preferences.login.value;
		var mdp=preferences.mdp.value;
		var nbRES=preferences.nbpage.value;
		
		var siteweb=preferences.urlweb.value;		
		FunkylabData = url.fetch(siteweb+"/funkylab_info.php?login="+login+"&mdp="+mdp+"&nbpage="+nbRES);		
		
		if (FunkylabData){
		
			var xml = XMLDOM.parse( FunkylabData );				
	
			var subItem = 0;
			var itemsAdded = 0;		
			var heightOffset = 0;
			var heightFrame=0
			var FrameHeightTotal=0;
			itemArray = new Array();
					
			var resultsETAT = xml.evaluate( "funkylab/etat" );				
			var nodeETAT = resultsETAT.item(0);
			var _etat=nodeETAT.evaluate( "string(msg)" );
			var _etatValue=nodeETAT.evaluate( "string(value)" );
			
			if (_etatValue==1){		
				
				etatmsg.data="etat : "+_etat;
						
				var resultsETAT = xml.evaluate( "funkylab/utilisateur" );				
				var nodeETAT = resultsETAT.item(0);
				nomprenom.data=nodeETAT.evaluate( "string(nom)" )+" "+nodeETAT.evaluate( "string(prenom)" );
				email.data=nodeETAT.evaluate( "string(email)" );
				
				message.data=" Message ("+nodeETAT.evaluate( "string(nbmessage)" )+") "+"Message non lu ("+nodeETAT.evaluate( "string(messagenonlu)")+")";				
				
				if (nodeETAT.evaluate( "string(messagenonlu)")==1){
					msgletter.src="Resources/Images/newmsg.png";
				}else{
					msgletter.src="Resources/Images/nomsg.png";
				}		
					
				var results = xml.evaluate( "funkylab/billet/page" );		
				
				for (n = 0; n < results.length; n++){				
					//print(node.evaluate( "string(titre)" )+" >> nbcom : "+node.evaluate( "string(nbcom)" ));				
					var itemFrame = new Frame();
					var itemFrameViews = new Array();
					var image = null;
					var text = null;
					var node = results.item(n);
					
					itemFrame.width = 188;				
		
					text = new Text();
					text.color = "#ffbb00";
					text.font = "Arial Bold";
					text.size = 12;
					text.data = "("+node.evaluate( "string(nbcom)" )+") "+node.evaluate( "string(titre)" );
					text.hOffset = 10;
					text.vOffset = 12;
					text.width =160;
					text.truncation = "end";
					text.zOrder = 1;
					text.onMouseUp = "openURL(\"" +siteweb+"/index.php?billet="+ node.evaluate( "string(id)" ) + "\");";
					
					itemFrame.addSubview( text );
					itemFrameViews.title = text;
		
					tmpTextHeight = text.height;
					
					var resultsPAGE = xml.evaluate( "funkylab/billet/page/lastcom" );				
					var nodePAGE = resultsPAGE.item(n);				
					
					text = new TextArea();
					text.color = "#ffbbff";
					text.font = "Arial Bold";
					text.size = 9;
					text.data ="par "+nodePAGE.evaluate( "string(auteur)" );
					text.hOffset = 8;
					text.vOffset = tmpTextHeight;
					text.width = 160;
					text.editable = false;
					text.scrollbar = false;
					text.zOrder = 3;	
					text.onMouseUp = "openURL(\"" + nodePAGE.evaluate( "string(email)" ) + "\");";	
					itemFrame.addSubview( text );
					itemFrameViews.address = text;
					tmpTextHeight = text.vOffset + text.height;				
		
					text = new TextArea();
					text.color = "#00bbff";
					text.font = "Arial Bold";
					text.size = 9;
					text.data ="le "+nodePAGE.evaluate( "string(date)" );
					text.hOffset = 8;
					text.vOffset = tmpTextHeight;
					text.width = 160;
					text.editable = false;
					text.scrollbar = false;
					text.zOrder = 3;	
					itemFrame.addSubview( text );
					itemFrameViews.address = text;
					tmpTextHeight = text.vOffset + text.height;				
									
					text = new TextArea();
					text.color = "#ffffff";
					text.font = "Arial";
					text.size = 9;
					text.data =nodePAGE.evaluate( "string(commentaire)" );
					text.hOffset = 8;
					text.vOffset = tmpTextHeight;
					text.width = 160;
					text.editable = false;
					text.scrollbar = false;
					text.zOrder = 3;	
					itemFrame.addSubview( text );
					itemFrameViews.address = text;
					tmpTextHeight = text.vOffset + text.height;				
				
					frameHeight = itemFrame.height;
					
					if ( subItem%2 ){
						image = new Image();
						image.src = "Resources/Images/row2.png";
						image.hOffset = 0;
						image.vOffset = 0;
						image.width = 188;
						image.height = frameHeight;
						image.zOrder = 0;
						itemFrame.addSubview( image );
						itemFrameViews.background = image;
					}
					
					itemFrame.height = frameHeight;
					itemFrame.vOffset = heightOffset;
					
					heightOffset += frameHeight;
					itemArray[itemArray.length] = itemFrame;
		
					itemList.addSubview( itemFrame );
					
					subItem++;
					itemsAdded++;
					
					FrameHeightTotal+=frameHeight;
				}	
			}		
			
			preferences.MAxlengthFrame.value=FrameHeightTotal;		
			
			if (FrameHeightTotal>=preferences.lengthFrame.value){
				var myNum=preferences.lengthFrame.value;
			}else{
				var myNum=FrameHeightTotal;
			}		
			
			print(myNum);		
			
			if (myNum<=1){
				slidebar.opacity=0;
			}else{
				slidebar.opacity=255;
			}			
			
			slidebar.height=myNum;		
			backgroundMid.height=myNum;					
			itemList.height = myNum;					
			bottom_result.vOffset=103+itemList.height;
			resize_sprite.vOffset=bottom_result.vOffset;					
			mainWindow.height = 308+itemList.height+15;
		
			print(bottom_result.vOffset+"/"+mainWindow.height);
		}
	}