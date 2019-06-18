<template>
	<v-container :style="containerStyle">
		<v-layout column>
		<v-flex xs12>
			<h1>Make a recipe</h1>
			<v-layout column align-center justify-center>

				<v-autocomplete :allow-overflow="false" :items="items" append-icon="undefined" append-outer-icon="search" flat :style="searchBoxStyle" v-model="select" placeholder="Search dishes" hide-no-data cache-items :search-input.sync="search" 
				></v-autocomplete>
			</v-layout>	
		</v-flex>
		<v-flex xs12>
			<v-layout column align-center justify-center>
				<v-layout style="width:80%;"  row align-center justify-center >

					<v-slider :style="sliderStyle" v-model="slider"></v-slider>
			
					<div :style="valueStyle"> {{slider}} {{unit}}</div>
				</v-layout>
			</v-layout>	
		</v-flex>
		<v-flex xs12>
			<v-layout column align-center fill-height >
				<v-layout style="width:80%;" row align-center justify-space-around >
					<v-flex xs6>
						<v-switch color="primary" :label="unitType" ></v-switch>
	  				</v-flex>
	  				<v-flex xs6>
	  					<v-layout row justify-end>
		  					<v-btn small fab dark color="primary">
			    				<v-icon dark>add</v-icon>
			  				</v-btn>
		  				</v-layout>
	  				</v-flex>
	  			</v-layout>
			</v-layout>	
		</v-flex>
		<v-flex xs12>
			<v-layout column  align-center >
				<div :style="dataTableStyle">
						<v-data-table id="ingredients" light hide-actions hide-headers :items="getIngredients()" >
							<template v-slot:items="props">
								<tr>
									<td :style="columnStyle" width="200">{{props.item.name}}</td>
									<td :style="columnStyle" >{{props.item.quantity}}</td>
								<td>						
								<v-icon color="primary">remove</v-icon>
								</td>
								</tr>
							</template>
						</v-data-table>
					</div>
				</v-layout>
			</v-flex>
			<v-flex xs12>
  				<v-layout row align-center justify-center>
					<v-btn to="/results" outline large round color="primary" :style="buttonStyle">
					carbon check
					</v-btn>
				</v-layout>
			</v-flex>
		</v-flex>
	</v-layout>
	</v-container>
</template>

<script>
	export default {
				name: 'SearchEatOut',
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
				buttonStyle:      	"margin-top:20px;", 
				placeholder:      	"Search dishes",
				items:            	[],
				search:           	null,
				select:           	null,
				dishItems:        	["Caesar salad","Vegan burger","Beef burger","Beef burger (UK produced)"],
				slider: 			45,
				sliderStyle: 		"margin-right:12px;",
				valueStyle: 		"width:60px;"+
									"font-size:20px;",
				unit:   			"oz",
				unitType: 			"Imperial",
				dataTableStyle: 	"height:120px;"+
									"margin:30px;"+
									"overflow:scroll;",
				columnStyle: 		"padding:0px;",
				ingredients: 		[
										{name:'chicken', quantity:"20g"},
										{name:'lettuce', quantity:"8g"},
										{name:'anchovies', quantity:"8g"},
										{name:'croutons', quantity:"3g"},
										{name:'extra item', quantity:"2g"}
									]
			}
	 },
			 created(){
				 document.body.style.backgroundColor = "#ebe6ff";
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
					return this.ingredients.slice(0,index);
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