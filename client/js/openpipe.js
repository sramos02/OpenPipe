//     OpenPipe.js 1.0.0
//     (c) 2011-2012 Sean Kenny, Southern Connecticut State University (SCSU).
//     OpenPipe is freely distributable under the MIT license.

(function() {
	
 	 // Establish the root object, `window` in the browser, or `global` on the server.
	 var root = this;

	 // Save the previous value of the `|` variable.
	 var previousOpenPipe = root.op;
	
	 var isFirstSegment = true;	
	
	 var lastPhase = -1;
	 var phases = [];
 	 var scripts = [];


	 var op = {};

	 //init the OpenPipe client - hide pipelets initally (no FLOCs)
	 op.init = function(){
		$('html').append('<div id="op-loading">loading</div>');
		$('.pipelet').hide();
	 };

	 //load a given segment object into the piplined document
	 op.load = function(segment){
		this.loadSegment(segment);
	 };


	 //register a given phase from a segment
	 op.registerPhase = function(phase){
		if(phase == undefined) phase = 0;
		if(phases[phase] == undefined) phases[phase] = phase;
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
			
	 };



	//load an pipeline segment. A segment contains an id (element to load on page), css, html and JavaScript. The id is found, css is loaded, html is loaded , and JavaScript is queued until the end of the segments phase
	 op.loadSegment = function(segment){
		if(isFirstSegment === true){
			isFirstSegment = false;
			$('#op-loading').hide();
		}
		
		this.registerPhase(segment.phase);
		
		if(segment.css != undefined) this.loadCss(segment.css);
		if(segment.html != undefined) this.loadHtml(segment.html, segment.id);
		$('#'+segment.id).show();
		
		if(segment.scripts != undefined) this.pushScripts(segment.scripts, segment.phase);
	 };



	//load html into the given #id'ed element by appending
	 op.loadHtml = function(html, id){
		if(id == undefined) id = 'content';
		
		$('#'+id).append(html);
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

	//push a single script block onto a phase of the pipeline cycel. Once the phase is marked complete all the script in that pahse will be loaded (and execueted)
	 op.pushScript = function(script, phase){
		if(phase == undefined) phase = 0;
		if(scripts[phase] == undefined) scripts[phase] = [];

		scripts[phase].push(script);
	 };
 
	op.loadScripts = function(phase){
		var that = this;
		_.each(scripts[phase], function(script){
			$('body').append(script);
		});
	};


	 //set the root open pipe object	
	 root['op'] = op;


}).call(this);