function avtorizationOn(str){
	var SUserId = document.getElementById(str);
	SUserId.className = "USERROOM_DIV_visible";
};
function avtorizationOff(str){
	var SUserId = document.getElementById(str);
	SUserId.className = "USERROOM_DIV_hidden";
};
function tabPaneFull(strId){
	if(strId){
		var nameId1 = document.getElementById('tabPaneFull_h1');
		var nameId2 = document.getElementById('tabPaneFull_h2');
		var nameId3 = document.getElementById('tabPaneFull_h3');
		
		var tabNameId1 = document.getElementById('tabPaneFull_o1');
		var tabNameId2 = document.getElementById('tabPaneFull_o2');
		var tabNameId3 = document.getElementById('tabPaneFull_o3');
		
		if(strId == 'tabPaneFull_h1'){
			nameId1.className = 'tabPaneFull_h_on';
			nameId2.className = 'tabPaneFull_h_off';
			nameId3.className = 'tabPaneFull_h_off';
			if(tabNameId1){
				tabNameId1.className = 'tabPaneFull_o_on';
				tabNameId2.className = 'tabPaneFull_o_off';
				tabNameId3.className = 'tabPaneFull_o_off';
			}
		}
		if(strId == 'tabPaneFull_h2'){
			nameId2.className = 'tabPaneFull_h_on';
			nameId1.className = 'tabPaneFull_h_off';
			nameId3.className = 'tabPaneFull_h_off';
			if(tabNameId2){
				tabNameId2.className = 'tabPaneFull_o_on';
				tabNameId1.className = 'tabPaneFull_o_off';
				tabNameId3.className = 'tabPaneFull_o_off';
			}
		}
		if(strId == 'tabPaneFull_h3'){
			nameId2.className = 'tabPaneFull_h_off';
			nameId1.className = 'tabPaneFull_h_off';
			nameId3.className = 'tabPaneFull_h_on';
			if(tabNameId2){
				tabNameId2.className = 'tabPaneFull_o_off';
				tabNameId1.className = 'tabPaneFull_o_off';
				tabNameId3.className = 'tabPaneFull_o_on';
			}
		}
	}
}