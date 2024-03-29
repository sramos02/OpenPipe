//     OpenPipe.js 1.0.0
//     (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
//     OpenPipe is freely distributable under the MIT license.

(function() {

	//debug vars - for timing
	var isDebug = (document.location.search.indexOf('debug=true') != -1);
	var debugInitTime;
	var debugDoneTime;
	var debugLastSegmentLoadTime;

	
	// Establish the root object, `window` in the browser, or `global` on the server.
	var root = this;

	// Save the previous value of the `op` variable.
	var previousOpenPipe = root.op;
	
	var isFirstSegment = true;
	
	var lastPhase = 0;
	var phases = [];
	var scripts = [];


	var op = {};

	//if this is debug then record performance statistics
	if(isDebug === true){
		op.performance = {};
		op.performance.timing = {};
		op.performance.timing.segments = [];
	}

	//init the OpenPipe client - hide pipelets initally (no FLOCs)
	op.init = function(){
		//record times for logging
		if(isDebug === true){
			debugInitTime = new Date().getTime();
			debugLastSegmentLoadTime = debugInitTime;
		}

		$("*[pipelet-loading-indicator]").append('<div class="op-loading">loading</div>');
		$("*[pipelet-auto-show='true']").hide();
	};

	//load a given segment object into the piplined document
	op.load = function(segment){
		this.loadSegment(segment);

		//record times and output to the log
		if(isDebug === true){
			var debugCurrentSegmentLoadTime = new Date().getTime();
			op.performance.timing.segments.push(debugCurrentSegmentLoadTime);
			console.log('SEGMENT "'+segment.id+'" LOAD TIME: '+(debugCurrentSegmentLoadTime-debugLastSegmentLoadTime));
			console.log('TIME UNTIL SEGMENT: '+(debugCurrentSegmentLoadTime-debugInitTime));
			debugLastSegmentLoadTime = debugCurrentSegmentLoadTime;
		}
	};


	//register a given phase from a segment
	op.registerPhase = function(phase){
		if(typeof(phase) == 'undefined') phase = 0;
		if(typeof(phases[phase]) == 'undefined') phases[phase] = phase;
	};

	//mark a phase complete. A completed phase has its deferred JavaScript elemetns loaded (and consequently run)
	op.phaseComplete = function(phase){
		lastPhase = phase;
		this.loadScripts(phase);
	};
	
	
	//mark the pipeline as completed. For all the phases mark complete if not already done (load all JavaScript for any phases where complete notification was not sent)
	op.done = function(){
		var that = this;
		_.each(phases, function(phase){
			if(phase > lastPhase) that.phaseComplete(phase);
		});

		if(isDebug === true){
			debugDoneTime = new Date().getTime();
			console.log('TOTAL LOAD TIME: '+(debugDoneTime-debugInitTime));
		}
			
	};



	//load an pipeline segment. A segment contains an id (element to load on page), css, html and JavaScript. The id is found, css is loaded, html is loaded , and JavaScript is queued until the end of the segments phase
	op.loadSegment = function(segment){
		if(isFirstSegment === true){
			isFirstSegment = false;
			$("*[pipelet-loading-indicator]").hide();
		}
		
		this.registerPhase(segment.phase);
		
		if(typeof(segment.css) != 'undefined') this.loadCss(segment.css);
		if(typeof(segment.html) != 'undefined') this.loadHtml(segment.html, segment.id);
		$("*[pipelet-id='"+segment.id+"']").show();
		
		if(typeof(segment.scripts) != 'undefined') this.pushScripts(segment.scripts, segment.phase);
	};



	//load html into the given #id'ed element by appending
	op.loadHtml = function(html, id){
		if(typeof(id) == 'undefined') id = 'content';
		
		$("*[pipelet-id='"+id+"']").append(html);
	};

	//load css into the head of the documents
	op.loadCss = function(css){
		
		var that = this;
		_.each(css, function(css_item){
			$('head').append(css_item);
		});
		

	};



	//push a set of script blocks onto a phase of the pipeline cycle. Once the phase is marked complete all the scripts in that phase will be loaded (and executed)
	op.pushScripts = function(scripts, phase){
		
		var that = this;
		_.each(scripts, function(script){
			that.pushScript(script, phase);
		});

	};

	//push a single script block onto a phase of the pipeline cycle. Once the phase is marked complete all the script in that pahse will be loaded (and execueted)
	op.pushScript = function(script, phase){
		if(typeof(phase) == 'undefined') phase=lastPhase+1;
		if(typeof(scripts[phase]) == 'undefined') scripts[phase] = [];
		scripts[phase].push(script);
	};
 
	//loads all the pusheds script for a given phase
	op.loadScripts = function(phase){
		var that = this;
		_.each(scripts[phase], function(script_item){
			jq_script = $(script_item);

			//if this is an external javascript then we make a new dom object to house it from the string data
			if(typeof(jq_script.src) != 'undefined'){
				var script = document.createElement('script');
				script.type = jq_script.attr('type') || '';
				script.src = jq_script.attr('src') || '';
				$('body').append(script);
			
			//if this was just an internal javascript append to body and jquery will execute it
			} else {
				$('body').append(script_item);
			}
			
		});
	};


	//set the root open pipe object
	root['op'] = op;


}).call(this);
