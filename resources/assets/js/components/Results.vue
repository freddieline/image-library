<template >
	<div>
	<div id="container" :style="containerStyle" >
		<v-layout row align-center justify-center>
			<h2>carbon check</h2><v-icon large color="#2c3e50">check_circle_outline</v-icon>
		</v-layout>
		<h1>Caesar salad</h1>
		<div :style="container2Style">
			<div :style="valueContainerStyle">
				<div :style="valueTitleStyle">Total</div>
				<div id="" :style="valueStyle"> 21.5</div>
				<div :style="unitStyle">kG of CO<sub>2</sub>e</div>
			</div>
			<div :style="valueContainerStyle">
				<div :style="valueTitleStyle">Average</div>
				<div :style="valueStyle">12.3</div>
				<div :style="unitStyle">kg of CO<sub>2</sub>e<br/>per kg food</div>
			</div>
		</div>
		<div id="chart-container">
			<canvas :style="chart" id="myChart"></canvas>
		</div>
		<div :style="container3Style" id="more-info-popup" v-show="this.showData">
			<v-icon @click="close()" style="float:right;" color="primary" large>clear</v-icon>
			<h1>More info</h1>
			<div :style="dataStyle">
				 Total value 53 kg &#177;3.5 kg of CO<sub>2</sub>e <br/>
				 Average value 32 kg &#177;1.2 kg of CO<sub>2</sub>e <br/>
					 </div>
				<v-expansion-panel style="box-shadow:none;">
					  <v-expansion-panel-content  color="primary"
						v-for="(item,i) in this.ingredients"
						:key="i" style="background-color:transparent;border:none;">
						<template v-slot:header color="primary">
						  <div>{{item.name}}</div>
						</template>
						<v-icon slot="actions" color="primary">$vuetify.icons.expand</v-icon>
						<div :style="sourceStyle" v-for="it in item['sources']">
							<template color="primary">
								<div style="float:left;width:120px;">Emission value:</div>
								<div style="float:right;width:140px;text-align:right;"> {{it.value}} C0<sub>2</sub>e</div><br/>
								<div style="float:left;width:120px;">Location:</div>
								<div style="float:right;width:140px;text-align:right;"> {{it.location}}</div><br/>
								<div style="float:left;width:120px;">External reference:</div>
								<div style="float:right;width:140px;text-align:right;"><a target="_blank" :href="it.link">{{it.ref}}</a></div>
							</template>
						</div>
					</v-expansion-panel-content>
				</v-expansion-panel>
		</div>
		<div :style="container4Style">
			<v-flex xs12>
				<v-layout justify-space-around row fill-height>
					<v-btn to="/" outline medium fab color="primary">
						<v-flex style="align-content:center;">
							<v-icon color="primary"  medium>keyboard_return</v-icon>
						</v-flex>
					</v-btn>
					<v-btn to="/" outline medium fab color="primary">
						<v-flex style="align-content:center;">
							<v-icon color="primary" medium>share</v-icon>
						</v-flex>
					</v-btn>
					<v-btn @click="open()" outline fab color="primary">
						<v-flex style="align-content:center;">
							<v-icon color="primary" medium>more_horiz</v-icon>
						</v-flex>
					</v-btn>
				</v-layout>
			</v-flex>
		</div>
	</div>
</div>

</template>

<script>

import Chart from 'chart.js';


export default {
	name: 'Results',
	props: {
		msg: String
	},
	data()  {
		return {
			showData:           false,
			chartSize:          0, 
			maxChartSize:       600,
			chartMargin:        0,
			windowWidth:        0,
			containerPadding:   12,
			containerStyle:     "padding:26px 14px 0 14px;"+
								"margin-right:0px;",
			switchStyle:        "float:left;width:100px;",
			container2Style:    "margin-top:28px;" +
								"height:130px;" +
								"padding-bottom:0px;",
			container3Style:    "z-index:1;"+
								"text-align:center;"+
								"position:absolute;"+
								"left:0px;"+
								"top:0px;" +
								"background-color:#fdf2d8;"+
								"padding:20px;" +
								"width:100%;",             
			container3Height:   600,
			dataStyle:          "text-align:left;"+
								"margin:25px 0px 15px 22px;" +
								"font-size:15px;" +
								"line-height:26px;",
			sourceStyle:        "padding:8px 22px 8px 22px;" +
								"height:90px;"+
								"clear:both;",
			container4Style:    "margin-top:40px;",
			valueContainerStyle: "width:50%;" +
								"float:left;",
			valueTitleStyle:    "fontSize:18px",
			valueStyle:         "fontSize:36px;" +
								"line-height:42px;",
			unitStyle:          "fontSize:12px;" ,
			chart:              "margin-top:0px;" +
								"margin-bottom:30px;",
			accuracyStyle:      "fontSize:14px;",
			switch1:            true,
			switchUnit:         "Metric units",
			metricLabels:[
								'50g Lettuce',
								'15g Croutons',
								'140g Chicken', 
								'5g Anchovies'
				],
			imperialLabels:[
					'5oz Lettuce',
					'1.5oz Croutons',
					'14oz Chicken', 
					'0.5oz Anchovies'
				],
			ingredients:[
				{
					name:'5oz Lettuce',
					sd:5,
					sources:[
						{
							name:'source1',
							value:34.2,
							location:"UK",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source2',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source3',
							value:34.2,
							location:"Europe",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						}
					]
				},
				{
					name:'1.5oz Croutons',
					sd:2.1,
					sources:[
						{
							name:'source1',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source2',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source3',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						}
					]
				},
				{
					name:'1.5oz Chicken',
					sd:1.1,
					sources:[
						{
							name:'source1',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source2',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source3',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						}
					]
				},
				{
					name:'0.5oz Anchovies',
					sd:2.4,
					sources:[
						{
							name:'source1',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source2',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						},
						{
							name:'source3',
							value:34.2,
							location:"Worldwide",
							ref:"Standford et al 2017",
							link:'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC4372775/'
						}
					]
				},

			]
		}
	},
	created(){
		window.addEventListener('resize', this.handleResize);
		document.body.style.backgroundColor = "#fdf2d8";

	},
	mounted() {
		this.handleResize();
		this.createChart();
	},
	methods: {
		close(){
			this.showData = false;
		},
		open(){ 
			console.log('open');
			this.showData = true;
		},
		createChart(){ 

			var ctx = document.getElementById('myChart');
			if(this.myDoughnutChart!=null){
				console.log('destroy');
				this.myDoughnutChart.destroy();
			}
			
			const context = ctx.getContext('2d');
			context.clearRect(0, 0, ctx.width, ctx.height);
  
			var labels1 =[];
			if( this.switch1 ==true){
					labels1 = this.metricLabels;
				}
				else{
					labels1 = this.imperialLabels;
				}
			var data = {

				datasets: [{
					  label: 'CO2e',
					data: [10, 20, 30, 30],
					backgroundColor: ['#fdd378', '#b9a2ff', '#ff93f9', "#7000bb"],
				}],

				// These labels appear in the legend and in the tooltips when hovering different arcs
				labels:labels1
			};
			// And for a doughnut chart
			this.myDoughnutChart = new Chart(ctx, {
				type: 'doughnut',
				data: data,
				options: {
					legend:{
						labels:{
							padding:10,
							fontSize:14,
							fontColor:"#434343"
						},
						position:'left',
						usePointStyle: true,
						
					},
					layout: {
						padding: {
							left: 0,
							right: 0,
							top: 0,
							bottom: 0
						}
					},
					animation:{
						animateScale: true 
					},
					cutoutPercentage: 50,
					tooltips:{
						xPadding: 10,
						yPadding: 10,
						//backgroundColor: 'rgba(54, 68, 77, 0.95)',
						callbacks: {
							title: function(tooltipItem, data){
								return [ data.labels[tooltipItem[0].index] ];
							},
							label: function(tooltipItem, data) {      
								return [ " " + data.datasets[0].data[tooltipItem.index] + " kg CO2e"];
							}
						}
					}
				}
			});
			this.myDoughnutChart.canvas.parentNode.style.width = this.chartSize + 'px';

		},

		/**
		* handle resize
		*/
		handleResize() {
			this.windowWidth = window.innerWidth;
			if(this.windowWidth > this.maxChartSize){
				this.chartSize = this.maxChartSize;
				this.containerSize = this.maxChartSize ;
				document.getElementById('chart-container').style.width = this.chartSize + 'px';
				document.getElementById('container').style.width = this.chartSize + 'px';
			}
			else{
				this.chartSize = this.windowWidth -  50;
				this.containerSize = this.windowWidth - 25;
				document.getElementById('chart-container').style.width = this.chartSize +'px';
				document.getElementById('container').style.width =  this.containerSize + 'px';
			}
			this.container3Height = this.chartSize + 230;
			console.log(this.container3Height);
		   document.getElementById('more-info-popup').style.height = this.container3Height +"px";
		var marginLeft = ( (this.windowWidth) /2 - (this.containerSize+ this.containerPadding )/2) +'px';
		   document.getElementById('container').style.marginLeft = marginLeft;
		},

		/**
		* handle resize
		*/
		destroyed() {
			window.removeEventListener('resize', this.handleResize);
		},
		
	 }

  };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
*{   box-sizing: border-box;}
body{  

  margin: 0px;
  padding: 0px;

}
</style>
