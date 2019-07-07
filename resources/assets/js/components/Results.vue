<template >
	<div>
	<div id="container" :style="containerStyle" >


		<h1>{{mealName}}</h1>
		<div :style="container2Style">
			<div :style="valueContainerStyle">
				<div id="" :style="valueStyle"> {{totalCarbon}}</div>
				<div :style="unitStyle">Total kg of CO<sub>2</sub>e</div>
			</div>

		</div>
		<div id="chart-container">
			<canvas :style="chart" id="myChart"></canvas>
		</div>
		<div :style="container3Style" id="more-info-popup" v-show="this.showData">
			<v-icon @click="close()" style="float:right;" color="primary" large>clear</v-icon>
			<h1>Ingredients info</h1>
				<v-expansion-panel style="box-shadow:none;">
					  <v-expansion-panel-content  color="primary"
						v-for="(item,i) in this.ingredientsSources"
						:key="i" style="background-color:transparent;border:none;font-size:15px;">
						<template v-slot:header color="primary">
							<v-layout flex-start row fill-height>
						  		<v-flex :style="dataStyle">{{item.name}}</v-flex><v-flex :style="data2Style">Average CO2e per kg<br/>{{item.value}} +/- {{item.sd}} (s.d.)</v-flex>
							</v-layout>
						</template>
						<v-icon slot="actions" color="primary">$vuetify.icons.expand</v-icon>
						<div :style="sourceStyle" v-for="it in item['food_sources']">
							<template color="primary">
								<v-layout flex-start row fill-height>
									<v-flex :style="leftColumn">Food:</v-flex>
									<v-flex :style="rightColumn"> {{it.food}} </v-flex>
								</v-layout>
								<v-layout flex-start row fill-height>
									<v-flex :style="leftColumn">Emission value:</v-flex>
									<v-flex :style="rightColumn"> {{it.kgCO2e_per_kg_food}} C0<sub>2</sub>e</v-flex>
								</v-layout>
								<v-layout flex-start row fill-height>
									<v-flex :style="leftColumn">Title:</v-flex>
									<v-flex :style="rightColumn"> {{it.source_title}}</v-flex>
								</v-layout>
								<v-layout flex-start row fill-height>
									<v-flex :style="leftColumn">External reference:</v-flex>
									<v-flex :style="rightColumn"><a target="_blank" :href="it.link">{{it.authors}}</a></v-flex>
								</v-layout>
							</template>
						</div>
					</v-expansion-panel-content>
				</v-expansion-panel>
		</div>
		<div :style="container4Style">
			<v-flex xs12>
				<v-layout justify-space-around row fill-height>
				
						<v-flex style="align-content:center;">
							<v-icon  @click="toHome()"  color="primary"  medium>keyboard_return</v-icon>
						</v-flex>
			
				
						<v-flex style="align-content:center;">
							<v-icon color="primary" medium>share</v-icon>
						</v-flex>
		
		
						<v-flex style="align-content:center;">
							<v-icon color="primary" @click="open()" medium>more_horiz</v-icon>
						</v-flex>
				
				</v-layout>
			</v-flex>
		</div>
	</div>
</div>

</template>

<script>

import Chart from 'chart.js';
import axios from 'axios';
import _ from 'lodash';

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
								"background-color:#ffffff;"+
								"padding:20px;" +
								"width:100%;",             
			container3Height:   600,
			dataStyle:          "text-align:left;"+
								"width:30%;" +
								"font-size:15px;" ,
			data2Style:          "text-align:left;"+
								"font-size:15px;" ,
			sourceStyle:        "padding:8px 22px 8px 22px;" +
								"height:100%;"+
								"font-size:13px;"+
								"clear:both;"+
								"background-color:#fafafa;",
			container4Style:    "margin-top:40px;",
			valueContainerStyle: "width:100%;" +
								"float:left;",
			valueTitleStyle:    "fontSize:18px",
			valueStyle:         "fontSize:40px;" +
								"line-height:42px;",
			unitStyle:          "fontSize:17px;" ,
			chart:              "margin-top:0px;" +
								"margin-bottom:30px;",
			accuracyStyle:      "fontSize:14px;",
			switch1:            true,
			switchUnit:         "Metric units",
			leftColumn:			
								"width:50%;",
			rightColumn:		
								"width:50%;"+
								"text-align:right;",
			mealName:			"",
				
			totalCarbon:		0,
			imperialLabels:		[
									'5oz Lettuce',
									'1.5oz Croutons',
									'14oz Chicken', 
									'0.5oz Anchovies'
								]
		}
	},
	created(){
		console.log('created');
		var id  = parseInt(this.$route.params.id);

		//get meal info
		window.addEventListener('resize', this.handleResize);
		document.body.style.backgroundColor = "#FFFFFF";
		console.log('hi');
		this.meal = _.filter(this.$store.getters.getMealsWithIngredients, { 'id':id})[0];
		console.log(this.meal);
		// get chart data
		this.getChartData();

	},
	mounted() {
		console.log('mounted');
		this.handleResize();
		this.createChart();
		
	},
	computed:{
		ingredientsSources:function(){
				return this.meal.meals_ingredients.map((ingredient) => {
					console.log(ingredient.ingredient.name);
					return {
							name: 			ingredient.ingredient.name,
							value:			ingredient.ingredient.average_kgCO2e_per_kg_food,
							sd:				ingredient.ingredient.standard_deviation,
							food_sources: 	ingredient.ingredient.food_sources
						
					}
				});
		},
		ingredientsCarbon: function(){

			return this.meal.meals_ingredients.map((i) => {
			
				return Math.round(i.ingredient.average_kgCO2e_per_kg_food * i.mass_of_ingredient_in_grams * 100) / 100;
			});
		},
		metricLabels: function(){

			return this.meal.meals_ingredients.map((ingredient) => {
			
				return ingredient.mass_of_ingredient_in_grams + "g " + ingredient.ingredient.name;
			});
		},

	},
	methods: {

		// get chart data
		getChartData(){
			this.mealName = this.meal.name;
			this.totalCarbon = this.meal.total_kgCO2e;
		},

		// close more info
		close(){
			this.showData = false;
		},

		// open more info
		open(){ 
			this.showData = true;
		},

		// navigate to home
		toHome(){
			this.$router.push('/');
		},

		// create chart
		createChart(){ 

			var ctx = document.getElementById('myChart');
			if(this.myDoughnutChart!=null){
			
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
					data: this.ingredientsCarbon,
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
