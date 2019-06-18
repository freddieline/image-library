<template>
	<v-container :style="layoutStyle">
		<v-layout
			text-xs-center
			wrap
			:style="containerStyle"
		>
			<v-flex xs12>
				<h1>Home prepared food</h1>
			</v-flex>
			<v-flex
				xs12
				mb-5
			>
			
				<v-autocomplete :items="items" append-icon="undefined" append-outer-icon="search" flat :style="searchBoxStyle" v-model="select" placeholder="Search dishes" hide-no-data :search-input.sync="search"

		 
				></v-autocomplete>
					<v-checkbox :style="checkboxStyle" :label="this.unitText" color="primary" v-model="imperial" @change="toggleUnits()" ></v-checkbox>
				
								<v-btn to="/results" outline large round color="primary" :style="buttonStyle">
					carbon check
					</v-btn>

			</v-flex>

		</v-layout>
	</v-container>
</template>

<script>
	export default {
				name: 'SearchEatOut',
	 data(){
			return {
				layoutStyle:      "padding:28px 28px 0 28px;",
				containerStyle:  "max-width:600px;"+
													"margin: 0 auto;",
				searchBoxStyle:   "margin-top:100px;"+
													"margin-bottom:100px;",
				searchButton:     "color:#fff;" +
													"padding:10px;"+
													"font-size:16px;" +
													"font-weight:bold;" +
													"width:150px;" +
													"height:45px;" +
													"border-radius:20px;"+
													"margin:100px auto;"+
													"background-color: #7000bb;",
				unitText:          "Use Imperial units for food ingredients",
				showAccuracy:     false,
				showAccuracyText:  "Show accuracy values",
				imperial:           false,
				checkboxStyle:    "margin-top:5px;",
				buttonStyle:      "margin-top:80px;", 
				placeholder:      "Search dishes",
				items:            [],
				search:           null,
				select:           null,
				dishItems:        ["Caesar salad","Vegan burger","Beef burger","Beef burger (UK produced)"]

				// searchBoxStyle:   "box-shadow:none;background-color:trasnparent;"

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
				}
			}
	}
</script>

<style>

</style>