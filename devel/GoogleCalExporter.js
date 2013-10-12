;(function ($, window, document, undefined) {
	// Create the defaults once
	var pluginName = "GoogleCalExporter",
		defaults = {
			apiKey: 'AIzaSyApJTQMtiAC532KFH4p6MKtaoWK_pRqRQ4',
			clientId: '718486116028.apps.googleusercontent.com',

			scopes: 'https://www.googleapis.com/auth/calendar',
			calName: '2013 金馬影展 - Moovee'
		};

	// The actual plugin constructor
	function Plugin (element, options) {
		var self = this;

		self.element = element;
		self.$el = (self.element instanceof jQuery) ? self.element : $(self.element);

		// object variables
		self.loggined = false;
		self.calId = null;
		self.progressObservers = [];
		self.eventObservers = {};

		self.settings = $.extend({}, defaults, options);
		self._defaults = defaults;
		self._name = pluginName;

		gapi.client.setApiKey(self.settings.apiKey);
		self.init();
	}

	Plugin.prototype = {
		// Attach an event handler function for the event name
		on: function(event, callback) {
			var self = this;

			if ( !self.eventObservers[event]) {
				self.eventObservers[event] = [];
			}

			self.eventObservers[event].push(callback);
		},

		// Execute all handlers and behaviors attached for the given event type.
		trigger: function(event) {
			var self = this;

			if (self.eventObservers[event]) {
				$.each(self.eventObservers[event], function(idx, f) {
					f();
				});
			}
		},

		// listen to the create progresses
		registProgressObserver: function(f) {
			var self = this;

			if ($.isFunction(f)) {
				self.progressObservers.push(f);
			}
		},

		// Display the current progress.
		// YOu can register observer via registProgressObserver function.
		progress: function(msg) {
			var self = this;

			if (console && console.log) {
				console.log(msg);
			}

			$.each(self.progressObservers, function(idx, obf) {
				obf(msg);
			});
		},

		init: function () {},

		// Sliently check wether the user is authed or not.
		// If not, it will ask user to auth.
		checkAuth: function () {
			var self = this;

			gapi.auth.authorize({
				client_id: self.settings.clientId,
				scope: self.settings.scopes,
				immediate: true
			}, function(authResult) {
				if (authResult && !authResult.error) {
				  	self.loggined = true;
				  	self.progress('Login succeeded');
				  	self.checkCalendarCreated();
				} else {
					self.prompAuthForm();
				}
			});
		},

		// To prompt the auth form to ask user to auth.
		// If the user deny to auth, I didn'y know what will happened.
		prompAuthForm: function() {
			var self = this;

			self.progress('Asking user to login');
			gapi.auth.authorize({
				client_id: self.settings.clientId,
				scope: self.settings.scopes,
				immediate: false
			}, function(authResult) {
				if (authResult && !authResult.error) {
				  	self.loggined = true;
				  	self.progress('Login succeeded');
				  	self.checkCalendarCreated();
				} else {
					self.progress('Login failed');
				}
			});
		},

		// Wether the calendar is exist or not.
		// If not, we'll try to create it.
		checkCalendarCreated: function() {
			var self = this;

			self.progress('Checking wether the calendar was created or not.');

			gapi.client.load('calendar', 'v3', function() {
				// retrieve all the calendar of this user
				var request = gapi.client.calendar.calendarList.list({
					minAccessRole: 'owner',
					fields: 'items(id,summary)'
				});

				request.execute(function(resp) {
					// check wether we have created this calendar or not before
					if (resp.items) {
						for (var key in resp.items) {
							if (resp.items[key].summary==self.settings.calName) {
								calCreated = true;
								self.calId = resp.items[key].id;
								break;
							}
						};
					}

					// create this calendar if necessary
					if (self.calId) {
						self.createEvents();
					} else {
						self.createCalendar();
					}
				});
			});
		},

		// Create a calendar for those movies with the self.settings.calName name
		createCalendar: function() {
			var self = this,
				request;

			self.progress('Creating the calendar: '+self.settings.calName);

			request = gapi.client.calendar.calendars.insert({
				resource: {
					summary: self.settings.calName,
					description: 'Created by moovee. http://moovee.wallagroup.com/'
				}
			});

			request.execute(function(resp) {
				if (resp && !resp.error) {
					self.calId = resp.id;
					self.progress('Created the calendar successfully.');
					self.createEvents();
				} else {
					self.progress('Failed to create calendar.');
				}
			});
		},

		// Export self.waitingExportMovies to the google calendar
		createEvents: function() {
			var self = this,
				httpBatch = gapi.client.newHttpBatch();

			// !!! Notice:
			// It looks like the START_T and END_T are converted as the UTC time.
			// But it's should be converted as the +8 GMT time.
			// I directly sub 8 hours here. Please remove it once you fix the time conversion.
			$.each(self.waitingExportMovies, function(index, movie) {
				var eventName = movie.CTITLE + ' ' + movie.ETITLE,
					requestForBatch;

				requestForBatch = gapi.client.request({
					path: 'calendar/v3/calendars/'+self.calId+'/events',
					method: 'POST',
					params: {
						calendarId: self.calId,
					},
					body: JSON.stringify({
						summary: eventName,
						description: eventName + ' from http://moovee.wallagroup.com/',
						location: movie.PLACE,
						start: {
							dateTime: (new Date((movie.START_T-8*3600)*1000).toISOString())
						},
						end: {
							dateTime: (new Date((movie.END_T-8*3600)*1000).toISOString())
						}
					})
				});

				httpBatch.add(requestForBatch);
			});

			httpBatch.execute(function(resp) {
				if (resp) {
					$.each(resp, function(id, row) {
						self.progress('Created: '+row.result.summary);
					});

					self.trigger('done');
				};
			});
		},

		// Queue the given movies to export to google calendar.
		//
		// @param movies array [movie, movie, ...]
		// 	Every movies contains following attrs :
		// 		CATEGORY: "性別越界"
		// 		CTITLE: "十種懷孕的方法"
		// 		DATE: "2013-11-12 [二]"
		// 		DURATION: "75"
		// 		END: "2013-11-12 [二] 15:35"
		// 		END_T: 1384270500
		// 		ETITLE: "Two Mothers"
		// 		GRADE: "限"
		// 		KEY: 85
		// 		PAGE: "177"
		// 		PLACE: "新光影城 2 廳"
		// 		REMARK: ""
		// 		START: "2013-11-12 [二] 14:20"
		// 		START_T: 1384266000
		//
		exportMovies: function(movies) {
			var self = this;

			self.waitingExportMovies = movies;
			self.checkAuth(); // start the chain
		}
	};

	window[pluginName] = Plugin;
})(jQuery, window, document);
