<template >
	<div>
	<div id="container" :style="containerStyle" >


		<h1>{{mealName}}</h1>
		<div :style="container2Style">
			<v-layout flex-start row fill-height>
				<v-flex xs6>
					<v-rating readonly size="20" style="margin-top:10px;" background-color="#fcba03" color="#fcba03" dense
						v-model="rating"></v-rating>
				</v-flex>
				<v-flex xs6>
					<div :style="valueStyle">{{totalCarbonDisplay}}</div>
				</v-flex >
			</v-layout>
			<v-layout flex-start row fill-height>
				<v-flex xs6>
					<div :style="unitStyle">green rating</div>
				</v-flex>
				<v-flex xs6>
					<div :style="unitStyle">CO<sub>2</sub>e emissions</div>
				</v-flex >
			</v-layout>

		</div>
		<div id="chart-container">
			<canvas :style="chart" id="myChart"></canvas>
		</div>
		<div :style="container3Style" id="more-info-popup" v-show="this.showData">
			<v-icon @click="close()" style="float:right;" color="primary" large>clear</v-icon>
			<h1>More info</h1>
				<div style="padding:12px 24px 0px 24px;">
					<div :style="dataStyle">Summary:</div>
					<div :style="data2Style"> {{totalCarbon}} kg CO<sub>2</sub>e emissions</div>
					<div :style="data2Style">{{averageCarbon}} kg CO<sub>2</sub>e emissions per kg food </div>
					<br/>
					<div :style="dataStyle">Ingredients:</div>
				</div>
				<v-expansion-panel style="box-shadow:none;">
					  <v-expansion-panel-content  color="primary"
						v-for="(item,i) in this.ingredientsSources"
						:key="i" :style="expansionPanel">
						<template v-slot:header color="primary">
							<v-layout flex-start column fill-height>
						  		<v-flex :style="data2Style">{{item.name}}</v-flex>
								<v-flex :style="data2Style">{{item.value}} CO<sub>2</sub>e per kg &#177; {{item.sd}}&#37;</v-flex>
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
							<v-icon  @click="toHome()"  color="primary"  medium>arrow_back_ios</v-icon>
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
			container2Style:    "margin-top:40px;" +
								"padding-bottom:60px;",
			container3Style:    "z-index:1;"+
								"text-align:center;"+
								"position:absolute;"+
								"left:0px;"+
								"top:0px;" +
								"background-color:#ffffff;"+
								"padding:20px;" +
								"width:100%;",             
			container3Height:   600,
			valueStyle:         "text-align:center;"+
								"fontSize:2.3em;" +
								"font-weight:200;" +
								"line-height:1.5em;",
			unitStyle:          "text-align:center;"+ 
								"fontSize:15px;" +
								"color:#555;",
			dataStyle:          "margin-bottom:8px;"+
								"text-align:left;"+
								"width:100%;" +
								"font-size:15px;" ,
			data2Style:          "text-align:left;"+
								"font-style:italic;"+
								"font-size:15px;",
			sourceStyle:        "padding:8px 22px 8px 22px;" +
								"height:100%;"+
								"font-size:13px;"+
								"clear:both;"+
								"background-color:#fafafa;",
			container4Style:    "margin-top:40px;",
			valueContainerStyle: "width:100%;" +
								"float:left;",
			valueTitleStyle:    "fontSize:18px",
			chart:              "margin-top:0px;" +
								"margin-bottom:30px;",
			accuracyStyle:      "fontSize:14px;",
			leftColumn:			"width:50%;",
			rightColumn:		"width:50%;"+
								"text-align:right;",
			mealName:			"",
			totalCarbon:		0,
			expansionPanel:		"background-color:transparent;border:none;font-size:15px;"
		}
	},
	created(){
		console.log('created results');
		var id  = parseInt(this.$route.query.id);
		this.isImperial  = ( this.$route.query.isImperial === 'true') ? true : false;

		//get meal info
		window.addEventListener('resize', this.handleResize);
		document.body.style.backgroundColor = "#FFFFFF";

		this.meal = _.filter(this.$store.getters.getMealsWithIngredients, {'id':id})[0];
		console.log(this.meal);
		
		// get chart data
		this.getChartData();

		// get rating
		this.getRating();

	},
	mounted() {
		console.log('mounted');
		this.handleResize();
		this.createChart();
		
	},
	computed:{
		ingredientsSources: function(){
				return this.meal.meals_ingredients.map((ingredient) => {
					return {
							name: 			ingredient.ingredient.name,
							value:			ingredient.ingredient.average_kgCO2e_per_kg_food,
							sd:				Math.round(ingredient.ingredient.standard_deviation * 100 *100/ ingredient.ingredient.average_kgCO2e_per_kg_food ) / 100,
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
				if(ingredient.ingredient.name.length > 11){
					var newName = ingredient.ingredient.name.slice(0,9) + "..";
					return ingredient.mass_of_ingredient_in_grams + "g " + newName;
				}
				else{
					return ingredient.mass_of_ingredient_in_grams + "g " + ingredient.ingredient.name;
				}
			});
		},
		imperialLabels: function(){

			return this.meal.meals_ingredients.map((ingredient) => {
				if(ingredient.ingredient.name.length > 11){
					var newName = ingredient.ingredient.name.slice(0,9) + "..";
					return Math.round(ingredient.mass_of_ingredient_in_grams * 0.035274 * 100) / 100 + "oz " + newName;
				}
				else{
					return Math.round(ingredient.mass_of_ingredient_in_grams * 0.035274 * 100) / 100 + "oz " + ingredient.ingredient.name;
				}
			});
		},

	},
	methods: {

		// get chart data
		getChartData(){
			this.mealName = this.meal.name;
			this.totalCarbon = this.meal.total_kgCO2e;

			// get total emissions
			if(this.isImperial === true){
				this.totalCarbonOunces = Math.round(this.totalCarbon * 1000 * 100 * 0.035274 ) / 100;
				if(Math.round(this.totalCarbonOunces / 16)  === 0){
					this.totalCarbonDisplay = this.totalCarbonOunces + " ozs";
				}
				else{
					this.totalCarbonDisplay = Math.round(this.totalCarbonOunces / 16) + "lbs " +  Math.round(this.totalCarbonOunces % 16) + "ozs";
				}
			}
			else{
				this.totalCarbonDisplay = this.meal.total_kgCO2e + " kg";
			}

			// gte total mass of ingredients
			this.totalMassInKg = 0;
			this.meal.meals_ingredients.forEach((ingredient) => {
				this.totalMassInKg += ingredient.mass_of_ingredient_in_grams / 1000;
			})

			// get average
			this.averageCarbon = this.totalCarbon / this.totalMassInKg;
		},

		
		getRating(){
			this.rating = 5;
			var count = 0;

			this.greenRatings = this.$store.getters.getGreenRatings;
			console.log(this.greenRatings);
			console.log(this.averageCarbon );

			if ( this.averageCarbon > this.greenRatings[0] ){
				this.rating = 0;
			}
			else if ( (this.greenRatings[0] > this.averageCarbon) && ( this.averageCarbon > this.greenRatings[1])){
				this.rating = 1;
			}
			else if ( (this.greenRatings[1] > this.averageCarbon) && ( this.averageCarbon > this.greenRatings[2])){
				this.rating = 2;
			}
			else if ( (this.greenRatings[2] > this.averageCarbon) && ( this.averageCarbon > this.greenRatings[3])){
				this.rating = 3;
			}
			else if ( (this.greenRatings[3] > this.averageCarbon) && ( this.averageCarbon > this.greenRatings[4])){
				this.rating = 4;
			}
			else{
				this.rating = 5;
			}			
			console.log(this.rating);
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
  
			var labels1 = [];
			if( this.isImperial === true){
					labels1 = this.imperialLabels;
				}
			else{
				labels1 = this.metricLabels;
			}
			var data = {

				datasets: [{
					  label: 'CO2e',
					data: this.ingredientsCarbon,
					backgroundColor: ['#1fa9ff', '#1fd2ff', '#1ff8ff', "#a1fffd", "#99ffbe",'69ffaf'],

					// 					final Color chartColour1 = Color(0xff1fa9ff);
// final Color chartColour2 = Color(0xff1fd2ff);
// final Color chartColour3 = Color(0xff1ff8ff);
// final Color chartColour4 = Color(0xffa1fffd);
// final Color chartColour5 = Color(0xff99ffbe);
// final Color chartColour6 = Color(0xff69ffaf);
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
							color:"#555;"
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

			// define width of window
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
