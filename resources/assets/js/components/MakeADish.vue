<template>
	<v-container :style="containerStyle">
		<v-form
			flex-start
			ref="form"
			v-model="valid"
			lazy-validation>
			<v-layout column>
				<v-flex xs12>
					<h1>Design a dish</h1>
					<v-layout column align-center justify-center>

						<v-autocomplete 
							:allow-overflow="false" 
							:items="items" 
							append-icon="undefined" 
							append-outer-icon="search" 
							flat 
							:style="searchBoxStyle" 
							v-model="select" 
							:placeholder="placeholder" 
							hide-no-data 
							cache-items 
							:search-input.sync="search" 
							:rules="searchRules"
						></v-autocomplete>
					</v-layout>	
				</v-flex>
				<v-flex xs12>
					<v-layout column align-center justify-center>
						<v-layout style="width:80%;"  row align-center justify-center >

							<v-slider 
								:style="sliderStyle" 
								v-model="slider"
								:rules="massRules">
							</v-slider>
					
							<div :style="valueStyle"> {{slider}} {{unit}}</div>
						</v-layout>
					</v-layout>	
				</v-flex>
				<v-flex xs12>
					<v-layout row align-center fill-height >
						<v-flex xs6>
							<v-btn small fab dark color="primary">
								<v-icon dark @click="addIngredient()">add</v-icon>
							</v-btn>
						</v-flex>
						<v-flex xs6>
							<v-switch
								:style="checkboxStyle" 
								:label="getUnitText()" 
								color="primary" 
								v-model="isImperial" 
								small>
							</v-switch>
						</v-flex>
					</v-layout>	
				</v-flex>
				<v-flex xs12>
					<v-layout column  align-center >
						<div :style="dataTableStyle">
								<v-data-table id="ingredients" light hide-actions hide-headers :items="getIngredients()" >
									<template v-slot:items="props">
										<tr>
											<td :style="columnStyle" width="200">{{props.item.name}}</td>
											<td :style="columnStyle" width="70" >{{props.item.quantity}} {{unit}}</td>
											<td><v-icon @click="removeItem(props.item.id)" color="primary">remove</v-icon></td>
										</tr>
									</template>
									<template v-slot:no-data >
										<div style="color:#999;">Add ingredients</div>
									</template>
								</v-data-table>
							</div>
					</v-layout>
				</v-flex>
				<v-flex xs12>
					<v-layout row align-center justify-center>
						<v-btn @click="submitCustomDish()" outline large round color="primary" :style="buttonStyle">
						carbon check
						</v-btn>
					</v-layout>
				</v-flex>
			</v-layout>
		</v-form>
	</v-container>
</template>

<script>
	export default {
				name: 'SearchMakeARecipe',
	 data(){
			return {
				containerStyle:     "padding:38px 28px 0 28px;"+
									"margin: 0 auto;",
				searchBoxStyle:   	"margin-top:50px;" + 
									"width:80%;" +
									"height:30px;" +
									"margin-bottom:30px;",
				searchButton:     	"color:#fff;" +
									"padding:10px;"+
									"font-size:16px;" +
									"font-weight:bold;" +
									"width:150px;" +
									"height:45px;" +
									"border-radius:20px;"+
									"margin:100px auto;"+
									"background-color: #7000bb;",
				imperial:           false,
				checkboxStyle:    	"margin-top:5px;",
				buttonStyle:      	"margin-top:20px;"+
									"text-transform:lowercase;", 
				placeholder:      	"Search ingredients",
				items:            	[],
				search:           	null,
				select:           	null,
				slider: 			0,
				sliderStyle: 		"margin-right:12px;",
				valueStyle: 		"width:60px;"+
									"font-size:20px;",
				unit:   			"g",
				unitType: 			"Imperial",
				dataTableStyle: 	"height:120px;"+
									"margin:30px;"+
									"overflow:scroll;",
				columnStyle: 		"padding:0px;",
				selectedIngredients: [],
				isImperial: 		false,
				valid:				true,
				searchRules:		[
										v => !!v || 'An ingredient is required'
									],
				massRules:			[ v => v !== 0 || "Mass must not be zero"]
			}
	 },
			 created(){
				 document.body.style.backgroundColor = "#ffffff";
				 this.foodIngredients = this.$store.getters.getFoodIngredients;
				 console.log(this.foodIngredients);
				 if(this.isImperial === true){
					this.unit = "oz";
				 }	 
			},
			computed:{
				dishItems: function(){
					return this.foodIngredients.map((ingredient) => {
						return ingredient.name
					});
				}
			},
			watch:{
				search(val){
					val && val !== this.select && this.querySelections(val);
				}
			},
			methods:{
				querySelections(v) {
					setTimeout(() => {
						this.items = this.dishItems.filter(e => {
							return (e || "").toLowerCase().indexOf((v || "").toLowerCase()) > -1;
						});
					}, 500);
				},
				getIngredients(){
					var index = 4;
					return this.selectedIngredients.slice(0,index);
				},
				getUnitText(){
					if(this.isImperial){
						this.unit = "oz";
						return "Imperial";
					}
					else{
						this.unit = "g";
						return "metric";
					}
				},
				addIngredient(){

					// check that an ingredient has been selected and a mass given
					this.$refs.form.validate();
	
					// continue only if a meal is selected
					if(this.$refs.form.validate()){

						console.log('valid');
	
						// find meal id
						this.foodIngredients.forEach(function(ingredient) {
							if (ingredient.name === this.select){
								this.ingredientId = ingredient.id;
							}
						}.bind(this));

						// find out whether ingredient exists already
						this.ingredientExists = false;
						this.selectedIngredients.forEach(function(ingredient) {
							if (this.ingredientId === ingredient.id){
								this.ingredientExists = true;
							}
						}.bind(this));
	
						// only add ingredient if it does not already exist
						if(this.ingredientExists === false){
							console.log('exisit');
							// add selected ingredient to ingredients
							var unit = (this.isImperial === false) ? "g" : "oz";

							// add selected ingredient to ingredients
							var selectedIngredient = {
								name:		this.select,
								quantity:	this.slider,
								unit:		unit,
								id:			this.ingredientId 
							};

							// add selected ingredient to ingredients
							this.selectedIngredients.push(selectedIngredient);

							// reset validation and reset form
							this.$refs.form.resetValidation();
					
						}
					}
  		
					// remove ingredient from search
					this.select = "";

					// set slider to 0
					this.slider = 0
					

				},
				removeItem(id){
					this.selectedIngredients = this.selectedIngredients.filter(function(ingredient) {
						if (ingredient.id !== id){
							return ingredient;
						}
					});
				},
				submitCustomDish(){

					this.$router.push({ 
							path: 'custom-results' , 
							query :	{
									isImperial: this.isImperial,
									i1: this.selectedIngredients[0]['id'],
									i2: (this.selectedIngredients[1]) ? this.selectedIngredients[1]['id'] :0,
									i3: (this.selectedIngredients[2]) ? this.selectedIngredients[2]['id'] : 0,
									i4: (this.selectedIngredients[3]) ? this.selectedIngredients[3]['id'] : 0,
									m1: (this.selectedIngredients[0]) ? this.selectedIngredients[0]['quantity'] : 0,
									m2: (this.selectedIngredients[1]) ? this.selectedIngredients[1]['quantity'] : 0,
									m3: (this.selectedIngredients[2]) ? this.selectedIngredients[2]['quantity'] : 0,
									m4: (this.selectedIngredients[3]) ? this.selectedIngredients[3]['quantity'] : 0
									}
							});
				}
			}
	}
</script>

<style>

.theme--light.application{
	background-color:transparent;
}
thead{
	display:none;
}
.v-datatable__actions{
	display:none;
}
td:first-child{
	padding-left:0px;
}
table.v-table tbody td, table.v-table tbody th {
    height: 25px;
    font-size:17px;
    text-align:left;
    padding:0px;
    background-color:transparent;
} 
.theme--light.v-table{ 
	background-color:transparent;
}
.theme--light.v-table tbody tr:hover:not(.v-datatable__expand-row){
	background-color:transparent;
}
.theme--light.v-table tbody tr:not(:last-child){
	border-bottom:none;
}
</style>