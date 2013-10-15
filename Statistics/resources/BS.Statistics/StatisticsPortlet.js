Ext.define('BS.Statistics.StatisticsPortlet', {
	extend: 'BS.portal.ChartPortlet',
	height: 350,
	axes: [],
	series:[],
	portletConfigClass : 'BS.Statistics.StatisticsPortletConfig',
	categoryLabel: 'Bluespice',
	beforeInitCompontent: function() {

		Ext.Ajax.on('requestcomplete', this.onRequestComplete, this);

		this.ctMainConfig.axes.push({
			type: 'Numeric',
			position: 'left',
			fields: ['hits'],
			title: this.ctMainConfig.yTitle,
			grid: {
				odd: {
					opacity: 1,
					fill: '#ddd',
					stroke: '#bbb',
					'stroke-width': 0.5
				}
			}
		});

		this.ctMainConfig.axes.push({
			type: 'Category',
			position: 'bottom',
			fields: ['name'],
			title: this.categoryLabel
		});

		this.ctMainConfig.series.push({
			type: 'line',
			highlight: {
				size: 7,
				radius: 7
			},
			axis: 'left',
			smooth: false,
			fill: true,
			xField: 'name',
			yField: 'hits',
			markerConfig: {
				type: 'cross',
				size: 4,
				radius: 4,
				'stroke-width': 0
			}
		});

	},

	afterInitComponent: function() {
		
	},

	onRequestComplete: function( conn, response, options) {
		if( typeof options.params.portletType === 'undefined' ) return;
		if( options.params.portletType !== 'ExtendedStatistics') return;

		var x = Ext.decode(response.responseText);
		this.ctMain.axes.getAt(1).title = x.label;
		
	}
});
