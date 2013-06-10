(function($) {
	$(function(){
		$('#google-search').focus();

		$('#save-project').on('click', function(evt){
			evt.preventDefault();

			var form = $('#add-project form');

			var errors = false;
			$('input.required', form).each(function(counter, element){
				var value = $(element),
					parent = value.parents('.control-group');
				
				if('' === value.val()) {
					if(!parent.hasClass('error')) {
						var errorDiv = $('<div />').addClass('help-block').html('This field is required');
						parent.addClass('error').append(errorDiv);
					}
					
					errors = true;
				} else if(parent.hasClass('error')) {
					parent.removeClass('error');
					$('.help-block', parent).remove();
				}
			});
			
			if(!errors) {
				var values = form.serialize();
				
				$.ajax({
					"url" : "/projects/add",
					"method" : "post",
					"data" : values,
					"dataType" : "json",
					"success" : function(data){
						clearFormErrors('#add-project form');
						var link = $('<a />').attr({'href' : data.url, 'rel' : 'external', 'target' : '_blank'}).html(data.title);
						var project = $('<li />').append(link).hide();
						$('#project-list').append(project);
						project.fadeIn('slow');
						clearForm('#add-project form');
						$('#add-project').modal('hide');
					}
				});
			}
		});
		
		$('#save-site').on('click', function(evt){
			evt.preventDefault();

			var form = $('#add-site form');

			var errors = false;
			$('input.required', form).each(function(counter, element){
				var value = $(element),
					parent = value.parents('.control-group');
				
				if('' === value.val()) {
					if(!parent.hasClass('error')) {
						var errorDiv = $('<div />').addClass('help-block').html('This field is required');
						parent.addClass('error').append(errorDiv);
					}
					
					errors = true;
				} else if(parent.hasClass('error')) {
					parent.removeClass('error');
					$('.help-block', parent).remove();
				}
			});
			
			if(!errors) {
				var values = form.serialize();
				
				$.ajax({
					"url" : "/sites/add",
					"method" : "post",
					"data" : values,
					"dataType" : "json",
					"success" : function(data){
						clearFormErrors('#add-site form');
						var link = $('<a />').attr({'href' : data.url, 'rel' : 'external', 'target' : '_blank'}).html(data.title);
						var site = $('<li />').append(link).hide();
						$('#site-list').append(site);
						site.fadeIn('slow');
						clearForm('#add-site form');
						$('#add-site').modal('hide');
					}
				});
			}
		});
		
		// TRASH
		$('#project-list li, #site-list li').each(function(counter, element){
			$(element).on(
			{
				'mouseenter' : function(){
					$('.actions', this).show();
				},
				"mouseleave" : function(){
					$('.actions', this).hide();
				}
			});
		});
		
		$('.delete-project').on('click', function(evt) {
			evt.preventDefault();
			
			if(confirm("Are you sure you want to delete this project?")) {
				var parent = $(this).parents('li');
				var link = $('a[rel=external]', parent);
				
				$.ajax({
					"url" 		: "/projects/delete",
					"data" 		: {
									"title" : link.html(),
									"url"	: link.attr('href')
									},
					"method" 	: "post",
					"dataType" 	: "json",
					"success"	: function(){
						parent.fadeOut('slow', function(){
							$(this).remove();
						});
					}
				});
			}
		});
		
		$('.delete-site').on('click', function(evt) {
			evt.preventDefault();
			
			if(confirm("Are you sure you want to delete this site?")) {
				var parent = $(this).parents('li');
				var link = $('a[rel=external]', parent);
				
				$.ajax({
					"url" 		: "/sites/delete",
					"data" 		: {
									"title" : link.html(),
									"url"	: link.attr('href')
									},
					"method" 	: "post",
					"dataType" 	: "json",
					"success"	: function(){
						parent.fadeOut('slow', function(){
							$(this).remove();
						});
					}
				});
			}
		});
		
		// Tooltip
		$('[rel=tooltip]').tooltip();
	});
	
	function clearFormErrors(element)
	{
		$('.control-group.error', element).each(function(){
			var $this = $(this);
			
			$this.removeClass('error');
			$('.help-block', $this).remove();
		});
	}
	
	function clearForm(element)
	{
		$('input', element).each(function(){
			$(this).val('');
		});
	}

})(window.jQuery);
