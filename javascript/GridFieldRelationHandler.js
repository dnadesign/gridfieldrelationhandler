jQuery(function($) {
	$.entwine('ss', function($) {
		$('.ss-gridfield .ss-gridfield-item .col-noedit').entwine({
			onclick: function(e) {
				e.stopPropagation();
				e.stopImmediatePropagation();
			}
		});
		$('.ss-gridfield .ss-gridfield-item .col-noedit input').entwine({
			getState: function () {
				return this.getGridField().getState().GridFieldRelationHandler;
			},
			setState: function(val) {
				this.getGridField().setState('GridFieldRelationHandler', val);
			},
			onchange: function(e) {
				var state = this.getState();
				var input = $(e.target).closest('input');
				if(input.hasClass('radio')) {
					state.RelationVal = input.val();
				} else if(input.hasClass('checkbox')) {
					if(state.RelationVal.indexOf) {
						if(input.is(':checked')) {
							state.RelationVal.push(input.val());
						} else {
							var index = state.RelationVal.indexOf(input.val());
							if(index != -1) {
								var left = state.RelationVal.slice(0, index);
								var right = state.RelationVal.slice(index+1);
								state.RelationVal = left.concat(right);
							}
						}
					} else if(input.is(':checked')) {
						state.RelationVal = [input.val()];
					} else {
						state.RelationVal = [];
					}
				}
				this.setState(state);
			}
		});
	});
});

if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(elt /*, from*/)
	{
		var len = this.length >>> 0;

		var from = Number(arguments[1]) || 0;
		from = (from < 0)
				 ? Math.ceil(from)
				 : Math.floor(from);
		if (from < 0)
			from += len;

		for (; from < len; from++)
		{
			if (from in this &&
					this[from] === elt)
				return from;
		}
		return -1;
	};
}
