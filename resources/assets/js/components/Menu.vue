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
							   <b class="logo">check your carbon  <img style="width:40px;margin:-5px 10px;" src="/img/carbon_check.png" /></b>
				</v-layout>
		
					<div @click="clickDishes()" class="clickable" outline large round color="primary" :style="dishesStyle">
					Search meals<br/>
					<a style="color:white;text-decoration:none;float:right;padding:4px 6px;font-family:-apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Helvetica Neue&quot;, Helvetica, Ubuntu, Roboto, Noto, &quot;Segoe UI&quot;, Arial, sans-serif;font-size:12px;font-weight:bold;line-height:1.2;display:inline-block;border-radius:3px" href="https://unsplash.com/@cellisboa?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target="_blank" rel="noopener noreferrer" title="Download free do whatever you want high-resolution photos from Cel Lisboa"><span style="display:inline-block;float:right;padding:2px 3px"><svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-2px;fill:white" viewBox="0 0 32 32"><title>unsplash-logo</title><path d="M10 9V0h12v9H10zm12 5h10v18H0V14h10v9h12v-9z"></path></svg></span><span style="display:inline-block;padding:2px 3px">Cel Lisboa</span></a>
					
					</div>
				
					<div @click="clickMakeADish()" class="clickable"  outline large round color="primary" :style="makeADishStyle">Make a dish</div>

					<div @click="clickDishes()" class="clickable"  outline large round color="primary" :style="ingredientsStyle">Search references</div>
			
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
		containerStyle:  "max-width:750px;" +
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
		dishesStyle:      	"background-image:url('./img/search_dishes.jpg')", 
		makeADishStyle:     "background-image:url('./img/make_a_dish.jpg')", 
		ingredientsStyle:   "background-image:url('./img/ingredients.jpg')", 
		placeholder:      	"Search dishes",
		linkStyle:			"width:100%;"+
							"text-decoration:none;"

	  }
   },
	   created(){
		 		document.body.style.backgroundColor = "#FFFfff";
	  },
	  methods:{
		clickDishes(){
			this.$router.push('/search-dishes');
		},
		clickMakeADish(){
			this.$router.push('/make-a-dish');
		}
	  }
  }
</script>

<style>
.clickable{
	border-radius:5px;
	margin-top:30px;
	display: block;
	height:220px;
	padding-top:60px;
	color:#ffffff;
	font-size:36px;
	font-weight:bold;
	width:100%;
}
.clickable:hover{
	cursor:pointer;
}

</style>