<template>
	<v-container :style="layoutStyle">
		
		<v-layout
			text-xs-center
			wrap
			:style="containerStyle"
		>
			<v-flex xs12>
				<h1>Search meals</h1>
			</v-flex>
			<v-flex xs12 mb-5 flex-start>
				<v-form
					flex-start
					ref="form"
      				v-model="valid"
      				lazy-validation>
					<v-layout column>
						<v-autocomplete 
							:items="items" 
							append-icon="undefined" 
							append-outer-icon="search" 
							flat 
							:style="searchBoxStyle" 
							v-model="select" 
							placeholder="Search dishes" 
							hide-no-data 
							:search-input.sync="search"
							:rules="searchRules">
						</v-autocomplete>
						<v-label style="width:20%;">Metric or Imperial units?</v-label>
						<v-switch
							:style="checkboxStyle" 
							:label="getUnitText()" 
							color="primary" 
							v-model="isImperial" 
							small
							>
						</v-switch>
	
					</v-layout>
					<v-btn 
						:disabled="!valid"
						@click='submitDish()' 
						outline 
						large 
						round 
						color="primary" 
						:style="buttonStyle">
						carbon check
					</v-btn>
				</v-form>
			</v-flex>

		</v-layout>
	</v-container>
</template>

<script>
	export default {
				name: 'SearchDishes',
				props: [ 'composerProp','app', 'composer' ],
	 data(){
			return {

				layoutStyle:    	"padding:28px 28px 0 28px;",
				containerStyle: 	"max-width:600px;" +
									"margin: 0 auto;",
				searchBoxStyle: 	"margin-top:100px;" +
									"margin-bottom:100px;",
				searchButton:   	"color:#fff;" +
									"padding:10px;" +
									"font-size:16px;" +
									"font-weight:bold;" +
									"width:150px;" +
									"height:45px;" +
									"border-radius:20px;" +
									"margin:100px auto;" +
									"background-color: #7000bb;",
				unitText:          	"Use Imperial units for food ingredients",
				showAccuracy:     	false,
				showAccuracyText:  	"Show accuracy values",
				imperial:           false,
				checkboxStyle:    	"margin-top:5px;",
				buttonStyle:      	"margin-top:80px;" +
									"text-transform: lowercase;",
				placeholder:      	"Search dishes",
				items:            	[	],
				search:           	null,
				select:           	null,
				meals:				[],
				searchRules:		[
										v => !!v || 'A meal name is required'
									],
				valid:				true,
				isImperial: 		false


			}
	 },
			 created(){
				document.body.style.backgroundColor = "#FFFFFF";
				this.meals = this.$store.getters.getMeals;
				
			},
			watch:{
				search(val){
					val && val !== this.select && this.querySelections(val);
				}
			},
			computed:{
				dishItems: function(){
					return this.meals.map((meal) => {
						return meal.name;
					});
				}
			},
			methods:{
				getUnitText(){
					if(this.isImperial){
						return "Imperial";
					}
					else{
						return "metric";
					}
				},

				querySelections(v) {
					setTimeout(() => {
						this.items = this.dishItems.filter(e => {
							return (e || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1;
						});
					}, 500);
				},
				submitDish(){
					
					this.$refs.form.validate();

					// find meal id
					this.meals.forEach(function(meal) {
						if (meal.name === this.select){
							this.id = meal.id;
						}
					}.bind(this));

					// if id is defined pass value into url
					if(this.id ){
						console.log('submit');
						console.log(this.id);
						this.$router.push({ 
							path: 'results' , 
							query :	{
									id: this.id,
									isImperial: this.isImperial
									}
							});
					}
				}
				

				
			}
	}
</script>

<style>

</style>