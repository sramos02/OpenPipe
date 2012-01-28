//     OpenPipe.js 0.0.1
//     (c) 2011-2012 Sean Kenny, Southern Connecticut State Unitversity (SCSU).
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


	 op.init = function(){
		$('html').append('<div id="op-loading">loading</div>');
		$('.pipelet').hide();
	 }


	 op.load = function(segment){
		this.loadSegment(segment);
	 };


	 op.registerPhase = function(phase){
		if(phase == undefined) phase = 0;
		if(phases[phase] == undefined) phases[phase] = phase;
	 };

	 op.phaseComplete = function(phase){
		lastPhase = phase;
		this.loadScripts(phase);
	 };
	
	
	 op.done = function(){
		var that = this;
		_.each(phases, function(phase){
			if(phase > lastPhase) that.phaseComplete(phase);
		});
			
	 }




	 op.loadSegment = function(segment){
		if(isFirstSegment == true){
			isFirstSegment = false;
			$('#op-loading').hide();
		}
		
		this.registerPhase(segment.phase);
		
		if(segment.html != undefined) this.loadHtml(segment.html, segment.id);
		if(segment.css != undefined) this.loadCss(segment.css);
		$('#'+segment.id).show();
		
		if(segment.script != undefined) this.pushScript(segment.script, segment.phase);
	 };




	 op.loadHtml = function(html, id){
		if(id == undefined) id = 'content';
		
		$('#'+id).append(html);
	 };

	 op.loadCss = function(css){
		$('head').append('<style type="text/css" >'+css+'</style>');

	 };




	 op.pushScript = function(script, phase){
		if(phase == undefined) phase = 0;
		if(scripts[phase] == undefined) scripts[phase] = [];

		scripts[phase].push(script);
	 };
 
	op.loadScripts = function(phase){
		var that = this;
		_.each(scripts[phase], function(script){
			$('body').append('<script type="text/javascript">'+script+'</script>');
		});
	}


	 //set the root open pipe object	
	 root['op'] = op;


}).call(this);