;function DogTrigger()
{
	this._state = {number : 1}
	this.ORIG_STATE = 1;
	this.state = function(number, data)
	{
		data.number 	= number;
		data.trigger_id = this.id;
		var stepChanged = data.number != this._state.number; 
		this._state 	= data;
		if (this._state.number != this.ORIG_STATE)
			this._update();
		else if (stepChanged)
		{
			this._updateSteps();
		}
	}
	
	this._updateSteps = function()
	{
			_(".trigger.active").removeClass("active").addClass("off");
			_("#step_" + this._state.number).removeClass("off").addClass("active");	
			switch(this._state.number)
			{
				case "1":
				case 1:
					_(".template-select").removeAttr("checked");
				break;
			}	
	}
	
	this._update 	  = function()
	{
		_.get(this.updateUrl, this._state, _.proxy(function(data){
			_("#content_" + this._state.number).html(data);
			this._updateSteps();
		}, this));
	}
}
/** Added for order rate sentence */
function DogOrderrateTrigger() {
	
	this.makeSentence	= function() {
		var ordersCreated	= _("#orders_created").val();
		var plusminus		= _("#plusminus").find(":selected").text();
		var interval		= _("#interval").find(":selected").text();
		
		_("#sentence").html('There are <span class="plusminus sentence">'+plusminus.toLowerCase()+'</span> <span class="ordersCreated sentence">'+ordersCreated+'</span> orders created, <span class="sentence interval">'+interval.toLowerCase()+'</span>.');
		
	};
}
_(document).ready(function() {
//	_("#sentence")
/** Make this global **/
	trig	= new DogOrderrateTrigger();
	_("#content").on("keyup","#orders_created",function(e) {
		trig.makeSentence();
	});
	_("#content").on("change","#plusminus",function(e) {
		trig.makeSentence();
	});
	_("#content").on("change","#interval",function(e) {
		trig.makeSentence();
	});
});
