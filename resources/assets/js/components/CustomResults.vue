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
						:key="i" style="background-color:transparent;border:none;font-size:15px;">
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
			container2Style:    "margin-top:50px;" +			
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
			valueTitleStyle:    "fontSize:18px",
			chart:              "margin-top:0px;" +
								"margin-bottom:30px;",
			accuracyStyle:      "fontSize:14px;",
			leftColumn:			"width:50%;"+ 
								"color:#555;",
			rightColumn:		"width:50%;"+
								"color:#555;"+
								"text-align:right;",
			mealName:			"",
			totalCarbon:		0
		}
	},
	created(){
		console.log('created custom results');
		
		// get query string variables
		this.getQueryStringVariables();
		this.getIngredientsMasses();
		this.getMealIngredients();
		this.getTotalMassInGrams();
		this.getAverageCarbon();
		this.getTotalCarbonDisplay();
		this.getRating();

		//get meal info
		window.addEventListener('resize', this.handleResize);
		document.body.style.backgroundColor = "#FFFFFF";


	},
	mounted() {
		console.log('mounted');
		this.handleResize();
		this.createChart();
		
	},
	computed:{
		ingredientsSources: function(){
			return this.food_ingredients.map((ingredient) => {
				return {
						name: 			ingredient.ingredient.name,
						value:			ingredient.ingredient.average_kgCO2e_per_kg_food,
						sd:				Math.round(ingredient.ingredient.standard_deviation * 100 * 100/ ingredient.ingredient.average_kgCO2e_per_kg_food ) / 100,
						food_sources: 	ingredient.ingredient.food_sources
				}
			});
		},
		ingredientsCarbon: function(){

			return this.food_ingredients.map((i) => {
			
				return Math.round(i.ingredient.average_kgCO2e_per_kg_food * i.mass /10) / 100;
			});
		},
		metricLabels: function(){

			return this.food_ingredients.map((ingredient) => {
			
				return ingredient.mass + "g " + ingredient.ingredient.name;
			});
		},
		imperialLabels: function(){

			return this.food_ingredients.map((ingredient) => {
				if(Math.round(ingredient.mass / 16) === 0){
					return ingredient.mass + " ozs " + ingredient.ingredient.name;
				}
				else{
					return Math.round(ingredient.mass / 16) + 'lbs ' + Math.round(ingredient.mass % 16)+ "ozs " + ingredient.ingredient.name;
				}

			});
		},

	},
	methods: {

		// get array of ingredients
		getQueryStringVariables(){
			this.isImperial  = ( this.$route.query.isImperial === 'true') ? true : false;
			this.ingredient1 = parseInt(this.$route.query.i1);
			this.ingredient2 = parseInt(this.$route.query.i2);
			this.ingredient3 = parseInt(this.$route.query.i3);
			this.ingredient4 = parseInt(this.$route.query.i4);
			this.mass1 = parseInt(this.$route.query.m1);
			this.mass2 = parseInt(this.$route.query.m2);
			this.mass3 = parseInt(this.$route.query.m3);
			this.mass4 = parseInt(this.$route.query.m4);
			console.log(this.$route.query.isImperial);
		},

		// get array of ingredients
		getIngredientsMasses(){
			this.ingredientsMasses = [];
			if(this.ingredient1 !== 0){
				this.ingredientsMasses.push({ id: this.ingredient1, mass: this.mass1 });
			}
			if(this.ingredient2 !== 0){
				this.ingredientsMasses.push({ id: this.ingredient2, mass: this.mass2 });
			}
			if(this.ingredient3 !== 0){
				this.ingredientsMasses.push({ id: this.ingredient3, mass: this.mass3 });
			}
			if(this.ingredient4 !== 0){
				this.ingredientsMasses.push({ id: this.ingredient4, mass: this.mass4 });
			}
			console.log(this.ingredientsMasses);
		},

		// get total mass in grams
		getTotalMassInGrams(){

			this.totalMassInGrams = 0;
			if(this.isImperial === true){
				this.ingredientsMasses.forEach(function(im){
					this.totalMassInGrams += im.mass / 0.035274;
				}.bind(this));
			}
			else{
				this.ingredientsMasses.forEach(function(im){
					this.totalMassInGrams += im.mass ;
				}.bind(this));
			}
			this.totalMassInGrams = Math.round(this.totalMassInGrams);
			console.log("total mass in grams" + this.totalMassInGrams);
		},


		// get array of ingredients
		getMealIngredients(){
			this.food_ingredients = this.$store.getters.getFoodIngredients.filter((i) => {
					for(var ingredientMass of this.ingredientsMasses){
						if(ingredientMass.id === i.id){
							return {
									mass:ingredientMass.mass,
									ingredient:i
								};
						}
					}
			});
			this.food_ingredients = [];
			this.$store.getters.getFoodIngredients.forEach(function(i){
				for(var ingredientMass of this.ingredientsMasses){
						if(ingredientMass.id === i.id){
							this.food_ingredients.push({
									mass:ingredientMass.mass,
									ingredient:i
								});
						}
					}
			}.bind(this));
			console.log(this.food_ingredients);
		},

		

		// get chart data
		getAverageCarbon(){
			this.mealName = "My dish";
			this.totalCarbon = 0;
			this.food_ingredients.forEach((i) => {
				this.totalCarbon += Math.round(i.ingredient.average_kgCO2e_per_kg_food * i.mass / (10 * 0.035274)) / 100;
			});
			console.log('total carbon kg' + this.totalCarbon);
			this.averageCarbon = Math.round(this.totalCarbon * 1000 * 100 / this.totalMassInGrams) /100;
			console.log('average kg carbon per kg' + this.totalCarbon);

		
		},

		getTotalCarbonDisplay(){
			if(this.isImperial === false){
				this.totalCarbonDisplay = this.totalCarbon.toFixed(2) + " kg";
			}
			else{
				this.totalCarbonOunces = Math.round(this.totalCarbon * 1000 * 100 * 0.035274 ) / 100;
				console.log('total carbon ounces'+this.totalCarbonOunces);
		
				if(Math.round(this.totalCarbonOunces / 16)  === 0){
					this.totalCarbonDisplay =  this.totalCarbonOunces + " ozs";
				}
				else{
					this.totalCarbonDisplay = Math.round(this.totalCarbonOunces / 16) + "lbs " +  Math.round(this.totalCarbonOunces % 16) + "ozs";
				}
			}
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
			console.log('open');
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
			console.log(this.isImperial);
			if( this.isImperial === true){
				console.log('is ium');
					labels1 = this.imperialLabels;
				}
			else{
				labels1 = this.metricLabels;
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
