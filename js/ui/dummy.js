function generateWorkflow(){
               
    localStorage.removeItem('projectData');
    localStorage.removeItem('workFlowData');
    localStorage.removeItem('companyData');
    localStorage.removeItem('webbAppData');
    
    var projects = [		
		{projectName:"Maxi Växjö",projectId:"MXV",active:true,startDate:"2014-01-01",endDate:"2002-07-02"},
		{projectName:"Gunnebo Fastigheter Västervik",projectId:"GFV",active:true,startDate:"2015-01-01",endDate:""},
		{projectName:"Hilleryd fastigheter Växjö",projectId:"HFV",active:false,startDate:"1999-01-01",endDate:"2002-07-02"},
		{projectName:"Araby Fastigheter Växjö ",projectId:"AFV",active:false,startDate:"2002-01-01",endDate:"2002-07-02"},
		{projectName:"Centrifugen",projectId:"CTFN",active:false,startDate:"2015-01-01",endDate:"2015-02-02"},
		{projectName:"Fruängen ålderdomshem",projectId:"FÅH",active:false,startDate:"2003-01-01",endDate:"2003-07-02"},
		{projectName:"Sjukhuset Växjö",projectId:"SJV",active:false,startDate:"2004-01-01",endDate:"2004-07-02"},
		{projectName:"Skävlinge pappersbruk",projectId:"SPB",active:false,startDate:"2005-01-01",endDate:"2005-07-02"},
		{projectName:"Fyringe bygdskola",projectId:"FBS",active:false,startDate:"2015-01-01",endDate:"2002-07-02"},
		{projectName:"Fejdens Skola",projectId:"FJNS",active:false,startDate:"2015-01-01",endDate:"2002-07-02"}
		];

    var workFlows = [{
		timeStamp:"2016-01-22",
		projectId:"DCEFLJS",
		active:false,
		startDate:"2016-01-01",
		workFlowId:"1",
		message:"martin skopade sand för muraren"},
    	{
		timeStamp:"2016-01-23",
		projectId:"DCEFLJS",
		active:true,
		startDate:"2016-01-01",
		workFlowId:"1",
		message:"boris inspekterade staget e798 på konstruktion"},
    	{
		timeStamp:"2016-01-22",
		projectId:"DCEFLJSSAA",
		active:false,
		startDate:"2016-01-01",
		workFlowId:"2",
		message:"martin skopade sand för muraren"},
    	{
		timeStamp:"2016-01-23",
		projectId:"DCEFLJSSAA",
		active:true,
		startDate:"2016-01-01",
		workFlowId:"1",
		message:"boris inspekterade staget e798 på konstruktion"}];

    var company = {
		companyName:"Lasses lastare och grushög",
		companyId:"LASLASOGH",
		employerId:"1",
		startDateJob:"1972-01-21",
		workRound:"Flexible",
		typeOfWork:"CEO",
		message:"Jag är chefen"};

	var appData = {
		latestUpdateCheck:"2017-01-12",
		webbAppVersion:"1.0",
		authorCopyRight:"Ollesmobilservice (c) 2017"
	};

	localStorage.setItem('workFlowData',JSON.stringify(workFlows));       
    localStorage.setItem('projectData',JSON.stringify(projects));   
    localStorage.setItem('companyData',JSON.stringify(company));
    localStorage.setItem('webbAppData',JSON.stringify(appData));
}

