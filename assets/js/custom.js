$(document).ready(function(){
	$('span.waiting').html('');
	
	$('select[name="witel"]').change(function(){
		witel = $(this).val();
	
		$.ajax({
			url : './getClusters.php',
			type : 'post',
			data : {'witel': witel},
			beforeSend : function(){
				
			},
			success : function(data){
				//data = JSON.parse(data);
				console.log(data);
				$('select[name="cluster_name"]').html(data);
				$('.selectpicker').selectpicker('refresh');
			}
		});
	});
});