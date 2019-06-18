<template>
  	<v-container :style="layoutStyle">
	  	<v-flex xs12>
	  		<v-layout
	  			align-center
	  			text-xs-center
	  			wrap
	  			:style="containerStyle"
	  			column>
	  			<v-layout row align-center>
					<h2>carbon check </h2><v-icon large color="#2c3e50">check_circle_outline</v-icon>
				</v-layout>
				<v-btn to="/search-eating-out" outline large round color="primary" :style="buttonStyle">Eating out/takeaways</v-btn>
				<v-btn to="/search-home-prepared" outline large round color="primary" :style="buttonStyle">Home prepared food</v-btn>
				<v-btn to="/search-make-a-recipe" outline large round color="primary" :style="buttonStyle">Make a recipe</v-btn>
			</v-layout>
	  	</v-flex>
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

	  }
   },
	   created(){
		 document.body.style.backgroundColor = "#ebe6ff";
	  },
	  methods:{
	 
	  }
  }
</script>

<style>

</style>