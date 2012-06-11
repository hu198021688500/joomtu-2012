function strToJson(str){
	if (typeof (JSON) == 'undefined'){
		//return eval('(' + str + ')');
		return (new Function("return " + str))();
	}else{
		return JSON.parse(str);
	}
}