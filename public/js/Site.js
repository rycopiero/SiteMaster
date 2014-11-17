

var FormStyle = {
	clearClassError: function(sourceId){
		//var thisForm = 
	},
	setElementError: function(sourceId, elementId){
		$('#'+elementId, $(sourceId)).closest('.form-group').addClass('has-error');
	}
};

$(document).ready(function(){
	function setSelectOption(selectElement){
		var tempFirstOption = $(selectElement).find("option:eq(0)")
			   		
   		$(selectElement).empty();
   		$(selectElement).append(tempFirstOption);
	}
	
	setInitialize = function(source){
		$(source).find("#Country")
			.on('change', function(){
				var stateSelect = $(source).find("#State");
				
				if ( $(this).val() == 0 ) { setSelectOption(stateSelect); }
				
				$.ajax({
				    url: baseUrl+'admin/getStatesJson',
				    type: "POST",
				    data: { CountryId : $(this).val() },
				    dataType: "json",
				})
				.success(function(returnData, returnStatus) {
					setSelectOption(stateSelect);
					
				   	returnData.forEach( function(data) { 
				   		$(stateSelect).append('<option value="'+data.Id+'">'+data.CodeName+'</option>');
				   	});
				});
		});
	};
	
	setInitialize('body');
});
	