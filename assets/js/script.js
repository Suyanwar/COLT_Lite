function report_load(sm, page, id, date){
	$.ajax({
        type: 'POST',
        url: site_url("report/" + sm + "/data/" + page + "/" + id),
        data: {date},
        success: function(response) {
            $("#load_content").html(response);
        },
        error: function(response){
			alert(response.status +' - '+ response.statusText);
		}
    });
}