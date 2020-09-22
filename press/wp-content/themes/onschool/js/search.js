class Search {
	constructor() {
		//describe and create/initiate our object
		this.addSearchHTML();
		this.openBtn = $(".js-search-trigger");
		this.closeBtn = $(".search-overlay__close");
		this.searchOverlay = $(".search-overlay");
		this.searchField = $("#search-term");
		this.events();
		this.isOverlayOpen = false;
		this.typingTimer;
		this.resultsDiv = $("#search-overlay__results");
		this.isSpinnerVisible = false;
		this.previousValue;
	}
	//events
	events(){
		this.openBtn.on("click", this.openOverlay.bind(this));
		this.closeBtn.on("click", this.closeOverlay.bind(this));
		$(document).on("keydown", this.keyPressDispatcher.bind(this));
		this.searchField.on("keyup", this.typingLogic.bind(this));
	}
	//methods (function, action ..)
	typingLogic(){
		if (this.searchField.val() != this.previousValue) {
			clearTimeout(this.typingTimer);
			if (this.searchField.val()) {
				if (!this.isSpinnerVisible) {
					this.resultsDiv.html('<div class="spinner-loader"></div>');
					this.isSpinnerVisible = true;
				}
				this.typingTimer = setTimeout(this.getResults.bind(this), 800);
				this.previousValue = this.searchField.val();
			} else{
				this.resultsDiv.html("");
				this.isSpinnerVisible = false;
			}

			
		}
		
	}
	/*
	getResults(){
		$.when(
			$.getJSON(universityData.root_url+'/wp-json/wp/v2/posts?search='+ this.searchField.val()),
			$.getJSON(universityData.root_url+'/wp-json/wp/v2/pages?search='+ this.searchField.val() )
			).then((posts, pages) => {
			var combinedResults = posts[0].concat(pages[0]);
				this.resultsDiv.html(`
					<h2 class="search-overlay__section-title">General Information</h2>
					${combinedResults.length ? '<ul class="link-list min-list">' : '<p>Nothing found</p>'}
						${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a>${item.type == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
					${combinedResults.length ? '</ul>' : ''}
				`); 
			this.isSpinnerVisible = false;
		},() => {
			this.resultsDiv.html(`<p>unexpected error</p>`);
		}

		);		
	}*/
	getResults(){
		$.getJSON(universityData.root_url+'/wp-json/university/v1/search?term='+ this.searchField.val(), (resutls) => {
			this.resultsDiv.html(`
				<div class="row">
					<div class="one-third">
						<h2 class="search-overlay__section-title">General Information</h2>
						${resutls.generalInfo.length ? '<ul class="link-list min-list">' : `<p>No posts or pages found <a href="${universityData.root_url}/blog">view all posts</a></p>`}
							${resutls.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a>${item.postType == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
						${resutls.generalInfo.length ? '</ul>' : ''}
					</div>
					<div class="one-third">
						<h2 class="search-overlay__section-title">Programs</h2>
						${resutls.program.length ? '<ul class="link-list min-list">' : `<p>No programs found <a href="${universityData.root_url}/programs">view all programs</a></p>`}
							${resutls.program.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
						${resutls.program.length ? '</ul>' : ''}

						<h2 class="search-overlay__section-title">Professors</h2>

						${resutls.professor.length ? '<ul class="professor-cards">' : `<p>No professors found</p>`}
							${resutls.professor.map(item => `
								<li class="professor-card__list-item">
								  <a class="professor-card" href="${item.permalink}">
								    <img class="professor-card__image" src="${item.image}" alt="" >
								    <span class="professor-card__name">${item.title}</span>
								  </a>
								</li>
								`).join('')}
						${resutls.professor.length ? '</ul>' : ''}

					</div>
					<div class="one-third">
						<h2 class="search-overlay__section-title">Campuses</h2>
						${resutls.campus.length ? '<ul class="link-list min-list">' : `<p>No campuses found <a href="${universityData.root_url}/campuses">view all campuses</a></p>`}
							${resutls.campus.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
						${resutls.campus.length ? '</ul>' : ''}
						
						<h2 class="search-overlay__section-title">Events</h2>
						${resutls.event.length ? '' : `<p>No events found <a href="${universityData.root_url}/events">view all eventes</a></p>`}
							${resutls.event.map(item => `
								<div class="event-summary">
								  <a class="event-summary__date t-center" href="${item.permalink}">
								    <span class="event-summary__month">${item.month}</span>
								    <span class="event-summary__day">${item.day}</span>
								  </a>
								  <div class="event-summary__content">
								    <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
								    <p>${item.description}<a href="${item.permalink}" class="nu gray">Learn more</a></p>
								  </div>
								</div>
								`).join('')}
						${resutls.event.length ? '' : ''}
					</div>
				</div>
				`);
			this.isSpinnerVisible = false;
		});
	}
	keyPressDispatcher(e){
		if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
			this.openOverlay();
		}
		if (e.keyCode == 27 && this.isOverlayOpen) {
			this.closeOverlay();
		}
	}
	openOverlay(){
		this.searchOverlay.addClass("search-overlay--active");
		$("body").addClass("body-no-scroll");
		setTimeout(()=>this.searchField.focus(), 301);
		this.searchField.val('');
		this.isOverlayOpen = true;
		return false;
	}
	closeOverlay(){
		this.searchOverlay.removeClass("search-overlay--active");
		$("body").removeClass("body-no-scroll");
		this.isOverlayOpen = false;
	}
	addSearchHTML(){
		$("body").append(`
				<div class="search-overlay">
				  <div class="search-overlay--top">
				    <div class="container">
				      <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
				      <input class="search-term" id="search-term" type="text" placeholder="what are you looking for?">
				      <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
				    </div>
				  </div>
				  <div class="container">
				    <div id="search-overlay__results">
				      
				    </div>
				  </div>
				</div>
			`);
	}
}

var search = new Search();