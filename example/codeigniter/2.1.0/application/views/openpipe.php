<style>

	#container {
		width: 960px;
		margin: auto;
	}
	
	#left-sidebar {
		float: left;
		overflow: auto;
		width: 200px;
	}
	
	#content {
		float: left;
		overflow: auto;
		width: 560px;
	}
	
	#right-sidebar {
		float: left;
		overflow: auto;
		width: 200px;
	}
	
</style>
<div id="container" >

	<div id="header">
		<h1>Pipelets - Basic </h1>
	</div>
	
	
	<div id="left-sidebar">
		&nbsp;
		<div pipelet-id="pipelet3"  pipelet-priority="100"   ></div>
	</div>
	
	<div id="content">
		&nbsp;
		<div pipelet-id="pipelet1" pipelet-priority="500" ></div>
		
	</div>
	
	<div id="right-sidebar">
		&nbsp;
		<div pipelet-id="pipelet2" pipelet-priority="100" ></div>
	</div>
	
</div>
	
