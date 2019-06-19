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
					<h2><b>carbon check <v-icon  color="#2c3e50">check</v-icon></b></h2>
				</v-layout>
			
				<div to="/search-dishes" outline large round color="primary" :style="buttonStyle">
				Food dishes<br/>
				<a style="color:white;text-decoration:none;float:right;padding:4px 6px;font-family:-apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Helvetica Neue&quot;, Helvetica, Ubuntu, Roboto, Noto, &quot;Segoe UI&quot;, Arial, sans-serif;font-size:12px;font-weight:bold;line-height:1.2;display:inline-block;border-radius:3px" href="https://unsplash.com/@cellisboa?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target="_blank" rel="noopener noreferrer" title="Download free do whatever you want high-resolution photos from Cel Lisboa"><span style="display:inline-block;float:right;padding:2px 3px"><svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-2px;fill:white" viewBox="0 0 32 32"><title>unsplash-logo</title><path d="M10 9V0h12v9H10zm12 5h10v18H0V14h10v9h12v-9z"></path></svg></span><span style="display:inline-block;padding:2px 3px">Cel Lisboa</span></a>
				</div>
				<v-btn to="/search-make-a-recipe" outline large round color="primary" :style="buttonStyle">Make a recipe</v-btn>
				<v-btn  outline large round color="primary" :style="buttonStyle">Food ingredients data</v-btn>
			</v-layout>
	  	</v-flex>
  </v-container>
</template>
<script>
  export default {
		name: 'Menu',
   data(){
	  return {
		layoutStyle:     "padding:28px 28px 0 28px;",
		containerStyle:  "max-width:600px;"+
						  "margin: 0 auto;",
		searchBoxStyle:   "margin-top:100px;"+
						  "margin-bottom:100px;",
		searchButton:     "color:#fff;" +
						  "padding:10px;" +
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
		checkboxStyle:    	"margin-top:5px;",
		buttonStyle:      	"margin-top:80px;"+
							"padding-top:12px;"+
							"color:#ffffff;"+
							'font-size:36px;'+
							'font-weight:bold;' +
							"width:100%;"+
							"background-image:url('./img/food_search.jpg')", 
		placeholder:      	"Search dishes",

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