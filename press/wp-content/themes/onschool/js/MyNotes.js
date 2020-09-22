class MyNotes{
	constructor(){
		this.events();
	}

	events(){
		$("#my-notes").on("click", ".delete-note", this.deleteNote);
		$("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
		$("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
		$(".submit-note").on("click", this.createNote.bind(this));

	}

	//Method goes here
	deleteNote(e){
		var thisNote = $(e.target).parents("li");
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityNotesData.nonce)
			},
			url: universityData.root_url+'/wp-json/wp/v2/note/'+thisNote.data('id'),
			type: 'DELETE',
			success: (response) => {
				thisNote.slideUp();
				console.log('congrats');
				console.log(response);
				if (response.userNoteCount < 5) {
					$(".note-limit-message").removeClass("active");
				}
			},
			error: (response) => {
				console.log('failed');
				console.log(response);
			}
		});
	}
	updateNote(e){
		var thisNote = $(e.target).parents("li");
		var ourUpdatedPost = {
			'title': thisNote.find(".note-title-field").val(),
			'content': thisNote.find(".note-body-field").val()
		}
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityNotesData.nonce)
			},
			url: universityData.root_url+'/wp-json/wp/v2/note/'+thisNote.data('id'),
			type: 'POST',
			data: ourUpdatedPost,
			success: (response) => {
				this.makeNoteReadOnly(thisNote);
				console.log('congrats');
				console.log(response);
			},
			error: (response) => {
				console.log('failed');
			}
		});
	}
	createNote(e){
		var ourNewPost = {
			'title': $(".new-note-title").val(),
			'content': $(".new-note-body").val(),
			'status': 'publish'
		}
		$.ajax({
			beforeSend: (xhr) => {
				xhr.setRequestHeader('X-WP-Nonce', universityNotesData.nonce)
			},
			url: universityData.root_url+'/wp-json/wp/v2/note/',
			type: 'POST',
			data: ourNewPost,
			success: (response) => {
				$(".new-note-title, note-body-field").val();
				$(`
					<li data-id="${response.id}">
					  <input readonly class="note-title-field" type="text" value="${response.title.raw}">
					  <span class="edit-note"><i class="fa fa-pencil"></i> Edit</span>
					  <span class="delete-note"><i class="fa fa-trash-o"></i> Delete</span>
					  <textarea readonly class="note-body-field">${response.content.raw}</textarea>
					  <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right"></i> Save</span>
					</li>
				`).prependTo('#my-notes').hide().slideDown();
				console.log('congrats');
				console.log(response);
			},
			error: (response) => {
				if (response.responseText == "you have reached your limit") {
					$(".note-limit-message").addClass("active");
				}
				console.log('failed');
				console.log(response);
			}
		});
	}
	editNote(e){
		var thisNote = $(e.target).parents("li");
		if (thisNote.data("state") == "editable") {
			this.makeNoteReadOnly(thisNote);
		} else{
			this.makeNoteEditable(thisNote);
		}
		
		
		
	}
	makeNoteEditable(thisNote){
		thisNote.find(".edit-note").html('<i class="fa fa-times"></i> Cancel');
		thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
		thisNote.find(".update-note").addClass("update-note--visible");
		thisNote.data("state", "editable");
	}
	makeNoteReadOnly(thisNote){
		thisNote.find(".edit-note").html('<i class="fa fa-pencil"></i> Edit');
		thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
		thisNote.find(".update-note").removeClass("update-note--visible");
		thisNote.data("state", "cancel")
	}
}

var notes = new MyNotes();